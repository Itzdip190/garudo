<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class WishlistService
{
    const SESSION_KEY = 'garudo_b2b_wishlist';

    /**
     * Get Wishlist product IDs
     */
    public static function getProductIds(): array
    {
        if (Auth::guard('customer')->check()) {
            return Wishlist::where('customer_id', Auth::guard('customer')->id())
                ->pluck('product_id')
                ->toArray();
        }

        return Session::get(self::SESSION_KEY, []);
    }

    /**
     * Get Wishlist Items
     */
    public static function getItems()
    {
        $ids = self::getProductIds();
        if (empty($ids)) {
            return collect();
        }

        return Product::whereIn('id', $ids)
            ->with(['primaryImage', 'vendor', 'brand', 'category'])
            ->get();
    }

    /**
     * Get Wishlist Count
     */
    public static function getCount(): int
    {
        return count(self::getProductIds());
    }

    /**
     * Check if product is in wishlist
     */
    public static function has($productId): bool
    {
        return in_array($productId, self::getProductIds());
    }

    /**
     * Toggle product in wishlist
     */
    public static function toggle($productId): array
    {
        $product = Product::find($productId);
        if (!$product) {
            return ['success' => false, 'message' => 'Product not found.'];
        }

        if (Auth::guard('customer')->check()) {
            $customerId = Auth::guard('customer')->id();
            $existing = Wishlist::where('customer_id', $customerId)
                ->where('product_id', $productId)
                ->first();

            if ($existing) {
                $existing->delete();
                return [
                    'success' => true,
                    'action'  => 'removed',
                    'message' => "Removed {$product->name} from Wishlist.",
                    'count'   => self::getCount()
                ];
            } else {
                Wishlist::create([
                    'customer_id' => $customerId,
                    'product_id'  => $productId,
                ]);
                return [
                    'success' => true,
                    'action'  => 'added',
                    'message' => "Saved {$product->name} to Wishlist.",
                    'count'   => self::getCount()
                ];
            }
        } else {
            $ids = Session::get(self::SESSION_KEY, []);
            if (in_array($productId, $ids)) {
                $ids = array_values(array_diff($ids, [$productId]));
                Session::put(self::SESSION_KEY, $ids);
                return [
                    'success' => true,
                    'action'  => 'removed',
                    'message' => "Removed from Wishlist.",
                    'count'   => count($ids)
                ];
            } else {
                $ids[] = (int) $productId;
                Session::put(self::SESSION_KEY, $ids);
                return [
                    'success' => true,
                    'action'  => 'added',
                    'message' => "Saved to Wishlist.",
                    'count'   => count($ids)
                ];
            }
        }
    }

    /**
     * Remove product from wishlist
     */
    public static function remove($productId): bool
    {
        if (Auth::guard('customer')->check()) {
            Wishlist::where('customer_id', Auth::guard('customer')->id())
                ->where('product_id', $productId)
                ->delete();
            return true;
        }

        $ids = Session::get(self::SESSION_KEY, []);
        $ids = array_values(array_diff($ids, [$productId]));
        Session::put(self::SESSION_KEY, $ids);
        return true;
    }
}
