<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionalCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_code',
        'vendor_id',
        'plan_tier',
        'plan_title',
        'duration_days',
        'product_ids',
        'amount',
        'gst_amount',
        'total_payable',
        'payment_mode',
        'payment_reference_utr',
        'status',
        'requested_at',
        'activated_at',
        'expires_at',
        'approved_by',
        'admin_notes',
        'impressions_count',
        'clicks_count',
    ];

    protected $casts = [
        'product_ids'       => 'array',
        'amount'            => 'decimal:2',
        'gst_amount'        => 'decimal:2',
        'total_payable'     => 'decimal:2',
        'duration_days'     => 'integer',
        'impressions_count' => 'integer',
        'clicks_count'      => 'integer',
        'requested_at'      => 'datetime',
        'activated_at'      => 'datetime',
        'expires_at'        => 'datetime',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function approver()
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    public function getTargetProductsAttribute()
    {
        if (empty($this->product_ids)) {
            return collect();
        }
        return Product::whereIn('id', $this->product_ids)->get();
    }

    public function getIsLiveAttribute(): bool
    {
        return $this->status === 'active' &&
               $this->activated_at &&
               (!$this->expires_at || now()->lte($this->expires_at));
    }

    public function getRemainingDaysAttribute(): int
    {
        if (!$this->expires_at) {
            return $this->duration_days;
        }
        if (now()->gt($this->expires_at)) {
            return 0;
        }
        return max(0, (int) now()->diffInDays($this->expires_at, false) + 1);
    }
}
