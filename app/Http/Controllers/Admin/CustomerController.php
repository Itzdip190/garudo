<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
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

        $customers = $query->withCount(['orders'])->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => Customer::count(),
            'active' => Customer::where('status', 'active')->count(),
            'inactive' => Customer::where('status', '!=', 'active')->count(),
        ];

        return view('admin.customers.index', compact('customers', 'stats'));
    }

    public function show($id)
    {
        $customer = Customer::with(['orders' => function($q) {
            $q->latest()->take(10);
        }, 'addresses'])->findOrFail($id);

        $totalSpent = $customer->orders()->where('payment_status', 'paid')->sum('grand_total');

        return view('admin.customers.show', compact('customer', 'totalSpent'));
    }

    public function toggleStatus($id)
    {
        $customer = Customer::findOrFail($id);
        $newStatus = $customer->status === 'active' ? 'inactive' : 'active';
        $customer->update(['status' => $newStatus]);

        $message = $newStatus === 'active' 
            ? "Customer '{$customer->name}' is now Active." 
            : "Customer '{$customer->name}' has been Inactivated/Blocked.";

        return back()->with('success', $message);
    }

    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|min:6|max:100',
        ]);

        $customer = Customer::findOrFail($id);
        $customer->password = \Illuminate\Support\Facades\Hash::make($request->password);
        if (\Illuminate\Support\Facades\Schema::hasColumn('customers', 'must_change_password')) {
            $customer->must_change_password = true;
        }
        $customer->save();

        return back()->with('success', "Password for buyer '{$customer->name}' was updated to: {$request->password}. The customer will be required to create a new password on their next login.");
    }
}
