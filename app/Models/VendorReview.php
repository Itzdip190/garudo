<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorReview extends Model
{
    use HasFactory;

    protected $table = 'vendor_reviews';

    protected $guarded = [];

    protected $casts = [
        'rating'                 => 'integer',
        'material_quality_rating'=> 'integer',
        'delivery_rating'        => 'integer',
        'pricing_rating'         => 'integer',
        'is_verified_buyer'      => 'boolean',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
