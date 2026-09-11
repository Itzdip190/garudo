<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\Subscription;
use App\Models\Vendor;
use App\Models\Customer;
use App\Services\AdminNotificationService;
use App\Services\VendorNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminSubscriptionPlanController extends Controller
{
    /**
     * Display all subscription plans
     */
    public function plans(Request $request)
    {
        $roleFilter = $request->query('role', 'all');

        $query = SubscriptionPlan::withCount('subscriptions');
        if ($roleFilter === 'vendor') {
            $query->where('target_role', 'vendor');
        } elseif ($roleFilter === 'customer') {
            $query->where('target_role', 'customer');
        }

        $plans = $query->orderBy('sort_order', 'asc')->get();

        return view('admin.subscriptions.plans', compact('plans', 'roleFilter'));
    }

    /**
     * Store a new subscription plan
     */
    public function storePlan(Request $request)
    {
        $validated = $request->validate([
            'name'                     => 'required|string|max:200',
            'target_role'              => 'required|in:vendor,customer',
            'badge_title'              => 'nullable|string|max:100',
            'description'              => 'nullable|string|max:1000',
            'monthly_price'            => 'required|numeric|min:0',
            'yearly_price'             => 'required|numeric|min:0',
            'has_24_7_support'         => 'nullable',
            'sponsored_products_quota' => 'nullable|integer|min:0',
            'commission_rate'          => 'required|numeric|min:0|max:100',
            'product_limit'            => 'required|integer',
            'custom_logistics_enabled' => 'nullable',
            'verified_badge_included'  => 'nullable',
            'rfq_access_level'         => 'required|in:standard,priority,unlimited_instant',
            'features_text'            => 'nullable|string',
            'is_featured'              => 'nullable',
            'is_active'                => 'nullable',
        ]);

        // Parse features text line by line
        $features = [];
        if (!empty($validated['features_text'])) {
            $lines = explode("\n", str_replace("\r", "", $validated['features_text']));
            foreach ($lines as $line) {
                $line = trim($line, " \t\n\r\0\x0B-•*");
                if (!empty($line)) {
                    $features[] = $line;
                }
            }
        }

        $slug = Str::slug($validated['target_role'] . '-' . $validated['name']) . '-' . Str::random(4);

        SubscriptionPlan::create([
            'name'                     => $validated['name'],
            'slug'                     => $slug,
            'target_role'              => $validated['target_role'],
            'badge_title'              => $validated['badge_title'] ?? null,
            'description'              => $validated['description'] ?? null,
            'monthly_price'            => $validated['monthly_price'],
            'yearly_price'             => $validated['yearly_price'],
            'billing_interval'         => 'monthly',
            'has_24_7_support'         => $request->boolean('has_24_7_support'),
            'sponsored_products_quota' => $validated['sponsored_products_quota'] ?? 0,
            'commission_rate'          => $validated['commission_rate'],
            'product_limit'            => $validated['product_limit'],
            'custom_logistics_enabled' => $request->boolean('custom_logistics_enabled'),
            'verified_badge_included'  => $request->boolean('verified_badge_included'),
            'rfq_access_level'         => $validated['rfq_access_level'],
            'features'                 => $features,
            'is_featured'              => $request->boolean('is_featured'),
            'is_active'                => $request->boolean('is_active', true),
            'sort_order'               => SubscriptionPlan::count() + 1,
        ]);

        return back()->with('success', "Subscription Plan '{$validated['name']}' created successfully.");
    }

    /**
     * Update an existing subscription plan
     */
    public function updatePlan(Request $request, $id)
    {
        $plan = SubscriptionPlan::findOrFail($id);

        $validated = $request->validate([
            'name'                     => 'required|string|max:200',
            'badge_title'              => 'nullable|string|max:100',
            'description'              => 'nullable|string|max:1000',
            'monthly_price'            => 'required|numeric|min:0',
            'yearly_price'             => 'required|numeric|min:0',
            'has_24_7_support'         => 'nullable',
            'sponsored_products_quota' => 'nullable|integer|min:0',
            'commission_rate'          => 'required|numeric|min:0|max:100',
            'product_limit'            => 'required|integer',
            'custom_logistics_enabled' => 'nullable',
            'verified_badge_included'  => 'nullable',
            'rfq_access_level'         => 'required|in:standard,priority,unlimited_instant',
            'features_text'            => 'nullable|string',
            'is_featured'              => 'nullable',
            'is_active'                => 'nullable',
        ]);

        $features = [];
        if (!empty($validated['features_text'])) {
            $lines = explode("\n", str_replace("\r", "", $validated['features_text']));
            foreach ($lines as $line) {
                $line = trim($line, " \t\n\r\0\x0B-•*");
                if (!empty($line)) {
                    $features[] = $line;
                }
            }
        }

        $plan->update([
            'name'                     => $validated['name'],
            'badge_title'              => $validated['badge_title'] ?? null,
            'description'              => $validated['description'] ?? null,
            'monthly_price'            => $validated['monthly_price'],
            'yearly_price'             => $validated['yearly_price'],
            'has_24_7_support'         => $request->boolean('has_24_7_support'),
            'sponsored_products_quota' => $validated['sponsored_products_quota'] ?? 0,
            'commission_rate'          => $validated['commission_rate'],
            'product_limit'            => $validated['product_limit'],
            'custom_logistics_enabled' => $request->boolean('custom_logistics_enabled'),
            'verified_badge_included'  => $request->boolean('verified_badge_included'),
            'rfq_access_level'         => $validated['rfq_access_level'],
            'features'                 => $features,
            'is_featured'              => $request->boolean('is_featured'),
            'is_active'                => $request->boolean('is_active', true),
        ]);

        return back()->with('success', "Subscription Plan '{$plan->name}' updated successfully.");
    }

    /**
     * Toggle active/inactive status of a plan
     */
    public function togglePlanStatus($id)
    {
        $plan = SubscriptionPlan::findOrFail($id);
        $plan->update(['is_active' => !$plan->is_active]);

        $statusStr = $plan->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Plan '{$plan->name}' has been {$statusStr}.");
    }

    /**
     * Delete a subscription plan
     */
    public function deletePlan($id)
    {
        $plan = SubscriptionPlan::findOrFail($id);
        $plan->delete();

        return back()->with('success', "Plan '{$plan->name}' deleted.");
    }

    /**
     * Display all subscribers & approvals
     */
    public function subscribers(Request $request)
    {
        $statusFilter = $request->query('status', 'all');
        $roleFilter = $request->query('role', 'all');

        $query = Subscription::with(['plan', 'subscribable', 'approvedByAdmin'])->latest();

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        if ($roleFilter === 'vendor') {
            $query->where('subscribable_type', Vendor::class);
        } elseif ($roleFilter === 'customer') {
            $query->where('subscribable_type', Customer::class);
        }

        $subscriptions = $query->paginate(15);
        $plans = SubscriptionPlan::where('is_active', true)->get();
        $vendors = Vendor::where('status', 'active')->orderBy('company_name', 'asc')->get();

        $stats = [
            'total_active'   => Subscription::where('status', 'active')->count(),
            'pending_review' => Subscription::where('status', 'pending_approval')->count(),
            'vendor_subs'    => Subscription::where('subscribable_type', Vendor::class)->where('status', 'active')->count(),
            'revenue_mrr'    => Subscription::where('status', 'active')->where('billing_cycle', 'monthly')->sum('amount_paid'),
        ];

        return view('admin.subscriptions.index', compact('subscriptions', 'plans', 'vendors', 'statusFilter', 'roleFilter', 'stats'));
    }

    /**
     * Approve a pending subscription (e.g. verified payment UTR)
     */
    public function approveSubscription(Request $request, $id)
    {
        $subscription = Subscription::with('plan', 'subscribable')->findOrFail($id);

        $days = ($subscription->billing_cycle === 'yearly') ? 365 : 30;

        $subscription->update([
            'status'                 => 'active',
            'starts_at'              => now(),
            'expires_at'             => now()->addDays($days),
            'remaining_boost_tokens' => $subscription->plan->sponsored_products_quota ?? 0,
            'approved_by'            => auth('admin')->id(),
            'admin_notes'            => $request->input('admin_notes', 'Approved by Admin on ' . now()->format('d M Y')),
        ]);

        // Send alert to vendor if subscribable is Vendor
        if ($subscription->subscribable instanceof Vendor) {
            VendorNotificationService::send(
                $subscription->subscribable->id,
                'subscription_active',
                "🎉 Subscription Plan Active: {$subscription->plan->name}",
                "Your subscription is now live! 24/7 Support: " . ($subscription->plan->has_24_7_support ? 'ACTIVE' : 'Standard') . " | Boost Tokens: {$subscription->remaining_boost_tokens}",
                route('vendor.subscription.index'),
                'bi-award-fill text-success'
            );
        }

        return back()->with('success', "Subscription for '{$subscription->plan->name}' approved and activated successfully!");
    }

    /**
     * Directly assign a subscription plan to a vendor or customer manually from Admin
     */
    public function assignSubscription(Request $request)
    {
        $validated = $request->validate([
            'target_type'          => 'required|in:vendor,customer',
            'target_id'            => 'required|integer',
            'subscription_plan_id' => 'required|exists:subscription_plans,id',
            'duration_months'      => 'required|integer|min:1|max:36',
            'admin_notes'          => 'nullable|string|max:500',
        ]);

        $plan = SubscriptionPlan::findOrFail($validated['subscription_plan_id']);
        $modelClass = ($validated['target_type'] === 'vendor') ? Vendor::class : Customer::class;
        $entity = $modelClass::findOrFail($validated['target_id']);

        $durationDays = $validated['duration_months'] * 30;

        // Cancel previous active subscriptions for this entity
        Subscription::where('subscribable_type', $modelClass)
            ->where('subscribable_id', $entity->id)
            ->where('status', 'active')
            ->update(['status' => 'cancelled']);

        $sub = Subscription::create([
            'subscription_plan_id'   => $plan->id,
            'subscribable_type'      => $modelClass,
            'subscribable_id'        => $entity->id,
            'plan_name'              => $plan->name,
            'billing_cycle'          => ($validated['duration_months'] >= 12) ? 'yearly' : 'monthly',
            'amount_paid'            => 0.00,
            'starts_at'              => now(),
            'expires_at'             => now()->addDays($durationDays),
            'status'                 => 'active',
            'payment_method'         => 'admin_assigned',
            'remaining_boost_tokens' => $plan->sponsored_products_quota ?? 0,
            'admin_notes'            => $validated['admin_notes'] ?? ('Assigned by Admin ' . auth('admin')->user()->name),
            'approved_by'            => auth('admin')->id(),
        ]);

        if ($entity instanceof Vendor) {
            VendorNotificationService::send(
                $entity->id,
                'subscription_active',
                "🌟 Plan Assigned by Admin: {$plan->name}",
                "You have been granted {$validated['duration_months']} months access to {$plan->name}. 24/7 Support & Sponsored tokens are active.",
                route('vendor.subscription.index'),
                'bi-patch-check-fill text-success'
            );
        }

        return back()->with('success', "Plan '{$plan->name}' successfully assigned to " . ($entity->company_name ?? $entity->name));
    }
}
