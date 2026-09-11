<?php

namespace App\Http\Controllers\Auth\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('vendor.auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'email' => ['required', 'string', 'email', 'max:200', 'unique:vendors'],
            'phone' => ['required', 'string', 'max:15'],
            'company_name' => ['required', 'string', 'max:300'],
            'business_type' => ['required', 'in:manufacturer,wholesaler,trader,retailer,distributor'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $vendor = Vendor::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'company_name' => $validated['company_name'],
            'company_slug' => Str::slug($validated['company_name']) . '-' . Str::random(5),
            'business_type' => $validated['business_type'],
            'password' => Hash::make($validated['password']),
            'approval_status' => 'pending',
            'kyc_status' => 'pending',
            'status' => 'active',
        ]);

        Auth::guard('vendor')->login($vendor);

        // Assign Default Free Starter Plan if available
        $freePlan = \App\Models\SubscriptionPlan::forVendors()->where('monthly_price', '<=', 0)->first();
        if ($freePlan) {
            \App\Models\Subscription::create([
                'subscription_plan_id'   => $freePlan->id,
                'subscribable_type'      => \App\Models\Vendor::class,
                'subscribable_id'        => $vendor->id,
                'plan_name'              => $freePlan->name,
                'billing_cycle'          => 'monthly',
                'amount_paid'            => 0.00,
                'starts_at'              => now(),
                'expires_at'             => null,
                'status'                 => 'active',
                'payment_method'         => 'free',
                'remaining_boost_tokens' => $freePlan->sponsored_products_quota ?? 0,
            ]);
        }

        \App\Services\VendorNotificationService::ensureOnboardingMessages($vendor);

        \App\Services\AdminNotificationService::send(
            'vendor',
            'New Vendor Registered: ' . $vendor->company_name,
            "Vendor '{$vendor->company_name}' ({$vendor->name}) registered and submitted store for verification.",
            route('admin.vendors.show', $vendor->id),
            'bi-shop-window text-warning'
        );

        return redirect()->route('vendor.dashboard')->with('success', 'Registration submitted successfully! Welcome to Garudo Marketplace.');
    }
}
