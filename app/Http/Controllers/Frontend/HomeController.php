<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\Brand;
use App\Models\Banner;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::root()->with('children')->active()->take(8)->get();
        $featuredCategories = Category::featured()->take(6)->get();
        
        $featuredProducts = Product::active()->featured()->with(['primaryImage', 'vendor', 'category'])->take(8)->get();
        $trendingProducts = Product::active()->where('is_trending', true)->with(['primaryImage', 'vendor'])->take(8)->get();
        $newArrivals = Product::active()->where('is_new_arrival', true)->with(['primaryImage', 'vendor'])->take(8)->get();
        
        $vendorHighlights = Vendor::approved()->active()->featured()->take(6)->get();
        $brands = Brand::where('status', 'active')->where('is_featured', true)->take(10)->get();

        // 1. Deals of the Day (Admin Curated & Managed)
        $dealsQuery = Product::active()->with(['primaryImage', 'vendor', 'category', 'brand']);
        $dealsOfTheDay = collect();
        if (\Illuminate\Support\Facades\Schema::hasColumn('products', 'is_deal_of_the_day')) {
            $dealsOfTheDay = (clone $dealsQuery)->where('is_deal_of_the_day', true)->take(4)->get();
        }
        if ($dealsOfTheDay->isEmpty()) {
            $dealsOfTheDay = (clone $dealsQuery)->whereNotNull('mrp')->where('mrp', '>', 0)->orderByRaw('(mrp - selling_price) DESC')->take(4)->get();
            if ($dealsOfTheDay->isEmpty()) {
                $dealsOfTheDay = (clone $dealsQuery)->take(4)->get();
            }
        }

        // 2. Sponsored Products Showcase (Admin Curated & Managed - Multi-Item Horizontal Carousel)
        $sponsoredQuery = Product::active()->with(['primaryImage', 'vendor', 'category', 'brand']);
        $sponsoredProducts = collect();
        if (\Illuminate\Support\Facades\Schema::hasColumn('products', 'is_sponsored')) {
            $sponsoredProducts = (clone $sponsoredQuery)->where('is_sponsored', true)->take(12)->get();
        }
        if ($sponsoredProducts->count() < 6) {
            $extra = (clone $sponsoredQuery)
                ->when($sponsoredProducts->isNotEmpty(), function ($q) use ($sponsoredProducts) {
                    $q->whereNotIn('id', $sponsoredProducts->pluck('id'));
                })
                ->take(8 - $sponsoredProducts->count())
                ->get();
            $sponsoredProducts = $sponsoredProducts->concat($extra);
        }

        return view('frontend.home.index', compact(
            'categories',
            'featuredCategories',
            'featuredProducts',
            'trendingProducts',
            'newArrivals',
            'vendorHighlights',
            'brands',
            'dealsOfTheDay',
            'sponsoredProducts'
        ));
    }
}
