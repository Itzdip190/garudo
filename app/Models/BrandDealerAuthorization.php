<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BrandDealerAuthorization extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'brand_dealer_authorizations';

    protected $fillable = [
        'brand_id',
        'vendor_id',
        'authorization_type',
        'territory_type',
        'territory_states',
        'territory_cities',
        'territory_pincodes',
        'authorization_doc_path',
        'agreement_ref_no',
        'valid_from',
        'valid_until',
        'is_lifetime_valid',
        'brand_owner_approval',
        'brand_owner_approved_at',
        'garudo_approval_status',
        'verified_at',
        'verified_by',
        'rejection_reason',
        'admin_notes',
        'certificate_number',
        'qr_token',
        'status',
    ];

    protected $casts = [
        'territory_states'         => 'array',
        'territory_cities'         => 'array',
        'territory_pincodes'       => 'array',
        'valid_from'               => 'date',
        'valid_until'              => 'date',
        'is_lifetime_valid'        => 'boolean',
        'brand_owner_approved_at'  => 'datetime',
        'verified_at'              => 'datetime',
    ];

    // ---------------------------------------------------------------
    // Relationships
    // ---------------------------------------------------------------

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function verifiedByAdmin()
    {
        return $this->belongsTo(Admin::class, 'verified_by');
    }

    public function logs()
    {
        return $this->hasMany(BrandAuthorizationLog::class, 'authorization_id');
    }

    // ---------------------------------------------------------------
    // Business Logic & Helpers
    // ---------------------------------------------------------------

    public function isVerified(): bool
    {
        return $this->garudo_approval_status === 'verified' && $this->status === 'active' && !$this->isExpired();
    }

    public function isExpired(): bool
    {
        if ($this->is_lifetime_valid) {
            return false;
        }

        if ($this->valid_until && $this->valid_until->isPast()) {
            return true;
        }

        return false;
    }

    public function getFormattedTypeAttribute(): string
    {
        return match($this->authorization_type) {
            'exclusive_distributor'   => 'Exclusive National Distributor',
            'authorized_distributor'  => 'Authorized Regional Distributor',
            'authorized_dealer'       => 'Authorized City Dealer',
            'tier2_stockist'          => 'Tier-2 Stockist / Reseller',
            'franchise_partner'       => 'Official Franchise Partner',
            default                   => ucwords(str_replace('_', ' ', $this->authorization_type)),
        };
    }

    public function getFormattedTerritoryAttribute(): string
    {
        if ($this->territory_type === 'national') {
            return 'Pan-India (National Territory)';
        }

        if ($this->territory_type === 'state' && !empty($this->territory_states)) {
            return 'State: ' . implode(', ', (array) $this->territory_states);
        }

        if ($this->territory_type === 'city' && !empty($this->territory_cities)) {
            return 'Cities: ' . implode(', ', (array) $this->territory_cities);
        }

        if ($this->territory_type === 'pincode_zone' && !empty($this->territory_pincodes)) {
            return 'PIN Codes: ' . implode(', ', (array) $this->territory_pincodes);
        }

        return ucwords(str_replace('_', ' ', $this->territory_type));
    }

    /**
     * Generate a unique cryptographic Certificate ID and QR Token
     */
    public static function generateCertificateId(int $brandId, int $vendorId): string
    {
        $year = date('Y');
        $random = strtoupper(Str::random(5));
        return "GRD-AUTH-{$year}-B{$brandId}V{$vendorId}-{$random}";
    }

    // ---------------------------------------------------------------
    // Scopes
    // ---------------------------------------------------------------

    public function scopeVerified($query)
    {
        return $query->where('garudo_approval_status', 'verified')->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('garudo_approval_status', 'pending');
    }
}
