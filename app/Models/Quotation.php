<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quotation extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'valid_until'            => 'date',
        'expected_delivery_date' => 'date',
        'quoted_at'              => 'datetime',
        'unit_price'             => 'decimal:2',
        'gst_rate'               => 'decimal:2',
        'gst_amount'             => 'decimal:2',
        'total_amount'           => 'decimal:2',
        'discount_amount'        => 'decimal:2',
        'shipping_amount'        => 'decimal:2',
        'target_price'           => 'decimal:2',
        'custom_variations'      => 'array',
        'quantity'               => 'integer',
        'validity_days'          => 'integer',
    ];

    /**
     * Relationship: Customer (if registered & logged in)
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Relationship: Vendor providing the quote
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Relationship: Product requested
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relationship: Associated B2B Enquiry thread
     */
    public function enquiry()
    {
        return $this->belongsTo(Enquiry::class);
    }

    /**
     * Relationship: Converted Order (if quotation is converted to live order)
     */
    public function convertedOrder()
    {
        return $this->belongsTo(Order::class, 'converted_order_id');
    }

    /**
     * Check if quotation is still awaiting vendor response
     */
    public function isPending(): bool
    {
        return in_array($this->status, ['pending', 'new', 'draft']);
    }

    /**
     * Check if vendor has provided a formal price proposal
     */
    public function isQuoted(): bool
    {
        return in_array($this->status, ['sent', 'quoted', 'viewed']);
    }

    /**
     * Check if buyer has accepted the quote
     */
    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    /**
     * Check if quote has been converted into an order
     */
    public function isConverted(): bool
    {
        return $this->status === 'converted_to_order';
    }

    /**
     * Get HTML Badge for Status
     */
    public function getStatusBadge(): string
    {
        return match ($this->status) {
            'pending', 'new'     => '<span class="badge bg-warning text-dark"><i class="bi bi-clock-history me-1"></i>Pending Quote</span>',
            'sent', 'quoted'     => '<span class="badge bg-primary text-white"><i class="bi bi-send-check me-1"></i>Proposal Sent</span>',
            'viewed'             => '<span class="badge bg-info text-white"><i class="bi bi-eye me-1"></i>Buyer Viewed</span>',
            'accepted'           => '<span class="badge bg-success text-white"><i class="bi bi-check-circle-fill me-1"></i>Quote Accepted</span>',
            'rejected'           => '<span class="badge bg-danger text-white"><i class="bi bi-x-circle me-1"></i>Rejected / Declined</span>',
            'converted_to_order' => '<span class="badge bg-success-subtle text-success border border-success fw-bold"><i class="bi bi-trophy-fill text-warning me-1"></i>Converted to Order</span>',
            'expired'            => '<span class="badge bg-secondary text-white"><i class="bi bi-hourglass-bottom me-1"></i>Expired</span>',
            'closed'             => '<span class="badge bg-dark text-white"><i class="bi bi-lock me-1"></i>Closed</span>',
            default              => '<span class="badge bg-secondary">' . ucfirst($this->status) . '</span>',
        };
    }

    /**
     * Get Urgency Level Badge
     */
    public function getUrgencyBadge(): string
    {
        $urgency = $this->urgency_level ?? 'Standard';
        if (str_contains(strtolower($urgency), 'immediate') || str_contains(strtolower($urgency), 'critical')) {
            return '<span class="badge bg-danger-subtle text-danger border border-danger"><i class="bi bi-lightning-fill me-1"></i>' . e($urgency) . '</span>';
        }
        if (str_contains(strtolower($urgency), 'urgent') || str_contains(strtolower($urgency), '1 week')) {
            return '<span class="badge bg-warning-subtle text-dark border border-warning"><i class="bi bi-fire me-1"></i>' . e($urgency) . '</span>';
        }
        return '<span class="badge bg-light text-dark border">' . e($urgency) . '</span>';
    }

    /**
     * Scope for pending RFQs
     */
    public function scopePending($query)
    {
        return $query->whereIn('status', ['pending', 'new']);
    }

    /**
     * Scope for active quotes sent to buyers
     */
    public function scopeActiveQuoted($query)
    {
        return $query->whereIn('status', ['sent', 'quoted', 'viewed']);
    }

    /**
     * Scope for converted quotes
     */
    public function scopeConverted($query)
    {
        return $query->where('status', 'converted_to_order');
    }
}
