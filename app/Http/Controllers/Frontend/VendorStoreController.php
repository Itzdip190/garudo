<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Category;
use App\Models\VendorReview;
use Illuminate\Http\Request;

class VendorStoreController extends Controller
{
    /**
     * Display a Vendor's Public Storefront
     */
    public function show(Request $request, $slug)
    {
        // Find vendor by company_slug or ID
        $vendor = Vendor::where('company_slug', $slug)
            ->orWhere('id', is_numeric($slug) ? $slug : 0)
            ->firstOrFail();

        $query = $vendor->products()
            ->where('approval_status', 'approved')
            ->where('status', 'active')
            ->with(['primaryImage', 'category', 'brand']);

        // Filter by category
        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Search within store
        if ($request->has('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Sort
        $sort = $request->query('sort', 'latest');
        if ($sort === 'price_low') {
            $query->orderBy('selling_price', 'asc');
        } elseif ($sort === 'price_high') {
            $query->orderBy('selling_price', 'desc');
        } elseif ($sort === 'rating') {
            $query->orderBy('rating', 'desc');
        } else {
            $query->latest();
        }

        $products = $query->paginate(18);

        // Store categories
        $storeCategoryIds = $vendor->products()
            ->where('approval_status', 'approved')
            ->where('status', 'active')
            ->pluck('category_id')
            ->unique();
        
        $categories = Category::whereIn('id', $storeCategoryIds)->get();

        // Vendor Reviews & Ratings
        $vendorReviews = VendorReview::where('vendor_id', $vendor->id)
            ->where('status', 'approved')
            ->latest()
            ->paginate(10, ['*'], 'review_page');

        $allReviews = VendorReview::where('vendor_id', $vendor->id)->where('status', 'approved')->get();
        $totalReviews = $allReviews->count();
        $avgRating = $totalReviews > 0 ? round($allReviews->avg('rating'), 1) : round($vendor->rating ?? 4.8, 1);

        $starCounts = [
            5 => $allReviews->where('rating', 5)->count(),
            4 => $allReviews->where('rating', 4)->count(),
            3 => $allReviews->where('rating', 3)->count(),
            2 => $allReviews->where('rating', 2)->count(),
            1 => $allReviews->where('rating', 1)->count(),
        ];

        $starPercents = [];
        foreach ($starCounts as $star => $count) {
            $starPercents[$star] = $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0;
        }

        $avgQuality = $totalReviews > 0 ? round($allReviews->avg('material_quality_rating'), 1) : 4.9;
        $avgDelivery = $totalReviews > 0 ? round($allReviews->avg('delivery_rating'), 1) : 4.8;
        $avgPricing = $totalReviews > 0 ? round($allReviews->avg('pricing_rating'), 1) : 4.7;

        // Active Subscription perks for badges
        $activeSubscription = $vendor->activeSubscription;
        $has247Support = $vendor->has247Support();

        return view('frontend.store.show', compact(
            'vendor',
            'products',
            'categories',
            'activeSubscription',
            'has247Support',
            'sort',
            'vendorReviews',
            'totalReviews',
            'avgRating',
            'starCounts',
            'starPercents',
            'avgQuality',
            'avgDelivery',
            'avgPricing'
        ));
    }

    /**
     * Store a Customer / Buyer Rating & Review for the Vendor
     */
    public function storeReview(Request $request, $slug)
    {
        $vendor = Vendor::where('company_slug', $slug)
            ->orWhere('id', is_numeric($slug) ? $slug : 0)
            ->firstOrFail();

        $validated = $request->validate([
            'rating'                  => 'required|integer|between:1,5',
            'reviewer_name'           => 'required|string|max:150',
            'reviewer_company'        => 'nullable|string|max:200',
            'reviewer_phone'          => 'nullable|string|max:30',
            'reviewer_email'          => 'nullable|email|max:150',
            'title'                   => 'nullable|string|max:255',
            'review'                  => 'required|string|min:5|max:2000',
            'material_quality_rating' => 'nullable|integer|between:1,5',
            'delivery_rating'         => 'nullable|integer|between:1,5',
            'pricing_rating'          => 'nullable|integer|between:1,5',
        ]);

        $customerId = auth('customer')->id();
        $isVerifiedBuyer = auth('customer')->check();

        VendorReview::create([
            'vendor_id'               => $vendor->id,
            'customer_id'             => $customerId,
            'reviewer_name'           => $validated['reviewer_name'],
            'reviewer_company'        => $validated['reviewer_company'] ?? (auth('customer')->user()?->company_name ?? null),
            'reviewer_phone'          => $validated['reviewer_phone'] ?? (auth('customer')->user()?->phone ?? null),
            'reviewer_email'          => $validated['reviewer_email'] ?? (auth('customer')->user()?->email ?? null),
            'rating'                  => (int)$validated['rating'],
            'title'                   => $validated['title'] ?? null,
            'review'                  => $validated['review'],
            'material_quality_rating' => (int)($validated['material_quality_rating'] ?? 5),
            'delivery_rating'         => (int)($validated['delivery_rating'] ?? 5),
            'pricing_rating'          => (int)($validated['pricing_rating'] ?? 5),
            'is_verified_buyer'       => $isVerifiedBuyer,
            'status'                  => 'approved',
        ]);

        // Recalculate vendor average rating
        $vendor->recalculateRating();

        return redirect()->to(route('store.show', $vendor->company_slug ?? $vendor->id) . '#reviews')
            ->with('success', 'Thank you! Your verified rating & review for ' . $vendor->company_name . ' has been published successfully.');
    }
}
