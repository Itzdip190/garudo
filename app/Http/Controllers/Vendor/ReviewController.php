<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Review;
use App\Models\VendorReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();
        
        $ratingFilter = $request->query('rating', 'all');

        $query = VendorReview::where('vendor_id', $vendor->id)->where('status', '!=', 'hidden');

        if ($ratingFilter !== 'all' && is_numeric($ratingFilter)) {
            $query->where('rating', (int)$ratingFilter);
        }

        $reviews = $query->latest()->paginate(15)->withQueryString();

        // Rating metrics
        $allVendorReviews = VendorReview::where('vendor_id', $vendor->id)->where('status', 'approved')->get();
        $totalReviews = $allVendorReviews->count();
        $avgRating = $totalReviews > 0 ? round($allVendorReviews->avg('rating'), 1) : round($vendor->rating ?? 5.0, 1);

        $starCounts = [
            5 => $allVendorReviews->where('rating', 5)->count(),
            4 => $allVendorReviews->where('rating', 4)->count(),
            3 => $allVendorReviews->where('rating', 3)->count(),
            2 => $allVendorReviews->where('rating', 2)->count(),
            1 => $allVendorReviews->where('rating', 1)->count(),
        ];

        $starPercents = [];
        foreach ($starCounts as $star => $count) {
            $starPercents[$star] = $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0;
        }

        // Sub-ratings averages
        $avgQuality = $totalReviews > 0 ? round($allVendorReviews->avg('material_quality_rating'), 1) : 5.0;
        $avgDelivery = $totalReviews > 0 ? round($allVendorReviews->avg('delivery_rating'), 1) : 5.0;
        $avgPricing = $totalReviews > 0 ? round($allVendorReviews->avg('pricing_rating'), 1) : 5.0;

        // Review QR URL
        $reviewUrl = $vendor->review_url;
        $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($reviewUrl) . "&margin=10";

        $isKycApproved = $vendor->isKycApproved();

        return view('vendor.reviews.index', compact(
            'vendor',
            'reviews',
            'ratingFilter',
            'totalReviews',
            'avgRating',
            'starCounts',
            'starPercents',
            'avgQuality',
            'avgDelivery',
            'avgPricing',
            'reviewUrl',
            'qrCodeUrl',
            'isKycApproved'
        ));
    }
}
