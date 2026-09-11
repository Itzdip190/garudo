<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CatalogRequest;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Category;
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
            $query->where('type', $typeFilter);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('values', 'like', "%{$search}%")
                  ->orWhere('reason', 'like', "%{$search}%")
                  ->orWhereHas('vendor', function ($vq) use ($search) {
                      $vq->where('business_name', 'like', "%{$search}%")
                         ->orWhere('contact_person', 'like', "%{$search}%");
                  });
            });
        }

        $requests = $query->paginate(15)->withQueryString();

        $stats = [
            'pending'   => CatalogRequest::where('status', 'pending')->count(),
            'approved'  => CatalogRequest::where('status', 'approved')->count(),
            'rejected'  => CatalogRequest::where('status', 'rejected')->count(),
            'total'     => CatalogRequest::count(),
            'specs_pending' => CatalogRequest::where('type', 'attribute_spec')->where('status', 'pending')->count(),
            'cats_pending'  => CatalogRequest::where('type', 'category')->where('status', 'pending')->count(),
        ];

        return view('admin.catalog_requests.index', compact('requests', 'stats', 'statusFilter', 'typeFilter', 'search'));
    }

    /**
     * Approve a vendor catalog request and automatically create the specification or category
     */
    public function approve(Request $request, $id)
    {
        $catalogRequest = CatalogRequest::findOrFail($id);

        if ($catalogRequest->status === 'approved') {
            return back()->with('info', 'This request is already approved.');
        }

        DB::beginTransaction();
        try {
            if ($catalogRequest->type === 'attribute_spec') {
                // Check if attribute already exists or create new
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
            } elseif ($catalogRequest->type === 'category') {
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
                    'icon'         => 'bi-tags',
                    'depth'        => $parentCategory ? 1 : 0,
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
            }

            // Mark Request as Approved
            $catalogRequest->update([
                'status'      => 'approved',
                'handled_by'  => Auth::guard('admin')->id() ?? 1,
                'handled_at'  => now(),
                'admin_notes' => $request->admin_notes ?: 'Request verified and automatically added to marketplace catalog.',
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

        $catalogRequest->update([
            'status'      => 'rejected',
            'handled_by'  => Auth::guard('admin')->id() ?? 1,
            'handled_at'  => now(),
            'admin_notes' => $request->admin_notes ?: 'Declined as this item is duplicate or outside standard guidelines.',
        ]);

        return back()->with('success', "Vendor request '{$catalogRequest->name}' marked as rejected.");
    }
}
