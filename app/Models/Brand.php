<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'parent_id', 'brand_title_id', 'brand_tier', 'owner_vendor_id', 'brand_type', 'logo', 'banner',
        'description', 'story', 'warranty_policy', 'website', 'support_email', 'support_phone',
        'country_of_origin', 'year_established', 'trademark_number', 'trademark_class',
        'trademark_status', 'trademark_doc', 'verification_status', 'is_protected',
        'verified_at', 'verified_by', 'rejection_reason', 'admin_notes',
        'is_featured', 'sort_order', 'status', 'meta_title', 'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'parent_id'        => 'integer',
            'brand_title_id'   => 'integer',
            'is_featured'      => 'boolean',
            'is_protected'     => 'boolean',
            'sort_order'       => 'integer',
            'verified_at'      => 'datetime',
            'year_established' => 'integer',
        ];
    }

    // ---------------------------------------------------------------
    // Relationships
    // ---------------------------------------------------------------

    public function brandTitle()
    {
        return $this->belongsTo(BrandTitle::class, 'brand_title_id');
    }

    public function superBrand()
    {
        return $this->belongsTo(Brand::class, 'parent_id');
    }

    public function subBrands()
    {
        return $this->hasMany(Brand::class, 'parent_id')->orderBy('sort_order')->orderBy('name');
    }

    public function activeSubBrands()
    {
        return $this->hasMany(Brand::class, 'parent_id')->where('status', 'active')->orderBy('name');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function ownerVendor()
    {
        return $this->belongsTo(Vendor::class, 'owner_vendor_id');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(Admin::class, 'verified_by');
    }

    public function dealerAuthorizations()
    {
        return $this->hasMany(BrandDealerAuthorization::class);
    }

    public function verifiedDealerAuthorizations()
    {
        return $this->hasMany(BrandDealerAuthorization::class)
                    ->where('garudo_approval_status', 'verified')
                    ->where('status', 'active');
    }

    public function authorizedDealers()
    {
        return $this->belongsToMany(Vendor::class, 'brand_dealer_authorizations', 'brand_id', 'vendor_id')
                    ->wherePivot('garudo_approval_status', 'verified')
                    ->wherePivot('status', 'active')
                    ->withPivot('authorization_type', 'territory_type', 'territory_states', 'territory_cities', 'certificate_number', 'valid_until', 'is_lifetime_valid')
                    ->withTimestamps();
    }

    public function registrationApplications()
    {
        return $this->hasMany(BrandRegistration::class, 'created_brand_id');
    }

    // ---------------------------------------------------------------
    // Business Logic & Helpers
    // ---------------------------------------------------------------

    public function isSuperBrand(): bool
    {
        return is_null($this->parent_id) || $this->brand_tier === 'super_brand';
    }

    public function isSubBrand(): bool
    {
        return !is_null($this->parent_id);
    }

    public function isAuthorized(): bool
    {
        return $this->verification_status === 'authorized' || $this->verification_status === 'active';
    }

    public function isOwnedBy(?Vendor $vendor): bool
    {
        if (!$vendor || !$this->owner_vendor_id) {
            return false;
        }
        return (int) $this->owner_vendor_id === (int) $vendor->id;
    }

    /**
     * Check if a specific vendor has permission to list/sell this brand's products
     * (Supports inheritance: verified dealer of Super Brand can sell Sub Brand products)
     */
    public function canVendorSell(?Vendor $vendor): bool
    {
        if (!$vendor) {
            return false;
        }

        // If brand is not protected, any active vendor can list
        if (!$this->is_protected) {
            return true;
        }

        // Direct Brand Owner can always list
        if ($this->isOwnedBy($vendor)) {
            return true;
        }

        // Parent Super Brand Owner can always list sub-brands
        if ($this->parent_id && $this->superBrand && $this->superBrand->isOwnedBy($vendor)) {
            return true;
        }

        // Must hold an active verified authorization for this brand OR its parent super brand
        $eligibleBrandIds = array_filter([$this->id, $this->parent_id]);

        return BrandDealerAuthorization::whereIn('brand_id', $eligibleBrandIds)
                    ->where('vendor_id', $vendor->id)
                    ->where('garudo_approval_status', 'verified')
                    ->where('status', 'active')
                    ->exists();
    }

    /**
     * Get the authorization record for a given vendor
     */
    public function getAuthorizationFor(Vendor $vendor)
    {
        return $this->dealerAuthorizations()
                    ->where('vendor_id', $vendor->id)
                    ->first();
    }

    // ---------------------------------------------------------------
    // Scopes
    // ---------------------------------------------------------------

    public function scopeSuperBrands($query)
    {
        return $query->whereNull('parent_id')->orWhere('brand_tier', 'super_brand');
    }

    public function scopeSubBrands($query)
    {
        return $query->whereNotNull('parent_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeAuthorized($query)
    {
        return $query->where('status', 'active')
                     ->whereIn('verification_status', ['authorized', 'active']);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)->where('status', 'active');
    }

    public function scopeProtected($query)
    {
        return $query->where('is_protected', true);
    }
}
