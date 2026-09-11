<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryQuote extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'quote_amount' => 'decimal:2',
        'safety_rating' => 'decimal:2',
        'insurance_included' => 'boolean',
        'smart_score' => 'decimal:2',
        'is_recommended' => 'boolean',
        'is_lowest_price' => 'boolean',
        'is_fastest' => 'boolean',
    ];

    public function shipment()
    {
        return $this->belongsTo(DeliveryShipment::class, 'shipment_id');
    }

    public function partner()
    {
        return $this->belongsTo(DeliveryPartner::class, 'delivery_partner_id');
    }
}
