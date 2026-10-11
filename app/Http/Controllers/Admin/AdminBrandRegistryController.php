<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\BrandRegistration;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminBrandRegistryController extends Controller
{
    /**
     * Listing of all Brand Registration Applications
     */
    public function index(Request $request)
    {
        $statusFilter = $request->query('status', 'all');
        $search       = trim($request->query('search', ''));

        $query = BrandRegistration::with(['vendor', 'reviewer', 'createdBrand'])->latest();

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('brand_name', 'like', "%{$search}%")
                  ->orWhere('trademark_number', 'like', "%{$search}%")
                  ->orWhere('official_email', 'like', "%{$search}%")
                  ->orWhereHas('vendor', function ($vq) use ($search) {
                      $vq->where('company_name', 'like', "%{$search}%")
                         ->orWhere('name', 'like', "%{$search}%")
                         ->orWhere('gstin', 'like', "%{$search}%");
                  });
            });
        }

        $applications = $query->paginate(15)->withQueryString();

        $stats = [
            'pending'      => BrandRegistration::where('status', 'pending')->count(),
            'under_review' => BrandRegistration::where('status', 'under_review')->count(),
            'approved'     => BrandRegistration::where('status', 'approved')->count(),
            'rejected'     => BrandRegistration::where('status', 'rejected')->count(),
            'total'        => BrandRegistration::count(),
            'active_brands'=> Brand::where('verification_status', 'authorized')->count(),
        ];

        return view('admin.brands.registry.index', compact('applications', 'stats', 'statusFilter', 'search'));
    }

    /**
     * Show detailed Brand Application for legal review & verification
     */
    public function show($id)
    {
        $application = BrandRegistration::with(['vendor.kycDocuments', 'reviewer', 'createdBrand'])->findOrFail($id);

        $selectedCategories = [];
        if (!empty($application->category_ids)) {
            $selectedCategories = Category::whereIn('id', (array) $application->category_ids)->get();
        }

        return view('admin.brands.registry.show', compact('application', 'selectedCategories'));
    }

    /**
     * Authorize and Approve Brand Registration
     */
    public function approve(Request $request, $id)
    {
        $application = BrandRegistration::with('vendor')->findOrFail($id);
        $adminId = Auth::guard('admin')->id();

        $request->validate([
            'admin_notes'   => 'nullable|string|max:1000',
            'is_protected'  => 'nullable|boolean',
            'is_featured'   => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            // Generate unique slug for the brand
            $baseSlug = Str::slug($application->brand_name);
            $slug = $baseSlug;
            $count = 1;
            while (Brand::where('slug', $slug)->where('id', '!=', $application->created_brand_id)->exists()) {
                $slug = $baseSlug . '-' . $count++;
            }

            // Find existing brand or create new
            $brand = null;
            if ($application->created_brand_id) {
                $brand = Brand::find($application->created_brand_id);
            }

            if (!$brand) {
                $brand = Brand::where('slug', $slug)->first();
            }

            if (!$brand) {
                $brand = new Brand();
            }

            $brand->name                = $application->brand_name;
            $brand->slug                = $slug;
            $brand->parent_id           = $application->parent_brand_id ?? $brand->parent_id;
            $brand->brand_tier          = $application->brand_tier ?? ($brand->parent_id ? 'sub_brand' : 'super_brand');
            $brand->owner_vendor_id     = $application->vendor_id;
            $brand->brand_type          = $application->brand_type ?? 'oem_manufacturer';
            $brand->logo                = $application->logo_path ?? $brand->logo;
            $brand->banner              = $application->banner_path ?? $brand->banner;
            $brand->description         = $application->brand_story ?? $brand->description;
            $brand->story               = $application->brand_story;
            $brand->website             = $application->official_website;
            $brand->support_email       = $application->official_email;
            $brand->support_phone       = $application->official_phone;
            $brand->country_of_origin   = $application->country_of_origin ?? 'India';
            $brand->year_established    = $application->year_established;
            $brand->trademark_number    = $application->trademark_number;
            $brand->trademark_class     = $application->trademark_class;
            $brand->trademark_status    = $application->trademark_status;
            $brand->trademark_doc       = $application->trademark_doc_path;
            $brand->warranty_policy     = $application->warranty_policy;
            $brand->verification_status = 'authorized';
            $brand->is_protected        = $request->boolean('is_protected', $application->is_exclusive_distribution);
            $brand->is_featured         = $request->boolean('is_featured', false);
            $brand->verified_at         = now();
            $brand->verified_by         = $adminId;
            $brand->status              = 'active';
            $brand->admin_notes         = $request->input('admin_notes');
            $brand->save();

            // Update registration application
            $application->status           = 'approved';
            $application->admin_notes      = $request->input('admin_notes');
            $application->reviewed_by      = $adminId;
            $application->reviewed_at      = now();
            $application->created_brand_id = $brand->id;
            $application->save();

            DB::commit();

            return redirect()->route('admin.brands.registry.index')
                             ->with('success', "Brand '{$brand->name}' has been officially verified & authorized with Verified OEM Badge!");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to approve brand registration: ' . $e->getMessage());
        }
    }

    /**
     * Reject Brand Registration with Reason
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
            'admin_notes'      => 'nullable|string|max:1000',
        ]);

        $application = BrandRegistration::findOrFail($id);
        $adminId = Auth::guard('admin')->id();

        $application->status           = 'rejected';
        $application->rejection_reason = $request->input('rejection_reason');
        $application->admin_notes      = $request->input('admin_notes');
        $application->reviewed_by      = $adminId;
        $application->reviewed_at      = now();
        $application->save();

        return redirect()->route('admin.brands.registry.index')
                         ->with('warning', "Brand registration application for '{$application->brand_name}' was rejected.");
    }

    /**
     * Toggle Brand Protected / Gated Catalog Setting
     */
    public function toggleProtection($brandId)
    {
        $brand = Brand::findOrFail($brandId);
        $brand->is_protected = !$brand->is_protected;
        $brand->save();

        $statusText = $brand->is_protected ? 'Protected (Gated to Authorized Dealers)' : 'Open (Any Vendor Can List)';
        return back()->with('success', "Brand '{$brand->name}' catalog policy updated: {$statusText}");
    }
}
