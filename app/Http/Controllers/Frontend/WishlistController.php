<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\WishlistService;
use App\Services\CartService;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * Display Wishlist Page
     */
    public function index()
    {
        $products = WishlistService::getItems();
        $count = WishlistService::getCount();

        return view('frontend.wishlist.index', compact('products', 'count'));
    }

    /**
     * Toggle product in wishlist (AJAX or direct)
     */
    public function toggle(Request $request, $productId)
    {
        $result = WishlistService::toggle($productId);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($result);
        }

        return back()->with('success', $result['message']);
    }

    /**
     * Remove item from wishlist
     */
    public function remove(Request $request, $productId)
    {
        WishlistService::remove($productId);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'count'   => WishlistService::getCount(),
                'message' => 'Product removed from wishlist.'
            ]);
        }

        return back()->with('success', 'Product removed from wishlist.');
    }

    /**
     * Move wishlist item directly to shopping cart
     */
    public function moveToCart(Request $request, $productId)
    {
        $cartResult = CartService::add($productId, 1);
        WishlistService::remove($productId);

        return redirect()->route('cart.index')->with('success', 'Product moved to cart.');
    }
}
