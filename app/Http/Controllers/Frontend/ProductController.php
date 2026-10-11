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
                // Check if direct products exist for this category/subcategory
                $hasDirect = Product::active()->whereIn('category_id', $categoryIds)->exists();
                if ($hasDirect) {
                    $query->whereIn('category_id', $categoryIds);
                } else {
                    // Fallback: If no products directly assigned to this subcategory yet, match by name or parent
                    $catName = $matchedCategory->name;
                    $query->where(function ($q) use ($matchedCategory, $catName) {
                        $q->where('name', 'like', "%{$catName}%")
                          ->orWhere('short_description', 'like', "%{$catName}%");
                        if ($matchedCategory->parent_id) {
                            $q->orWhere('category_id', $matchedCategory->parent_id);
                        }
                    });
                }
            } else {
                // If slug is not in DB (e.g. demo taxonomy), search by keywords in product name & description
                $searchKeywords = str_replace('-', ' ', $catSlug);
                $query->where(function ($q) use ($searchKeywords) {
                    $q->where('name', 'like', "%{$searchKeywords}%")
                      ->orWhere('short_description', 'like', "%{$searchKeywords}%");
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

        if ($request->filled('trending') || $request->filter === 'trending' || $request->sort === 'trending') {
            $hasTrendingNowCol = \Illuminate\Support\Facades\Schema::hasColumn('products', 'is_trending_now');
            $query->where(function ($q) use ($hasTrendingNowCol) {
                if ($hasTrendingNowCol) {
                    $q->where('is_trending_now', true)->orWhere('is_trending', true);
                } else {
                    $q->where('is_trending', true)->orWhere('is_popular', true);
                }
            })->orderBy('view_count', 'desc');
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
