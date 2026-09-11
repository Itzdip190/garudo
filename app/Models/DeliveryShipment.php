<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryShipment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'pickup_otp_verified' => 'boolean',
        'pickup_otp_verified_at' => 'datetime',
        'delivery_otp_verified' => 'boolean',
        'delivery_otp_verified_at' => 'datetime',
        'eway_bill_generated_at' => 'datetime',
        'package_weight_kg' => 'decimal:2',
        'quoted_delivery_cost' => 'decimal:2',
        'estimated_pickup_date' => 'datetime',
        'estimated_delivery_date' => 'datetime',
        'actual_pickup_at' => 'datetime',
        'actual_delivered_at' => 'datetime',
    ];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function deliveryPartner()
    {
        return $this->belongsTo(DeliveryPartner::class);
    }

    public function quotes()
    {
        return $this->hasMany(DeliveryQuote::class, 'shipment_id');
    }

    public function recommendedQuote()
    {
        return $this->hasOne(DeliveryQuote::class, 'shipment_id')->where('is_recommended', true);
    }

    public function selectedQuote()
    {
        return $this->hasOne(DeliveryQuote::class, 'shipment_id')->where('status', 'selected');
    }

    public function dispatchWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'dispatch_warehouse_id');
    }
}
