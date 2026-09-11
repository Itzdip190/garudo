<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $query = Vendor::query();

        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $query->where('approval_status', 'pending');
            } elseif ($request->status === 'approved') {
                $query->where('approval_status', 'approved');
            } elseif ($request->status === 'suspended') {
                $query->where('status', 'suspended');
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('gstin', 'like', "%{$search}%");
            });
        }

        $vendors = $query->withCount(['products'])->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => Vendor::count(),
            'pending' => Vendor::where('approval_status', 'pending')->count(),
            'approved' => Vendor::where('approval_status', 'approved')->count(),
            'suspended' => Vendor::where('status', 'suspended')->count(),
        ];

        return view('admin.vendors.index', compact('vendors', 'stats'));
    }

    public function show($id)
    {
        $vendor = Vendor::with(['products' => function($q) {
            $q->latest()->take(10);
        }])->findOrFail($id);

        return view('admin.vendors.show', compact('vendor'));
    }

    public function approve($id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->update([
            'approval_status' => 'approved',
            'kyc_status' => 'verified',
            'status' => 'active',
            'approved_at' => now(),
            'approved_by' => auth('admin')->id(),
            'rejection_reason' => null,
        ]);

        return back()->with('success', "Vendor '{$vendor->company_name}' has been successfully approved and activated!");
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $vendor = Vendor::findOrFail($id);
        $vendor->update([
            'approval_status' => 'rejected',
            'kyc_status' => 'rejected',
            'status' => 'inactive',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('warning', "Vendor '{$vendor->company_name}' has been rejected. Reason recorded.");
    }

    public function toggleStatus($id)
    {
        $vendor = Vendor::findOrFail($id);
        $newStatus = $vendor->status === 'active' ? 'suspended' : 'active';
        $vendor->update(['status' => $newStatus]);

        $message = $newStatus === 'active' 
            ? "Vendor '{$vendor->company_name}' is now Active." 
            : "Vendor '{$vendor->company_name}' has been Suspended.";

        return back()->with('success', $message);
    }

    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|min:6|max:100',
        ]);

        $vendor = Vendor::findOrFail($id);
        $vendor->password = \Illuminate\Support\Facades\Hash::make($request->password);
        if (\Illuminate\Support\Facades\Schema::hasColumn('vendors', 'must_change_password')) {
            $vendor->must_change_password = true;
        }
        $vendor->save();

        return back()->with('success', "Password for vendor '{$vendor->company_name}' was updated to: {$request->password}. The vendor will be required to create a new password on their next login.");
    }
}
