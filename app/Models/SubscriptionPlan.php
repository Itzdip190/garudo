<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionPlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'target_role',
        'badge_title',
        'description',
        'monthly_price',
        'yearly_price',
        'billing_interval',
        'has_24_7_support',
        'sponsored_products_quota',
        'commission_rate',
        'product_limit',
        'custom_logistics_enabled',
        'verified_badge_included',
        'rfq_access_level',
        'analytics_access',
        'features',
        'is_featured',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'monthly_price'            => 'decimal:2',
            'yearly_price'             => 'decimal:2',
            'commission_rate'          => 'decimal:2',
            'has_24_7_support'         => 'boolean',
            'custom_logistics_enabled' => 'boolean',
            'verified_badge_included'  => 'boolean',
            'is_featured'              => 'boolean',
            'is_active'                => 'boolean',
            'product_limit'            => 'integer',
            'sponsored_products_quota' => 'integer',
            'sort_order'               => 'integer',
            'features'                 => 'array',
        ];
    }

    // ---------------------------------------------------------------
    // Relationships
    // ---------------------------------------------------------------

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscriptions()
    {
        return $this->hasMany(Subscription::class)->where('status', 'active');
    }

    // ---------------------------------------------------------------
    // Scopes
    // ---------------------------------------------------------------

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order', 'asc');
    }

    public function scopeForVendors($query)
    {
        return $query->where('target_role', 'vendor');
    }

    public function scopeForCustomers($query)
    {
        return $query->where('target_role', 'customer');
    }

    // ---------------------------------------------------------------
    // Helpers
    // ---------------------------------------------------------------

    public function isFree(): bool
    {
        return (float) $this->monthly_price <= 0 && (float) $this->yearly_price <= 0;
    }

    public function getFormattedMonthlyPriceAttribute(): string
    {
        return $this->isFree() ? 'Free' : '₹' . number_format($this->monthly_price, 0);
    }

    public function getFormattedYearlyPriceAttribute(): string
    {
        return $this->isFree() ? 'Free' : '₹' . number_format($this->yearly_price, 0);
    }

    public function getEffectiveFeaturesList(): array
    {
        if (is_array($this->features) && count($this->features) > 0) {
            return $this->features;
        }

        // Fallback dynamic features
        $list = [];
        if ($this->product_limit === -1) {
            $list[] = 'Unlimited Product Listings';
        } else {
            $list[] = "Up to {$this->product_limit} Active Product Listings";
        }

        if ((float) $this->commission_rate <= 0) {
            $list[] = '0% ZERO Platform Commission (Keep 100%)';
        } else {
            $list[] = "{$this->commission_rate}% Platform Commission";
        }

        if ($this->has_24_7_support) {
            $list[] = '24/7 Dedicated Account Manager & Hotline Support';
        } else {
            $list[] = 'Standard Email & Ticket Support';
        }

        if ($this->sponsored_products_quota > 0) {
            $list[] = "{$this->sponsored_products_quota} Monthly Sponsored Product Boost Tokens";
        }

        if ($this->verified_badge_included) {
            $list[] = 'Official Verified Partner Accreditation Badge';
        }

        if ($this->custom_logistics_enabled) {
            $list[] = 'Custom F.O.R. & Dispatch Logistics Configuration';
        }

        return $list;
    }
}
