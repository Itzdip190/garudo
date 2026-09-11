<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_plan_id',
        'subscribable_type',
        'subscribable_id',
        'plan_name',
        'billing_cycle',
        'amount_paid',
        'starts_at',
        'expires_at',
        'status',
        'payment_method',
        'payment_reference',
        'remaining_boost_tokens',
        'admin_notes',
        'approved_by',
    ];

    protected function casts(): array
    {
        return [
            'amount_paid'            => 'decimal:2',
            'starts_at'              => 'datetime',
            'expires_at'             => 'datetime',
            'remaining_boost_tokens' => 'integer',
        ];
    }

    // ---------------------------------------------------------------
    // Relationships
    // ---------------------------------------------------------------

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function subscribable()
    {
        return $this->morphTo();
    }

    public function approvedByAdmin()
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    // ---------------------------------------------------------------
    // Helpers
    // ---------------------------------------------------------------

    public function isActive(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }

    public function isPending(): bool
    {
        return $this->status === 'pending_approval';
    }

    public function getDaysRemainingAttribute(): int
    {
        if (!$this->expires_at) {
            return 999; // Lifetime / unlimited
        }

        return max(0, (int) now()->diffInDays($this->expires_at, false));
    }
}
