<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Category;
use App\Models\HomepageSetting;
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

        // Storefront Launch & Tagline Settings
        $launchSettings = [
            'launch_mode_active'     => HomepageSetting::get('launch_mode_active', '1'),
            'launch_date'            => HomepageSetting::get('launch_date', '20th October'),
            'launch_strip_text'      => HomepageSetting::get('launch_strip_text', 'Official Launching on 20th October | Pre-Launch Preview'),
            'launch_notice_message'  => HomepageSetting::get('launch_notice_message', 'Official Launching on 20th October. Direct order purchasing will start on our official launch day!'),
            'header_tagline'         => HomepageSetting::get('header_tagline', "India's Leading B2B Commerce & Wholesale Network"),
            'header_tagline_mobile'  => HomepageSetting::get('header_tagline_mobile', "India's #1 B2B Commerce & Wholesale Network"),
        ];

        return view('admin.dashboard.index', compact('stats', 'pendingVendors', 'pendingProducts', 'recentOrders', 'liveActivity', 'launchSettings'));
    }

    /**
     * Quick toggle for Pre-Launch Mode
     */
    public function toggleLaunchMode(Request $request)
    {
        $current = HomepageSetting::get('launch_mode_active', '1');
        
        if ($request->has('active')) {
            $newStatus = $request->input('active') ? '1' : '0';
        } else {
            $newStatus = ($current === '1') ? '0' : '1';
        }

        HomepageSetting::set('launch_mode_active', $newStatus);

        $launchDate = HomepageSetting::get('launch_date', '20th October');
        $message = $newStatus === '1'
            ? "Pre-Launch Mode ACTIVATED. Top strip visible ({$launchDate}) and purchasing notices active."
            : "Pre-Launch Mode DEACTIVATED. Storefront is LIVE! Strip hidden and product purchasing is enabled.";

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'launch_mode_active' => $newStatus,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Update Launch Parameters & Header Taglines
     */
    public function updateLaunchSettings(Request $request)
    {
        $request->validate([
            'launch_date'           => 'required|string|max:100',
            'launch_strip_text'     => 'required|string|max:255',
            'launch_notice_message' => 'required|string|max:500',
            'header_tagline'        => 'required|string|max:255',
            'header_tagline_mobile' => 'required|string|max:255',
        ]);

        $launchModeActive = $request->has('launch_mode_active') ? '1' : '0';

        HomepageSetting::set('launch_mode_active', $launchModeActive);
        HomepageSetting::set('launch_date', $request->input('launch_date'));
        HomepageSetting::set('launch_strip_text', $request->input('launch_strip_text'));
        HomepageSetting::set('launch_notice_message', $request->input('launch_notice_message'));
        HomepageSetting::set('header_tagline', $request->input('header_tagline'));
        HomepageSetting::set('header_tagline_mobile', $request->input('header_tagline_mobile'));

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Storefront launch settings and header tagline updated successfully.',
            ]);
        }

        return back()->with('success', 'Storefront launch settings and header tagline updated successfully.');
    }
}
