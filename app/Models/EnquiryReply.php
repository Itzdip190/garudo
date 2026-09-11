<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnquiryReply extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'enquiry_id',
        'sender_type',
        'sender_id',
        'message',
        'attachment',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function enquiry()
    {
        return $this->belongsTo(Enquiry::class);
    }

    public function getSenderNameAttribute(): string
    {
        if ($this->sender_type === 'admin') {
            return 'Garudo Official Desk';
        }
        if ($this->sender_type === 'vendor') {
            $vendor = Vendor::find($this->sender_id);
            return $vendor ? ($vendor->company_name ?? $vendor->name) : 'Seller Support';
        }
        if ($this->sender_type === 'customer') {
            $customer = Customer::find($this->sender_id);
            return $customer ? ($customer->company_name ?? $customer->name) : 'Customer';
        }
        return 'User';
    }
}
