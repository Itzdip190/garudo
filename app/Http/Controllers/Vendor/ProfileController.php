<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class ProfileController extends Controller
{
    /**
     * Show vendor profile & password reset view
     */
    public function index()
    {
        $vendor = Auth::guard('vendor')->user();
        return view('vendor.profile.index', compact('vendor'));
    }

    /**
     * Update vendor personal & company details
     */
    public function update(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'phone'        => 'required|string|max:20',
            'company_name' => 'required|string|max:255',
        ]);

        $vendor->name         = $validated['name'];
        $vendor->phone        = $validated['phone'];
        $vendor->company_name = $validated['company_name'];
        $vendor->save();

        return back()->with('success', 'Profile details updated successfully!');
    }

    /**
     * Reset / Change Password from Vendor Profile
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password'      => ['required', 'string'],
            'password'              => ['required', 'string', 'min:6', 'confirmed'],
            'password_confirmation' => ['required', 'string'],
        ], [
            'password.confirmed' => 'New password confirmation does not match.',
            'password.min'       => 'New password must be at least 6 characters.',
        ]);

        $vendor = Auth::guard('vendor')->user();

        if (!Hash::check($request->current_password, $vendor->password)) {
            return back()->withErrors(['current_password' => 'The provided current password does not match our records.'])->withInput();
        }

        $vendor->password = Hash::make($request->password);
        if (Schema::hasColumn('vendors', 'must_change_password')) {
            $vendor->must_change_password = false;
        }
        $vendor->save();

        return back()->with('password_success', 'Your password has been changed successfully!');
    }
}
