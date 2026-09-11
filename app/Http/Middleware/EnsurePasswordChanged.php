<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordChanged
{
    /**
     * Handle an incoming request.
     * Intercept users with temporary demo passwords and force them to set a new password.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check authenticated Customer
        if (Auth::guard('customer')->check()) {
            $customer = Auth::guard('customer')->user();
            if ($customer && !empty($customer->must_change_password)) {
                $allowedCustomerRoutes = [
                    'customer.force_change_password',
                    'customer.force_change_password.submit',
                    'customer.logout',
                ];

                $currentRouteName = $request->route() ? $request->route()->getName() : null;

                if (!in_array($currentRouteName, $allowedCustomerRoutes, true)) {
                    return redirect()->route('customer.force_change_password')
                        ->with('warning', 'You are logged in with a temporary demo password. Please set your new permanent password before proceeding.');
                }
            }
        }

        // Check authenticated Vendor
        if (Auth::guard('vendor')->check()) {
            $vendor = Auth::guard('vendor')->user();
            if ($vendor && !empty($vendor->must_change_password)) {
                $allowedVendorRoutes = [
                    'vendor.force_change_password',
                    'vendor.force_change_password.submit',
                    'vendor.logout',
                ];

                $currentRouteName = $request->route() ? $request->route()->getName() : null;

                if (!in_array($currentRouteName, $allowedVendorRoutes, true)) {
                    return redirect()->route('vendor.force_change_password')
                        ->with('warning', 'You are logged in with a temporary demo password. Please set your new permanent password before proceeding.');
                }
            }
        }

        return $next($request);
    }
}
