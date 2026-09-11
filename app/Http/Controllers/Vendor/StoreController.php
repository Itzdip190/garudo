<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StoreController extends Controller
{
    protected function getVendor(): Vendor
    {
        return Auth::guard('vendor')->user();
    }

    public function profile()
    {
        $vendor = $this->getVendor();
        return view('vendor.store.profile', compact('vendor'));
    }

    public function business()
    {
        $vendor = $this->getVendor();
        return view('vendor.store.business', compact('vendor'));
    }

    public function media()
    {
        $vendor = $this->getVendor();
        return view('vendor.store.media', compact('vendor'));
    }

    /**
     * Storefront Designer Studio
     */
    public function design()
    {
        $vendor = $this->getVendor();
        $products = $vendor->products()->where('status', 'active')->take(12)->get();
        return view('vendor.store.design', compact('vendor', 'products'));
    }

    /**
     * Save Storefront Design & Branding Customizations
     */
    public function updateDesign(Request $request)
    {
        $vendor = $this->getVendor();

        $validated = $request->validate([
            'company_name'        => 'required|string|max:200',
            'company_slug'        => 'nullable|string|max:200|unique:vendors,company_slug,' . $vendor->id,
            'company_description' => 'nullable|string|max:2000',
            'business_type'       => 'nullable|string|max:100',
            'company_website'     => 'nullable|string|max:255',
            'company_logo'        => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
            'company_banner'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:6144',
            'banner_preset'       => 'nullable|string',
        ]);

        $data = [
            'company_name'        => $validated['company_name'],
            'company_description' => $validated['company_description'] ?? $vendor->company_description,
            'business_type'       => $validated['business_type'] ?? $vendor->business_type,
            'company_website'     => $validated['company_website'] ?? $vendor->company_website,
        ];

        if (!empty($validated['company_slug'])) {
            $data['company_slug'] = Str::slug($validated['company_slug']);
        }

        // Upload Logo
        if ($request->hasFile('company_logo')) {
            if ($vendor->company_logo && Storage::disk('public')->exists($vendor->company_logo)) {
                Storage::disk('public')->delete($vendor->company_logo);
            }
            $data['company_logo'] = $request->file('company_logo')->store('vendors/logos', 'public');
        }

        // Upload Banner or set preset
        if ($request->hasFile('company_banner')) {
            if ($vendor->company_banner && Storage::disk('public')->exists($vendor->company_banner)) {
                Storage::disk('public')->delete($vendor->company_banner);
            }
            $data['company_banner'] = $request->file('company_banner')->store('vendors/banners', 'public');
        } elseif (!empty($request->input('banner_preset'))) {
            $data['company_banner'] = $request->input('banner_preset');
        }

        $vendor->update($data);

        return back()->with('success', 'Storefront branding and design updated successfully! Check your live store.');
    }
}
