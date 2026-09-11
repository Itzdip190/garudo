<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'barcode',
        'mrp',
        'selling_price',
        'wholesale_price',
        'cost_price',
        'weight',
        'image_id',
        'status',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'mrp'             => 'float',
            'selling_price'   => 'float',
            'wholesale_price' => 'float',
            'cost_price'      => 'float',
            'weight'          => 'float',
            'sort_order'      => 'integer',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function image()
    {
        return $this->belongsTo(ProductImage::class, 'image_id');
    }

    public function attributes()
    {
        return $this->hasMany(ProductVariantAttribute::class, 'variant_id');
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class, 'variant_id');
    }

    public function bulkPrices()
    {
        return $this->hasMany(ProductBulkPrice::class, 'variant_id');
    }
}
