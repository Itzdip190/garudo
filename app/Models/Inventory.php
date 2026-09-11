<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'variant_id', 'warehouse_id',
        'quantity_available', 'quantity_reserved', 'quantity_incoming',
        'low_stock_threshold', 'low_stock_alert_sent',
        'track_inventory', 'allow_backorder', 'last_updated_at',
    ];

    protected function casts(): array
    {
        return [
            'quantity_available'   => 'integer',
            'quantity_reserved'    => 'integer',
            'quantity_incoming'    => 'integer',
            'low_stock_threshold' => 'integer',
            'low_stock_alert_sent' => 'boolean',
            'track_inventory'      => 'boolean',
            'allow_backorder'     => 'boolean',
            'last_updated_at'     => 'datetime',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function logs()
    {
        return $this->hasMany(InventoryLog::class)->latest();
    }
}
