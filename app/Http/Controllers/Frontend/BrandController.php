<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\BrandDealerAuthorization;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display all certified building material brands
     */
    public function index(Request $request)
    {
        $search = $request->query('q');

        $query = Brand::where('status', 'active')
            ->withCount(['products' => function ($q) {
                $q->where('approval_status', 'approved')->where('status', 'active');
            }])
            ->withCount(['verifiedDealerAuthorizations'])
            ->orderBy('name', 'asc');

        if (!empty($search)) {
            $query->where('name', 'like', "%{$search}%");
        }

        $brands = $query->get();

        // Featured / Top Brands
        $topBrands = Brand::where('status', 'active')
            ->where('is_featured', true)
            ->withCount(['products' => function ($q) {
                $q->where('approval_status', 'approved')->where('status', 'active');
            }])
            ->take(8)
            ->get();

        return view('frontend.brands.index', compact('brands', 'topBrands', 'search'));
    }

    /**
     * Show products of a specific brand + Authorized Dealer Network Directory
     */
    public function show(Request $request, $slug)
    {
        $brand = Brand::where('slug', $slug)
            ->where('status', 'active')
            ->with(['ownerVendor'])
            ->firstOrFail();

        // Fetch authorized dealers for this brand
        $dealerQuery = BrandDealerAuthorization::where('brand_id', $brand->id)
            ->where('garudo_approval_status', 'verified')
            ->where('status', 'active')
            ->with(['vendor.primaryWarehouse', 'vendor.addresses']);

        $dealerState = $request->query('dealer_state');
        if (!empty($dealerState)) {
            $dealerQuery->where(function ($dq) use ($dealerState) {
                $dq->where('territory_type', 'national')
                   ->orWhereJsonContains('territory_states', $dealerState);
            });
        }

        $dealers = $dealerQuery->get();

        $query = Product::active()
            ->where('brand_id', $brand->id)
            ->with(['primaryImage', 'vendor', 'category']);

        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $sort = $request->query('sort', 'popular');
        if ($sort === 'price_low') {
            $query->orderBy('selling_price', 'asc');
        } elseif ($sort === 'price_high') {
            $query->orderBy('selling_price', 'desc');
        } elseif ($sort === 'rating') {
            $query->orderBy('rating', 'desc');
        } else {
            $query->latest();
        }

        $products = $query->paginate(24);
        $categories = Category::root()->with('children')->active()->get();

        $indianStates = [
            "Andhra Pradesh", "Arunachal Pradesh", "Assam", "Bihar", "Chhattisgarh", "Goa", "Gujarat", 
            "Haryana", "Himachal Pradesh", "Jharkhand", "Karnataka", "Kerala", "Madhya Pradesh", 
            "Maharashtra", "Manipur", "Meghalaya", "Mizoram", "Nagaland", "Odisha", "Punjab", 
            "Rajasthan", "Sikkim", "Tamil Nadu", "Telangana", "Tripura", "Uttar Pradesh", 
            "Uttarakhand", "West Bengal", "Delhi NCR"
        ];

        return view('frontend.brands.show', compact('brand', 'products', 'categories', 'sort', 'dealers', 'indianStates', 'dealerState'));
    }
}
