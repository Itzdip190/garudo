<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VendorAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('vendor')->check()) {
            return redirect()->route('vendor.login')->with('error', 'Please login to access the Vendor Dashboard.');
        }

        // Check if vendor is logged in with a temporary demo password
        $vendor = Auth::guard('vendor')->user();
        if ($vendor && !empty($vendor->must_change_password)) {
            $allowedRoutes = [
                'vendor.force_change_password',
                'vendor.force_change_password.submit',
                'vendor.logout',
            ];
            $currentRoute = $request->route() ? $request->route()->getName() : null;

            if (!in_array($currentRoute, $allowedRoutes, true)) {
                return redirect()->route('vendor.force_change_password')
                    ->with('warning', 'You logged in with a temporary demo password. Please set your new permanent password before proceeding.');
            }
        }

        return $next($request);
    }
}
