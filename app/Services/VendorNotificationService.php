<?php

namespace App\Services;

use App\Models\Vendor;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Enquiry;
use App\Models\EnquiryReply;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class VendorNotificationService
{
    /**
     * Send a notification to a specific vendor in the database
     */
    public static function send($vendorId, $type, $title, $message, $link = null, $icon = 'bi-bell-fill')
    {
        try {
            if (!Schema::hasTable('notifications')) {
                return;
            }

            // Check for duplicate recent notification to prevent spamming
            $exists = DB::table('notifications')
                ->where('notifiable_type', Vendor::class)
                ->where('notifiable_id', $vendorId)
                ->where('type', $type)
                ->where('data', 'LIKE', '%' . json_encode($title) . '%')
                ->exists();

            if ($exists) {
                return;
            }

            DB::table('notifications')->insert([
                'id'              => (string) Str::uuid(),
                'type'            => $type,
                'notifiable_type' => Vendor::class,
                'notifiable_id'   => $vendorId,
                'data'            => json_encode([
                    'title'   => $title,
                    'message' => $message,
                    'link'    => $link,
                    'icon'    => $icon,
                    'type'    => $type,
                ]),
                'read_at'         => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        } catch (\Throwable $e) {}
    }

    /**
     * Ensure new vendor receives automated welcome, KYC reminder, and start selling messages
     */
    public static function ensureOnboardingMessages(Vendor $vendor)
    {
        try {
            if (!Schema::hasTable('enquiries')) {
                return;
            }

            $isVerified = in_array($vendor->kyc_status, ['verified', 'approved']) || $vendor->is_verified;

            // 1. Automated Welcome & KYC Reminder Message Thread
            $welcomeEnquiry = Enquiry::where('vendor_id', $vendor->id)
                ->where('enquiry_number', 'LIKE', 'GARUDO-WELCOME%')
                ->first();

            if (!$welcomeEnquiry) {
                $welcomeMsg = "Welcome {$vendor->company_name} to Garudo B2B Marketplace! 🎉\n\n"
                    . "Thank you for choosing Garudo as your primary B2B building materials & industrial commerce network.\n\n"
                    . ($isVerified
                        ? "✅ Your Merchant KYC Verification is approved and your store is ACTIVE! You can now add unlimited product catalogs, configure your delivery & F.O.R. rules, and start receiving bulk wholesale purchase orders."
                        : "⚠️ ACTION REQUIRED — Complete KYC Verification:\nTo activate your Garudo Verified Partner badge, unlock wholesale buyer RFQ inquiries, and start receiving direct purchase orders, please submit your KYC verification documents (GSTIN, Business PAN, Trade License).\n\n👉 Complete KYC Submission here: " . route('vendor.kyc.index'));

                $enquiry = Enquiry::create([
                    'enquiry_number' => 'GARUDO-WELCOME-' . $vendor->id,
                    'customer_id'    => null,
                    'vendor_id'      => $vendor->id,
                    'name'           => 'Garudo Merchant Onboarding Desk',
                    'email'          => 'support@garudo.in',
                    'phone'          => '+91 98765 43210',
                    'company_name'   => 'Garudo B2B Marketplace',
                    'message'        => $welcomeMsg,
                    'status'         => 'new',
                ]);

                EnquiryReply::create([
                    'enquiry_id'  => $enquiry->id,
                    'sender_type' => 'admin',
                    'sender_id'   => 1,
                    'message'     => $welcomeMsg,
                    'created_at'  => $vendor->created_at ?? now(),
                ]);

                // Notification for Welcome & KYC
                self::send(
                    $vendor->id,
                    'onboarding_welcome',
                    'Welcome to Garudo B2B Marketplace!',
                    $isVerified
                        ? 'Your KYC is verified! Click here to add products and start selling to contractors.'
                        : 'Welcome! Please submit your GSTIN and KYC documents to unlock wholesale orders.',
                    $isVerified ? route('vendor.products.create') : route('vendor.kyc.index'),
                    'bi-gift-fill text-success'
                );
            }

            // 2. Automated "KYC Completed - Start Selling!" Message Thread
            if ($isVerified) {
                $kycDoneEnquiry = Enquiry::where('vendor_id', $vendor->id)
                    ->where('enquiry_number', 'LIKE', 'GARUDO-KYC-DONE%')
                    ->first();

                if (!$kycDoneEnquiry) {
                    $kycDoneMsg = "🎉 Congratulations {$vendor->company_name}!\n\n"
                        . "Your Merchant KYC Verification and Channel Partner Accreditation have been approved by Garudo Compliance. Your Garudo Verified Partner Badge is now LIVE!\n\n"
                        . "🚀 You are now ready to START SELLING:\n"
                        . "1. Add your products to catalog: " . route('vendor.products.create') . "\n"
                        . "2. Configure your F.O.R. dispatch rules & delivery timeline: " . route('vendor.shipping.index') . "\n\n"
                        . "Contractors and builders across your operational zone can now discover your products and place direct bulk orders.";

                    $enqKyc = Enquiry::create([
                        'enquiry_number' => 'GARUDO-KYC-DONE-' . $vendor->id,
                        'customer_id'    => null,
                        'vendor_id'      => $vendor->id,
                        'name'           => 'Garudo Compliance & Operations Desk',
                        'email'          => 'support@garudo.in',
                        'phone'          => '+91 98765 43210',
                        'company_name'   => 'Garudo B2B Marketplace',
                        'message'        => $kycDoneMsg,
                        'status'         => 'new',
                    ]);

                    EnquiryReply::create([
                        'enquiry_id'  => $enqKyc->id,
                        'sender_type' => 'admin',
                        'sender_id'   => 1,
                        'message'     => $kycDoneMsg,
                        'created_at'  => now(),
                    ]);

                    self::send(
                        $vendor->id,
                        'kyc_verified',
                        '🎉 KYC Approved - Start Selling Today!',
                        'Your store is verified! Click here to add your first product catalog and start selling.',
                        route('vendor.products.create'),
                        'bi-patch-check-fill text-success'
                    );
                }
            }
        } catch (\Throwable $e) {}
    }

    /**
     * Sync recent vendor events (orders, products) into notifications table so all items are persistent in DB
     */
    public static function syncActivityNotifications(Vendor $vendor)
    {
        try {
            if (!Schema::hasTable('notifications')) {
                return;
            }

            // Sync recent order items
            $orderItems = OrderItem::with('order', 'product')
                ->where('vendor_id', $vendor->id)
                ->latest()
                ->take(5)
                ->get();

            foreach ($orderItems as $item) {
                $orderNum = $item->order->order_number ?? ('ORD-' . $item->id);
                $title = "Purchase: Order #{$orderNum}";
                
                $exists = DB::table('notifications')
                    ->where('notifiable_type', Vendor::class)
                    ->where('notifiable_id', $vendor->id)
                    ->where('type', 'order')
                    ->where('data', 'LIKE', "%{$orderNum}%")
                    ->exists();

                if (!$exists) {
                    $prodName = $item->product->name ?? 'Industrial Material';
                    $qty = $item->quantity;
                    $total = number_format($item->total_price ?? ($item->unit_price * $qty), 2);

                    DB::table('notifications')->insert([
                        'id'              => (string) Str::uuid(),
                        'type'            => 'order',
                        'notifiable_type' => Vendor::class,
                        'notifiable_id'   => $vendor->id,
                        'data'            => json_encode([
                            'title'   => $title,
                            'message' => "{$prodName} (Qty: {$qty}) — ₹{$total}",
                            'link'    => route('vendor.orders.index'),
                            'icon'    => 'bi-cart-check-fill text-success',
                            'type'    => 'order',
                        ]),
                        'read_at'         => null,
                        'created_at'      => $item->created_at ?? now(),
                        'updated_at'      => $item->created_at ?? now(),
                    ]);
                }
            }

            // Sync recent products
            $products = Product::where('vendor_id', $vendor->id)->latest()->take(3)->get();
            foreach ($products as $prod) {
                $title = "Catalog: " . Str::limit($prod->name, 26);
                
                $exists = DB::table('notifications')
                    ->where('notifiable_type', Vendor::class)
                    ->where('notifiable_id', $vendor->id)
                    ->where('type', 'product')
                    ->where('data', 'LIKE', "%prd_id_{$prod->id}%")
                    ->exists();

                if (!$exists) {
                    $statusText = $prod->approval_status === 'approved' ? 'Active & Approved' : 'Under Review';
                    DB::table('notifications')->insert([
                        'id'              => (string) Str::uuid(),
                        'type'            => 'product',
                        'notifiable_type' => Vendor::class,
                        'notifiable_id'   => $vendor->id,
                        'data'            => json_encode([
                            'title'   => $title,
                            'message' => "prd_id_{$prod->id} | Status: {$statusText} | Stock: {$prod->stock_quantity} {$prod->unit}",
                            'link'    => route('vendor.products.index'),
                            'icon'    => 'bi-box-seam text-primary',
                            'type'    => 'product',
                        ]),
                        'read_at'         => null,
                        'created_at'      => $prod->created_at ?? now(),
                        'updated_at'      => $prod->created_at ?? now(),
                    ]);
                }
            }
        } catch (\Throwable $e) {}
    }

    /**
     * Get recent notifications for the authenticated vendor (From persistent DB)
     */
    public static function getLatest(Vendor $vendor, $limit = 10)
    {
        self::ensureOnboardingMessages($vendor);
        self::syncActivityNotifications($vendor);

        try {
            if (Schema::hasTable('notifications')) {
                $dbNotifications = DB::table('notifications')
                    ->where('notifiable_type', Vendor::class)
                    ->where('notifiable_id', $vendor->id)
                    ->latest('created_at')
                    ->take($limit)
                    ->get()
                    ->map(function ($item) {
                        $data = json_decode($item->data, true) ?? [];
                        // Clean up internal tags from message if present
                        $msg = preg_replace('/prd_id_\d+\s*\|\s*/', '', $data['message'] ?? '');
                        return (object) [
                            'id'         => $item->id,
                            'type'       => $data['type'] ?? 'system',
                            'title'      => $data['title'] ?? 'Store Update',
                            'message'    => $msg,
                            'link'       => $data['link'] ?? '#',
                            'icon'       => $data['icon'] ?? 'bi-bell text-success',
                            'read_at'    => $item->read_at,
                            'created_at' => \Carbon\Carbon::parse($item->created_at),
                        ];
                    });

                return $dbNotifications;
            }
        } catch (\Throwable $e) {}

        return collect();
    }

    /**
     * Count unread notifications for a vendor (Strict DB count, returns 0 if all read)
     */
    public static function getUnreadCount(Vendor $vendor)
    {
        try {
            if (Schema::hasTable('notifications')) {
                return DB::table('notifications')
                    ->where('notifiable_type', Vendor::class)
                    ->where('notifiable_id', $vendor->id)
                    ->whereNull('read_at')
                    ->count();
            }
        } catch (\Throwable $e) {}

        return 0;
    }

    /**
     * Get vendor message threads (Inquiries from Customers & Admin notices)
     */
    public static function getMessageThreads(Vendor $vendor, $limit = 15)
    {
        self::ensureOnboardingMessages($vendor);

        $enquiries = Enquiry::with(['customer', 'product', 'replies'])
            ->where('vendor_id', $vendor->id)
            ->latest()
            ->take($limit)
            ->get();

        if ($enquiries->isNotEmpty()) {
            return $enquiries->map(function ($enq) {
                $lastReply = $enq->replies->last();
                $isAdmin = str_starts_with($enq->enquiry_number, 'GARUDO-') || str_starts_with($enq->enquiry_number, 'ADM-');
                return (object) [
                    'id'             => $enq->id,
                    'enquiry_number' => $enq->enquiry_number,
                    'sender_type'    => $isAdmin ? 'admin' : 'customer',
                    'sender_name'    => $isAdmin ? 'Garudo Official Desk' : ($enq->name . ($enq->company_name ? ' (' . $enq->company_name . ')' : '')),
                    'sender_role'    => $isAdmin ? 'Platform Staff' : 'Verified Buyer',
                    'sender_avatar'  => $isAdmin ? 'bi-shield-check text-success' : 'bi-person-circle text-primary',
                    'subject'        => $isAdmin ? ($enq->name ?? 'Platform Notice') : ('Inquiry for ' . ($enq->product->name ?? 'Building Materials')),
                    'latest_message' => $lastReply ? $lastReply->message : $enq->message,
                    'product_name'   => $enq->product->name ?? ($isAdmin ? 'Platform Notification' : 'General Inquiry'),
                    'status'         => $enq->status,
                    'unread'         => ($enq->status === 'new'),
                    'created_at'     => $enq->created_at,
                    'replies'        => $enq->replies,
                ];
            });
        }

        return collect();
    }

    /**
     * Get unread messages count for vendor (Strict DB count, returns 0 if all read)
     */
    public static function getUnreadMessagesCount(Vendor $vendor)
    {
        try {
            if (Schema::hasTable('enquiries')) {
                return Enquiry::where('vendor_id', $vendor->id)->where('status', 'new')->count();
            }
        } catch (\Throwable $e) {}

        return 0;
    }
}

