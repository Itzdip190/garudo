<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerMembershipController extends Controller
{
    /**
     * Display customer membership tiers and benefits
     */
    public function index()
    {
        $customer = Auth::guard('customer')->user();
        if (!$customer) {
            return redirect()->route('customer.login');
        }

        $plans = SubscriptionPlan::forCustomers()
            ->active()
            ->get();

        $activeSubscription = $customer->activeSubscription;

        return view('customer.membership.index', compact('customer', 'plans', 'activeSubscription'));
    }
}
