<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordResetRequest extends Model
{
    use HasFactory;

    protected $table = 'password_reset_requests';

    protected $fillable = [
        'user_type',
        'user_id',
        'email',
        'phone',
        'name',
        'status',
        'temp_password',
        'admin_notes',
        'handled_by',
        'handled_at',
    ];

    protected $casts = [
        'handled_at' => 'datetime',
    ];

    /**
     * Relationship to customer if user_type is customer
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'user_id');
    }

    /**
     * Relationship to vendor if user_type is vendor
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'user_id');
    }

    /**
     * Admin who handled/approved this request
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'handled_by');
    }

    /**
     * Scope for pending requests
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved requests
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
