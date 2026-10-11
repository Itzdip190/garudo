<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CatalogRequest;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\Brand;
use App\Services\VendorNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CatalogRequestController extends Controller
{
    /**
     * Display listing of all Vendor Catalog Requests
     */
    public function index(Request $request)
    {
        $statusFilter = $request->query('status', 'all');
        $typeFilter   = $request->query('type', 'all');
        $search       = trim($request->query('search', ''));

        $query = CatalogRequest::with(['vendor', 'handler', 'parentCategory'])->latest();

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        if ($typeFilter !== 'all') {
            if ($typeFilter === 'category') {
                $query->whereIn('type', ['category', 'subcategory']);
            } else {
                $query->where('type', $typeFilter);
            }
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('values', 'like', "%{$search}%")
                  ->orWhere('reason', 'like', "%{$search}%")
                  ->orWhereHas('vendor', function ($vq) use ($search) {
                      $vq->where('company_name', 'like', "%{$search}%")
                         ->orWhere('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $requests = $query->paginate(15)->withQueryString();

        $stats = [
            'pending'        => CatalogRequest::where('status', 'pending')->count(),
            'approved'       => CatalogRequest::where('status', 'approved')->count(),
            'rejected'       => CatalogRequest::where('status', 'rejected')->count(),
            'total'          => CatalogRequest::count(),
            'specs_pending'  => CatalogRequest::where('type', 'attribute_spec')->where('status', 'pending')->count(),
            'cats_pending'   => CatalogRequest::whereIn('type', ['category', 'subcategory'])->where('status', 'pending')->count(),
            'brands_pending' => CatalogRequest::where('type', 'brand')->where('status', 'pending')->count(),
        ];

        return view('admin.catalog_requests.index', compact('requests', 'stats', 'statusFilter', 'typeFilter', 'search'));
    }

    /**
     * Approve a vendor catalog request and automatically create the Brand, Category, or Specification
     */
    public function approve(Request $request, $id)
    {
        $catalogRequest = CatalogRequest::findOrFail($id);

        if ($catalogRequest->status === 'approved') {
            return back()->with('info', 'This request is already approved.');
        }

        DB::beginTransaction();
        try {
            if ($catalogRequest->type === 'brand') {
                // 1. Create Brand in Live Catalog
                $baseSlug = Str::slug($catalogRequest->name);
                $slug = $baseSlug;
                $counter = 1;
                while (Brand::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter++;
                }

                $origin = 'India';
                $website = null;
                if ($catalogRequest->values) {
                    if (preg_match('/Website:\s*([^|]+)/i', $catalogRequest->values, $wm)) {
                        $website = trim($wm[1]);
                    }
                    if (preg_match('/Origin:\s*([^|]+)/i', $catalogRequest->values, $om)) {
                        $origin = trim($om[1]);
                    }
                }

                Brand::create([
                    'name'              => $catalogRequest->name,
                    'slug'              => $slug,
                    'country_of_origin' => $origin,
                    'website'           => $website,
                    'description'       => $catalogRequest->reason,
                    'owner_vendor_id'   => $catalogRequest->vendor_id,
                    'status'            => 'active',
                ]);

                // Notify Vendor
                VendorNotificationService::send(
                    $catalogRequest->vendor_id,
                    'catalog_brand_approved',
                    "Brand Approved: {$catalogRequest->name}",
                    "Your requested Brand '{$catalogRequest->name}' has been verified and added to the live marketplace catalog! You can now select it when listing products.",
                    route('vendor.products.create'),
                    'bi-patch-check-fill'
                );

            } elseif (in_array($catalogRequest->type, ['category', 'subcategory'])) {
                // 2. Create Category / Subcategory in Live Tree
                $baseSlug = Str::slug($catalogRequest->name);
                $slug = $baseSlug;
                $counter = 1;
                while (Category::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter++;
                }

                $parentCategory = $catalogRequest->parent_id ? Category::find($catalogRequest->parent_id) : null;

                $category = Category::create([
                    'name'         => $catalogRequest->name,
                    'slug'         => $slug,
                    'parent_id'    => $parentCategory ? $parentCategory->id : null,
                    'icon'         => $parentCategory ? 'bi-tag' : 'bi-tags',
                    'depth'        => $parentCategory ? 1 : 0,
                    'description'  => $catalogRequest->reason,
                    'status'       => 'active',
                    'show_in_menu' => true,
                    'show_in_home' => false,
                ]);

                // If subcategories were provided in values and this is a parent category
                if (!empty($catalogRequest->values) && !$parentCategory) {
                    $subNames = preg_split('/[,\r\n]+/', $catalogRequest->values);
                    foreach ($subNames as $sub) {
                        $subTrim = trim($sub);
                        if ($subTrim !== '') {
                            $subSlug = Str::slug($subTrim);
                            $sc = 1;
                            while (Category::where('slug', $subSlug)->exists()) {
                                $subSlug = Str::slug($subTrim) . '-' . $sc++;
                            }

                            Category::create([
                                'parent_id'    => $category->id,
                                'name'         => $subTrim,
                                'slug'         => $subSlug,
                                'depth'        => 1,
                                'status'       => 'active',
                                'show_in_menu' => true,
                            ]);
                        }
                    }
                }

                // Notify Vendor
                VendorNotificationService::send(
                    $catalogRequest->vendor_id,
                    'catalog_category_approved',
                    "Category Approved: {$catalogRequest->name}",
                    "Your requested Category '{$catalogRequest->name}' has been verified and added to the marketplace hierarchy.",
                    route('vendor.products.create'),
                    'bi-diagram-3-fill'
                );

            } elseif ($catalogRequest->type === 'attribute_spec') {
                // 3. Create Attribute Specification
                $baseName = Str::slug($catalogRequest->name, '_');
                $name = $baseName;
                $counter = 1;
                while (Attribute::where('name', $name)->exists()) {
                    $name = $baseName . '_' . $counter++;
                }

                $attribute = Attribute::create([
                    'name'          => $name,
                    'display_name'  => $catalogRequest->name,
                    'type'          => 'select',
                    'is_filterable' => true,
                    'is_required'   => false,
                    'sort_order'    => (Attribute::max('sort_order') ?? 0) + 1,
                    'status'        => 'active',
                ]);

                // Parse and create values
                $rawValues = preg_split('/[,\r\n]+/', $catalogRequest->values ?? '');
                $order = 0;
                foreach ($rawValues as $val) {
                    $trimmed = trim($val);
                    if ($trimmed !== '') {
                        AttributeValue::create([
                            'attribute_id'  => $attribute->id,
                            'value'         => $trimmed,
                            'display_value' => $trimmed,
                            'sort_order'    => $order++,
                            'status'        => 'active',
                        ]);
                    }
                }

                // Notify Vendor
                VendorNotificationService::send(
                    $catalogRequest->vendor_id,
                    'catalog_spec_approved',
                    "Specification Approved: {$catalogRequest->name}",
                    "Your requested Specification '{$catalogRequest->name}' has been added to the product attribute library.",
                    route('vendor.attributes'),
                    'bi-sliders'
                );
            }

            // Mark Request as Approved
            $catalogRequest->update([
                'status'      => 'approved',
                'handled_by'  => Auth::guard('admin')->id() ?? 1,
                'handled_at'  => now(),
                'admin_notes' => $request->admin_notes ?: 'Request verified and approved by Super Admin.',
            ]);

            DB::commit();
            return back()->with('success', "Vendor request '{$catalogRequest->name}' has been approved and added to active marketplace successfully!");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Approval failed: ' . $e->getMessage());
        }
    }

    /**
     * Reject a vendor catalog request with a reason/note
     */
    public function reject(Request $request, $id)
    {
        $catalogRequest = CatalogRequest::findOrFail($id);

        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $reasonNote = $request->admin_notes ?: 'Declined as this item is duplicate or outside standard catalog taxonomy.';

        $catalogRequest->update([
            'status'      => 'rejected',
            'handled_by'  => Auth::guard('admin')->id() ?? 1,
            'handled_at'  => now(),
            'admin_notes' => $reasonNote,
        ]);

        // Notify Vendor of Rejection
        VendorNotificationService::send(
            $catalogRequest->vendor_id,
            'catalog_request_rejected',
            "Catalog Request Declined: {$catalogRequest->name}",
            "Your request for '{$catalogRequest->name}' was declined: {$reasonNote}",
            route('vendor.categories'),
            'bi-x-circle-fill'
        );

        return back()->with('success', "Vendor request '{$catalogRequest->name}' marked as rejected.");
    }
}
