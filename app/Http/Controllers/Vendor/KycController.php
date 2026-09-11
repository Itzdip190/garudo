<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\KycStepRequest;
use App\Models\VendorKycDocument;
use App\Models\VendorBankDetail;
use App\Events\VendorKycSubmittedEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class KycController extends Controller
{
    /**
     * Display the Vendor KYC Verification Center.
     */
    public function index()
    {
        $vendor = auth('vendor')->user()->load(['kycDocuments', 'bankDetails', 'activeCertificate']);
        $step = request()->query('step', $vendor->kyc_step ?? 1);

        $documents = $vendor->kycDocuments->keyBy('document_type');
        $bankDetails = $vendor->bankDetails;

        return view('vendor.kyc.index', compact('vendor', 'step', 'documents', 'bankDetails'));
    }

    /**
     * Save progress for each step of the KYC onboarding wizard.
     */
    public function storeStep(KycStepRequest $request)
    {
        $vendor = auth('vendor')->user();
        $step = (int) $request->input('step', 1);

        DB::transaction(function () use ($request, $vendor, $step) {
            if ($step === 1) {
                // Step 1: Enterprise Profile Details
                $vendor->update([
                    'company_name'        => $request->company_name,
                    'business_type'       => $request->business_type,
                    'year_established'    => $request->year_established,
                    'company_website'     => $request->company_website,
                    'company_description' => $request->company_description,
                    'kyc_step'            => max($vendor->kyc_step, 2),
                ]);
            } elseif ($step === 2) {
                // Step 2: Statutory Identifiers (GSTIN & PAN)
                $vendor->update([
                    'gstin'      => strtoupper(trim($request->gstin)),
                    'pan_number' => strtoupper(trim($request->pan_number)),
                    'kyc_step'   => max($vendor->kyc_step, 3),
                ]);
            } elseif ($step === 3) {
                // Step 3: Payout Bank Account Details
                VendorBankDetail::updateOrCreate(
                    ['vendor_id' => $vendor->id],
                    [
                        'account_holder_name' => $request->account_holder_name,
                        'account_number'      => trim($request->account_number),
                        'bank_name'           => $request->bank_name,
                        'branch_name'         => $request->branch_name ?? 'Main Branch',
                        'ifsc_code'           => strtoupper(trim($request->ifsc_code)),
                        'account_type'        => $request->account_type,
                        'is_verified'         => false,
                    ]
                );

                $vendor->update([
                    'kyc_step' => max($vendor->kyc_step, 4),
                ]);
            } elseif ($step === 4) {
                // Step 4: Multi-Document Uploads
                $docTypes = [
                    'pan_document'              => 'pan',
                    'gstin_document'            => 'gstin_certificate',
                    'cancelled_cheque_document' => 'cancelled_cheque',
                    'trade_license_document'    => 'trade_license',
                ];

                foreach ($docTypes as $inputKey => $docType) {
                    if ($request->hasFile($inputKey)) {
                        $file = $request->file($inputKey);
                        $path = $file->store("kyc_documents/{$vendor->id}", 'public');

                        VendorKycDocument::updateOrCreate(
                            [
                                'vendor_id'     => $vendor->id,
                                'document_type' => $docType,
                            ],
                            [
                                'file_path'       => $path,
                                'file_name'       => $file->getClientOriginalName(),
                                'status'          => 'pending',
                                'rejection_notes' => null,
                                'uploaded_at'     => now(),
                            ]
                        );
                    }
                }

                $vendor->update([
                    'kyc_step' => 4,
                ]);
            }
        });

        $nextStep = min(4, $step + 1);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'   => true,
                'message'   => "Step {$step} saved successfully.",
                'next_step' => $nextStep,
            ]);
        }

        return redirect()->route('vendor.kyc.index', ['step' => $nextStep])
                         ->with('success', "Step {$step} information saved successfully.");
    }

    /**
     * Submit KYC for final Admin Compliance & Channel Partner Verification.
     */
    public function submitFinal(Request $request)
    {
        $vendor = auth('vendor')->user()->load(['kycDocuments', 'bankDetails']);

        // Check required statutory info
        if (empty($vendor->gstin) || empty($vendor->pan_number)) {
            return back()->with('error', 'Please complete Step 2 (GSTIN and PAN) before submitting.');
        }

        if (!$vendor->bankDetails) {
            return back()->with('error', 'Please complete Step 3 (Bank Account Details) before submitting.');
        }

        // Check essential document uploads
        $hasPanDoc = $vendor->kycDocuments()->where('document_type', 'pan')->exists();
        $hasGstDoc = $vendor->kycDocuments()->where('document_type', 'gstin_certificate')->exists();

        if (!$hasPanDoc || !$hasGstDoc) {
            return back()->with('error', 'Please upload at least your PAN Card and GST Registration Certificate before submitting.');
        }

        $vendor->update([
            'kyc_status'           => 'submitted',
            'kyc_submitted_at'     => now(),
            'kyc_rejected_reason'  => null,
            'approval_status'      => $vendor->approval_status === 'approved' ? 'approved' : 'pending',
        ]);

        // Trigger Event for compliance logs / admin notifications
        event(new VendorKycSubmittedEvent($vendor));

        return redirect()->route('vendor.kyc.index')
            ->with('success', '🎉 Your KYC verification packet has been submitted! Our compliance team will review your credentials within 24 business hours.');
    }
}
