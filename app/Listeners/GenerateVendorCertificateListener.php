<?php

namespace App\Listeners;

use App\Events\VendorVerifiedEvent;
use App\Services\Certificate\VendorCertificateService;
use App\Notifications\VendorKycApprovedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class GenerateVendorCertificateListener
{
    protected VendorCertificateService $certificateService;

    public function __construct(VendorCertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
    }

    public function handle(VendorVerifiedEvent $event): void
    {
        try {
            $vendor = $event->vendor;
            
            // Issue 1-year Verified Channel Partner Certificate
            $certificate = $this->certificateService->issueCertificate($vendor, 1);

            // Send notification with Certificate details
            $vendor->notify(new VendorKycApprovedNotification($certificate));

            Log::info("Verified Channel Partner Certificate [{$certificate->certificate_number}] successfully issued to Vendor ID: {$vendor->id} ({$vendor->company_name}).");
        } catch (\Throwable $e) {
            Log::error("Failed to generate Vendor Certificate on VendorVerifiedEvent: " . $e->getMessage(), [
                'vendor_id' => $event->vendor->id,
                'trace'     => $e->getTraceAsString(),
            ]);
        }
    }
}
