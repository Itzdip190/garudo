<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Session;

class CartService
{
    const SESSION_KEY = 'garudo_b2b_cart';

    /**
     * Get all cart items from session
     */
    public static function getCart(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    /**
     * Get total quantity of items in cart
     */
    public static function getCount(): int
    {
        $cart = self::getCart();
        $count = 0;
        foreach ($cart as $item) {
            $count += (int) ($item['quantity'] ?? 1);
        }
        return $count;
    }

    /**
     * Add product to cart
     */
    public static function add($productId, $quantity = 1, $variantId = null): array
    {
        $product = Product::with(['primaryImage', 'vendor', 'category', 'brand'])->find($productId);
        if (!$product) {
            return ['success' => false, 'message' => 'Product not found.'];
        }

        $cart = self::getCart();
        $key = $productId . ($variantId ? '_' . $variantId : '');

        $quantity = max(1, (int) $quantity);

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {
            $primaryImg = $product->primaryImage ? $product->primaryImage->image_path : ($product->image_url ?? null);

            $cart[$key] = [
                'key'          => $key,
                'product_id'   => $product->id,
                'name'         => $product->name,
                'slug'         => $product->slug,
                'sku'          => $product->sku ?? ('SKU-' . $product->id),
                'image'        => $primaryImg,
                'price'        => (float) $product->selling_price,
                'mrp'          => (float) ($product->mrp ?? $product->selling_price),
                'unit'         => $product->unit ?? 'piece',
                'min_order_qty'=> (int) ($product->min_order_qty ?? 1),
                'vendor_id'    => $product->vendor_id,
                'vendor_name'  => $product->vendor->company_name ?? 'Garudo Supplier',
                'quantity'     => $quantity,
                'added_at'     => now()->toDateTimeString(),
            ];
        }

        Session::put(self::SESSION_KEY, $cart);

        return [
            'success' => true,
            'message' => "Added {$product->name} to cart.",
            'cart_count' => self::getCount(),
            'item' => $cart[$key]
        ];
    }

    /**
     * Update item quantity in cart
     */
    public static function update($key, $quantity): bool
    {
        $cart = self::getCart();
        if (isset($cart[$key])) {
            $qty = max(1, (int) $quantity);
            $cart[$key]['quantity'] = $qty;
            Session::put(self::SESSION_KEY, $cart);
            return true;
        }
        return false;
    }

    /**
     * Remove item from cart
     */
    public static function remove($key): bool
    {
        $cart = self::getCart();
        if (isset($cart[$key])) {
            unset($cart[$key]);
            Session::put(self::SESSION_KEY, $cart);
            return true;
        }
        return false;
    }

    /**
     * Clear the whole cart
     */
    public static function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    /**
     * Calculate Subtotal
     */
    public static function getSubtotal(): float
    {
        $cart = self::getCart();
        $subtotal = 0.0;
        foreach ($cart as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }
        return (float) $subtotal;
    }

    /**
     * Calculate Estimated GST (18%)
     */
    public static function getGstTax(): float
    {
        return round(self::getSubtotal() * 0.18, 2);
    }

    /**
     * Calculate Grand Total
     */
    public static function getTotal(): float
    {
        return round(self::getSubtotal() + self::getGstTax(), 2);
    }

    /**
     * Reorder past order items into cart
     */
    public static function reorderFromOrder($orderId): int
    {
        $order = Order::with('items.product')->find($orderId);
        if (!$order) {
            return 0;
        }

        $addedCount = 0;
        foreach ($order->items as $item) {
            if ($item->product_id) {
                self::add($item->product_id, $item->quantity ?? 1);
                $addedCount++;
            }
        }

        return $addedCount;
    }
}
