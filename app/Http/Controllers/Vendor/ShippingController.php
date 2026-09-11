<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShippingController extends Controller
{
    /**
     * Display Shipping & Delivery / Logistics Configuration page
     */
    public function index()
    {
        $vendor = Auth::guard('vendor')->user();
        $warehouses = $vendor->warehouses()->get();
        $primaryWarehouse = $vendor->primaryWarehouse;

        return view('vendor.shipping.index', compact('vendor', 'warehouses', 'primaryWarehouse'));
    }

    /**
     * Update Shipping, Dispatch Timeline, and F.O.R. Policy Configuration
     */
    public function update(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();

        $request->validate([
            'dispatch_time_type'        => 'required|string|in:1-2,2-4,5-7,custom',
            'custom_dispatch_min_days'  => 'nullable|required_if:dispatch_time_type,custom|integer|min:1|max:90',
            'custom_dispatch_max_days'  => 'nullable|required_if:dispatch_time_type,custom|integer|min:1|max:180|gte:custom_dispatch_min_days',
            'custom_dispatch_notes'     => 'nullable|string|max:255',
            'is_for_enabled'            => 'nullable|boolean',
            'for_condition_type'        => 'required|in:amount,quantity',
            'for_min_amount'            => 'nullable|numeric|min:0',
            'for_min_qty'               => 'nullable|integer|min:1',
            'for_qty_unit'              => 'nullable|string|max:50',
            'for_scope'                 => 'required|in:city,state,pan_india',
            'for_scope_details'         => 'nullable|string|max:255',
            'primary_dispatch_state'    => 'nullable|string|max:100',
        ]);

        $isForEnabled = $request->boolean('is_for_enabled', true);

        $vendor->update([
            'dispatch_time_type'        => $request->dispatch_time_type,
            'custom_dispatch_min_days'  => $request->dispatch_time_type === 'custom' ? $request->custom_dispatch_min_days : null,
            'custom_dispatch_max_days'  => $request->dispatch_time_type === 'custom' ? $request->custom_dispatch_max_days : null,
            'custom_dispatch_notes'     => $request->custom_dispatch_notes,
            'is_for_enabled'            => $isForEnabled,
            'for_condition_type'        => $request->for_condition_type,
            'for_min_amount'            => $request->for_min_amount ?? 50000.00,
            'for_min_qty'               => $request->for_min_qty ?? 100,
            'for_qty_unit'              => $request->for_qty_unit ?? 'Bags',
            'for_scope'                 => $request->for_scope,
            'for_scope_details'         => $request->for_scope_details,
            'primary_dispatch_state'    => $request->primary_dispatch_state ?? $vendor->primary_dispatch_state ?? 'Maharashtra, India',
        ]);

        return back()->with('success', 'Shipping and F.O.R. Delivery Logistics settings saved successfully!');
    }
}
