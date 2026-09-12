<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\BrandRegistration;
use App\Models\BrandDealerAuthorization;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VendorBrandRegistryController extends Controller
{
    /**
     * Brand Registry Dashboard for Brand Owners & Applicants
     */
    public function index()
    {
        $vendor = Auth::guard('vendor')->user();

        $ownedBrands = Brand::where('owner_vendor_id', $vendor->id)
                            ->withCount(['products', 'verifiedDealerAuthorizations'])
                            ->get();

        $applications = BrandRegistration::where('vendor_id', $vendor->id)
                                         ->with(['createdBrand'])
                                         ->latest()
                                         ->get();

        $stats = [
            'total_owned'   => $ownedBrands->count(),
            'pending_apps'  => $applications->where('status', 'pending')->count(),
            'approved_apps' => $applications->where('status', 'approved')->count(),
            'total_dealers' => BrandDealerAuthorization::whereIn('brand_id', $ownedBrands->pluck('id'))->where('garudo_approval_status', 'verified')->count(),
        ];

        return view('vendor.brand_registry.index', compact('vendor', 'ownedBrands', 'applications', 'stats'));
    }

    /**
     * Show Form to Register a New Brand
     */
    public function create()
    {
        $vendor = Auth::guard('vendor')->user();
        $categories = Category::whereNull('parent_id')->with('children')->orderBy('name')->get();

        return view('vendor.brand_registry.create', compact('vendor', 'categories'));
    }

    /**
     * Store Brand Registration Application
     */
    public function store(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();

        $request->validate([
            'brand_name'                => 'required|string|max:200',
            'brand_type'                => 'required|string|in:oem_manufacturer,trademark_owner,authorized_importer,private_label',
            'category_ids'              => 'nullable|array',
            'category_ids.*'            => 'exists:categories,id',
            'logo'                      => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:3072',
            'banner'                    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'brand_story'               => 'nullable|string|max:3000',
            'official_website'          => 'nullable|url|max:300',
            'official_email'            => 'nullable|email|max:150',
            'official_phone'            => 'nullable|string|max:50',
            'year_established'          => 'nullable|integer|min:1800|max:' . date('Y'),
            'country_of_origin'         => 'nullable|string|max:100',
            'trademark_number'          => 'required|string|max:100',
            'trademark_class'           => 'required|string|max:50',
            'trademark_status'          => 'required|string|in:registered,applied_pending,licensed',
            'trademark_doc'             => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'company_incorporation_doc' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'power_of_attorney_doc'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'warranty_policy'           => 'nullable|string|max:2000',
            'is_exclusive_distribution' => 'nullable|boolean',
        ]);

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
            $tmDocPath = $request->file('trademark_doc')->store('brands/legal', 'public');
        }

        $incDocPath = null;
        if ($request->hasFile('company_incorporation_doc')) {
            $incDocPath = $request->file('company_incorporation_doc')->store('brands/legal', 'public');
        }

        $poaDocPath = null;
        if ($request->hasFile('power_of_attorney_doc')) {
            $poaDocPath = $request->file('power_of_attorney_doc')->store('brands/legal', 'public');
        }

        $baseSlug = Str::slug($request->input('brand_name'));

        $application = BrandRegistration::create([
            'vendor_id'                     => $vendor->id,
            'brand_name'                    => $request->input('brand_name'),
            'brand_slug'                    => $baseSlug,
            'brand_type'                    => $request->input('brand_type'),
            'category_ids'                  => $request->input('category_ids', []),
            'logo_path'                     => $logoPath,
            'banner_path'                   => $bannerPath,
            'brand_story'                   => $request->input('brand_story'),
            'official_website'              => $request->input('official_website'),
            'official_email'                => $request->input('official_email', $vendor->email),
            'official_phone'                => $request->input('official_phone', $vendor->phone),
            'year_established'              => $request->input('year_established'),
            'country_of_origin'             => $request->input('country_of_origin', 'India'),
            'trademark_number'              => $request->input('trademark_number'),
            'trademark_class'               => $request->input('trademark_class'),
            'trademark_status'              => $request->input('trademark_status'),
            'trademark_doc_path'            => $tmDocPath,
            'company_incorporation_doc_path'=> $incDocPath,
            'power_of_attorney_doc_path'    => $poaDocPath,
            'warranty_policy'               => $request->input('warranty_policy'),
            'is_exclusive_distribution'     => $request->boolean('is_exclusive_distribution', true),
            'status'                        => 'pending',
        ]);

        return redirect()->route('vendor.brand-registry.show', $application->id)
                         ->with('success', "Brand registration for '{$application->brand_name}' submitted successfully! Garudo Compliance Team will review your trademark documents.");
    }

    /**
     * Show Single Brand Application Details & Status Tracker
     */
    public function show($id)
    {
        $vendor = Auth::guard('vendor')->user();
        $application = BrandRegistration::where('vendor_id', $vendor->id)->with('createdBrand')->findOrFail($id);

        $selectedCategories = [];
        if (!empty($application->category_ids)) {
            $selectedCategories = Category::whereIn('id', (array) $application->category_ids)->get();
        }

        return view('vendor.brand_registry.show', compact('vendor', 'application', 'selectedCategories'));
    }

    /**
     * Brand Owner Dealer Management Workbench
     */
    public function dealers($brandId)
    {
        $vendor = Auth::guard('vendor')->user();
        $brand = Brand::where('owner_vendor_id', $vendor->id)->findOrFail($brandId);

        $dealers = BrandDealerAuthorization::where('brand_id', $brand->id)
                                           ->with('vendor')
                                           ->latest()
                                           ->paginate(15);

        return view('vendor.brand_registry.dealers', compact('vendor', 'brand', 'dealers'));
    }

    /**
     * Brand Owner Approves Dealer Authorization
     */
    public function approveDealer(Request $request, $brandId, $authId)
    {
        $vendor = Auth::guard('vendor')->user();
        $brand = Brand::where('owner_vendor_id', $vendor->id)->findOrFail($brandId);
        $authRecord = BrandDealerAuthorization::where('brand_id', $brand->id)->findOrFail($authId);

        $authRecord->brand_owner_approval   = 'approved';
        $authRecord->brand_owner_approved_at = now();
        $authRecord->save();

        return back()->with('success', "Dealer {$authRecord->vendor->company_name} approved! Final Garudo accreditation in progress.");
    }

    /**
     * Brand Owner Rejects Dealer Authorization
     */
    public function rejectDealer(Request $request, $brandId, $authId)
    {
        $vendor = Auth::guard('vendor')->user();
        $brand = Brand::where('owner_vendor_id', $vendor->id)->findOrFail($brandId);
        $authRecord = BrandDealerAuthorization::where('brand_id', $brand->id)->findOrFail($authId);

        $authRecord->brand_owner_approval = 'rejected';
        $authRecord->garudo_approval_status = 'rejected';
        $authRecord->rejection_reason = 'Rejected by Brand Owner: ' . $request->input('reason', 'Authorization revoked by OEM');
        $authRecord->save();

        return back()->with('warning', "Dealer authorization request rejected.");
    }
}
