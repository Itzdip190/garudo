<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Vendor extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $guard = 'vendor';

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'must_change_password',
        'company_name', 'company_slug', 'company_logo', 'company_banner',
        'company_description', 'company_website', 'business_type', 'vendor_type', 'year_established',
        'gstin', 'pan_number',
        'primary_dispatch_warehouse_id', 'primary_dispatch_state',
        'dispatch_time_type', 'custom_dispatch_min_days', 'custom_dispatch_max_days', 'custom_dispatch_notes',
        'is_for_enabled', 'for_condition_type', 'for_min_amount', 'for_min_qty', 'for_qty_unit', 'for_scope', 'for_scope_details',
        'kyc_status', 'kyc_step', 'kyc_submitted_at', 'kyc_verified_at', 'kyc_rejected_reason', 'kyc_review_notes',
        'approval_status', 'approved_at', 'approved_by', 'rejection_reason',
        'membership_plan', 'membership_expires_at',
        'total_products', 'total_orders', 'rating', 'rating_count',
        'is_featured', 'is_verified', 'avatar', 'status',
        'last_login_at', 'last_login_ip',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at'        => 'datetime',
            'phone_verified_at'        => 'datetime',
            'kyc_submitted_at'         => 'datetime',
            'kyc_verified_at'          => 'datetime',
            'approved_at'              => 'datetime',
            'last_login_at'            => 'datetime',
            'membership_expires_at'    => 'date',
            'kyc_step'                 => 'integer',
            'is_featured'              => 'boolean',
            'is_verified'              => 'boolean',
            'rating'                   => 'float',
            'password'                 => 'hashed',
            'must_change_password'     => 'boolean',
            'is_for_enabled'           => 'boolean',
            'for_min_amount'           => 'decimal:2',
            'for_min_qty'              => 'integer',
            'custom_dispatch_min_days' => 'integer',
            'custom_dispatch_max_days' => 'integer',
        ];
    }

    // ---------------------------------------------------------------
    // Relationships
    // ---------------------------------------------------------------

    public function warehouses()
    {
        return $this->hasMany(Warehouse::class);
    }

    public function primaryWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'primary_dispatch_warehouse_id');
    }

    public function kycDocuments()
    {
        return $this->hasMany(VendorKycDocument::class);
    }

    public function bankDetails()
    {
        return $this->hasOne(VendorBankDetail::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'vendor_categories')
                    ->withPivot('is_primary', 'assigned_at', 'assigned_by')
                    ->withTimestamps();
    }

    public function primaryCategory()
    {
        return $this->belongsToMany(Category::class, 'vendor_categories')
                    ->wherePivot('is_primary', true)
                    ->first();
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function activeProducts()
    {
        return $this->hasMany(Product::class)
                    ->where('status', 'active')
                    ->where('approval_status', 'approved');
    }

    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function defaultAddress()
    {
        return $this->morphOne(Address::class, 'addressable')->where('is_default', true);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function enquiries()
    {
        return $this->hasMany(Enquiry::class);
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class)->latest();
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }

    public function promotionalCampaigns()
    {
        return $this->hasMany(PromotionalCampaign::class);
    }

    public function activeBoostCampaign()
    {
        return $this->hasOne(PromotionalCampaign::class)
                    ->where('status', 'active')
                    ->where(function($q) {
                        $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
                    })
                    ->latestOfMany();
    }

    public function subscriptions()
    {
        return $this->morphMany(Subscription::class, 'subscribable');
    }

    public function activeSubscription()
    {
        return $this->morphOne(Subscription::class, 'subscribable')
                    ->where('status', 'active')
                    ->where(function($q) {
                        $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
                    })
                    ->latestOfMany();
    }

    public function getCurrentPlanAttribute()
    {
        $activeSub = $this->activeSubscription;
        if ($activeSub && $activeSub->plan) {
            return $activeSub->plan;
        }

        return SubscriptionPlan::where('target_role', 'vendor')->where('is_active', true)->orderBy('monthly_price', 'asc')->first();
    }

    public function has247Support(): bool
    {
        $plan = $this->current_plan;
        return $plan ? (bool) $plan->has_24_7_support : false;
    }

    public function getSponsoredBoostTokensAttribute(): int
    {
        $activeSub = $this->activeSubscription;
        return $activeSub ? (int) $activeSub->remaining_boost_tokens : 0;
    }

    public function approvedBy()
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    // ---------------------------------------------------------------
    // Accessors
    // ---------------------------------------------------------------

    public function getLogoUrlAttribute(): string
    {
        return $this->company_logo
            ? asset('storage/' . $this->company_logo)
            : asset('images/default-vendor-logo.png');
    }

    public function getIsApprovedAttribute(): bool
    {
        return $this->approval_status === 'approved';
    }

    public function getIsKycVerifiedAttribute(): bool
    {
        return $this->kyc_status === 'verified';
    }

    public function certificates()
    {
        return $this->hasMany(VendorCertificate::class);
    }

    public function activeCertificate()
    {
        return $this->hasOne(VendorCertificate::class)->where('status', 'active')->latestOfMany();
    }

    public function isVerifiedPartner(): bool
    {
        return $this->kyc_status === 'verified' && $this->approval_status === 'approved' && $this->is_verified;
    }

    public function ownedBrands()
    {
        return $this->hasMany(Brand::class, 'owner_vendor_id');
    }

    public function brandRegistrations()
    {
        return $this->hasMany(BrandRegistration::class);
    }

    public function brandAuthorizations()
    {
        return $this->hasMany(BrandDealerAuthorization::class);
    }

    public function verifiedBrandAuthorizations()
    {
        return $this->hasMany(BrandDealerAuthorization::class)
                    ->where('garudo_approval_status', 'verified')
                    ->where('status', 'active');
    }

    public function authorizedBrands()
    {
        return $this->belongsToMany(Brand::class, 'brand_dealer_authorizations', 'vendor_id', 'brand_id')
                    ->wherePivot('garudo_approval_status', 'verified')
                    ->wherePivot('status', 'active')
                    ->withPivot('authorization_type', 'territory_type', 'certificate_number', 'valid_until', 'is_lifetime_valid')
                    ->withTimestamps();
    }

    public function isAuthorizedForBrand($brand): bool
    {
        $brandId = $brand instanceof Brand ? $brand->id : (int) $brand;

        // Check if vendor owns this brand
        if ($this->ownedBrands()->where('id', $brandId)->exists()) {
            return true;
        }

        // Check if vendor holds active verified dealer authorization
        return $this->brandAuthorizations()
                    ->where('brand_id', $brandId)
                    ->where('garudo_approval_status', 'verified')
                    ->where('status', 'active')
                    ->exists();
    }

    public function canSellBrand($brand): bool
    {
        $brandModel = $brand instanceof Brand ? $brand : Brand::find($brand);
        if (!$brandModel) {
            return true;
        }

        return $brandModel->canVendorSell($this);
    }

    public function isBrandOwner(): bool
    {
        return $this->vendor_type === 'brand_owner' || $this->ownedBrands()->exists();
    }

    public function getCertificateUrlAttribute(): ?string
    {
        return $this->activeCertificate?->verification_url;
    }

    public function getKycProgressPercentageAttribute(): int
    {
        if ($this->kyc_status === 'verified') {
            return 100;
        }
        if ($this->kyc_status === 'submitted' || $this->kyc_status === 'under_review') {
            return 90;
        }
        return match ($this->kyc_step ?? 1) {
            1 => 25,
            2 => 50,
            3 => 75,
            4 => 90,
            default => 25,
        };
    }

    public function getCanAddProductsAttribute(): bool
    {
        return $this->approval_status === 'approved' && $this->status === 'active';
    }

    // ---------------------------------------------------------------
    // Scopes
    // ---------------------------------------------------------------

    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('approval_status', 'pending');
    }

    public function vendorReviews()
    {
        return $this->hasMany(VendorReview::class);
    }

    public function isKycApproved(): bool
    {
        return in_array($this->kyc_status, ['verified', 'approved']);
    }

    public function getStoreUrlAttribute(): string
    {
        return url('/store/' . ($this->company_slug ?? $this->id));
    }

    public function getReviewUrlAttribute(): string
    {
        return url('/store/' . ($this->company_slug ?? $this->id) . '#write-review');
    }

    public function recalculateRating(): void
    {
        $avg = $this->vendorReviews()->where('status', 'approved')->avg('rating') ?? 5.0;
        $count = $this->vendorReviews()->where('status', 'approved')->count();

        $this->update([
            'rating' => round($avg, 2),
            'rating_count' => $count,
        ]);
    }

    /**
     * Relationship: Sample Requests received by this vendor
     */
    public function sampleRequests()
    {
        return $this->hasMany(SampleRequest::class)->latest();
    }

    public function pendingQuotationsCount(): int
    {
        return $this->quotations()->whereIn('status', ['pending', 'new'])->count();
    }

    /**
     * Helper: Sample-to-Bulk Conversion Rate (%)
     */
    public function getSampleConversionRate(): float
    {
        $totalDeliveredOrConverted = $this->sampleRequests()->whereIn('status', ['delivered', 'converted_to_order'])->count();
        if ($totalDeliveredOrConverted === 0) {
            return 0.0;
        }

        $converted = $this->sampleRequests()->where('status', 'converted_to_order')->count();
        return round(($converted / $totalDeliveredOrConverted) * 100, 1);
    }
}

