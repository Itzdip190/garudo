<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BrandRegistration extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'brand_registrations';

    protected $fillable = [
        'vendor_id',
        'brand_name',
        'brand_slug',
        'brand_type',
        'category_ids',
        'logo_path',
        'banner_path',
        'brand_story',
        'official_website',
        'official_email',
        'official_phone',
        'year_established',
        'country_of_origin',
        'trademark_number',
        'trademark_class',
        'trademark_status',
        'trademark_doc_path',
        'company_incorporation_doc_path',
        'power_of_attorney_doc_path',
        'warranty_policy',
        'is_exclusive_distribution',
        'status',
        'admin_notes',
        'rejection_reason',
        'reviewed_by',
        'reviewed_at',
        'created_brand_id',
    ];

    protected $casts = [
        'category_ids'              => 'array',
        'is_exclusive_distribution' => 'boolean',
        'year_established'          => 'integer',
        'reviewed_at'               => 'datetime',
    ];

    // ---------------------------------------------------------------
    // Relationships
    // ---------------------------------------------------------------

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(Admin::class, 'reviewed_by');
    }

    public function createdBrand()
    {
        return $this->belongsTo(Brand::class, 'created_brand_id');
    }

    // ---------------------------------------------------------------
    // Scopes
    // ---------------------------------------------------------------

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeUnderReview($query)
    {
        return $query->where('status', 'under_review');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
