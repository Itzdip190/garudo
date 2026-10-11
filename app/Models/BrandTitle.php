<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandTitle extends Model
{
    use HasFactory;

    protected $table = 'brand_titles';

    protected $fillable = [
        'name',
        'slug',
        'badge_color',
        'description',
        'is_parent_tier',
        'sort_order',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'is_parent_tier' => 'boolean',
            'sort_order'     => 'integer',
        ];
    }

    public function brands()
    {
        return $this->hasMany(Brand::class, 'brand_title_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Get HTML Badge styling class or badge
     */
    public function getBadgeClassAttribute(): string
    {
        return match ($this->badge_color) {
            'success' => 'bg-success-subtle text-success border border-success-subtle',
            'primary' => 'bg-primary-subtle text-primary border border-primary-subtle',
            'warning' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
            'info'    => 'bg-info-subtle text-info border border-info-subtle',
            'purple'  => 'bg-purple-subtle text-purple border border-purple-subtle',
            'gold'    => 'bg-warning text-dark fw-bold border border-warning',
            default   => 'bg-secondary-subtle text-secondary border border-secondary-subtle',
        };
    }
}
