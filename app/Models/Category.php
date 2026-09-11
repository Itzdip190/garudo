<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'parent_id', 'name', 'slug', 'description', 'policy', 'image', 'icon',
        'meta_title', 'meta_description', 'meta_keywords',
        'commission_rate', 'is_featured', 'show_in_menu', 'show_in_home',
        'sort_order', 'depth', 'status',
    ];

    /**
     * Get policy, inheriting from parent category if current category policy is empty
     */
    public function getInheritedPolicy(): ?string
    {
        if (!empty($this->policy)) {
            return $this->policy;
        }

        if ($this->parent) {
            return $this->parent->getInheritedPolicy();
        }

        return null;
    }

    protected function casts(): array
    {
        return [
            'is_featured'     => 'boolean',
            'show_in_menu'    => 'boolean',
            'show_in_home'    => 'boolean',
            'commission_rate' => 'float',
            'depth'           => 'integer',
            'sort_order'      => 'integer',
        ];
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order', 'asc');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'category_attributes')
                    ->withPivot('is_required', 'sort_order')
                    ->orderBy('sort_order', 'asc');
    }

    public function vendors()
    {
        return $this->belongsToMany(Vendor::class, 'vendor_categories');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)->where('status', 'active');
    }
}
