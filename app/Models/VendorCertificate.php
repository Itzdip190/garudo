<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class VendorCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'certificate_number',
        'verification_token',
        'issue_date',
        'expiry_date',
        'digital_signature_hash',
        'qr_code_svg',
        'status',
        'revocation_reason',
        'metadata',
        'download_count',
    ];

    protected function casts(): array
    {
        return [
            'issue_date'  => 'date',
            'expiry_date' => 'date',
            'metadata'    => 'array',
            'download_count' => 'integer',
        ];
    }

    // ---------------------------------------------------------------
    // Relationships
    // ---------------------------------------------------------------

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    // ---------------------------------------------------------------
    // Accessors & Helpers
    // ---------------------------------------------------------------

    public function getVerificationUrlAttribute(): string
    {
        return route('certificate.verify', ['certificate_number' => $this->certificate_number]);
    }

    public function getIsValidAttribute(): bool
    {
        return $this->status === 'active' && $this->expiry_date->isFuture();
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->expiry_date->isPast();
    }

    public function getDaysUntilExpiryAttribute(): int
    {
        return max(0, (int) now()->diffInDays($this->expiry_date, false));
    }

    public function getStatusBadgeAttribute(): string
    {
        if ($this->status === 'revoked') {
            return '<span class="badge bg-danger">Revoked</span>';
        }
        if ($this->status === 'suspended') {
            return '<span class="badge bg-warning text-dark">Suspended</span>';
        }
        if ($this->is_expired) {
            return '<span class="badge bg-secondary">Expired</span>';
        }
        return '<span class="badge bg-success"><i class="bi bi-patch-check-fill me-1"></i>Active & Verified</span>';
    }

    // ---------------------------------------------------------------
    // Scopes
    // ---------------------------------------------------------------

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeValid($query)
    {
        return $query->where('status', 'active')
                     ->where('expiry_date', '>=', now()->toDateString());
    }
}
