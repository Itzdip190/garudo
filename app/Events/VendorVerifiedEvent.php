<?php

namespace App\Events;

use App\Models\Vendor;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VendorVerifiedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Vendor $vendor;
    public ?int $approvedByAdminId;

    public function __construct(Vendor $vendor, ?int $approvedByAdminId = null)
    {
        $this->vendor = $vendor;
        $this->approvedByAdminId = $approvedByAdminId;
    }
}
