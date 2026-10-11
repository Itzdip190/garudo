<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SampleRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'request_number',
        'product_id',
        'vendor_id',
        'customer_id',
        'sample_type',
        'quantity',
        'sample_price',
        'sample_shipping_fee',
        'total_amount',
        'payment_status',
        'payment_method',
        'transaction_id',
        'status',
        'converted_order_id',
        'buyer_name',
        'buyer_email',
        'buyer_phone',
        'company_name',
        'delivery_address',
        'city',
        'state',
        'pincode',
        'project_type',
        'notes',
        'courier_partner',
        'tracking_number',
        'dispatched_at',
        'delivered_at',
        'vendor_notes',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'quantity'            => 'integer',
            'sample_price'        => 'float',
            'sample_shipping_fee' => 'float',
            'total_amount'        => 'float',
            'dispatched_at'       => 'datetime',
            'delivered_at'        => 'datetime',
        ];
    }

    /**
     * Relationship: Product being sampled
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relationship: Vendor providing the sample
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Relationship: Registered customer who requested (if logged in)
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Relationship: Converted commercial order (if buyer subsequently bought bulk)
     */
    public function convertedOrder()
    {
        return $this->belongsTo(Order::class, 'converted_order_id');
    }

    /**
     * Helper: Check if sample was 100% free
     */
    public function isFree(): bool
    {
        return $this->sample_type === 'free';
    }

    /**
     * Helper: Check if sample was paid
     */
    public function isPaid(): bool
    {
        return $this->sample_type === 'paid';
    }

    /**
     * Helper: Check if sample was partial paid (shipping only)
     */
    public function isPartialPaid(): bool
    {
        return $this->sample_type === 'partial_paid';
    }

    /**
     * Helper: Status badge HTML
     */
    public function getStatusBadge(): string
    {
        return match ($this->status) {
            'pending'            => '<span class="badge bg-warning text-dark"><i class="bi bi-clock-history me-1"></i>Pending Review</span>',
            'approved'           => '<span class="badge bg-info text-white"><i class="bi bi-check-circle me-1"></i>Approved (In Preparation)</span>',
            'dispatched'         => '<span class="badge bg-primary text-white"><i class="bi bi-truck me-1"></i>Dispatched (In Transit)</span>',
            'delivered'          => '<span class="badge bg-success text-white"><i class="bi bi-box2-heart me-1"></i>Delivered</span>',
            'rejected'           => '<span class="badge bg-danger text-white"><i class="bi bi-x-circle me-1"></i>Rejected</span>',
            'converted_to_order' => '<span class="badge bg-success-subtle text-success border border-success fw-bold"><i class="bi bi-trophy-fill text-warning me-1"></i>Converted to Bulk Order</span>',
            default              => '<span class="badge bg-secondary">' . ucfirst($this->status) . '</span>',
        };
    }

    /**
     * Helper: Sample Type badge HTML
     */
    public function getSampleTypeBadge(): string
    {
        return match ($this->sample_type) {
            'free'         => '<span class="badge bg-success-subtle text-success border border-success"><i class="bi bi-gift-fill me-1"></i>100% Free Sample</span>',
            'paid'         => '<span class="badge bg-primary-subtle text-primary border border-primary"><i class="bi bi-currency-rupee me-1"></i>Paid Sample (₹' . number_format($this->sample_price, 2) . ')</span>',
            'partial_paid' => '<span class="badge bg-warning-subtle text-dark border border-warning"><i class="bi bi-box-seam me-1"></i>Partial Paid (Shipping ₹' . number_format($this->sample_shipping_fee, 2) . ')</span>',
            default        => '<span class="badge bg-light text-dark">' . ucfirst($this->sample_type) . '</span>',
        };
    }

    /**
     * Helper: Payment Status badge HTML
     */
    public function getPaymentStatusBadge(): string
    {
        return match ($this->payment_status) {
            'free'     => '<span class="badge bg-light text-muted border">Free (₹0.00)</span>',
            'paid'     => '<span class="badge bg-success text-white"><i class="bi bi-check-lg me-1"></i>Paid</span>',
            'pending'  => '<span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>Payment Pending</span>',
            'waived'   => '<span class="badge bg-info text-white">Fee Waived</span>',
            'refunded' => '<span class="badge bg-danger text-white">Refunded</span>',
            default    => '<span class="badge bg-secondary">' . ucfirst($this->payment_status) . '</span>',
        };
    }

    /**
     * Scope: Pending requests
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Active processing
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'approved', 'dispatched']);
    }

    /**
     * Scope: Completed/Delivered
     */
    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    /**
     * Scope: Converted to actual order
     */
    public function scopeConverted($query)
    {
        return $query->where('status', 'converted_to_order');
    }
}
