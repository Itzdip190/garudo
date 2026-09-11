<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryLog extends Model
{
    use HasFactory;

    public $timestamps = false; // Uses created_at only

    protected $fillable = [
        'inventory_id', 'product_id', 'variant_id', 'action',
        'quantity_before', 'quantity_changed', 'quantity_after',
        'reference_type', 'reference_id', 'note',
        'created_by_type', 'created_by_id', 'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}
