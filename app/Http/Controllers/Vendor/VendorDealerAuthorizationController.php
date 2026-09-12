<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\BrandDealerAuthorization;
use App\Models\BrandAuthorizationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class VendorDealerAuthorizationController extends Controller
{
    /**
     * List all Brand Dealership Authorizations for the logged-in vendor
     */
    public function index()
    {
        $vendor = Auth::guard('vendor')->user();

        $authorizations = BrandDealerAuthorization::where('vendor_id', $vendor->id)
                                                 ->with(['brand.ownerVendor'])
                                                 ->latest()
                                                 ->paginate(15);

        $stats = [
            'total'     => BrandDealerAuthorization::where('vendor_id', $vendor->id)->count(),
            'verified'  => BrandDealerAuthorization::where('vendor_id', $vendor->id)->where('garudo_approval_status', 'verified')->count(),
            'pending'   => BrandDealerAuthorization::where('vendor_id', $vendor->id)->where('garudo_approval_status', 'pending')->count(),
            'rejected'  => BrandDealerAuthorization::where('vendor_id', $vendor->id)->where('garudo_approval_status', 'rejected')->count(),
        ];

        return view('vendor.dealer_authorizations.index', compact('vendor', 'authorizations', 'stats'));
    }

    /**
     * Show Form to Apply for Brand Dealership / Distribution Authorization
     */
    public function apply()
    {
        $vendor = Auth::guard('vendor')->user();

        // Get all authorized active brands on Garudo
        $brands = Brand::where('status', 'active')
                       ->orderBy('name')
                       ->get();

        $indianStates = [
            "Andhra Pradesh", "Arunachal Pradesh", "Assam", "Bihar", "Chhattisgarh", "Goa", "Gujarat", 
            "Haryana", "Himachal Pradesh", "Jharkhand", "Karnataka", "Kerala", "Madhya Pradesh", 
            "Maharashtra", "Manipur", "Meghalaya", "Mizoram", "Nagaland", "Odisha", "Punjab", 
            "Rajasthan", "Sikkim", "Tamil Nadu", "Telangana", "Tripura", "Uttar Pradesh", 
            "Uttarakhand", "West Bengal", "Delhi NCR", "Jammu and Kashmir", "Ladakh"
        ];

        return view('vendor.dealer_authorizations.apply', compact('vendor', 'brands', 'indianStates'));
    }

    /**
     * Submit Brand Dealership Authorization Request
     */
    public function storeApplication(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();

        $request->validate([
            'brand_id'           => 'required|exists:brands,id',
            'authorization_type' => 'required|string|in:exclusive_distributor,authorized_distributor,authorized_dealer,tier2_stockist,franchise_partner',
            'territory_type'     => 'required|string|in:national,state,city,pincode_zone',
            'territory_states'   => 'nullable|array',
            'territory_cities'   => 'nullable|string',
            'territory_pincodes' => 'nullable|string',
            'authorization_doc'  => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'agreement_ref_no'   => 'nullable|string|max:100',
            'valid_from'         => 'nullable|date',
            'valid_until'        => 'nullable|date|after_or_equal:valid_from',
            'is_lifetime_valid'  => 'nullable|boolean',
        ]);

        // Check if authorization already exists for this brand & vendor
        $existing = BrandDealerAuthorization::where('brand_id', $request->input('brand_id'))
                                            ->where('vendor_id', $vendor->id)
                                            ->first();

        if ($existing && in_array($existing->garudo_approval_status, ['verified', 'pending'])) {
            return back()->withInput()->with('error', 'You already have an active or pending authorization request for this brand.');
        }

        $docPath = null;
        if ($request->hasFile('authorization_doc')) {
            $docPath = $request->file('authorization_doc')->store('dealerships/agreements', 'public');
        }

        $citiesArray = null;
        if ($request->filled('territory_cities')) {
            $citiesArray = array_map('trim', explode(',', $request->input('territory_cities')));
        }

        $pincodesArray = null;
        if ($request->filled('territory_pincodes')) {
            $pincodesArray = array_map('trim', explode(',', $request->input('territory_pincodes')));
        }

        $brand = Brand::findOrFail($request->input('brand_id'));
        $brandOwnerApproval = $brand->owner_vendor_id ? 'pending' : 'not_required';

        $authRecord = BrandDealerAuthorization::updateOrCreate(
            [
                'brand_id'  => $brand->id,
                'vendor_id' => $vendor->id,
            ],
            [
                'authorization_type'     => $request->input('authorization_type'),
                'territory_type'         => $request->input('territory_type'),
                'territory_states'       => $request->input('territory_states', []),
                'territory_cities'       => $citiesArray,
                'territory_pincodes'     => $pincodesArray,
                'authorization_doc_path' => $docPath,
                'agreement_ref_no'       => $request->input('agreement_ref_no'),
                'valid_from'             => $request->input('valid_from'),
                'valid_until'            => $request->input('valid_until'),
                'is_lifetime_valid'      => $request->boolean('is_lifetime_valid', false),
                'brand_owner_approval'   => $brandOwnerApproval,
                'garudo_approval_status' => 'pending',
                'status'                 => 'active',
                'rejection_reason'       => null,
            ]
        );

        // Record log
        BrandAuthorizationLog::create([
            'authorization_id' => $authRecord->id,
            'action'           => 'created',
            'actor_type'       => 'vendor',
            'actor_id'         => $vendor->id,
            'old_status'       => null,
            'new_status'       => 'pending',
            'notes'            => 'Dealer authorization application submitted by vendor.',
        ]);

        return redirect()->route('vendor.dealer-authorizations.index')
                         ->with('success', "Dealership authorization application for '{$brand->name}' submitted successfully! Our verification team is reviewing your documents.");
    }

    /**
     * Show & Print Official Garudo Authorized Dealer Digital Certificate
     */
    public function showCertificate($id)
    {
        $vendor = Auth::guard('vendor')->user();
        $authRecord = BrandDealerAuthorization::where('vendor_id', $vendor->id)
                                             ->with(['brand.ownerVendor', 'vendor', 'verifiedByAdmin'])
                                             ->findOrFail($id);

        if ($authRecord->garudo_approval_status !== 'verified') {
            return redirect()->route('vendor.dealer-authorizations.index')
                             ->with('error', 'Certificate is only available for verified authorizations.');
        }

        return view('vendor.dealer_authorizations.certificate', compact('vendor', 'authRecord'));
    }
}
