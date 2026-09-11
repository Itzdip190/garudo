<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\PasswordResetRequest;
use App\Models\Vendor;
use App\Services\AdminNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PasswordResetRequestController extends Controller
{
    /**
     * Show customer forgot password / reset request form
     */
    public function showCustomerRequestForm()
    {
        return view('customer.auth.forgot-password');
    }

    /**
     * Handle customer password reset request submission
     */
    public function storeCustomerRequest(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'phone' => ['nullable', 'string', 'max:25'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $email = strtolower(trim($request->email));
        $customer = Customer::where('email', $email)->first();

        // Check if there is already a pending request
        if (Schema::hasTable('password_reset_requests')) {
            $existing = PasswordResetRequest::where('email', $email)
                ->where('user_type', 'customer')
                ->where('status', 'pending')
                ->first();

            if ($existing) {
                return back()->with('info', 'A password reset request for this email is already pending Super Admin approval. Please contact the administrator.');
            }

            PasswordResetRequest::create([
                'user_type'   => 'customer',
                'user_id'     => $customer?->id,
                'email'       => $email,
                'phone'       => $request->phone ?: $customer?->phone,
                'name'        => $customer?->name ?: 'Customer',
                'status'      => 'pending',
                'admin_notes' => $request->notes,
            ]);

            // Notify Super Admin
            $customerName = $customer ? $customer->name : 'Buyer';
            AdminNotificationService::send(
                'password_reset_request',
                'Password Reset Request - Customer',
                "Customer '{$customerName}' ({$email}) has submitted a password reset request.",
                route('admin.password-requests.index'),
                'bi-key-fill'
            );
        }

        return back()->with('success', 'Your password reset request has been submitted to the Super Administrator. Once approved, you will receive a temporary demo password to sign in and set your new password.');
    }

    /**
     * Show vendor forgot password / reset request form
     */
    public function showVendorRequestForm()
    {
        return view('vendor.auth.forgot-password');
    }

    /**
     * Handle vendor password reset request submission
     */
    public function storeVendorRequest(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'phone' => ['nullable', 'string', 'max:25'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $email = strtolower(trim($request->email));
        $vendor = Vendor::where('email', $email)->first();

        // Check if there is already a pending request
        if (Schema::hasTable('password_reset_requests')) {
            $existing = PasswordResetRequest::where('email', $email)
                ->where('user_type', 'vendor')
                ->where('status', 'pending')
                ->first();

            if ($existing) {
                return back()->with('info', 'A password reset request for this vendor email is already pending Super Admin approval. Please contact the administrator.');
            }

            PasswordResetRequest::create([
                'user_type'   => 'vendor',
                'user_id'     => $vendor?->id,
                'email'       => $email,
                'phone'       => $request->phone ?: $vendor?->phone,
                'name'        => $vendor?->company_name ?: ($vendor?->name ?: 'Vendor'),
                'status'      => 'pending',
                'admin_notes' => $request->notes,
            ]);

            // Notify Super Admin
            $companyName = $vendor ? ($vendor->company_name ?: $vendor->name) : 'Vendor';
            AdminNotificationService::send(
                'password_reset_request',
                'Password Reset Request - Vendor',
                "Vendor '{$companyName}' ({$email}) has submitted a password reset request.",
                route('admin.password-requests.index'),
                'bi-shield-lock-fill'
            );
        }

        return back()->with('success', 'Your vendor password reset request has been submitted to the Super Administrator. Once approved, you will receive a temporary demo password to sign in and set your new password.');
    }
}
