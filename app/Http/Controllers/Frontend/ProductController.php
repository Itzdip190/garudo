<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()->with(['primaryImage', 'vendor', 'category', 'brand']);

        if ($request->filled('category')) {
            $catSlug = $request->category;
            $matchedCategory = Category::where('slug', $catSlug)->first();
            if ($matchedCategory) {
                $categoryIds = $matchedCategory->children()->pluck('id')->push($matchedCategory->id);
                $query->whereIn('category_id', $categoryIds);
            } else {
                $query->whereHas('category', function ($q) use ($catSlug) {
                    $q->where('slug', $catSlug);
                });
            }
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        $products = $query->paginate(24);
        $categories = Category::root()->with('children')->active()->get();

        return view('frontend.products.listing', compact('products', 'categories'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('approval_status', 'approved')
            ->with([
                'vendor',
                'category',
                'brand',
                'images',
                'videos',
                'documents',
                'specifications',
                'bulkPrices',
                'variants.attributes',
                'reviews.customer',
                'qna',
            ])
            ->firstOrFail();

        // Increment view count
        $product->increment('view_count');

        $relatedProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['primaryImage', 'vendor'])
            ->take(6)
            ->get();

        $vendorProducts = Product::active()
            ->where('vendor_id', $product->vendor_id)
            ->where('id', '!=', $product->id)
            ->with(['primaryImage'])
            ->take(6)
            ->get();

        return view('frontend.products.show', compact('product', 'relatedProducts', 'vendorProducts'));
    }
}
