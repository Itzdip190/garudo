<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinanceController extends Controller
{
    protected function getVendor()
    {
        return Auth::guard('vendor')->user();
    }

    public function earnings()
    {
        $vendor = $this->getVendor();

        $totalEarnings = (float) $vendor->orderItems()->where('item_status', 'delivered')->sum('vendor_payout');
        $pendingPayouts = (float) $vendor->orderItems()->where('item_status', 'delivered')->where('payout_status', 'pending')->sum('vendor_payout');
        $completedPayouts = (float) $vendor->orderItems()->where('payout_status', 'paid')->sum('vendor_payout');

        $payoutHistory = $vendor->orderItems()
            ->where('item_status', 'delivered')
            ->latest('payout_at')
            ->paginate(15);

        return view('vendor.finance.earnings', compact('vendor', 'totalEarnings', 'pendingPayouts', 'completedPayouts', 'payoutHistory'));
    }

    public function payments()
    {
        $vendor = $this->getVendor();

        $transactions = $vendor->orderItems()
            ->with('order')
            ->latest()
            ->paginate(15);

        return view('vendor.finance.payments', compact('vendor', 'transactions'));
    }
}
