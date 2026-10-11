<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display Shopping Cart Page
     */
    public function index()
    {
        $cartItems = CartService::getCart();
        $subtotal = CartService::getSubtotal();
        $gstTax = CartService::getGstTax();
        $grandTotal = CartService::getTotal();
        $itemCount = CartService::getCount();

        // Suggested / Top Products to add
        $suggestedProducts = Product::active()
            ->with(['primaryImage', 'vendor'])
            ->take(4)
            ->get();

        return view('frontend.cart.index', compact('cartItems', 'subtotal', 'gstTax', 'grandTotal', 'itemCount', 'suggestedProducts'));
    }

    /**
     * Add product to cart (Supports form submission and AJAX)
     */
    public function add(Request $request)
    {
        // Enforce Pre-Launch Mode check if active
        if (\App\Models\HomepageSetting::get('launch_mode_active', '1') === '1') {
            $launchDate = \App\Models\HomepageSetting::get('launch_date', '20th October');
            $launchNotice = \App\Models\HomepageSetting::get('launch_notice_message', "Official Launching on {$launchDate}! Product purchasing will open on launch day.");

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'is_launch_mode' => true,
                    'launch_date' => $launchDate,
                    'message' => $launchNotice,
                ], 403);
            }

            return back()->with('error', $launchNotice)->with('launch_notice', $launchNotice);
        }

        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);
        $variantId = $request->input('variant_id');

        $result = CartService::add($productId, $quantity, $variantId);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($result);
        }

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message'] ?? 'Unable to add product to cart.');
    }

    /**
     * Update item quantity
     */
    public function update(Request $request)
    {
        $key = $request->input('key');
        $quantity = $request->input('quantity', 1);

        CartService::update($key, $quantity);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'    => true,
                'cart_count' => CartService::getCount(),
                'subtotal'   => CartService::getSubtotal(),
                'gst'        => CartService::getGstTax(),
                'total'      => CartService::getTotal(),
            ]);
        }

        return back()->with('success', 'Cart updated successfully.');
    }

    /**
     * Remove item from cart
     */
    public function remove(Request $request, $key)
    {
        CartService::remove($key);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'    => true,
                'cart_count' => CartService::getCount(),
                'subtotal'   => CartService::getSubtotal(),
                'total'      => CartService::getTotal(),
            ]);
        }

        return back()->with('success', 'Item removed from cart.');
    }

    /**
     * Clear all cart items
     */
    public function clear()
    {
        CartService::clear();
        return redirect()->route('cart.index')->with('success', 'Cart has been cleared.');
    }

    /**
     * Reorder past order into active cart
     */
    public function reorder($orderId)
    {
        $added = CartService::reorderFromOrder($orderId);

        if ($added > 0) {
            return redirect()->route('cart.index')->with('success', "Added {$added} items from Order #{$orderId} to your cart!");
        }

        return back()->with('error', 'Unable to reorder items from this order.');
    }
}
