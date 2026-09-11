<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlatformFeedback extends Model
{
    use HasFactory;

    protected $table = 'platform_feedbacks';

    protected $guarded = [];

    protected $casts = [
        'rating'      => 'integer',
        'is_featured' => 'boolean',
    ];

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
