<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'document_type',
        'file_path',
        'file_name',
        'file_size',
        'is_public',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'file_size'  => 'integer',
            'is_public'  => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
