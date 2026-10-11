<?php

namespace App\Http\Controllers\Auth\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\Brand;
use App\Models\BrandRegistration;
use App\Models\BrandDealerAuthorization;
use App\Models\SubscriptionPlan;
use App\Models\Subscription;
use App\Services\AdminNotificationService;
use App\Services\VendorNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /**
     * Show registration form with options for Vendor or Brand Owner
     */
    public function showRegisterForm()
    {
        $brands = Brand::where('status', 'active')->orderBy('name')->get();
        $superBrands = Brand::whereNull('parent_id')->where('status', 'active')->orderBy('name')->get();

        $indianStates = [
            "Andhra Pradesh", "Arunachal Pradesh", "Assam", "Bihar", "Chhattisgarh", "Goa", "Gujarat", 
            "Haryana", "Himachal Pradesh", "Jharkhand", "Karnataka", "Kerala", "Madhya Pradesh", 
            "Maharashtra", "Manipur", "Meghalaya", "Mizoram", "Nagaland", "Odisha", "Punjab", 
            "Rajasthan", "Sikkim", "Tamil Nadu", "Telangana", "Tripura", "Uttar Pradesh", 
            "Uttarakhand", "West Bengal", "Delhi NCR"
        ];

        return view('vendor.auth.register', compact('brands', 'superBrands', 'indianStates'));
    }

    /**
     * Handle registration for both Standard Vendor and Brand Owner
     */
    public function register(Request $request)
    {
        $regType = $request->input('registration_type', 'standard_vendor');

        if ($regType === 'brand_owner') {
            return $this->registerBrandOwner($request);
        }

        return $this->registerStandardVendor($request);
    }

    /**
     * Pathway A: Standard Vendor / Dealer / Trader Registration
     */
    protected function registerStandardVendor(Request $request)
    {
        $validated = $request->validate([
            'name'                      => ['required', 'string', 'max:200'],
            'email'                     => ['required', 'string', 'email', 'max:200', 'unique:vendors'],
            'phone'                     => ['required', 'string', 'max:15'],
            'company_name'              => ['required', 'string', 'max:300'],
            'business_type'             => ['required', 'in:wholesaler,trader,retailer,distributor,manufacturer'],
            'password'                  => ['required', 'string', 'min:8', 'confirmed'],
            // Optional Dealership Verification fields
            'sells_existing_brand'      => ['nullable', 'boolean'],
            'dealership_brand_id'       => ['nullable', 'required_if:sells_existing_brand,1', 'exists:brands,id'],
            'is_authorized_dealer'      => ['nullable', 'boolean'],
            'agreement_ref_no'          => ['nullable', 'string', 'max:100'],
            'territory_type'            => ['nullable', 'string', 'in:national,state,city,district'],
            'territory_state'           => ['nullable', 'string', 'max:100'],
            'valid_until'               => ['nullable', 'date'],
            'authorization_doc'         => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ]);

        DB::beginTransaction();
        try {
            $vendor = Vendor::create([
                'name'            => $validated['name'],
                'email'           => $validated['email'],
                'phone'           => $validated['phone'],
                'company_name'    => $validated['company_name'],
                'company_slug'    => Str::slug($validated['company_name']) . '-' . Str::random(5),
                'business_type'   => $validated['business_type'],
                'vendor_type'     => 'standard_vendor',
                'password'        => Hash::make($validated['password']),
                'approval_status' => 'pending',
                'kyc_status'      => 'pending',
                'status'          => 'active',
            ]);

            // Handle Authorized Dealership proof if submitted
            if ($request->boolean('sells_existing_brand') && !empty($validated['dealership_brand_id'])) {
                $authDocPath = null;
                if ($request->hasFile('authorization_doc')) {
                    $authDocPath = $request->file('authorization_doc')->store('dealer_authorizations/proofs', 'public');
                }

                $dealerAuth = BrandDealerAuthorization::create([
                    'brand_id'               => $validated['dealership_brand_id'],
                    'vendor_id'              => $vendor->id,
                    'authorization_type'     => 'authorized_dealer',
                    'territory_type'         => $validated['territory_type'] ?? 'state',
                    'territory_states'       => !empty($validated['territory_state']) ? [$validated['territory_state']] : null,
                    'authorization_doc_path' => $authDocPath,
                    'agreement_ref_no'       => $validated['agreement_ref_no'] ?? null,
                    'valid_from'             => now()->toDateString(),
                    'valid_until'            => $validated['valid_until'] ?? null,
                    'is_lifetime_valid'      => empty($validated['valid_until']),
                    'garudo_approval_status' => 'pending',
                    'status'                 => 'active',
                ]);

                $brandObj = Brand::find($validated['dealership_brand_id']);
                AdminNotificationService::send(
                    'dealer_auth',
                    'New Dealership Verification Request: ' . $vendor->company_name,
                    "Vendor '{$vendor->company_name}' submitted authorization proof to sell '{$brandObj?->name}'.",
                    route('admin.dealer-authorizations.show', $dealerAuth->id),
                    'bi-patch-check-fill text-warning'
                );
            }

            $this->assignDefaultPlanAndNotify($vendor);

            DB::commit();

            Auth::guard('vendor')->login($vendor);

            return redirect()->route('vendor.dashboard')->with('success', 'Vendor registration submitted successfully! Welcome to Garudo Marketplace.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Registration failed: ' . $e->getMessage());
        }
    }

    /**
     * Pathway B: Brand Owner / Manufacturer Registration (New Brand Application)
     */
    protected function registerBrandOwner(Request $request)
    {
        $validated = $request->validate([
            'name'                      => ['required', 'string', 'max:200'],
            'email'                     => ['required', 'string', 'email', 'max:200', 'unique:vendors'],
            'phone'                     => ['required', 'string', 'max:15'],
            'company_name'              => ['required', 'string', 'max:300'],
            'password'                  => ['required', 'string', 'min:8', 'confirmed'],
            // Brand Details
            'brand_name'                => ['required', 'string', 'max:200'],
            'brand_tier'                => ['required', 'in:super_brand,sub_brand'],
            'parent_brand_id'           => ['nullable', 'required_if:brand_tier,sub_brand', 'exists:brands,id'],
            'brand_type'                => ['required', 'string', 'in:oem_manufacturer,trademark_owner,authorized_importer,private_label'],
            'official_website'          => ['nullable', 'url', 'max:300'],
            'country_of_origin'         => ['nullable', 'string', 'max:100'],
            'brand_story'               => ['nullable', 'string', 'max:3000'],
            'brand_logo'                => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:4096'],
            // Legal / Trademark Verification
            'trademark_number'          => ['required', 'string', 'max:100'],
            'trademark_class'           => ['required', 'string', 'max:50'],
            'trademark_status'          => ['required', 'string', 'in:registered,applied_pending,licensed'],
            'trademark_doc'             => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'company_incorporation_doc' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ]);

        DB::beginTransaction();
        try {
            // 1. Create Vendor Account
            $vendor = Vendor::create([
                'name'            => $validated['name'],
                'email'           => $validated['email'],
                'phone'           => $validated['phone'],
                'company_name'    => $validated['company_name'],
                'company_slug'    => Str::slug($validated['company_name']) . '-' . Str::random(5),
                'business_type'   => 'manufacturer',
                'vendor_type'     => 'brand_owner',
                'password'        => Hash::make($validated['password']),
                'approval_status' => 'pending',
                'kyc_status'      => 'submitted',
                'kyc_submitted_at'=> now(),
                'status'          => 'active',
            ]);

            // 2. Upload Documents
            $tmDocPath = $request->file('trademark_doc')->store('brands/documents', 'public');
            $incorpDocPath = $request->file('company_incorporation_doc')->store('brands/documents', 'public');
            
            $logoPath = null;
            if ($request->hasFile('brand_logo')) {
                $logoPath = $request->file('brand_logo')->store('brands/logos', 'public');
            }

            // 3. Generate unique slug for Brand
            $baseSlug = Str::slug($validated['brand_name']);
            $brandSlug = $baseSlug;
            $counter = 1;
            while (Brand::where('slug', $brandSlug)->exists()) {
                $brandSlug = $baseSlug . '-' . $counter++;
            }

            $parentId = ($validated['brand_tier'] === 'sub_brand') ? $validated['parent_brand_id'] : null;

            // 4. Create Brand Record (Pending verification by Super Admin)
            $brand = Brand::create([
                'name'                => $validated['brand_name'],
                'slug'                => $brandSlug,
                'parent_id'           => $parentId,
                'brand_tier'          => $validated['brand_tier'],
                'owner_vendor_id'     => $vendor->id,
                'brand_type'          => $validated['brand_type'],
                'logo'                => $logoPath,
                'website'             => $validated['official_website'] ?? null,
                'support_email'       => $validated['email'],
                'support_phone'       => $validated['phone'],
                'country_of_origin'   => $validated['country_of_origin'] ?? 'India',
                'trademark_number'    => $validated['trademark_number'],
                'trademark_class'     => $validated['trademark_class'],
                'trademark_status'    => $validated['trademark_status'],
                'trademark_doc'       => $tmDocPath,
                'story'               => $validated['brand_story'] ?? null,
                'verification_status' => 'pending',
                'is_protected'        => true, // New brands are protected by default
                'status'              => 'inactive', // Active once approved by Super Admin
            ]);

            // 5. Create BrandRegistration application for Admin Brand Registry queue
            $app = BrandRegistration::create([
                'vendor_id'                     => $vendor->id,
                'created_brand_id'              => $brand->id,
                'parent_brand_id'               => $parentId,
                'brand_tier'                    => $validated['brand_tier'],
                'brand_name'                    => $validated['brand_name'],
                'brand_slug'                    => $brandSlug,
                'brand_type'                    => $validated['brand_type'],
                'logo_path'                     => $logoPath,
                'official_website'              => $validated['official_website'] ?? null,
                'official_email'                => $validated['email'],
                'official_phone'                => $validated['phone'],
                'country_of_origin'             => $validated['country_of_origin'] ?? 'India',
                'trademark_number'              => $validated['trademark_number'],
                'trademark_class'               => $validated['trademark_class'],
                'trademark_status'              => $validated['trademark_status'],
                'trademark_doc_path'            => $tmDocPath,
                'company_incorporation_doc_path'=> $incorpDocPath,
                'brand_story'                   => $validated['brand_story'] ?? null,
                'status'                        => 'pending',
            ]);

            // 6. Assign Default Plan & Notify Admin
            $this->assignDefaultPlanAndNotify($vendor);

            AdminNotificationService::send(
                'brand_registry',
                'New Brand Registration Application: ' . $brand->name,
                "Brand owner '{$vendor->company_name}' submitted new brand '{$brand->name}' with legal trademark documents for Super Admin verification.",
                route('admin.brands.registry.show', $app->id),
                'bi-award-fill text-warning'
            );

            DB::commit();

            Auth::guard('vendor')->login($vendor);

            return redirect()->route('vendor.brand-registry.index')->with('success', "Brand '{$brand->name}' registered successfully! Your legal documents have been submitted to Super Admin for verification.");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Brand registration failed: ' . $e->getMessage());
        }
    }

    /**
     * Helper: Assign Default Subscription Plan and Trigger Welcome Notifications
     */
    protected function assignDefaultPlanAndNotify(Vendor $vendor)
    {
        $freePlan = SubscriptionPlan::forVendors()->where('monthly_price', '<=', 0)->first();
        if ($freePlan) {
            Subscription::create([
                'subscription_plan_id'   => $freePlan->id,
                'subscribable_type'      => Vendor::class,
                'subscribable_id'        => $vendor->id,
                'plan_name'              => $freePlan->name,
                'billing_cycle'          => 'monthly',
                'amount_paid'            => 0.00,
                'starts_at'              => now(),
                'expires_at'             => null,
                'status'                 => 'active',
                'payment_method'         => 'free',
                'remaining_boost_tokens' => $freePlan->sponsored_products_quota ?? 0,
            ]);
        }

        VendorNotificationService::ensureOnboardingMessages($vendor);

        AdminNotificationService::send(
            'vendor',
            'New Account Registered: ' . $vendor->company_name,
            "{$vendor->company_name} ({$vendor->name}) registered as " . ($vendor->isBrandOwner() ? 'Brand Owner' : 'Standard Vendor') . ".",
            route('admin.vendors.show', $vendor->id),
            'bi-shop-window text-info'
        );
    }
}
