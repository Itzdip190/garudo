<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'image',
        'mobile_image',
        'link',
        'link_target',
        'position',
        'section_row',
        'background_color',
        'text_color',
        'button_text',
        'button_color',
        'sort_order',
        'start_date',
        'end_date',
        'status',
        'clicks',
    ];

    protected function casts(): array
    {
        return [
            'section_row' => 'integer',
            'sort_order'  => 'integer',
            'clicks'      => 'integer',
            'start_date'  => 'date',
            'end_date'    => 'date',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePosition($query, string $pos)
    {
        return $query->where('position', $pos);
    }

    public function scopeRow($query, int $row)
    {
        return $query->where('section_row', $row);
    }

    public function getImageUrlAttribute(): string
    {
        if (empty($this->image)) {
            return asset('images/banners/mega_construction_sale.jpg');
        }
        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }
        if (str_starts_with($this->image, 'images/')) {
            return asset($this->image);
        }
        return asset('storage/' . $this->image);
    }
}
