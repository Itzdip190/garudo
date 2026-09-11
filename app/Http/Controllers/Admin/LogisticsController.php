<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryPartner;
use Database\Seeders\DeliveryPartnerSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class LogisticsController extends Controller
{
    public function index()
    {
        if (!Schema::hasTable('delivery_partners')) {
            $partners = collect();
            $stats = [
                'total_carriers' => 0,
                'active_carriers' => 0,
                'avg_safety_score' => 0,
            ];
            $migrationNeeded = true;
            return view('admin.logistics.index', compact('partners', 'stats', 'migrationNeeded'));
        }

        // Auto-seed default delivery partners if table is empty
        if (DeliveryPartner::count() === 0) {
            try {
                $seeder = new DeliveryPartnerSeeder();
                $seeder->run();
            } catch (\Throwable $e) {}
        }

        $partners = DeliveryPartner::all();
        
        $stats = [
            'total_carriers' => DeliveryPartner::count(),
            'active_carriers' => DeliveryPartner::where('is_active', true)->count(),
            'avg_safety_score' => round(DeliveryPartner::avg('safety_score') ?? 98, 1),
        ];
        $migrationNeeded = false;

        return view('admin.logistics.index', compact('partners', 'stats', 'migrationNeeded'));
    }

    public function toggleStatus($id)
    {
        $partner = DeliveryPartner::findOrFail($id);
        $partner->update(['is_active' => !$partner->is_active]);

        $status = $partner->is_active ? 'Activated' : 'Deactivated';
        return back()->with('success', "Carrier '{$partner->name}' is now {$status}.");
    }

    public function updateRate(Request $request, $id)
    {
        $request->validate([
            'base_rate_per_kg' => 'required|numeric|min:0',
        ]);

        $partner = DeliveryPartner::findOrFail($id);
        $partner->update(['base_rate_per_kg' => $request->base_rate_per_kg]);

        return back()->with('success', "Carrier '{$partner->name}' rate updated to ₹{$request->base_rate_per_kg}/kg.");
    }
}
