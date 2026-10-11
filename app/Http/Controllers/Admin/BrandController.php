<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\BrandTitle;
use App\Models\BrandRegistration;
use App\Models\BrandDealerAuthorization;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BrandController extends Controller
{
    /**
     * Display a listing of all Brands with hierarchy filters (Super Brand vs Sub Brand)
     */
    public function index(Request $request)
    {
        $statusFilter     = $request->query('status', 'all');
        $tierFilter       = $request->query('tier', 'all');
        $titleFilter      = $request->query('title', 'all');
        $protectionFilter = $request->query('protection', 'all');
        $search           = trim($request->query('search', ''));

        $query = Brand::with(['superBrand', 'brandTitle'])
                      ->withCount(['subBrands', 'products', 'verifiedDealerAuthorizations'])
                      ->latest();

        // Custom Brand Title Filter
        if ($titleFilter !== 'all') {
            $query->where('brand_title_id', $titleFilter);
        }

        // Tier Filter
        if ($tierFilter === 'super_brand') {
            $query->whereNull('parent_id');
        } elseif ($tierFilter === 'sub_brand') {
            $query->whereNotNull('parent_id');
        }

        // Status Filter
        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        // Protection Filter
        if ($protectionFilter === 'protected') {
            $query->where('is_protected', true);
        } elseif ($protectionFilter === 'open') {
            $query->where('is_protected', false);
        }

        // Search Filter
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('country_of_origin', 'like', "%{$search}%")
                  ->orWhere('trademark_number', 'like', "%{$search}%")
                  ->orWhereHas('superBrand', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $brands = $query->paginate(15)->withQueryString();

        $stats = [
            'total'         => Brand::count(),
            'super_brands'  => Brand::whereNull('parent_id')->count(),
            'sub_brands'    => Brand::whereNotNull('parent_id')->count(),
            'protected'     => Brand::where('is_protected', true)->count(),
            'active'        => Brand::where('status', 'active')->count(),
        ];

        $brandTitles = BrandTitle::active()->get();

        return view('admin.brands.index', compact('brands', 'stats', 'brandTitles', 'statusFilter', 'tierFilter', 'titleFilter', 'protectionFilter', 'search'));
    }

    /**
     * Show form to create a new Brand (Super Brand or Sub Brand)
     */
    public function create()
    {
        // Fetch all active Super Brands (where parent_id is null) for the parent selector
        $superBrands = Brand::whereNull('parent_id')->where('status', 'active')->orderBy('name')->get();
        $brandTitles = BrandTitle::active()->get();

        return view('admin.brands.create', compact('superBrands', 'brandTitles'));
    }

    /**
     * Store a newly created Brand in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:200',
            'brand_tier'        => 'required|in:super_brand,sub_brand',
            'brand_title_id'    => 'nullable|exists:brand_titles,id',
            'parent_id'         => 'nullable|required_if:brand_tier,sub_brand|exists:brands,id',
            'brand_type'        => 'required|string|in:oem_manufacturer,trademark_owner,authorized_importer,private_label',
            'logo'              => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
            'banner'            => 'nullable|image|mimes:jpeg,png,jpg,webp|max:6144',
            'description'       => 'nullable|string|max:5000',
            'story'             => 'nullable|string|max:5000',
            'warranty_policy'   => 'nullable|string|max:3000',
            'website'           => 'nullable|url|max:300',
            'support_email'     => 'nullable|email|max:150',
            'support_phone'     => 'nullable|string|max:50',
            'country_of_origin' => 'nullable|string|max:100',
            'year_established'  => 'nullable|integer|min:1800|max:' . date('Y'),
            'trademark_number'  => 'nullable|string|max:100',
            'trademark_class'   => 'nullable|string|max:50',
            'trademark_status'  => 'nullable|string|in:registered,applied_pending,licensed',
            'trademark_doc'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'is_protected'      => 'nullable|boolean',
            'is_featured'       => 'nullable|boolean',
            'status'            => 'required|in:active,inactive',
            'sort_order'        => 'nullable|integer|min:0',
            'meta_title'        => 'nullable|string|max:300',
            'meta_description'  => 'nullable|string|max:1000',
        ]);

        // Generate Unique Slug
        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $counter = 1;
        while (Brand::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        // File Uploads
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('brands/logos', 'public');
        }

        $bannerPath = null;
        if ($request->hasFile('banner')) {
            $bannerPath = $request->file('banner')->store('brands/banners', 'public');
        }

        $tmDocPath = null;
        if ($request->hasFile('trademark_doc')) {
            $tmDocPath = $request->file('trademark_doc')->store('brands/documents', 'public');
        }

        $parentId = ($validated['brand_tier'] === 'sub_brand') ? $validated['parent_id'] : null;

        $brand = Brand::create([
            'name'                => $validated['name'],
            'slug'                => $slug,
            'parent_id'           => $parentId,
            'brand_title_id'      => $validated['brand_title_id'] ?? null,
            'brand_tier'          => $validated['brand_tier'],
            'brand_type'          => $validated['brand_type'],
            'logo'                => $logoPath,
            'banner'              => $bannerPath,
            'description'         => $validated['description'] ?? null,
            'story'               => $validated['story'] ?? null,
            'warranty_policy'     => $validated['warranty_policy'] ?? null,
            'website'             => $validated['website'] ?? null,
            'support_email'       => $validated['support_email'] ?? null,
            'support_phone'       => $validated['support_phone'] ?? null,
            'country_of_origin'   => $validated['country_of_origin'] ?? 'India',
            'year_established'    => $validated['year_established'] ?? null,
            'trademark_number'    => $validated['trademark_number'] ?? null,
            'trademark_class'     => $validated['trademark_class'] ?? null,
            'trademark_status'    => $validated['trademark_status'] ?? 'registered',
            'trademark_doc'       => $tmDocPath,
            'verification_status' => 'authorized', // Created directly by Super Admin
            'is_protected'        => $request->boolean('is_protected', true),
            'is_featured'         => $request->boolean('is_featured', false),
            'status'              => $validated['status'],
            'sort_order'          => $validated['sort_order'] ?? 0,
            'verified_at'         => now(),
            'verified_by'         => Auth::guard('admin')->id(),
            'meta_title'          => $validated['meta_title'] ?? null,
            'meta_description'    => $validated['meta_description'] ?? null,
        ]);

        $tierLabel = $brand->isSuperBrand() ? 'Super Brand' : 'Sub Brand';
        return redirect()->route('admin.brands.index')->with('success', "{$tierLabel} '{$brand->name}' created successfully!");
    }

    /**
     * Show form to edit an existing Brand
     */
    public function edit($id)
    {
        $brand = Brand::with(['superBrand', 'subBrands', 'brandTitle'])->findOrFail($id);

        // Fetch all other active Super Brands (exclude self and its own children)
        $superBrands = Brand::whereNull('parent_id')
                            ->where('id', '!=', $brand->id)
                            ->where('status', 'active')
                            ->orderBy('name')
                            ->get();

        $brandTitles = BrandTitle::active()->get();

        return view('admin.brands.edit', compact('brand', 'superBrands', 'brandTitles'));
    }

    /**
     * Update an existing Brand
     */
    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $validated = $request->validate([
            'name'              => 'required|string|max:200',
            'slug'              => 'required|string|max:250|unique:brands,slug,' . $brand->id,
            'brand_tier'        => 'required|in:super_brand,sub_brand',
            'brand_title_id'    => 'nullable|exists:brand_titles,id',
            'parent_id'         => 'nullable|required_if:brand_tier,sub_brand|exists:brands,id|not_in:' . $brand->id,
            'brand_type'        => 'required|string|in:oem_manufacturer,trademark_owner,authorized_importer,private_label',
            'logo'              => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
            'banner'            => 'nullable|image|mimes:jpeg,png,jpg,webp|max:6144',
            'description'       => 'nullable|string|max:5000',
            'story'             => 'nullable|string|max:5000',
            'warranty_policy'   => 'nullable|string|max:3000',
            'website'           => 'nullable|url|max:300',
            'support_email'     => 'nullable|email|max:150',
            'support_phone'     => 'nullable|string|max:50',
            'country_of_origin' => 'nullable|string|max:100',
            'year_established'  => 'nullable|integer|min:1800|max:' . date('Y'),
            'trademark_number'  => 'nullable|string|max:100',
            'trademark_class'   => 'nullable|string|max:50',
            'trademark_status'  => 'nullable|string|in:registered,applied_pending,licensed',
            'trademark_doc'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'is_protected'      => 'nullable|boolean',
            'is_featured'       => 'nullable|boolean',
            'status'            => 'required|in:active,inactive',
            'sort_order'        => 'nullable|integer|min:0',
            'meta_title'        => 'nullable|string|max:300',
            'meta_description'  => 'nullable|string|max:1000',
        ]);

        // Handle File Uploads
        $logoPath = $brand->logo;
        if ($request->hasFile('logo')) {
            if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
                Storage::disk('public')->delete($brand->logo);
            }
            $logoPath = $request->file('logo')->store('brands/logos', 'public');
        }

        $bannerPath = $brand->banner;
        if ($request->hasFile('banner')) {
            if ($brand->banner && Storage::disk('public')->exists($brand->banner)) {
                Storage::disk('public')->delete($brand->banner);
            }
            $bannerPath = $request->file('banner')->store('brands/banners', 'public');
        }

        $tmDocPath = $brand->trademark_doc;
        if ($request->hasFile('trademark_doc')) {
            if ($brand->trademark_doc && Storage::disk('public')->exists($brand->trademark_doc)) {
                Storage::disk('public')->delete($brand->trademark_doc);
            }
            $tmDocPath = $request->file('trademark_doc')->store('brands/documents', 'public');
        }

        $parentId = ($validated['brand_tier'] === 'sub_brand') ? $validated['parent_id'] : null;

        $brand->update([
            'name'              => $validated['name'],
            'slug'              => Str::slug($validated['slug']),
            'parent_id'         => $parentId,
            'brand_title_id'    => $validated['brand_title_id'] ?? null,
            'brand_tier'        => $validated['brand_tier'],
            'brand_type'        => $validated['brand_type'],
            'logo'              => $logoPath,
            'banner'            => $bannerPath,
            'description'       => $validated['description'] ?? null,
            'story'             => $validated['story'] ?? null,
            'warranty_policy'   => $validated['warranty_policy'] ?? null,
            'website'             => $validated['website'] ?? null,
            'support_email'       => $validated['support_email'] ?? null,
            'support_phone'       => $validated['support_phone'] ?? null,
            'country_of_origin'   => $validated['country_of_origin'] ?? 'India',
            'year_established'    => $validated['year_established'] ?? null,
            'trademark_number'    => $validated['trademark_number'] ?? null,
            'trademark_class'     => $validated['trademark_class'] ?? null,
            'trademark_status'    => $validated['trademark_status'] ?? 'registered',
            'trademark_doc'       => $tmDocPath,
            'is_protected'        => $request->boolean('is_protected', false),
            'is_featured'         => $request->boolean('is_featured', false),
            'status'              => $validated['status'],
            'sort_order'          => $validated['sort_order'] ?? 0,
            'meta_title'          => $validated['meta_title'] ?? null,
            'meta_description'    => $validated['meta_description'] ?? null,
        ]);

        return redirect()->route('admin.brands.index')->with('success', "Brand '{$brand->name}' updated successfully!");
    }

    /**
     * Delete a brand safely
     */
    public function destroy($id)
    {
        $brand = Brand::withCount(['products', 'subBrands'])->findOrFail($id);

        if ($brand->products_count > 0) {
            return back()->with('error', "Cannot delete brand '{$brand->name}' because it has {$brand->products_count} active products attached. Please reassign or delete the products first.");
        }

        if ($brand->sub_brands_count > 0) {
            return back()->with('error', "Cannot delete Super Brand '{$brand->name}' because it has {$brand->sub_brands_count} Sub Brands attached. Please reassign or delete the sub-brands first.");
        }

        $brand->delete();

        return redirect()->route('admin.brands.index')->with('success', "Brand '{$brand->name}' has been deleted successfully.");
    }

    /**
     * Toggle Brand Status (Active / Inactive)
     */
    public function toggleStatus($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->status = ($brand->status === 'active') ? 'inactive' : 'active';
        $brand->save();

        return back()->with('success', "Brand '{$brand->name}' status changed to " . ucfirst($brand->status) . ".");
    }

    /**
     * Toggle Brand Protection (Protected vs Open)
     */
    public function toggleProtection($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->is_protected = !$brand->is_protected;
        $brand->save();

        $state = $brand->is_protected ? 'Enabled (Restricted to Authorized Dealers & Owners)' : 'Disabled (Open to all sellers)';
        return back()->with('success', "Trademark Protection for '{$brand->name}' is now {$state}.");
    }

    /**
     * Toggle Featured Brand status
     */
    public function toggleFeatured($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->is_featured = !$brand->is_featured;
        $brand->save();

        $state = $brand->is_featured ? 'marked as Featured' : 'removed from Featured';
        return back()->with('success', "Brand '{$brand->name}' is now {$state}.");
    }
}
