<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\VendorCertificate;
use Illuminate\Http\Request;

class CertificateVerificationController extends Controller
{
    /**
     * Publicly verify a Vendor's Channel Partner Certificate via QR Code or Certificate Number.
     */
    public function verify(string $certificate_number)
    {
        $certificate = VendorCertificate::with(['vendor.categories', 'vendor.primaryWarehouse'])
            ->where('certificate_number', $certificate_number)
            ->first();

        $searchedNumber = $certificate_number;

        return view('frontend.certificate.verify', compact('certificate', 'searchedNumber'));
    }
}
