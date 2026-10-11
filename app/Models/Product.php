<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vendor_id', 'category_id', 'brand_id', 'name', 'slug', 'sku',
        'barcode', 'hsn_code', 'product_type', 'mrp', 'selling_price',
        'wholesale_price', 'retail_price', 'cost_price', 'gst_rate',
        'gst_inclusive', 'min_order_qty', 'max_order_qty', 'unit',
        'weight', 'weight_unit', 'length', 'width', 'height', 'dimension_unit',
        'short_description', 'description', 'features', 'free_shipping',
        'shipping_charges', 'cod_available', 'shipping_days_min', 'shipping_days_max',
        'has_warranty', 'warranty_type', 'warranty_period', 'warranty_description',
        'policy_type', 'custom_policy', 'warehouse_id',
        'has_sample', 'sample_type', 'sample_price', 'sample_shipping_fee',
        'sample_max_qty', 'sample_unit_description', 'sample_instructions', 'sample_stock',
        'allow_quotation', 'quotation_min_qty', 'quotation_custom_fields', 'quotation_notes', 'quotation_attachments_allowed',
        'meta_title', 'meta_description', 'meta_keywords', 'is_featured',
        'is_trending', 'is_new_arrival', 'is_popular', 'is_best_seller',
        'is_sponsored', 'is_deal_of_the_day',
        'sec1_trending', 'sec2_best_seller', 'sec3_trending', 'sec4_best_seller',
        'is_trending_now',
        'approval_status', 'approved_at', 'approved_by', 'rejection_reason',
        'status', 'view_count', 'wishlist_count', 'rating', 'rating_count',
        'total_sold', 'published_at',
    ];

    /**
     * Get the effective policy for this product (Product custom -> Category / Subcategory -> Default)
     */
    public function getEffectivePolicy(): string
    {
        if (($this->policy_type ?? 'inherit') === 'custom' && !empty(trim($this->custom_policy ?? ''))) {
            return $this->custom_policy;
        }

        if ($this->category) {
            $catPolicy = $this->category->getInheritedPolicy();
            if (!empty(trim($catPolicy ?? ''))) {
                return $catPolicy;
            }
        }

        return "Standard Garudo B2B Building Materials Policy: All consignments are dispatched in factory-sealed condition. Buyers must inspect delivery before sharing pickup/delivery OTP. Any transit breakages, batch variations, or shortage must be reported within 24 hours.";
    }

    protected function casts(): array
    {
        return [
            'mrp'                 => 'float',
            'selling_price'       => 'float',
            'wholesale_price'     => 'float',
            'retail_price'        => 'float',
            'cost_price'          => 'float',
            'gst_rate'            => 'float',
            'gst_inclusive'       => 'boolean',
            'free_shipping'       => 'boolean',
            'cod_available'       => 'boolean',
            'has_sample'          => 'boolean',
            'sample_price'        => 'float',
            'sample_shipping_fee' => 'float',
            'sample_max_qty'      => 'integer',
            'sample_stock'        => 'integer',
            'allow_quotation'     => 'boolean',
            'quotation_min_qty'   => 'integer',
            'quotation_attachments_allowed' => 'boolean',
            'has_warranty'        => 'boolean',
            'is_featured'         => 'boolean',
            'is_trending'         => 'boolean',
            'is_new_arrival'      => 'boolean',
            'is_popular'          => 'boolean',
            'is_best_seller'      => 'boolean',
            'is_sponsored'        => 'boolean',
            'is_deal_of_the_day'  => 'boolean',
            'sec1_trending'       => 'boolean',
            'sec2_best_seller'    => 'boolean',
            'sec3_trending'       => 'boolean',
            'sec4_best_seller'    => 'boolean',
            'is_trending_now'     => 'boolean',
            'rating'              => 'float',
            'approved_at'         => 'datetime',
            'published_at'        => 'datetime',
        ];
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order', 'asc');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function galleryImages()
    {
        return $this->hasMany(ProductImage::class)->where('is_primary', false)->orderBy('sort_order', 'asc');
    }

    public function videos()
    {
        return $this->hasMany(ProductVideo::class)->orderBy('sort_order', 'asc');
    }

    public function documents()
    {
        return $this->hasMany(ProductDocument::class)->orderBy('sort_order', 'asc');
    }

    public function specifications()
    {
        return $this->hasMany(ProductSpecification::class)->orderBy('sort_order', 'asc');
    }

    public function bulkPrices()
    {
        return $this->hasMany(ProductBulkPrice::class)->orderBy('min_qty', 'asc');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class)->orderBy('sort_order', 'asc');
    }

    public function productAttributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function qna()
    {
        return $this->hasMany(ProductQna::class);
    }

    public function getPrimaryImageUrlAttribute(): string
    {
        if (!empty($this->attributes['custom_image_url'])) {
            return $this->attributes['custom_image_url'];
        }
        if (isset($this->custom_image_url) && !empty($this->custom_image_url)) {
            return $this->custom_image_url;
        }
        if ($this->primaryImage) {
            return $this->primaryImage->url;
        }
        $firstImage = $this->images->first();
        if ($firstImage) {
            return $firstImage->url;
        }
        return asset('images/product-placeholder.png');
    }

    public function getDiscountPercentAttribute(): int
    {
        if ($this->mrp > 0 && $this->selling_price > 0 && $this->mrp > $this->selling_price) {
            return (int) round((($this->mrp - $this->selling_price) / $this->mrp) * 100);
        }
        return 0;
    }

    public function sampleRequests()
    {
        return $this->hasMany(SampleRequest::class)->latest();
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class)->latest();
    }

    /**
     * Check if Ask for Quotation is enabled for this product
     */
    public function isQuotationEnabled(): bool
    {
        return $this->allow_quotation !== false;
    }

    /**
     * Get array of custom variation / inquiry fields configured by vendor
     */
    public function getQuotationCustomFields(): array
    {
        if (empty($this->quotation_custom_fields)) {
            return [];
        }
        if (is_array($this->quotation_custom_fields)) {
            return $this->quotation_custom_fields;
        }
        $decoded = json_decode($this->quotation_custom_fields, true);
        if (is_array($decoded)) {
            return $decoded;
        }
        // If stored as comma or newline separated string
        return array_filter(array_map('trim', preg_split('/\r\n|\r|\n|,/', $this->quotation_custom_fields)));
    }

    /**
     * Check if this product offers material samples
     */
    public function hasSample(): bool
    {
        return (bool) $this->has_sample;
    }

    /**
     * Get Sample Pricing Description badge
     */
    public function getSampleBadge(): string
    {
        if (!$this->has_sample) {
            return '';
        }

        return match ($this->sample_type) {
            'free'         => '<span class="badge bg-success-subtle text-success border border-success"><i class="bi bi-gift-fill me-1"></i>Free Sample Available</span>',
            'paid'         => '<span class="badge bg-primary-subtle text-primary border border-primary"><i class="bi bi-currency-rupee me-1"></i>Paid Sample (₹' . number_format($this->sample_price, 2) . ')</span>',
            'partial_paid' => '<span class="badge bg-warning-subtle text-dark border border-warning"><i class="bi bi-box-seam me-1"></i>Sample + Shipping (₹' . number_format($this->sample_shipping_fee, 2) . ')</span>',
            default        => '<span class="badge bg-success-subtle text-success">Sample Available</span>',
        };
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')->where('approval_status', 'approved');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)->where('status', 'active')->where('approval_status', 'approved');
    }

    public function scopeSponsored($query)
    {
        return $query->where('is_sponsored', true)->where('status', 'active')->where('approval_status', 'approved');
    }

    public function scopeDealOfTheDay($query)
    {
        return $query->where('is_deal_of_the_day', true)->where('status', 'active')->where('approval_status', 'approved');
    }
}

