<?php

namespace App\Http\Controllers\Auth\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('customer.auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'email' => ['required', 'string', 'email', 'max:200', 'unique:customers'],
            'phone' => ['nullable', 'string', 'max:15'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $customer = Customer::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'status' => 'active',
        ]);

        Auth::guard('customer')->login($customer);

        $freeCustomerPlan = \App\Models\SubscriptionPlan::forCustomers()->where('monthly_price', '<=', 0)->first();
        if ($freeCustomerPlan) {
            \App\Models\Subscription::create([
                'subscription_plan_id'   => $freeCustomerPlan->id,
                'subscribable_type'      => \App\Models\Customer::class,
                'subscribable_id'        => $customer->id,
                'plan_name'              => $freeCustomerPlan->name,
                'billing_cycle'          => 'monthly',
                'amount_paid'            => 0.00,
                'starts_at'              => now(),
                'expires_at'             => null,
                'status'                 => 'active',
                'payment_method'         => 'free',
                'remaining_boost_tokens' => 0,
            ]);
        }

        return redirect()->route('customer.dashboard')->with('success', 'Account created successfully! Welcome to Garudo Marketplace.');
    }
}
