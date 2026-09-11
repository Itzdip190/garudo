<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    public function index()
    {
        $vendor = Auth::guard('vendor')->user();

        $totalSales = (float) $vendor->orderItems()->sum('total_amount');
        $totalOrders = $vendor->orderItems()->distinct('order_id')->count('order_id');
        $totalUnits = (int) $vendor->orderItems()->sum('quantity');

        return view('vendor.analytics.index', compact('vendor', 'totalSales', 'totalOrders', 'totalUnits'));
    }
}
