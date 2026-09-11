<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VendorApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        $vendor = Auth::guard('vendor')->user();

        if ($vendor && $vendor->approval_status !== 'approved') {
            return redirect()->route('vendor.dashboard')->with('warning', 'Your vendor account is pending admin approval.');
        }

        return $next($request);
    }
}
