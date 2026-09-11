<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'mrp' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'gst_rate' => 'decimal:2',
        'gst_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'vendor_payout' => 'decimal:2',
        'variant_info' => 'array',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'payout_at' => 'datetime',
        'is_custom_order' => 'boolean',
        'production_lead_days' => 'integer',
        'estimated_ready_date' => 'date',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function shipment()
    {
        return $this->hasOne(DeliveryShipment::class, 'order_item_id')->latestOfMany();
    }

    public function shipments()
    {
        return $this->hasMany(DeliveryShipment::class, 'order_item_id');
    }

    /**
     * Customer-Facing Masked Vendor Name (Protects Vendor Identity)
     */
    public function getMaskedVendorHubAttribute(): string
    {
        $code = 'GH-' . str_pad($this->vendor_id ?? 1, 4, '0', STR_PAD_LEFT);
        return "Garudo Certified Fulfillment Hub ({$code})";
    }

    /**
     * Human-Readable Production Stage Label
     */
    public function getProductionStageLabelAttribute(): string
    {
        return match ($this->production_stage) {
            'order_accepted' => 'Order Accepted & Production Queued',
            'material_procurement' => 'Raw Materials Procured',
            'in_production' => 'Custom Fabrication / In Production',
            'quality_testing' => 'Quality & Safety Inspection',
            'packing_completed' => 'Packed & Awaiting Dispatch Trigger',
            'ready_for_dispatch' => 'Ready for Pickup / Logistics Triggered',
            default => 'Standard Order Processing',
        };
    }
}
