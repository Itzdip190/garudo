<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BrandDealerAuthorization;
use Illuminate\Http\Request;

class DealerAuthorizationVerificationController extends Controller
{
    /**
     * Public Digital Verification for Brand Authorized Dealer Certificate
     */
    public function verify($certificateNumber)
    {
        $authRecord = BrandDealerAuthorization::where('certificate_number', $certificateNumber)
                                             ->orWhere('qr_token', $certificateNumber)
                                             ->with(['brand.ownerVendor', 'vendor', 'verifiedByAdmin'])
                                             ->first();

        if (!$authRecord) {
            return view('frontend.verification.dealer-authorization', [
                'isValid'            => false,
                'errorMessage'       => 'No official Garudo Brand Dealer Authorization record found matching this certificate identifier.',
                'certificateNumber'  => $certificateNumber,
                'authRecord'         => null,
            ]);
        }

        $isValid = $authRecord->isVerified();
        $isExpired = $authRecord->isExpired();

        return view('frontend.verification.dealer-authorization', [
            'isValid'            => $isValid,
            'isExpired'          => $isExpired,
            'certificateNumber'  => $authRecord->certificate_number,
            'authRecord'         => $authRecord,
        ]);
    }
}
