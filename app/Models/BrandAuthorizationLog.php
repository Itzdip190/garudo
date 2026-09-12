<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandAuthorizationLog extends Model
{
    use HasFactory;

    protected $table = 'brand_authorization_logs';

    protected $fillable = [
        'authorization_id',
        'action',
        'actor_type',
        'actor_id',
        'old_status',
        'new_status',
        'notes',
    ];

    public function authorization()
    {
        return $this->belongsTo(BrandDealerAuthorization::class, 'authorization_id');
    }
}
