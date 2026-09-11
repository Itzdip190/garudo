<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'type',
        'is_filterable',
        'is_required',
        'sort_order',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'is_filterable' => 'boolean',
            'is_required'   => 'boolean',
            'sort_order'    => 'integer',
        ];
    }

    public function values()
    {
        return $this->hasMany(AttributeValue::class)->orderBy('sort_order', 'asc');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_attributes')
                    ->withPivot('is_required', 'sort_order');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
