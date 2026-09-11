<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Vendor;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['vendor', 'category', 'brand']);

        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $query->where('approval_status', 'pending');
            } elseif ($request->status === 'approved') {
                $query->where('approval_status', 'approved');
            } elseif ($request->status === 'rejected') {
                $query->where('approval_status', 'rejected');
            }
        }

        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhereHas('vendor', function ($vq) use ($search) {
                      $vq->where('company_name', 'like', "%{$search}%");
                  });
            });
        if ($request->filled('featured')) {
            $query->where('is_featured', (bool) $request->featured);
        }

        if ($request->filled('sponsored')) {
            $query->where('is_sponsored', (bool) $request->sponsored);
        }

        if ($request->filled('deal_of_day')) {
            $query->where('is_deal_of_the_day', (bool) $request->deal_of_day);
        }

        $products = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => Product::count(),
            'pending' => Product::where('approval_status', 'pending')->count(),
            'approved' => Product::where('approval_status', 'approved')->count(),
            'rejected' => Product::where('approval_status', 'rejected')->count(),
            'deals_count' => Product::where('is_deal_of_the_day', true)->count(),
            'sponsored_count' => Product::where('is_sponsored', true)->count(),
            'featured_count' => Product::where('is_featured', true)->count(),
        ];

        $categories = Category::whereNull('parent_id')->get();
        $vendors = Vendor::where('status', 'active')->get();

        return view('admin.products.index', compact('products', 'stats', 'categories', 'vendors'));
    }

    public function show($id)
    {
        $product = Product::with(['vendor', 'category', 'brand', 'images', 'specifications', 'bulkPrices'])->findOrFail($id);

        return view('admin.products.show', compact('product'));
    }

    public function approve($id)
    {
        $product = Product::findOrFail($id);
        $product->update([
            'approval_status' => 'approved',
            'status' => 'active',
            'approved_at' => now(),
            'approved_by' => auth('admin')->id(),
            'rejection_reason' => null,
            'published_at' => now(),
        ]);

        return back()->with('success', "Product '{$product->name}' has been approved and published to storefront!");
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $product = Product::findOrFail($id);
        $product->update([
            'approval_status' => 'rejected',
            'status' => 'inactive',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('warning', "Product '{$product->name}' has been rejected. Reason recorded.");
    }

    public function toggleFeatured($id)
    {
        $product = Product::findOrFail($id);
        $product->update(['is_featured' => !$product->is_featured]);

        $status = $product->is_featured ? 'marked as Featured' : 'removed from Featured';
        return back()->with('success', "Product '{$product->name}' is now {$status}.");
    }

    public function toggleSponsored($id)
    {
        $product = Product::findOrFail($id);
        $product->update(['is_sponsored' => !$product->is_sponsored]);

        $status = $product->is_sponsored ? 'marked as Sponsored Ad' : 'removed from Sponsored';
        return back()->with('success', "Product '{$product->name}' is now {$status}.");
    }

    public function toggleDealOfDay($id)
    {
        $product = Product::findOrFail($id);
        $product->update(['is_deal_of_the_day' => !$product->is_deal_of_the_day]);

        $status = $product->is_deal_of_the_day ? 'marked as Deal of the Day' : 'removed from Deals of the Day';
        return back()->with('success', "Product '{$product->name}' is now {$status}.");
    }

    public function toggleStatus($id)
    {
        $product = Product::findOrFail($id);
        $newStatus = $product->status === 'active' ? 'inactive' : 'active';
        $product->update(['status' => $newStatus]);

        return back()->with('success', "Product status changed to {$newStatus}.");
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', "Product deleted successfully.");
    }
}
