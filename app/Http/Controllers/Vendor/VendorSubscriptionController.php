<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\Subscription;
use App\Services\AdminNotificationService;
use App\Services\VendorNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorSubscriptionController extends Controller
{
    /**
     * Display available plans and current active subscription
     */
    public function index()
    {
        $vendor = Auth::guard('vendor')->user();

        $plans = SubscriptionPlan::forVendors()
            ->active()
            ->get();

        $activeSubscription = $vendor->activeSubscription;
        $currentPlan = $vendor->current_plan;

        $pendingSubscription = $vendor->subscriptions()
            ->where('status', 'pending_approval')
            ->latest()
            ->first();

        $subscriptionHistory = $vendor->subscriptions()
            ->with('plan')
            ->latest()
            ->take(10)
            ->get();

        return view('vendor.subscription.index', compact(
            'vendor',
            'plans',
            'activeSubscription',
            'currentPlan',
            'pendingSubscription',
            'subscriptionHistory'
        ));
    }

    /**
     * Handle subscription request / plan upgrade
     */
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'plan_id'           => 'required|exists:subscription_plans,id',
            'billing_cycle'     => 'required|in:monthly,yearly',
            'payment_reference' => 'nullable|string|max:100',
        ]);

        $vendor = Auth::guard('vendor')->user();
        $plan = SubscriptionPlan::findOrFail($validated['plan_id']);

        $price = ($validated['billing_cycle'] === 'yearly') ? $plan->yearly_price : $plan->monthly_price;
        $isFree = ((float) $price <= 0);

        if ($isFree) {
            // Cancel previous subscriptions
            Subscription::where('subscribable_type', \App\Models\Vendor::class)
                ->where('subscribable_id', $vendor->id)
                ->where('status', 'active')
                ->update(['status' => 'cancelled']);

            $sub = Subscription::create([
                'subscription_plan_id'   => $plan->id,
                'subscribable_type'      => \App\Models\Vendor::class,
                'subscribable_id'        => $vendor->id,
                'plan_name'              => $plan->name,
                'billing_cycle'          => $validated['billing_cycle'],
                'amount_paid'            => 0.00,
                'starts_at'              => now(),
                'expires_at'             => null, // Free forever
                'status'                 => 'active',
                'payment_method'         => 'free',
                'remaining_boost_tokens' => $plan->sponsored_products_quota ?? 0,
            ]);

            return back()->with('success', "You are now on the {$plan->name}!");
        }

        // Paid Plan: Require UTR reference
        if (empty($validated['payment_reference'])) {
            return back()->with('error', 'Please provide the UPI Transaction ID / UTR reference number.');
        }

        // Cancel previous pending requests
        Subscription::where('subscribable_type', \App\Models\Vendor::class)
            ->where('subscribable_id', $vendor->id)
            ->where('status', 'pending_approval')
            ->delete();

        $sub = Subscription::create([
            'subscription_plan_id'   => $plan->id,
            'subscribable_type'      => \App\Models\Vendor::class,
            'subscribable_id'        => $vendor->id,
            'plan_name'              => $plan->name,
            'billing_cycle'          => $validated['billing_cycle'],
            'amount_paid'            => $price,
            'starts_at'              => null,
            'expires_at'             => null,
            'status'                 => 'pending_approval',
            'payment_method'         => 'bank_transfer_utr',
            'payment_reference'      => $validated['payment_reference'],
            'remaining_boost_tokens' => $plan->sponsored_products_quota ?? 0,
        ]);

        // Send alert to Admin
        AdminNotificationService::send(
            'subscription_request',
            "New Subscription Upgrade: {$vendor->company_name}",
            "Vendor '{$vendor->company_name}' submitted UTR '{$validated['payment_reference']}' for {$plan->name} (₹" . number_format($price, 2) . ").",
            route('admin.subscriptions.index', ['status' => 'pending_approval']),
            'bi-credit-card-2-front text-warning'
        );

        return back()->with('success', "Your subscription request for {$plan->name} has been submitted! Admin will verify your payment UTR and activate your 24/7 support & sponsored boosts.");
    }
}
