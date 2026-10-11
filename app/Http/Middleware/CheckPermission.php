<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            abort(401, 'Unauthenticated.');
        }

        if ($admin->is_super_admin) {
            return $next($request);
        }

        $permissions = explode('|', $permission);
        $hasAny = false;
        foreach ($permissions as $perm) {
            if ($admin->hasPermission(trim($perm))) {
                $hasAny = true;
                break;
            }
        }

        if (!$hasAny) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized action.'], 403);
            }
            abort(403, 'Unauthorized action: Your account does not have the required access permission [' . $permission . '].');
        }

        return $next($request);
    }
}
