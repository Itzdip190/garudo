<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();

        // Get customers who bought from this vendor
        $customerIds = $vendor->orderItems()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->pluck('orders.customer_id')
            ->unique();

        $customers = Customer::whereIn('id', $customerIds)->latest()->paginate(15);

        return view('vendor.customers.index', compact('vendor', 'customers'));
    }
}
