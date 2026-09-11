<?php

namespace App\Http\Controllers\Auth\Vendor;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class ForcePasswordChangeController extends Controller
{
    /**
     * Show force change password form
     */
    public function showForm()
    {
        $vendor = Auth::guard('vendor')->user();

        if (!$vendor) {
            return redirect()->route('vendor.login');
        }

        if (empty($vendor->must_change_password)) {
            return redirect()->route('vendor.dashboard');
        }

        return view('vendor.auth.force-change-password', compact('vendor'));
    }

    /**
     * Update password to new permanent one
     */
    public function update(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();

        if (!$vendor) {
            return redirect()->route('vendor.login');
        }

        $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'current_password.required' => 'Please enter the temporary demo password provided by the Super Admin.',
            'password.min'              => 'Your new password must be at least 6 characters.',
            'password.confirmed'        => 'New password confirmation does not match.',
        ]);

        if (!Hash::check($request->current_password, $vendor->password)) {
            return back()->withErrors([
                'current_password' => 'The demo password entered does not match our records.',
            ]);
        }

        if (Hash::check($request->password, $vendor->password)) {
            return back()->withErrors([
                'password' => 'Your new password cannot be the same as your temporary demo password. Please choose a new password.',
            ]);
        }

        $vendor->password = Hash::make($request->password);
        if (Schema::hasColumn('vendors', 'must_change_password')) {
            $vendor->must_change_password = false;
        }
        $vendor->save();

        // If there was an active approved request, mark it complete in admin notes
        if (Schema::hasTable('password_reset_requests')) {
            PasswordResetRequest::where('email', $vendor->email)
                ->where('user_type', 'vendor')
                ->where('status', 'approved')
                ->latest()
                ->first()
                ?->update([
                    'admin_notes' => 'Password successfully updated by vendor upon sign-in.',
                ]);
        }

        return redirect()->route('vendor.dashboard')
            ->with('success', 'Your new permanent password has been set successfully! Welcome to your Vendor Hub.');
    }
}
