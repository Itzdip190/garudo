<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\PromotionalCampaign;
use App\Models\Product;
use App\Services\AdminNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MarketingController extends Controller
{
    /**
     * Display marketing dashboard with coupons and boost campaigns
     */
    public function index()
    {
        $vendor = Auth::guard('vendor')->user();
        $coupons = Coupon::where('vendor_id', $vendor->id)->latest()->get();
        $campaigns = PromotionalCampaign::where('vendor_id', $vendor->id)->latest()->get();
        $products = Product::where('vendor_id', $vendor->id)->where('status', 'active')->get();

        $stats = [
            'total_coupons'     => $coupons->count(),
            'active_coupons'    => $coupons->where('status', 'active')->count(),
            'active_campaigns'  => $campaigns->where('status', 'active')->count(),
            'pending_campaigns' => $campaigns->where('status', 'pending_payment_approval')->count(),
            'total_impressions' => $campaigns->sum('impressions_count'),
            'total_clicks'      => $campaigns->sum('clicks_count'),
        ];

        return view('vendor.marketing.index', compact('vendor', 'coupons', 'campaigns', 'products', 'stats'));
    }

    /**
     * Create a new vendor coupon
     */
    public function storeCoupon(Request $request)
    {
        $request->validate([
            'code'               => 'required|string|max:30|alpha_dash|unique:coupons,code',
            'title'              => 'required|string|max:150',
            'discount_type'      => 'required|in:percentage,fixed',
            'discount_value'     => 'required|numeric|min:1',
            'min_order_amount'   => 'nullable|numeric|min:0',
            'max_discount_amount'=> 'nullable|numeric|min:0',
            'usage_limit'        => 'nullable|integer|min:1',
            'start_date'         => 'nullable|date',
            'end_date'           => 'nullable|date|after_or_equal:start_date',
        ]);

        $vendor = Auth::guard('vendor')->user();

        Coupon::create([
            'vendor_id'          => $vendor->id,
            'code'               => strtoupper($request->code),
            'title'              => $request->title,
            'description'        => $request->description ?? "Special discount coupon by {$vendor->company_name}",
            'discount_type'      => $request->discount_type,
            'discount_value'     => $request->discount_value,
            'min_order_amount'   => $request->min_order_amount ?? 0.00,
            'max_discount_amount'=> $request->max_discount_amount,
            'usage_limit'        => $request->usage_limit,
            'used_count'         => 0,
            'start_date'         => $request->start_date ?? now()->toDateString(),
            'end_date'           => $request->end_date,
            'status'             => 'active',
        ]);

        return back()->with('success', "Coupon '{$request->code}' created and published successfully!");
    }

    /**
     * Toggle coupon active/inactive status
     */
    public function toggleCoupon($id)
    {
        $vendor = Auth::guard('vendor')->user();
        $coupon = Coupon::where('vendor_id', $vendor->id)->findOrFail($id);

        $coupon->status = $coupon->status === 'active' ? 'inactive' : 'active';
        $coupon->save();

        return back()->with('success', "Coupon '{$coupon->code}' status updated to {$coupon->status}.");
    }

    /**
     * Delete a coupon
     */
    public function deleteCoupon($id)
    {
        $vendor = Auth::guard('vendor')->user();
        $coupon = Coupon::where('vendor_id', $vendor->id)->findOrFail($id);
        $code = $coupon->code;
        $coupon->delete();

        return back()->with('success', "Coupon '{$code}' deleted successfully.");
    }

    /**
     * Launch a new promotional boost campaign (Sent to Admin for payment verification)
     */
    public function launchBoost(Request $request)
    {
        $request->validate([
            'plan_tier'              => 'required|in:category_top,homepage_spotlight,pan_india_boost',
            'product_ids'            => 'required|array|min:1',
            'product_ids.*'          => 'integer|exists:products,id',
            'payment_mode'           => 'required|string|in:upi_qr,bank_transfer,netbanking',
            'payment_reference_utr'  => 'required|string|min:4|max:100',
        ]);

        $vendor = Auth::guard('vendor')->user();

        // Calculate Pricing Tiers
        $plans = [
            'category_top' => [
                'title' => 'Category Top Placement Boost',
                'days' => 7,
                'amount' => 1499.00,
            ],
            'homepage_spotlight' => [
                'title' => 'Homepage Spotlight & Featured Showcase',
                'days' => 14,
                'amount' => 2999.00,
            ],
            'pan_india_boost' => [
                'title' => 'High-Priority Pan-India Search Boost & Verified Badge',
                'days' => 30,
                'amount' => 4999.00,
            ],
        ];

        $selectedPlan = $plans[$request->plan_tier] ?? $plans['category_top'];
        $amount = $selectedPlan['amount'];
        $gstAmount = round($amount * 0.18, 2);
        $totalPayable = $amount + $gstAmount;
        $campaignCode = 'BST-' . strtoupper(Str::random(6)) . '-' . date('y');

        $campaign = PromotionalCampaign::create([
            'campaign_code'          => $campaignCode,
            'vendor_id'              => $vendor->id,
            'plan_tier'              => $request->plan_tier,
            'plan_title'             => $selectedPlan['title'],
            'duration_days'          => $selectedPlan['days'],
            'product_ids'            => array_map('intval', $request->product_ids),
            'amount'                 => $amount,
            'gst_amount'             => $gstAmount,
            'total_payable'          => $totalPayable,
            'payment_mode'           => $request->payment_mode,
            'payment_reference_utr'  => $request->payment_reference_utr,
            'status'                 => 'pending_payment_approval',
            'requested_at'           => now(),
            'impressions_count'      => 0,
            'clicks_count'           => 0,
        ]);

        // Send alert to Admin to review payment & activate
        AdminNotificationService::send(
            'boost_campaign_request',
            "New Boost Request: #{$campaignCode}",
            "Vendor {$vendor->company_name} submitted boost '{$selectedPlan['title']}' for ₹" . number_format($totalPayable, 2) . ". UTR Ref: {$request->payment_reference_utr}. Please verify payment.",
            route('admin.marketing.campaigns'),
            'bi-megaphone-fill text-warning'
        );

        return back()->with('success', "Promotional Boost request #{$campaignCode} submitted successfully! Your campaign has been sent to Admin for payment verification (UTR: {$request->payment_reference_utr}). Admin will activate the boost once payment is confirmed.");
    }
}
