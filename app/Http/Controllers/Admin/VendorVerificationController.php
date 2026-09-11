<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\VendorKycDocument;
use App\Events\VendorVerifiedEvent;
use App\Notifications\VendorKycRejectedNotification;
use App\Services\Certificate\VendorCertificateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorVerificationController extends Controller
{
    protected VendorCertificateService $certificateService;

    public function __construct(VendorCertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
    }

    /**
     * Display the Admin KYC & Compliance Review Workbench for a vendor.
     */
    public function review($id)
    {
        $vendor = Vendor::with(['kycDocuments', 'bankDetails', 'activeCertificate', 'warehouses'])
            ->findOrFail($id);

        $documents = $vendor->kycDocuments;
        $bankDetails = $vendor->bankDetails;
        $activeCertificate = $vendor->activeCertificate;

        return view('admin.vendors.kyc_review', compact('vendor', 'documents', 'bankDetails', 'activeCertificate'));
    }

    /**
     * Approve Vendor KYC, activate Verified Channel Partner status, and issue Certificate.
     */
    public function approveKyc(Request $request, $id)
    {
        $vendor = Vendor::findOrFail($id);

        DB::transaction(function () use ($vendor) {
            $adminId = auth('admin')->id();

            // 1. Mark all pending documents as approved
            $vendor->kycDocuments()->where('status', 'pending')->update([
                'status'      => 'approved',
                'verified_at' => now(),
                'verified_by' => $adminId,
            ]);

            // 2. Mark bank details verified
            if ($vendor->bankDetails) {
                $vendor->bankDetails->update(['is_verified' => true]);
            }

            // 3. Update vendor verification flags
            $vendor->update([
                'kyc_status'          => 'verified',
                'kyc_verified_at'     => now(),
                'kyc_rejected_reason' => null,
                'kyc_review_notes'    => null,
                'approval_status'     => 'approved',
                'approved_at'         => $vendor->approved_at ?? now(),
                'approved_by'         => $adminId,
                'is_verified'         => true,
                'status'              => 'active',
            ]);

            // 4. Trigger Event to issue Digital Certificate and fire Notification
            event(new VendorVerifiedEvent($vendor, $adminId));

            // 5. Send automated Start Selling Message & Notification to vendor
            \App\Services\VendorNotificationService::ensureOnboardingMessages($vendor);
        });

        return redirect()->route('admin.vendors.show', $vendor->id)
            ->with('success', "Vendor '{$vendor->company_name}' has been officially verified! Digital Certificate and 1-Year Free Membership issued.");
    }

    /**
     * Reject Vendor KYC with structured feedback.
     */
    public function rejectKyc(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $vendor = Vendor::findOrFail($id);

        $vendor->update([
            'kyc_status'          => 'rejected',
            'kyc_rejected_reason' => $request->rejection_reason,
            'approval_status'     => 'rejected',
            'is_verified'         => false,
        ]);

        // Notify vendor with guidance
        $vendor->notify(new VendorKycRejectedNotification($request->rejection_reason));

        return redirect()->route('admin.vendors.show', $vendor->id)
            ->with('warning', "Vendor '{$vendor->company_name}' KYC verification rejected. Rejection notice dispatched.");
    }

    /**
     * Update individual document verification status.
     */
    public function updateDocumentStatus(Request $request, $id, $docId)
    {
        $request->validate([
            'status'          => 'required|in:approved,rejected',
            'rejection_notes' => 'nullable|string|max:500',
        ]);

        $document = VendorKycDocument::where('vendor_id', $id)->findOrFail($docId);

        $document->update([
            'status'          => $request->status,
            'rejection_notes' => $request->rejection_notes,
            'verified_at'     => now(),
            'verified_by'     => auth('admin')->id(),
        ]);

        return back()->with('success', "Document '{$document->document_type_name}' status updated to " . ucfirst($request->status));
    }
}
