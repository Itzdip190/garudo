<?php

namespace App\Http\Controllers\Auth\Customer;

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
        $customer = Auth::guard('customer')->user();

        if (!$customer) {
            return redirect()->route('customer.login');
        }

        if (empty($customer->must_change_password)) {
            return redirect()->route('customer.dashboard');
        }

        return view('customer.auth.force-change-password', compact('customer'));
    }

    /**
     * Update password to new permanent one
     */
    public function update(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        if (!$customer) {
            return redirect()->route('customer.login');
        }

        $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'current_password.required' => 'Please enter the temporary demo password you used to log in.',
            'password.min'              => 'Your new password must be at least 6 characters.',
            'password.confirmed'        => 'New password confirmation does not match.',
        ]);

        if (!Hash::check($request->current_password, $customer->password)) {
            return back()->withErrors([
                'current_password' => 'The demo password entered does not match our records.',
            ]);
        }

        if (Hash::check($request->password, $customer->password)) {
            return back()->withErrors([
                'password' => 'Your new password cannot be the same as your temporary demo password. Please choose a new password.',
            ]);
        }

        $customer->password = Hash::make($request->password);
        if (Schema::hasColumn('customers', 'must_change_password')) {
            $customer->must_change_password = false;
        }
        $customer->save();

        // If there was an active approved request, mark it complete in admin notes
        if (Schema::hasTable('password_reset_requests')) {
            PasswordResetRequest::where('email', $customer->email)
                ->where('user_type', 'customer')
                ->where('status', 'approved')
                ->latest()
                ->first()
                ?->update([
                    'admin_notes' => 'Password successfully updated by customer upon sign-in.',
                ]);
        }

        return redirect()->route('customer.dashboard')
            ->with('success', 'Your new permanent password has been set successfully! Welcome to your customer dashboard.');
    }
}
