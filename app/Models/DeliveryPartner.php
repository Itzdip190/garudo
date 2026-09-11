<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryPartner extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'service_types' => 'array',
        'rating' => 'decimal:2',
        'base_rate_per_kg' => 'decimal:2',
        'is_active' => 'boolean',
        'supports_otp_verification' => 'boolean',
        'provides_transit_insurance' => 'boolean',
    ];

    public function shipments()
    {
        return $this->hasMany(DeliveryShipment::class);
    }

    public function quotes()
    {
        return $this->hasMany(DeliveryQuote::class);
    }
}
