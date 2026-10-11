<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogRequest extends Model
{
    use HasFactory;

    protected $table = 'catalog_requests';

    protected $fillable = [
        'vendor_id',
        'type',
        'name',
        'parent_id',
        'values',
        'reason',
        'status',
        'admin_notes',
        'handled_by',
        'handled_at',
    ];

    protected $casts = [
        'handled_at' => 'datetime',
    ];

    /**
     * Relationship to the requesting Vendor
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Relationship to the Admin who approved/rejected the request
     */
    public function handler()
    {
        return $this->belongsTo(Admin::class, 'handled_by');
    }

    /**
     * Optional relationship to parent Category (if type is category/subcategory)
     */
    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Helper: Type badge HTML
     */
    public function getTypeBadge(): string
    {
        return match ($this->type) {
            'brand'          => '<span class="badge bg-primary-subtle text-primary border border-primary"><i class="bi bi-patch-check me-1"></i>Brand / Manufacturer</span>',
            'subcategory'    => '<span class="badge bg-info-subtle text-info border border-info"><i class="bi bi-diagram-2 me-1"></i>Subcategory</span>',
            'category'       => '<span class="badge bg-success-subtle text-success border border-success"><i class="bi bi-diagram-3 me-1"></i>Main Category</span>',
            'attribute_spec' => '<span class="badge bg-warning-subtle text-dark border border-warning"><i class="bi bi-sliders me-1"></i>Specification / Spec</span>',
            default          => '<span class="badge bg-light text-dark">' . ucfirst(str_replace('_', ' ', $this->type)) . '</span>',
        };
    }

    /**
     * Helper: Status badge HTML
     */
    public function getStatusBadge(): string
    {
        return match ($this->status) {
            'pending'  => '<span class="badge bg-warning text-dark"><i class="bi bi-clock-history me-1"></i>Pending Admin Verification</span>',
            'approved' => '<span class="badge bg-success text-white"><i class="bi bi-check-circle me-1"></i>Approved & Live</span>',
            'rejected' => '<span class="badge bg-danger text-white"><i class="bi bi-x-circle me-1"></i>Declined</span>',
            default    => '<span class="badge bg-secondary">' . ucfirst($this->status) . '</span>',
        };
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeBrands($query)
    {
        return $query->where('type', 'brand');
    }

    public function scopeCategories($query)
    {
        return $query->whereIn('type', ['category', 'subcategory']);
    }

    public function scopeAttributeSpecs($query)
    {
        return $query->where('type', 'attribute_spec');
    }
}
