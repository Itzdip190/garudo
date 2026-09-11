<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromotionalCampaign;
use App\Models\Product;
use App\Services\VendorNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMarketingCampaignController extends Controller
{
    /**
     * List all vendor promotional boost campaigns and payment requests
     */
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');

        $query = PromotionalCampaign::with('vendor')->latest();

        if ($status === 'pending') {
            $query->where('status', 'pending_payment_approval');
        } elseif ($status === 'active') {
            $query->where('status', 'active');
        } elseif ($status === 'rejected') {
            $query->where('status', 'rejected');
        } elseif ($status === 'completed') {
            $query->where('status', 'completed');
        }

        $campaigns = $query->paginate(15)->withQueryString();

        $stats = [
            'pending_count' => PromotionalCampaign::where('status', 'pending_payment_approval')->count(),
            'active_count'  => PromotionalCampaign::where('status', 'active')->count(),
            'total_revenue' => PromotionalCampaign::where('status', 'active')->sum('total_payable'),
            'total_campaigns' => PromotionalCampaign::count(),
        ];

        return view('admin.marketing.campaigns', compact('campaigns', 'stats', 'status'));
    }

    /**
     * Approve and Activate Boost Campaign after Payment Confirmation
     */
    public function approve(Request $request, $id)
    {
        $campaign = PromotionalCampaign::with('vendor')->findOrFail($id);
        $admin = Auth::guard('admin')->user();

        $activatedAt = now();
        $expiresAt = now()->addDays($campaign->duration_days);

        $campaign->update([
            'status'        => 'active',
            'activated_at'  => $activatedAt,
            'expires_at'    => $expiresAt,
            'approved_by'   => $admin ? $admin->id : 1,
            'admin_notes'   => $request->admin_notes ?? 'Payment verified successfully via UTR: ' . ($campaign->payment_reference_utr ?? 'N/A'),
        ]);

        // Boost target products
        if (!empty($campaign->product_ids)) {
            Product::whereIn('id', $campaign->product_ids)->update(['is_featured' => true]);
        }

        // Notify Vendor
        if ($campaign->vendor) {
            VendorNotificationService::send(
                $campaign->vendor->id,
                'boost_activated',
                '🎉 Promotional Boost Activated: ' . $campaign->plan_title,
                "Your campaign #{$campaign->campaign_code} has been approved and activated by Admin. Your products are now featured in category results for {$campaign->duration_days} days.",
                route('vendor.marketing'),
                'bi-rocket-takeoff-fill text-success'
            );
        }

        return back()->with('success', "Campaign #{$campaign->campaign_code} approved! Payment confirmed and product boost activated until " . $expiresAt->format('d M Y') . ".");
    }

    /**
     * Reject a Boost Campaign Request
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:500',
        ]);

        $campaign = PromotionalCampaign::with('vendor')->findOrFail($id);
        $admin = Auth::guard('admin')->user();

        $campaign->update([
            'status'      => 'rejected',
            'approved_by' => $admin ? $admin->id : 1,
            'admin_notes' => $request->admin_notes,
        ]);

        // Notify Vendor
        if ($campaign->vendor) {
            VendorNotificationService::send(
                $campaign->vendor->id,
                'boost_rejected',
                '⚠️ Promotional Boost Request Rejected',
                "Campaign #{$campaign->campaign_code} was not approved. Reason: {$request->admin_notes}. Please verify payment details with support@garudo.in.",
                route('vendor.marketing'),
                'bi-x-circle-fill text-danger'
            );
        }

        return back()->with('error', "Campaign #{$campaign->campaign_code} marked as rejected.");
    }
}
