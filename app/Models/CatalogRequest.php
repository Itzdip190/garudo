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
     * Optional relationship to parent Category (if type is category and requesting subcategory)
     */
    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'parent_id');
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

    public function scopeAttributeSpecs($query)
    {
        return $query->where('type', 'attribute_spec');
    }

    public function scopeCategories($query)
    {
        return $query->where('type', 'category');
    }
}
