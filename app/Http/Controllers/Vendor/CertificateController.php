<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\VendorCertificate;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    /**
     * Display the official Verified Channel Partner Digital Certificate.
     */
    public function show()
    {
        $vendor = auth('vendor')->user()->load(['activeCertificate', 'certificates']);

        $certificate = $vendor->activeCertificate;

        if (!$certificate) {
            return redirect()->route('vendor.kyc.index')
                ->with('info', 'Your Verified Channel Partner Certificate will be automatically issued once your KYC verification is approved by compliance.');
        }

        return view('vendor.certificate.index', compact('vendor', 'certificate'));
    }

    /**
     * Serve printable high-resolution Certificate for saving as PDF or printing.
     */
    public function download()
    {
        $vendor = auth('vendor')->user()->load(['activeCertificate']);
        $certificate = $vendor->activeCertificate;

        if (!$certificate) {
            abort(404, 'No active certificate found.');
        }

        $certificate->increment('download_count');

        return view('vendor.certificate.print', compact('vendor', 'certificate'));
    }
}
