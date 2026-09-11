<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorKycDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'document_type',
        'file_path',
        'file_name',
        'status',
        'remarks',
        'rejection_notes',
        'uploaded_at',
        'verified_at',
        'verified_by',
    ];

    protected function casts(): array
    {
        return [
            'uploaded_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function verifiedByAdmin()
    {
        return $this->belongsTo(Admin::class, 'verified_by');
    }

    public function getFileUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }

    public function getDocumentTypeNameAttribute(): string
    {
        return match ($this->document_type) {
            'pan'                       => 'PAN Card',
            'gstin_certificate'         => 'GSTIN Certificate',
            'aadhar'                    => 'Aadhaar Card',
            'bank_statement'            => 'Bank Statement (Last 3 Months)',
            'cancelled_cheque'          => 'Cancelled Cheque',
            'incorporation_certificate' => 'Certificate of Incorporation (COI)',
            'trade_license'             => 'Trade License / Shop & Est. Certificate',
            default                     => 'Supporting Document (' . ucfirst(str_replace('_', ' ', $this->document_type)) . ')',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'approved' => '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Verified</span>',
            'rejected' => '<span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Rejected</span>',
            default    => '<span class="badge bg-warning text-dark"><i class="bi bi-clock-history me-1"></i>Pending Review</span>',
        };
    }
}
