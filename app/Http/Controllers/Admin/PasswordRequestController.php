<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\PasswordResetRequest;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class PasswordRequestController extends Controller
{
    /**
     * List all password reset requests with filters
     */
    public function index(Request $request)
    {
        if (!Schema::hasTable('password_reset_requests')) {
            return view('admin.password_requests.index', [
                'requests'      => collect([]),
                'pendingCount'  => 0,
                'approvedCount' => 0,
                'rejectedCount' => 0,
                'totalCount'    => 0,
                'statusFilter'  => 'all',
                'typeFilter'    => 'all',
                'search'        => '',
            ]);
        }

        $statusFilter = $request->query('status', 'all');
        $typeFilter   = $request->query('type', 'all');
        $search       = trim($request->query('search', ''));

        $query = PasswordResetRequest::latest();

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        if ($typeFilter !== 'all') {
            $query->where('user_type', $typeFilter);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $requests = $query->paginate(15)->withQueryString();

        $pendingCount  = PasswordResetRequest::where('status', 'pending')->count();
        $approvedCount = PasswordResetRequest::where('status', 'approved')->count();
        $rejectedCount = PasswordResetRequest::where('status', 'rejected')->count();
        $totalCount    = PasswordResetRequest::count();

        return view('admin.password_requests.index', compact(
            'requests',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'totalCount',
            'statusFilter',
            'typeFilter',
            'search'
        ));
    }

    /**
     * Approve request and issue a temporary demo password
     */
    public function approve(Request $request, $id)
    {
        $resetRequest = PasswordResetRequest::findOrFail($id);

        $request->validate([
            'demo_password' => ['nullable', 'string', 'min:6', 'max:50'],
            'admin_notes'   => ['nullable', 'string', 'max:500'],
        ]);

        // Generate demo password if not custom entered
        $demoPassword = $request->filled('demo_password')
            ? trim($request->demo_password)
            : 'Garudo@' . rand(1000, 9999);

        $userUpdated = false;

        if ($resetRequest->user_type === 'customer') {
            $customer = $resetRequest->customer ?: Customer::where('email', $resetRequest->email)->first();
            if ($customer) {
                $customer->password = Hash::make($demoPassword);
                if (Schema::hasColumn('customers', 'must_change_password')) {
                    $customer->must_change_password = true;
                }
                $customer->save();
                $userUpdated = true;
            }
        } elseif ($resetRequest->user_type === 'vendor') {
            $vendor = $resetRequest->vendor ?: Vendor::where('email', $resetRequest->email)->first();
            if ($vendor) {
                $vendor->password = Hash::make($demoPassword);
                if (Schema::hasColumn('vendors', 'must_change_password')) {
                    $vendor->must_change_password = true;
                }
                $vendor->save();
                $userUpdated = true;
            }
        }

        $resetRequest->update([
            'status'        => 'approved',
            'temp_password' => $demoPassword,
            'admin_notes'   => $request->admin_notes ?: $resetRequest->admin_notes,
            'handled_by'    => Auth::guard('admin')->id(),
            'handled_at'    => now(),
        ]);

        $msg = "Password request approved! Demo password '{$demoPassword}' has been set for {$resetRequest->email}. The user will be required to change it upon login.";

        if (!$userUpdated) {
            $msg .= " (Note: Matching user record was not found in {$resetRequest->user_type}s table by email).";
        }

        return back()->with('success', $msg)
                     ->with('demo_password', $demoPassword)
                     ->with('user_email', $resetRequest->email)
                     ->with('user_type', $resetRequest->user_type);
    }

    /**
     * Reject request
     */
    public function reject(Request $request, $id)
    {
        $resetRequest = PasswordResetRequest::findOrFail($id);

        $request->validate([
            'rejection_reason' => ['nullable', 'string', 'max:500'],
        ]);

        $resetRequest->update([
            'status'      => 'rejected',
            'admin_notes' => $request->rejection_reason ?: 'Request declined by Super Administrator.',
            'handled_by'  => Auth::guard('admin')->id(),
            'handled_at'  => now(),
        ]);

        return back()->with('success', "Password reset request for {$resetRequest->email} has been rejected.");
    }

    /**
     * Delete request record
     */
    public function destroy($id)
    {
        $resetRequest = PasswordResetRequest::findOrFail($id);
        $resetRequest->delete();

        return back()->with('success', 'Request record removed.');
    }
}
