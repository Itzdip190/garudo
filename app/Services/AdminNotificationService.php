<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\Order;
use App\Models\Vendor;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AdminNotificationService
{
    /**
     * Send an admin alert / notification
     */
    public static function send($type, $title, $message, $link = null, $icon = 'bi-bell-fill')
    {
        try {
            if (!Schema::hasTable('notifications')) {
                return;
            }

            $admins = Admin::all();
            if ($admins->isEmpty()) {
                // If no admin record, insert for virtual admin id 1
                DB::table('notifications')->insert([
                    'id' => (string) Str::uuid(),
                    'type' => $type,
                    'notifiable_type' => Admin::class,
                    'notifiable_id' => 1,
                    'data' => json_encode([
                        'title' => $title,
                        'message' => $message,
                        'link' => $link,
                        'icon' => $icon,
                        'type' => $type,
                    ]),
                    'read_at' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                return;
            }

            foreach ($admins as $admin) {
                DB::table('notifications')->insert([
                    'id' => (string) Str::uuid(),
                    'type' => $type,
                    'notifiable_type' => Admin::class,
                    'notifiable_id' => $admin->id,
                    'data' => json_encode([
                        'title' => $title,
                        'message' => $message,
                        'link' => $link,
                        'icon' => $icon,
                        'type' => $type,
                    ]),
                    'read_at' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (\Throwable $e) {
            // Silently fail if table not migrated yet
        }
    }

    /**
     * Get recent notifications list for top header and dashboard
     */
    public static function getLatest($limit = 8)
    {
        try {
            if (Schema::hasTable('notifications')) {
                $adminId = auth('admin')->id();
                $dbNotifications = DB::table('notifications')
                    ->where('notifiable_type', Admin::class)
                    ->when($adminId, function($q) use ($adminId) {
                        $q->where('notifiable_id', $adminId);
                    })
                    ->latest()
                    ->take($limit)
                    ->get()
                    ->map(function ($item) {
                        $data = json_decode($item->data, true) ?? [];
                        return (object) [
                            'id' => $item->id,
                            'type' => $data['type'] ?? 'system',
                            'title' => $data['title'] ?? 'Platform Notification',
                            'message' => $data['message'] ?? '',
                            'link' => $data['link'] ?? '#',
                            'icon' => $data['icon'] ?? 'bi-bell-fill text-primary',
                            'read_at' => $item->read_at,
                            'created_at' => \Carbon\Carbon::parse($item->created_at),
                        ];
                    });

                if ($dbNotifications->isNotEmpty()) {
                    return $dbNotifications;
                }
            }
        } catch (\Throwable $e) {
            // Fallback to dynamic stream
        }

        return self::getDynamicActivityStream($limit);
    }

    /**
     * Count unread notifications
     */
    public static function getUnreadCount()
    {
        try {
            if (Schema::hasTable('notifications')) {
                $adminId = auth('admin')->id();
                $count = DB::table('notifications')
                    ->where('notifiable_type', Admin::class)
                    ->when($adminId, function($q) use ($adminId) {
                        $q->where('notifiable_id', $adminId);
                    })
                    ->whereNull('read_at')
                    ->count();

                if ($count > 0) {
                    return $count;
                }
            }
        } catch (\Throwable $e) {}

        // Fallback: pending items needing attention
        $pendingVendors = Vendor::where('approval_status', 'pending')->count();
        $pendingProducts = Product::where('approval_status', 'pending')->count();
        return $pendingVendors + $pendingProducts;
    }

    /**
     * Generate dynamic live activity stream from recent orders, vendors, and products
     */
    public static function getDynamicActivityStream($limit = 8)
    {
        $feed = collect();

        // 1. Orders Placed by Customers
        $orders = Order::with('customer')->latest()->take(4)->get();
        foreach ($orders as $order) {
            $feed->push((object) [
                'id' => 'ord_' . $order->id,
                'type' => 'order',
                'title' => 'New Order #' . $order->order_number,
                'message' => 'Customer ' . ($order->shipping_name ?? $order->customer->company_name ?? 'Buyer') . ' ordered for ₹' . number_format($order->grand_total, 2),
                'link' => route('admin.orders.show', $order->id),
                'icon' => 'bi-bag-check-fill text-success',
                'read_at' => null,
                'created_at' => $order->created_at,
            ]);
        }

        // 2. Vendors Registered / Pending
        $vendors = Vendor::latest()->take(3)->get();
        foreach ($vendors as $vendor) {
            $feed->push((object) [
                'id' => 'ven_' . $vendor->id,
                'type' => 'vendor',
                'title' => 'Vendor Store: ' . ($vendor->company_name ?? $vendor->name),
                'message' => 'Vendor registered with status: ' . ucfirst($vendor->approval_status),
                'link' => route('admin.vendors.show', $vendor->id),
                'icon' => 'bi-shop-window text-warning',
                'read_at' => null,
                'created_at' => $vendor->created_at,
            ]);
        }

        // 3. Products Submitted
        $products = Product::with('vendor')->latest()->take(3)->get();
        foreach ($products as $prod) {
            $feed->push((object) [
                'id' => 'prd_' . $prod->id,
                'type' => 'product',
                'title' => 'Product Listed: ' . Str::limit($prod->name, 25),
                'message' => 'By ' . ($prod->vendor->company_name ?? 'Vendor') . ' (' . ucfirst($prod->approval_status) . ')',
                'link' => route('admin.products.show', $prod->id),
                'icon' => 'bi-box-seam-fill text-danger',
                'read_at' => null,
                'created_at' => $prod->created_at,
            ]);
        }

        // 4. Sample Requests
        if (Schema::hasTable('sample_requests')) {
            $samples = \App\Models\SampleRequest::with(['vendor', 'product'])->latest()->take(3)->get();
            foreach ($samples as $smp) {
                $feed->push((object) [
                    'id' => 'smp_' . $smp->id,
                    'type' => 'sample',
                    'title' => 'Sample Request: #' . $smp->request_number,
                    'message' => ($smp->buyer_name ?? 'Buyer') . ' requested sample of ' . Str::limit($smp->product?->name ?? 'Material', 20),
                    'link' => route('admin.samples.show', $smp->id),
                    'icon' => 'bi-box-seam text-info',
                    'read_at' => null,
                    'created_at' => $smp->created_at,
                ]);
            }
        }

        return $feed->sortByDesc('created_at')->take($limit)->values();
    }

    /**
     * Send sample request alert to all admins
     */
    public static function sendSampleRequestNotification($sampleRequest)
    {
        $productName = $sampleRequest->product ? $sampleRequest->product->name : 'Product';
        $vendorName = $sampleRequest->vendor ? $sampleRequest->vendor->company_name : 'Vendor';
        $title = "🧪 New Sample Request: #{$sampleRequest->request_number}";
        $message = "Buyer '{$sampleRequest->buyer_name}' requested sample for '{$productName}' from '{$vendorName}'.";
        $link = route('admin.samples.show', $sampleRequest->id);

        self::send('sample_request', $title, $message, $link, 'bi-box2-heart-fill text-success');
    }

    /**
     * Send quotation request (RFQ) alert to all admins
     */
    public static function sendQuotationRequestNotification($quotation)
    {
        $productName = $quotation->product ? $quotation->product->name : 'Material';
        $vendorName = $quotation->vendor ? $quotation->vendor->company_name : 'Vendor';
        $buyerName = $quotation->buyer_name ?? ($quotation->company_name ?? 'Buyer');
        $qty = $quotation->quantity . ' ' . ($quotation->unit ?? 'Units');

        $title = "📋 New RFQ Submitted: #{$quotation->quotation_number}";
        $message = "Buyer '{$buyerName}' requested quote for '{$productName}' (Qty: {$qty}) from '{$vendorName}'.";
        $link = route('admin.quotations.show', $quotation->id);

        self::send('quotation_request', $title, $message, $link, 'bi-file-earmark-text-fill text-primary');
    }

    /**
     * Send quote proposal sent alert to all admins
     */
    public static function sendQuotationQuotedNotification($quotation)
    {
        $productName = $quotation->product ? $quotation->product->name : 'Material';
        $vendorName = $quotation->vendor ? $quotation->vendor->company_name : 'Vendor';
        $total = number_format($quotation->total_amount, 2);

        $title = "💰 Quote Proposal Sent: #{$quotation->quotation_number}";
        $message = "Vendor '{$vendorName}' sent price proposal of ₹{$total} for '{$productName}'.";
        $link = route('admin.quotations.show', $quotation->id);

        self::send('quotation_quoted', $title, $message, $link, 'bi-currency-rupee text-success');
    }
}

