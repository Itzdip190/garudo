<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $vendor = Auth::guard('vendor')->user();

        if (!$vendor) {
            return redirect()->route('vendor.login');
        }

        // Real Database Metric Stats
        $totalSales = (float) $vendor->orderItems()->sum('total_amount');
        $totalOrders = $vendor->orderItems()->distinct('order_id')->count('order_id');
        $storeViews = (int) $vendor->products()->sum('view_count');
        $activeProducts = $vendor->activeProducts()->count();
        $avgRating = $vendor->rating > 0 ? (float) $vendor->rating : 0.0;
        $walletBalance = (float) $vendor->orderItems()->where('item_status', 'delivered')->sum('vendor_payout');

        $stats = [
            'total_sales' => $totalSales,
            'total_orders' => $totalOrders,
            'store_views' => $storeViews,
            'total_products' => $vendor->products()->count(),
            'active_products' => $activeProducts,
            'avg_rating' => $avgRating,
            'wallet_balance' => $walletBalance,
            'pending_orders' => $vendor->orderItems()->whereIn('item_status', ['pending', 'processing'])->count(),
            'returns_count' => $vendor->orderItems()->where('item_status', 'returned')->count(),
            'quotations_count' => $vendor->quotations()->where('status', 'sent')->count(),
            'pending_enquiries' => $vendor->enquiries()->where('status', 'new')->count(),
        ];

        // Real Last 7 Days Sales Array for Spline Area Chart
        $chartDates = [];
        $chartSales = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $formattedDate = $date->format('d M');
            $chartDates[] = $formattedDate;

            $daySale = (float) $vendor->orderItems()
                ->whereDate('created_at', $date->toDateString())
                ->sum('total_amount');
            $chartSales[] = $daySale;
        }

        // Real Order Breakdown Counts for Donut Chart
        $orderCounts = [
            'delivered' => $vendor->orderItems()->where('item_status', 'delivered')->count(),
            'in_transit' => $vendor->orderItems()->where('item_status', 'shipped')->count(),
            'pending' => $vendor->orderItems()->whereIn('item_status', ['pending', 'confirmed', 'processing'])->count(),
            'cancelled' => $vendor->orderItems()->where('item_status', 'cancelled')->count(),
        ];

        // Real Recent Orders (Latest 5)
        $recentOrders = $vendor->orderItems()
            ->with(['order.customer', 'product.primaryImage'])
            ->latest()
            ->take(5)
            ->get();

        // Real Top Selling Products (Latest 5 by units sold / revenue)
        $topSellingProducts = $vendor->products()
            ->with(['primaryImage'])
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();

        return view('vendor.dashboard.index', compact(
            'vendor',
            'stats',
            'chartDates',
            'chartSales',
            'orderCounts',
            'recentOrders',
            'topSellingProducts'
        ));
    }
}
