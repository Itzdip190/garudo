<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_gmv' => Order::where('payment_status', 'paid')->sum('grand_total'),
            'total_vendors' => Vendor::count(),
            'pending_vendors' => Vendor::where('approval_status', 'pending')->count(),
            'active_vendors' => Vendor::where('status', 'active')->count(),
            'total_customers' => Customer::count(),
            'total_products' => Product::count(),
            'pending_products' => Product::where('approval_status', 'pending')->count(),
            'total_orders' => Order::count(),
            'completed_orders' => Order::whereIn('status', ['delivered', 'completed'])->count(),
            'total_categories' => Category::count(),
        ];

        // Recent Pending Vendors awaiting KYC / Approval
        $pendingVendors = Vendor::where('approval_status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        // Recent Pending Products awaiting Review
        $pendingProducts = Product::with(['vendor', 'category'])
            ->where('approval_status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        // Recent Orders
        $recentOrders = Order::with(['customer'])
            ->latest()
            ->take(8)
            ->get();

        // Live Platform Activity Feed
        $liveActivity = \App\Services\AdminNotificationService::getLatest(6);

        return view('admin.dashboard.index', compact('stats', 'pendingVendors', 'pendingProducts', 'recentOrders', 'liveActivity'));
    }
}
