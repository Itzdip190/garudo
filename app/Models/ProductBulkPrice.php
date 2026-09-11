<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductBulkPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'variant_id',
        'label',
        'min_qty',
        'max_qty',
        'price',
        'price_type',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'min_qty'    => 'integer',
            'max_qty'    => 'integer',
            'price'      => 'float',
            'sort_order' => 'integer',
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
}
