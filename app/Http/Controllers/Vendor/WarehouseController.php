<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class WarehouseController extends Controller
{
    protected function getVendor()
    {
        return Auth::guard('vendor')->user();
    }

    /**
     * Display all warehouses & depots for the vendor
     */
    public function index()
    {
        $vendor = $this->getVendor();
        $warehouses = $vendor->warehouses()->orderByDesc('is_primary')->latest()->get();

        $primaryWarehouse = $warehouses->firstWhere('is_primary', true);
        $primaryState = $primaryWarehouse ? $primaryWarehouse->state : ($vendor->primary_dispatch_state ?? 'Not Set');

        $stats = [
            'total' => $warehouses->count(),
            'active' => $warehouses->where('status', 'active')->count(),
            'primary_name' => $primaryWarehouse ? $primaryWarehouse->name : 'None Assigned',
            'primary_state' => $primaryState,
        ];

        // List of major Indian States for the warehouse dropdown
        $indianStates = [
            'Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chhattisgarh',
            'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jharkhand', 'Karnataka',
            'Kerala', 'Madhya Pradesh', 'Maharashtra', 'Manipur', 'Meghalaya', 'Mizoram',
            'Nagaland', 'Odisha', 'Punjab', 'Rajasthan', 'Sikkim', 'Tamil Nadu',
            'Telangana', 'Tripura', 'Uttar Pradesh', 'Uttarakhand', 'West Bengal',
            'Delhi', 'Jammu & Kashmir', 'Ladakh', 'Chandigarh'
        ];

        return view('vendor.warehouses.index', compact('vendor', 'warehouses', 'primaryWarehouse', 'primaryState', 'stats', 'indianStates'));
    }

    /**
     * Store a new warehouse
     */
    public function store(Request $request)
    {
        $vendor = $this->getVendor();

        $validated = $request->validate([
            'name'           => 'required|string|max:200',
            'code'           => 'nullable|string|max:50',
            'contact_person' => 'nullable|string|max:150',
            'phone'          => 'nullable|string|max:25',
            'email'          => 'nullable|email|max:150',
            'address_line1'  => 'required|string|max:500',
            'address_line2'  => 'nullable|string|max:500',
            'city'           => 'required|string|max:100',
            'state'          => 'required|string|max:100',
            'pincode'        => 'required|string|max:20',
            'is_primary'     => 'nullable|boolean',
            'notes'          => 'nullable|string|max:1000',
        ]);

        $isFirstWarehouse = ($vendor->warehouses()->count() === 0);
        $makePrimary = $request->boolean('is_primary') || $isFirstWarehouse;

        if ($makePrimary) {
            // Demote existing primary warehouses
            $vendor->warehouses()->update(['is_primary' => false]);
        }

        $warehouse = $vendor->warehouses()->create([
            'name'           => $validated['name'],
            'code'           => $validated['code'] ?: 'WH-' . strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $validated['city']), 0, 3)) . '-' . rand(10, 99),
            'contact_person' => $validated['contact_person'] ?? null,
            'phone'          => $validated['phone'] ?? null,
            'email'          => $validated['email'] ?? null,
            'address_line1'  => $validated['address_line1'],
            'address_line2'  => $validated['address_line2'] ?? null,
            'city'           => $validated['city'],
            'state'          => $validated['state'],
            'pincode'        => $validated['pincode'],
            'is_primary'     => $makePrimary,
            'status'         => 'active',
            'notes'          => $validated['notes'] ?? null,
        ]);

        if ($makePrimary) {
            $this->updateVendorPrimaryState($vendor, $warehouse);
        }

        return back()->with('success', "Warehouse '{$warehouse->name}' in {$warehouse->state} added successfully!" . ($makePrimary ? " Set as Primary Dispatch Origin." : ""));
    }

    /**
     * Update an existing warehouse
     */
    public function update(Request $request, $id)
    {
        $vendor = $this->getVendor();
        $warehouse = $vendor->warehouses()->findOrFail($id);

        $validated = $request->validate([
            'name'           => 'required|string|max:200',
            'code'           => 'nullable|string|max:50',
            'contact_person' => 'nullable|string|max:150',
            'phone'          => 'nullable|string|max:25',
            'email'          => 'nullable|email|max:150',
            'address_line1'  => 'required|string|max:500',
            'address_line2'  => 'nullable|string|max:500',
            'city'           => 'required|string|max:100',
            'state'          => 'required|string|max:100',
            'pincode'        => 'required|string|max:20',
            'is_primary'     => 'nullable|boolean',
            'status'         => 'required|in:active,inactive',
            'notes'          => 'nullable|string|max:1000',
        ]);

        $makePrimary = $request->boolean('is_primary');

        if ($makePrimary && !$warehouse->is_primary) {
            $vendor->warehouses()->where('id', '!=', $warehouse->id)->update(['is_primary' => false]);
            $warehouse->is_primary = true;
            $this->updateVendorPrimaryState($vendor, $warehouse);
        }

        $warehouse->update([
            'name'           => $validated['name'],
            'code'           => $validated['code'] ?: $warehouse->code,
            'contact_person' => $validated['contact_person'],
            'phone'          => $validated['phone'],
            'email'          => $validated['email'],
            'address_line1'  => $validated['address_line1'],
            'address_line2'  => $validated['address_line2'],
            'city'           => $validated['city'],
            'state'          => $validated['state'],
            'pincode'        => $validated['pincode'],
            'status'         => $validated['status'],
            'notes'          => $validated['notes'],
        ]);

        if ($warehouse->is_primary) {
            $this->updateVendorPrimaryState($vendor, $warehouse);
        }

        return back()->with('success', "Warehouse '{$warehouse->name}' updated successfully.");
    }

    /**
     * Designate this warehouse as the Primary Dispatch Warehouse and State
     */
    public function setPrimary($id)
    {
        $vendor = $this->getVendor();
        $warehouse = $vendor->warehouses()->findOrFail($id);

        $vendor->warehouses()->update(['is_primary' => false]);
        $warehouse->update(['is_primary' => true]);

        $this->updateVendorPrimaryState($vendor, $warehouse);

        return back()->with('success', "Primary Dispatch Origin switched to '{$warehouse->name}' ({$warehouse->city}, {$warehouse->state}). Future dispatches will default to this facility.");
    }

    /**
     * Delete a warehouse
     */
    public function destroy($id)
    {
        $vendor = $this->getVendor();
        $warehouse = $vendor->warehouses()->findOrFail($id);

        // Check if any in-transit shipments exist
        $activeShipments = $warehouse->shipments()->whereNotIn('status', ['delivered', 'cancelled'])->count();
        if ($activeShipments > 0) {
            return back()->with('error', "Cannot delete this warehouse because {$activeShipments} active shipment(s) are currently dispatched from it.");
        }

        $wasPrimary = $warehouse->is_primary;
        $name = $warehouse->name;
        $warehouse->delete();

        if ($wasPrimary) {
            $nextWarehouse = $vendor->warehouses()->active()->first();
            if ($nextWarehouse) {
                $nextWarehouse->update(['is_primary' => true]);
                $this->updateVendorPrimaryState($vendor, $nextWarehouse);
            } else {
                if (Schema::hasColumn('vendors', 'primary_dispatch_warehouse_id')) {
                    $vendor->update([
                        'primary_dispatch_warehouse_id' => null,
                        'primary_dispatch_state' => null,
                    ]);
                }
            }
        }

        return back()->with('success', "Warehouse '{$name}' has been removed.");
    }

    /**
     * Helper to keep vendor record in sync with primary dispatch state
     */
    protected function updateVendorPrimaryState($vendor, $warehouse)
    {
        if (Schema::hasColumn('vendors', 'primary_dispatch_warehouse_id') && Schema::hasColumn('vendors', 'primary_dispatch_state')) {
            $vendor->update([
                'primary_dispatch_warehouse_id' => $warehouse->id,
                'primary_dispatch_state'        => $warehouse->state,
            ]);
        }
    }
}
