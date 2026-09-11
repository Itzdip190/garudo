<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $table = 'warehouses';

    protected $fillable = [
        'vendor_id',
        'name',
        'code',
        'contact_person',
        'phone',
        'email',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'pincode',
        'is_primary',
        'status',
        'notes',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    /**
     * Relationship to Vendor
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Relationship to Delivery Shipments dispatched from this warehouse
     */
    public function shipments()
    {
        return $this->hasMany(DeliveryShipment::class, 'dispatch_warehouse_id');
    }

    /**
     * Relationship to Products assigned to this warehouse
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'warehouse_id');
    }

    /**
     * Get full formatted address string
     */
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address_line1,
            $this->address_line2,
            $this->city,
            $this->state,
            $this->pincode ? "PIN: {$this->pincode}" : null,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Scope for active warehouses
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for primary warehouse
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }
}
