<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductSpecification;
use App\Models\ProductVariant;
use App\Models\Inventory;
use App\Models\InventoryLog;
use App\Models\Attribute;
use App\Models\CatalogRequest;
use App\Services\AdminNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    protected function getVendor()
    {
        return Auth::guard('vendor')->user();
    }

    public function index(Request $request)
    {
        $vendor = $this->getVendor();
        $status = $request->query('status', 'all');
        $search = $request->query('q');
        $categoryId = $request->query('category_id');

        $query = $vendor->products()->with(['category', 'brand', 'primaryImage', 'variants'])->latest();

        if ($status === 'active') {
            $query->where('status', 'active');
        } elseif ($status === 'inactive') {
            $query->where('status', 'inactive');
        } elseif ($status === 'pending') {
            $query->where('approval_status', 'pending');
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('hsn_code', 'like', "%{$search}%");
            });
        }

        $products = $query->paginate(15);
        $categories = Category::where('status', 'active')->orderBy('name')->get();

        $counts = [
            'all' => $vendor->products()->count(),
            'active' => $vendor->products()->where('status', 'active')->count(),
            'inactive' => $vendor->products()->where('status', 'inactive')->count(),
            'pending' => $vendor->products()->where('approval_status', 'pending')->count(),
        ];

        return view('vendor.products.index', compact('vendor', 'products', 'categories', 'counts', 'status', 'search', 'categoryId'));
    }

    public function create()
    {
        $vendor = $this->getVendor();
        $categories = Category::where('status', 'active')->orderBy('name')->get();
        $superBrands = Brand::with('activeSubBrands')
                            ->whereNull('parent_id')
                            ->where('status', 'active')
                            ->orderBy('name')
                            ->get();
        $brands = Brand::with('superBrand')->where('status', 'active')->orderBy('name')->get();
        $warehouses = $vendor->warehouses()->active()->get();

        return view('vendor.products.create', compact('vendor', 'categories', 'brands', 'superBrands', 'warehouses'));
    }

    public function store(Request $request)
    {
        $vendor = $this->getVendor();

        $validated = $request->validate([
            'name' => 'required|string|max:500',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'product_type' => 'nullable|in:simple,variable',
            'policy_type' => 'nullable|in:inherit,custom',
            'custom_policy' => 'nullable|string|max:10000',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'selling_price' => 'required|numeric|min:0',
            'mrp' => 'nullable|numeric|min:0',
            'wholesale_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'gst_rate' => 'nullable|numeric|min:0|max:28',
            'unit' => 'nullable|string|max:50',
            'sku' => 'nullable|string|max:100',
            'hsn_code' => 'nullable|string|max:20',
            'barcode' => 'nullable|string|max:100',
            'min_order_qty' => 'nullable|integer|min:1',
            'max_order_qty' => 'nullable|integer|min:1',
            'weight' => 'nullable|numeric|min:0',
            'weight_unit' => 'nullable|string|in:kg,g,lb,oz',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'dimension_unit' => 'nullable|string|in:cm,mm,inch,ft,m',
            'short_description' => 'nullable|string|max:1000',
            'description' => 'nullable|string',
            'features' => 'nullable|string',
            'free_shipping' => 'nullable|boolean',
            'shipping_charges' => 'nullable|numeric|min:0',
            'cod_available' => 'nullable|boolean',
            'has_sample' => 'nullable|boolean',
            'sample_type' => 'nullable|in:free,paid,partial_paid',
            'sample_price' => 'nullable|numeric|min:0',
            'sample_shipping_fee' => 'nullable|numeric|min:0',
            'sample_max_qty' => 'nullable|integer|min:1',
            'sample_unit_description' => 'nullable|string|max:255',
            'sample_instructions' => 'nullable|string|max:2000',
            'sample_stock' => 'nullable|integer|min:0',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'specs' => 'nullable|array',
            'specs.*.group' => 'nullable|string|max:150',
            'specs.*.key' => 'nullable|string|max:200',
            'specs.*.value' => 'nullable|string',
            'variants' => 'nullable|array',
            'variants.*.sku' => 'nullable|string|max:150',
            'variants.*.mrp' => 'nullable|numeric|min:0',
            'variants.*.selling_price' => 'nullable|numeric|min:0',
            'variants.*.wholesale_price' => 'nullable|numeric|min:0',
            'variants.*.stock' => 'nullable|integer|min:0',
            'variants.*.weight' => 'nullable|numeric|min:0',
            'initial_stock' => 'nullable|integer|min:0',
        ]);

        if (!empty($validated['brand_id'])) {
            $selectedBrand = Brand::find($validated['brand_id']);
            if ($selectedBrand && !$selectedBrand->canVendorSell($vendor)) {
                return back()->withInput()->with('error', "The brand '{$selectedBrand->name}' is trademark protected on Garudo. You must be the verified Brand Owner or hold an active Authorized Dealership to list products under this brand.");
            }
        }

        DB::beginTransaction();
        try {
            $slug = Str::slug($validated['name']) . '-' . rand(1000, 9999);
            $sku = !empty($validated['sku']) ? $validated['sku'] : 'SKU-' . strtoupper(Str::random(8));

            $productType = $validated['product_type'] ?? 'simple';
            $mrp = $validated['mrp'] ?? $validated['selling_price'];
            $wholesalePrice = $validated['wholesale_price'] ?? $validated['selling_price'];

            $hasSample = $request->has('has_sample');
            $sampleType = $request->input('sample_type', 'free');
            $samplePrice = ($hasSample && $sampleType === 'paid') ? floatval($request->input('sample_price', 0)) : 0.00;
            $sampleShipping = ($hasSample && in_array($sampleType, ['paid', 'partial_paid'])) ? floatval($request->input('sample_shipping_fee', 0)) : 0.00;

            $allowQuotation = $request->has('allow_quotation');
            $quotationMinQty = $request->input('quotation_min_qty');
            $rawCustomFields = $request->input('quotation_custom_fields', []);
            $cleanedCustomFields = is_array($rawCustomFields) ? array_values(array_filter(array_map('trim', $rawCustomFields))) : [];
            $quotationNotes = $request->input('quotation_notes');
            $quotationAttachmentsAllowed = $request->has('quotation_attachments_allowed');

            $product = $vendor->products()->create([
                'category_id' => $validated['category_id'],
                'brand_id' => $validated['brand_id'] ?? null,
                'warehouse_id' => $request->input('warehouse_id') ?: ($vendor->primary_dispatch_warehouse_id ?? null),
                'policy_type' => $request->input('policy_type', 'inherit'),
                'custom_policy' => $request->input('custom_policy'),
                'name' => $validated['name'],
                'slug' => $slug,
                'sku' => $sku,
                'barcode' => $validated['barcode'] ?? null,
                'hsn_code' => $validated['hsn_code'] ?? null,
                'product_type' => $productType,
                'selling_price' => $validated['selling_price'],
                'mrp' => $mrp,
                'wholesale_price' => $wholesalePrice,
                'cost_price' => $validated['cost_price'] ?? null,
                'gst_rate' => $validated['gst_rate'] ?? 18.00,
                'gst_inclusive' => $request->has('gst_inclusive'),
                'unit' => $validated['unit'] ?? 'Piece',
                'min_order_qty' => $validated['min_order_qty'] ?? 1,
                'max_order_qty' => $validated['max_order_qty'] ?? null,
                'weight' => $validated['weight'] ?? null,
                'weight_unit' => $validated['weight_unit'] ?? 'kg',
                'length' => $validated['length'] ?? null,
                'width' => $validated['width'] ?? null,
                'height' => $validated['height'] ?? null,
                'dimension_unit' => $validated['dimension_unit'] ?? 'cm',
                'short_description' => $validated['short_description'] ?? null,
                'description' => $validated['description'] ?? null,
                'features' => $validated['features'] ?? null,
                'free_shipping' => $request->has('free_shipping'),
                'shipping_charges' => $validated['shipping_charges'] ?? 0.00,
                'cod_available' => $request->has('cod_available'),
                'has_sample' => $hasSample,
                'sample_type' => $sampleType,
                'sample_price' => $samplePrice,
                'sample_shipping_fee' => $sampleShipping,
                'sample_max_qty' => $request->input('sample_max_qty', 1),
                'sample_unit_description' => $request->input('sample_unit_description'),
                'sample_instructions' => $request->input('sample_instructions'),
                'sample_stock' => $request->input('sample_stock'),
                'allow_quotation' => $allowQuotation,
                'quotation_min_qty' => $quotationMinQty ?: null,
                'quotation_custom_fields' => !empty($cleanedCustomFields) ? json_encode($cleanedCustomFields) : null,
                'quotation_notes' => $quotationNotes,
                'quotation_attachments_allowed' => $quotationAttachmentsAllowed,
                'approval_status' => ($vendor->approval_status === 'approved' || $vendor->is_verified) ? 'approved' : 'pending',
                'approved_at' => ($vendor->approval_status === 'approved' || $vendor->is_verified) ? now() : null,
                'status' => 'active',
                'published_at' => now(),
            ]);

            // Handle Primary Image
            if ($request->hasFile('main_image')) {
                $mainPath = $request->file('main_image')->store('products', 'public');
                $product->images()->create([
                    'file_path' => $mainPath,
                    'is_primary' => true,
                    'sort_order' => 0,
                    'alt_text' => $product->name,
                ]);
            }

            // Handle Gallery Images
            if ($request->hasFile('gallery_images')) {
                $sort = 1;
                foreach ($request->file('gallery_images') as $galleryFile) {
                    $galleryPath = $galleryFile->store('products', 'public');
                    $product->images()->create([
                        'file_path' => $galleryPath,
                        'is_primary' => false,
                        'sort_order' => $sort++,
                        'alt_text' => $product->name . ' gallery ' . $sort,
                    ]);
                }
            }

            // Handle Specifications
            if (!empty($validated['specs']) && is_array($validated['specs'])) {
                $specSort = 0;
                foreach ($validated['specs'] as $specItem) {
                    if (!empty($specItem['key']) && !empty($specItem['value'])) {
                        $product->specifications()->create([
                            'spec_group' => $specItem['group'] ?? 'General',
                            'spec_key' => trim($specItem['key']),
                            'spec_value' => trim($specItem['value']),
                            'sort_order' => $specSort++,
                        ]);
                    }
                }
            }

            // Handle Initial Inventory for Simple Product
            $initialStock = $request->input('initial_stock', 100);
            $product->inventory()->create([
                'quantity_available' => (int) $initialStock,
                'low_stock_threshold' => 10,
                'track_inventory' => true,
                'allow_backorder' => false,
                'last_updated_at' => now(),
            ]);

            // Handle Variants if Variable Product
            if ($productType === 'variable' && !empty($validated['variants']) && is_array($validated['variants'])) {
                $varSort = 0;
                foreach ($validated['variants'] as $vData) {
                    if (!empty($vData['sku']) || !empty($vData['selling_price'])) {
                        $variantSku = !empty($vData['sku']) ? $vData['sku'] : $product->sku . '-V' . ($varSort + 1);
                        $variantMrp = !empty($vData['mrp']) ? $vData['mrp'] : ($vData['selling_price'] ?? $product->selling_price);
                        $variantSelling = !empty($vData['selling_price']) ? $vData['selling_price'] : $product->selling_price;
                        $variantWholesale = !empty($vData['wholesale_price']) ? $vData['wholesale_price'] : $variantSelling;
                        $variantStock = !empty($vData['stock']) ? (int) $vData['stock'] : 50;

                        $variant = $product->variants()->create([
                            'sku' => $variantSku,
                            'mrp' => $variantMrp,
                            'selling_price' => $variantSelling,
                            'wholesale_price' => $variantWholesale,
                            'weight' => $vData['weight'] ?? $product->weight,
                            'status' => 'active',
                            'sort_order' => $varSort++,
                        ]);

                        $variant->inventory()->create([
                            'product_id' => $product->id,
                            'quantity_available' => $variantStock,
                            'low_stock_threshold' => 5,
                            'track_inventory' => true,
                            'allow_backorder' => false,
                            'last_updated_at' => now(),
                        ]);
                    }
                }
            }

            DB::commit();

            \App\Services\AdminNotificationService::send(
                'product',
                'New Product Listed: ' . $product->name,
                "Vendor '{$vendor->company_name}' listed a new product '{$product->name}' (Status: {$product->approval_status})",
                route('admin.products.show', $product->id),
                'bi-box-seam-fill text-danger'
            );

            return redirect()->route('vendor.products.index')->with('success', 'Product "' . $product->name . '" created successfully with all specs and images!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create product: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $vendor = $this->getVendor();
        $product = $vendor->products()->with(['images', 'specifications', 'variants', 'inventory'])->findOrFail($id);
        $categories = Category::where('status', 'active')->orderBy('name')->get();
        $superBrands = Brand::with('activeSubBrands')
                            ->whereNull('parent_id')
                            ->where('status', 'active')
                            ->orderBy('name')
                            ->get();
        $brands = Brand::with('superBrand')->where('status', 'active')->orderBy('name')->get();
        $warehouses = $vendor->warehouses()->active()->get();

        return view('vendor.products.edit', compact('vendor', 'product', 'categories', 'brands', 'superBrands', 'warehouses'));
    }

    public function update(Request $request, $id)
    {
        $vendor = $this->getVendor();
        $product = $vendor->products()->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:500',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'product_type' => 'nullable|in:simple,variable',
            'policy_type' => 'nullable|in:inherit,custom',
            'custom_policy' => 'nullable|string|max:10000',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'selling_price' => 'required|numeric|min:0',
            'mrp' => 'nullable|numeric|min:0',
            'wholesale_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'gst_rate' => 'nullable|numeric|min:0|max:28',
            'unit' => 'nullable|string|max:50',
            'sku' => 'nullable|string|max:100',
            'hsn_code' => 'nullable|string|max:20',
            'barcode' => 'nullable|string|max:100',
            'min_order_qty' => 'nullable|integer|min:1',
            'max_order_qty' => 'nullable|integer|min:1',
            'weight' => 'nullable|numeric|min:0',
            'weight_unit' => 'nullable|string|in:kg,g,lb,oz',
            'short_description' => 'nullable|string|max:1000',
            'description' => 'nullable|string',
            'features' => 'nullable|string',
            'free_shipping' => 'nullable|boolean',
            'shipping_charges' => 'nullable|numeric|min:0',
            'cod_available' => 'nullable|boolean',
            'has_sample' => 'nullable|boolean',
            'sample_type' => 'nullable|in:free,paid,partial_paid',
            'sample_price' => 'nullable|numeric|min:0',
            'sample_shipping_fee' => 'nullable|numeric|min:0',
            'sample_max_qty' => 'nullable|integer|min:1',
            'sample_unit_description' => 'nullable|string|max:255',
            'sample_instructions' => 'nullable|string|max:2000',
            'sample_stock' => 'nullable|integer|min:0',
            'status' => 'nullable|in:active,inactive,out_of_stock,discontinued',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'integer',
            'specs' => 'nullable|array',
            'specs.*.group' => 'nullable|string|max:150',
            'specs.*.key' => 'nullable|string|max:200',
            'specs.*.value' => 'nullable|string',
            'variants' => 'nullable|array',
            'initial_stock' => 'nullable|integer|min:0',
        ]);

        if (!empty($validated['brand_id'])) {
            $selectedBrand = Brand::find($validated['brand_id']);
            if ($selectedBrand && !$selectedBrand->canVendorSell($vendor)) {
                return back()->withInput()->with('error', "The brand '{$selectedBrand->name}' is trademark protected on Garudo. You must be the verified Brand Owner or hold an active Authorized Dealership to list products under this brand.");
            }
        }

        DB::beginTransaction();
        try {
            $hasSample = $request->has('has_sample');
            $sampleType = $request->input('sample_type', 'free');
            $samplePrice = ($hasSample && $sampleType === 'paid') ? floatval($request->input('sample_price', 0)) : 0.00;
            $sampleShipping = ($hasSample && in_array($sampleType, ['paid', 'partial_paid'])) ? floatval($request->input('sample_shipping_fee', 0)) : 0.00;

            $allowQuotation = $request->has('allow_quotation');
            $quotationMinQty = $request->input('quotation_min_qty');
            $rawCustomFields = $request->input('quotation_custom_fields', []);
            $cleanedCustomFields = is_array($rawCustomFields) ? array_values(array_filter(array_map('trim', $rawCustomFields))) : [];
            $quotationNotes = $request->input('quotation_notes');
            $quotationAttachmentsAllowed = $request->has('quotation_attachments_allowed');

            $product->update([
                'category_id' => $validated['category_id'],
                'brand_id' => $validated['brand_id'] ?? null,
                'warehouse_id' => $request->input('warehouse_id') ?: $product->warehouse_id,
                'policy_type' => $request->input('policy_type', 'inherit'),
                'custom_policy' => $request->input('custom_policy'),
                'name' => $validated['name'],
                'sku' => $validated['sku'] ?? $product->sku,
                'barcode' => $validated['barcode'] ?? null,
                'hsn_code' => $validated['hsn_code'] ?? null,
                'product_type' => $validated['product_type'] ?? $product->product_type,
                'selling_price' => $validated['selling_price'],
                'mrp' => $validated['mrp'] ?? $validated['selling_price'],
                'wholesale_price' => $validated['wholesale_price'] ?? $validated['selling_price'],
                'cost_price' => $validated['cost_price'] ?? null,
                'gst_rate' => $validated['gst_rate'] ?? $product->gst_rate,
                'gst_inclusive' => $request->has('gst_inclusive'),
                'unit' => $validated['unit'] ?? $product->unit,
                'min_order_qty' => $validated['min_order_qty'] ?? 1,
                'max_order_qty' => $validated['max_order_qty'] ?? null,
                'weight' => $validated['weight'] ?? null,
                'weight_unit' => $validated['weight_unit'] ?? 'kg',
                'short_description' => $validated['short_description'] ?? null,
                'description' => $validated['description'] ?? null,
                'features' => $validated['features'] ?? null,
                'free_shipping' => $request->has('free_shipping'),
                'shipping_charges' => $request->input('shipping_charges', 0.00),
                'cod_available' => $request->has('cod_available'),
                'has_sample' => $hasSample,
                'sample_type' => $sampleType,
                'sample_price' => $samplePrice,
                'sample_shipping_fee' => $sampleShipping,
                'sample_max_qty' => $request->input('sample_max_qty', 1),
                'sample_unit_description' => $request->input('sample_unit_description'),
                'sample_instructions' => $request->input('sample_instructions'),
                'sample_stock' => $request->input('sample_stock'),
                'allow_quotation' => $allowQuotation,
                'quotation_min_qty' => $quotationMinQty ?: null,
                'quotation_custom_fields' => !empty($cleanedCustomFields) ? json_encode($cleanedCustomFields) : null,
                'quotation_notes' => $quotationNotes,
                'quotation_attachments_allowed' => $quotationAttachmentsAllowed,
                'status' => $validated['status'] ?? $product->status,
            ]);

            // Handle Image Deletions
            if (!empty($validated['delete_images'])) {
                $imagesToDelete = $product->images()->whereIn('id', $validated['delete_images'])->get();
                foreach ($imagesToDelete as $img) {
                    if (Storage::disk('public')->exists($img->file_path)) {
                        Storage::disk('public')->delete($img->file_path);
                    }
                    $img->delete();
                }
            }

            // Handle New Primary Image
            if ($request->hasFile('main_image')) {
                // Set old primary to false
                $product->images()->where('is_primary', true)->update(['is_primary' => false]);
                $mainPath = $request->file('main_image')->store('products', 'public');
                $product->images()->create([
                    'file_path' => $mainPath,
                    'is_primary' => true,
                    'sort_order' => 0,
                    'alt_text' => $product->name,
                ]);
            }

            // Handle New Gallery Images
            if ($request->hasFile('gallery_images')) {
                $maxSort = $product->images()->max('sort_order') ?? 0;
                foreach ($request->file('gallery_images') as $galleryFile) {
                    $galleryPath = $galleryFile->store('products', 'public');
                    $product->images()->create([
                        'file_path' => $galleryPath,
                        'is_primary' => false,
                        'sort_order' => ++$maxSort,
                        'alt_text' => $product->name . ' gallery',
                    ]);
                }
            }

            // Update Specifications
            $product->specifications()->delete();
            if (!empty($validated['specs']) && is_array($validated['specs'])) {
                $specSort = 0;
                foreach ($validated['specs'] as $specItem) {
                    if (!empty($specItem['key']) && !empty($specItem['value'])) {
                        $product->specifications()->create([
                            'spec_group' => $specItem['group'] ?? 'General',
                            'spec_key' => trim($specItem['key']),
                            'spec_value' => trim($specItem['value']),
                            'sort_order' => $specSort++,
                        ]);
                    }
                }
            }

            // Update Stock
            if ($request->has('initial_stock')) {
                if ($product->inventory) {
                    $product->inventory->update([
                        'quantity_available' => (int) $request->input('initial_stock'),
                        'last_updated_at' => now(),
                    ]);
                } else {
                    $product->inventory()->create([
                        'quantity_available' => (int) $request->input('initial_stock'),
                        'low_stock_threshold' => 10,
                        'track_inventory' => true,
                        'last_updated_at' => now(),
                    ]);
                }
            }

            // Update Variants
            if (!empty($validated['variants']) && is_array($validated['variants'])) {
                $product->variants()->delete();
                $varSort = 0;
                foreach ($validated['variants'] as $vData) {
                    if (!empty($vData['sku']) || !empty($vData['selling_price'])) {
                        $variantSku = !empty($vData['sku']) ? $vData['sku'] : $product->sku . '-V' . ($varSort + 1);
                        $variantMrp = !empty($vData['mrp']) ? $vData['mrp'] : ($vData['selling_price'] ?? $product->selling_price);
                        $variantSelling = !empty($vData['selling_price']) ? $vData['selling_price'] : $product->selling_price;
                        $variantWholesale = !empty($vData['wholesale_price']) ? $vData['wholesale_price'] : $variantSelling;
                        $variantStock = !empty($vData['stock']) ? (int) $vData['stock'] : 50;

                        $variant = $product->variants()->create([
                            'sku' => $variantSku,
                            'mrp' => $variantMrp,
                            'selling_price' => $variantSelling,
                            'wholesale_price' => $variantWholesale,
                            'weight' => $vData['weight'] ?? $product->weight,
                            'status' => 'active',
                            'sort_order' => $varSort++,
                        ]);

                        $variant->inventory()->create([
                            'product_id' => $product->id,
                            'quantity_available' => $variantStock,
                            'low_stock_threshold' => 5,
                            'track_inventory' => true,
                            'last_updated_at' => now(),
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('vendor.products.index')->with('success', 'Product "' . $product->name . '" updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Update failed: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $vendor = $this->getVendor();
        $product = $vendor->products()->findOrFail($id);
        $product->delete();

        return redirect()->route('vendor.products.index')->with('success', 'Product moved to trash.');
    }

    /*
    |--------------------------------------------------------------------------
    | AJAX Quick Creation: Category & Brand
    |--------------------------------------------------------------------------
    */
    public function ajaxStoreCategory(Request $request)
    {
        $vendor = $this->getVendor();
        $request->validate([
            'name' => 'required|string|max:200',
            'parent_id' => 'nullable|exists:categories,id',
            'reason' => 'nullable|string|max:1000',
            'description' => 'nullable|string|max:500',
        ]);

        $isSub = !empty($request->parent_id);
        $typeName = $isSub ? 'subcategory' : 'category';

        $catalogReq = CatalogRequest::create([
            'vendor_id' => $vendor->id,
            'type'      => $typeName,
            'name'      => trim($request->name),
            'parent_id' => $request->parent_id ?: null,
            'reason'    => $request->reason ?: ($request->description ?: 'Vendor requested category addition from product editor.'),
            'status'    => 'pending',
        ]);

        try {
            AdminNotificationService::send(
                'catalog_request',
                'New Category Request from ' . ($vendor->company_name ?? $vendor->name),
                "Vendor requested new " . ($isSub ? 'Subcategory' : 'Main Category') . ": '{$request->name}' for Admin review.",
                route('admin.catalog-requests.index', ['type' => 'category', 'status' => 'pending']),
                'bi-diagram-3'
            );
        } catch (\Throwable $e) {}

        return response()->json([
            'success' => true,
            'is_request' => true,
            'message' => "Category request for '{$request->name}' submitted to Admin! Once verified by Super Admin, it will be added to the marketplace catalog and appear in this dropdown.",
            'request_id' => $catalogReq->id,
            'request_name' => $request->name,
        ]);
    }

    public function ajaxStoreBrand(Request $request)
    {
        $vendor = $this->getVendor();
        $request->validate([
            'name' => 'required|string|max:200',
            'country_of_origin' => 'nullable|string|max:100',
            'website' => 'nullable|string|max:300',
            'reason' => 'nullable|string|max:1000',
            'description' => 'nullable|string|max:500',
        ]);

        $metaInfo = [];
        if ($request->country_of_origin) $metaInfo[] = 'Origin: ' . $request->country_of_origin;
        if ($request->website) $metaInfo[] = 'Website: ' . $request->website;

        $catalogReq = CatalogRequest::create([
            'vendor_id' => $vendor->id,
            'type'      => 'brand',
            'name'      => trim($request->name),
            'values'    => implode(' | ', $metaInfo),
            'reason'    => $request->reason ?: ($request->description ?: 'Vendor requested brand listing for product catalog.'),
            'status'    => 'pending',
        ]);

        try {
            AdminNotificationService::send(
                'catalog_request',
                'New Brand Request from ' . ($vendor->company_name ?? $vendor->name),
                "Vendor requested new Brand: '{$request->name}' for Admin review.",
                route('admin.catalog-requests.index', ['type' => 'brand', 'status' => 'pending']),
                'bi-patch-check'
            );
        } catch (\Throwable $e) {}

        return response()->json([
            'success' => true,
            'is_request' => true,
            'message' => "Brand request for '{$request->name}' submitted to Admin! Once verified by Super Admin, it will be added to the marketplace catalog and appear in this dropdown.",
            'request_id' => $catalogReq->id,
            'request_name' => $request->name,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Quick Price Change System (Batch & Inline)
    |--------------------------------------------------------------------------
    */
    public function quickPriceIndex(Request $request)
    {
        $vendor = $this->getVendor();
        $categoryId = $request->query('category_id');
        $brandId = $request->query('brand_id');
        $search = $request->query('q');

        $query = $vendor->products()->with(['category', 'brand', 'primaryImage', 'inventory'])->latest();

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        if ($brandId) {
            $query->where('brand_id', $brandId);
        }
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $products = $query->paginate(25);
        $categories = Category::where('status', 'active')->orderBy('name')->get();
        $brands = Brand::where('status', 'active')->orderBy('name')->get();

        return view('vendor.products.quick-price', compact('vendor', 'products', 'categories', 'brands', 'categoryId', 'brandId', 'search'));
    }

    public function quickPriceBatchUpdate(Request $request)
    {
        $vendor = $this->getVendor();

        $validated = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'target_field' => 'required|in:selling_price,wholesale_price,mrp,all_prices',
            'action_type' => 'required|in:percentage_increase,percentage_decrease,flat_increase,flat_decrease,set_fixed',
            'amount_value' => 'required|numeric|min:0',
        ]);

        $query = $vendor->products();

        if (!empty($validated['category_id'])) {
            $query->where('category_id', $validated['category_id']);
        }
        if (!empty($validated['brand_id'])) {
            $query->where('brand_id', $validated['brand_id']);
        }

        $products = $query->get();
        $updatedCount = 0;

        DB::beginTransaction();
        try {
            foreach ($products as $p) {
                $fieldsToUpdate = [];
                if ($validated['target_field'] === 'all_prices') {
                    $fieldsToUpdate = ['selling_price', 'wholesale_price', 'mrp'];
                } else {
                    $fieldsToUpdate = [$validated['target_field']];
                }

                $updateData = [];
                foreach ($fieldsToUpdate as $field) {
                    $currentVal = (float) $p->$field;
                    $newVal = $currentVal;

                    switch ($validated['action_type']) {
                        case 'percentage_increase':
                            $newVal = $currentVal + ($currentVal * ($validated['amount_value'] / 100));
                            break;
                        case 'percentage_decrease':
                            $newVal = max(0, $currentVal - ($currentVal * ($validated['amount_value'] / 100)));
                            break;
                        case 'flat_increase':
                            $newVal = $currentVal + $validated['amount_value'];
                            break;
                        case 'flat_decrease':
                            $newVal = max(0, $currentVal - $validated['amount_value']);
                            break;
                        case 'set_fixed':
                            $newVal = $validated['amount_value'];
                            break;
                    }
                    $updateData[$field] = round($newVal, 2);
                }

                $p->update($updateData);
                $updatedCount++;
            }

            DB::commit();
            return back()->with('success', "Batch price update complete! {$updatedCount} products updated successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Batch price update failed: ' . $e->getMessage());
        }
    }

    public function quickPriceInlineUpdate(Request $request)
    {
        $vendor = $this->getVendor();
        $prices = $request->input('prices', []);

        if (empty($prices) || !is_array($prices)) {
            return back()->with('error', 'No price changes submitted.');
        }

        DB::beginTransaction();
        try {
            $updated = 0;
            foreach ($prices as $productId => $data) {
                $product = $vendor->products()->find($productId);
                if ($product) {
                    $product->update([
                        'selling_price' => isset($data['selling_price']) ? (float)$data['selling_price'] : $product->selling_price,
                        'mrp' => isset($data['mrp']) ? (float)$data['mrp'] : $product->mrp,
                        'wholesale_price' => isset($data['wholesale_price']) ? (float)$data['wholesale_price'] : $product->wholesale_price,
                    ]);
                    $updated++;
                }
            }

            DB::commit();
            return back()->with('success', "Quick pricing updated successfully for {$updated} products!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Quick update failed: ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Bulk Upload / Excel Import & Export
    |--------------------------------------------------------------------------
    */
    public function bulk()
    {
        $vendor = $this->getVendor();
        $categories = Category::where('status', 'active')->orderBy('name')->get();
        return view('vendor.products.bulk', compact('vendor', 'categories'));
    }

    public function export(Request $request)
    {
        $vendor = $this->getVendor();
        $products = $vendor->products()->with(['category', 'brand', 'inventory', 'specifications'])->get();

        $filename = 'garudo_products_' . Str::slug($vendor->company_name ?? 'vendor') . '_' . date('Y_m_d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $columns = [
            'SKU', 'Product Name', 'Category', 'Brand', 'Selling Price (INR)',
            'MRP (INR)', 'Wholesale Price (INR)', 'Cost Price (INR)', 'GST Rate (%)',
            'Unit', 'Min Order Qty', 'Stock Available', 'Weight (KG)', 'HSN Code',
            'Product Type', 'Status', 'Short Description', 'Full Description', 'Specifications (JSON)'
        ];

        $callback = function () use ($products, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($products as $p) {
                $specsArray = [];
                foreach ($p->specifications as $s) {
                    $specsArray[$s->spec_key] = $s->spec_value;
                }

                fputcsv($file, [
                    $p->sku,
                    $p->name,
                    $p->category->name ?? 'General',
                    $p->brand->name ?? '',
                    $p->selling_price,
                    $p->mrp,
                    $p->wholesale_price,
                    $p->cost_price ?? '',
                    $p->gst_rate,
                    $p->unit,
                    $p->min_order_qty,
                    $p->inventory->quantity_available ?? 0,
                    $p->weight ?? '',
                    $p->hsn_code ?? '',
                    $p->product_type,
                    $p->status,
                    $p->short_description ?? '',
                    $p->description ?? '',
                    !empty($specsArray) ? json_encode($specsArray) : '',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function downloadTemplate()
    {
        $filename = 'garudo_product_import_sample_template.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $columns = [
            'SKU', 'Product Name', 'Category', 'Brand', 'Selling Price (INR)',
            'MRP (INR)', 'Wholesale Price (INR)', 'Cost Price (INR)', 'GST Rate (%)',
            'Unit', 'Min Order Qty', 'Stock Available', 'Weight (KG)', 'HSN Code',
            'Product Type', 'Status', 'Short Description', 'Full Description', 'Specifications (JSON)'
        ];

        $sampleRow1 = [
            'UTC-PPC-50KG', 'UltraTech Super Cement PPC 50kg', 'Cement & Concrete', 'UltraTech',
            '340.00', '390.00', '330.00', '290.00', '28.00', 'Bag', '50', '500', '50', '252329',
            'simple', 'active', 'High performance Portland Pozzolana Cement for structural construction.',
            'Manufactured with superior clinker and micro-fine pozzolanic materials meeting IS 1489 specifications.',
            '{"Grade":"PPC","Compressive Strength (28 Days)":"53 MPa","Initial Setting Time":"45 Mins","Standards":"IS 1489 Part 1"}'
        ];

        $sampleRow2 = [
            'JSW-TMT-12MM', 'JSW Neosteel Fe 550D TMT Rebar 12mm', 'Steel & TMT Bars', 'JSW Steel',
            '58500.00', '64000.00', '57200.00', '52000.00', '18.00', 'Ton', '2', '45', '1000', '721420',
            'simple', 'active', 'Primary producer earthquake resistant high ductility thermo-mechanically treated bar.',
            'Low carbon equivalent, superior bendability, corrosion resistant with high yield strength.',
            '{"Grade":"Fe 550D","Diameter":"12mm","Standard":"IS 1786:2008","Elongation":"16% min"}'
        ];

        $callback = function () use ($columns, $sampleRow1, $sampleRow2) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fputcsv($file, $sampleRow1);
            fputcsv($file, $sampleRow2);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        $vendor = $this->getVendor();
        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');

        $header = fgetcsv($handle);
        if (!$header) {
            return back()->with('error', 'CSV file is empty or invalid format.');
        }

        $imported = 0;
        $updated = 0;
        $errors = [];
        $rowNumber = 1;

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle)) !== false) {
                $rowNumber++;
                if (empty(array_filter($row))) {
                    continue;
                }

                $sku = trim($row[0] ?? '');
                $name = trim($row[1] ?? '');
                $categoryName = trim($row[2] ?? 'General');
                $brandName = trim($row[3] ?? '');
                $sellingPrice = (float) ($row[4] ?? 0);
                $mrp = !empty($row[5]) ? (float)$row[5] : $sellingPrice;
                $wholesalePrice = !empty($row[6]) ? (float)$row[6] : $sellingPrice;
                $costPrice = !empty($row[7]) ? (float)$row[7] : null;
                $gstRate = !empty($row[8]) ? (float)$row[8] : 18.00;
                $unit = trim($row[9] ?? 'Piece') ?: 'Piece';
                $minOrderQty = !empty($row[10]) ? (int)$row[10] : 1;
                $stock = !empty($row[11]) ? (int)$row[11] : 50;
                $weight = !empty($row[12]) ? (float)$row[12] : null;
                $hsnCode = trim($row[13] ?? '');
                $productType = in_array(trim($row[14] ?? ''), ['simple', 'variable']) ? trim($row[14]) : 'simple';
                $status = in_array(trim($row[15] ?? ''), ['active', 'inactive']) ? trim($row[15]) : 'active';
                $shortDesc = trim($row[16] ?? '');
                $desc = trim($row[17] ?? '');
                $specsJson = trim($row[18] ?? '');

                if (empty($name)) {
                    $errors[] = "Row #{$rowNumber}: Missing Product Name.";
                    continue;
                }

                // Resolve or create category
                $category = Category::where('name', $categoryName)->first();
                if (!$category) {
                    $category = Category::create([
                        'name' => $categoryName,
                        'slug' => Str::slug($categoryName) . '-' . rand(100, 999),
                        'status' => 'active',
                    ]);
                }

                // Resolve brand if present
                $brandId = null;
                if (!empty($brandName)) {
                    $brand = Brand::where('name', $brandName)->first();
                    if (!$brand) {
                        $brand = Brand::create([
                            'name' => $brandName,
                            'slug' => Str::slug($brandName) . '-' . rand(100, 999),
                            'status' => 'active',
                        ]);
                    }
                    $brandId = $brand->id;
                }

                $productSku = $sku ?: 'SKU-' . strtoupper(Str::random(8));

                // Check existing product by SKU under this vendor
                $existingProduct = $vendor->products()->where('sku', $productSku)->first();

                if ($existingProduct) {
                    $existingProduct->update([
                        'category_id' => $category->id,
                        'brand_id' => $brandId,
                        'name' => $name,
                        'selling_price' => $sellingPrice,
                        'mrp' => $mrp,
                        'wholesale_price' => $wholesalePrice,
                        'cost_price' => $costPrice,
                        'gst_rate' => $gstRate,
                        'unit' => $unit,
                        'min_order_qty' => $minOrderQty,
                        'weight' => $weight,
                        'hsn_code' => $hsnCode,
                        'product_type' => $productType,
                        'status' => $status,
                        'short_description' => $shortDesc,
                        'description' => $desc,
                    ]);

                    if ($existingProduct->inventory) {
                        $existingProduct->inventory->update(['quantity_available' => $stock]);
                    }

                    $this->syncSpecsFromJson($existingProduct, $specsJson);
                    $updated++;
                } else {
                    $newProduct = $vendor->products()->create([
                        'category_id' => $category->id,
                        'brand_id' => $brandId,
                        'name' => $name,
                        'slug' => Str::slug($name) . '-' . rand(1000, 9999),
                        'sku' => $productSku,
                        'selling_price' => $sellingPrice,
                        'mrp' => $mrp,
                        'wholesale_price' => $wholesalePrice,
                        'cost_price' => $costPrice,
                        'gst_rate' => $gstRate,
                        'unit' => $unit,
                        'min_order_qty' => $minOrderQty,
                        'weight' => $weight,
                        'hsn_code' => $hsnCode,
                        'product_type' => $productType,
                        'status' => $status,
                        'approval_status' => 'approved',
                        'short_description' => $shortDesc,
                        'description' => $desc,
                        'published_at' => now(),
                    ]);

                    $newProduct->inventory()->create([
                        'quantity_available' => $stock,
                        'low_stock_threshold' => 10,
                        'track_inventory' => true,
                        'last_updated_at' => now(),
                    ]);

                    $this->syncSpecsFromJson($newProduct, $specsJson);
                    $imported++;
                }
            }

            DB::commit();
            fclose($handle);

            $msg = "Import completed! {$imported} new products added, {$updated} existing products updated.";
            if (!empty($errors)) {
                $msg .= " (Errors on " . count($errors) . " rows: " . implode('; ', array_slice($errors, 0, 3)) . ")";
            }

            return back()->with('success', $msg);
        } catch (\Exception $e) {
            DB::rollBack();
            if (is_resource($handle)) fclose($handle);
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    private function syncSpecsFromJson(Product $product, string $jsonStr)
    {
        if (empty($jsonStr)) return;
        $specs = json_decode($jsonStr, true);
        if (is_array($specs)) {
            $product->specifications()->delete();
            $sort = 0;
            foreach ($specs as $key => $val) {
                $product->specifications()->create([
                    'spec_group' => 'Technical Specs',
                    'spec_key' => $key,
                    'spec_value' => is_array($val) ? json_encode($val) : (string)$val,
                    'sort_order' => $sort++,
                ]);
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Product Catalog Backup & Restore
    |--------------------------------------------------------------------------
    */
    public function backup()
    {
        $vendor = $this->getVendor();
        $products = $vendor->products()
            ->with(['category', 'brand', 'images', 'specifications', 'variants', 'inventory'])
            ->get();

        $backupData = [
            'vendor_id' => $vendor->id,
            'vendor_name' => $vendor->name,
            'company_name' => $vendor->company_name,
            'generated_at' => now()->toIso8601String(),
            'platform' => 'Garudo B2B Marketplace',
            'version' => '2.0',
            'product_count' => $products->count(),
            'products' => $products->toArray(),
        ];

        $json = json_encode($backupData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $filename = 'garudo_catalog_backup_' . Str::slug($vendor->company_name ?? 'vendor') . '_' . date('Y_m_d_His') . '.json';

        return response($json, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:json,txt|max:20480',
            'restore_mode' => 'required|in:overwrite,skip,clean',
        ]);

        $vendor = $this->getVendor();
        $content = file_get_contents($request->file('backup_file')->getRealPath());
        $data = json_decode($content, true);

        if (!$data || !isset($data['products']) || !is_array($data['products'])) {
            return back()->with('error', 'Invalid Garudo backup JSON file format.');
        }

        DB::beginTransaction();
        try {
            $mode = $request->restore_mode;

            if ($mode === 'clean') {
                $vendor->products()->forceDelete();
            }

            $restoredCount = 0;
            $skippedCount = 0;

            foreach ($data['products'] as $pData) {
                $sku = $pData['sku'] ?? ('SKU-' . strtoupper(Str::random(8)));
                $existing = $vendor->products()->where('sku', $sku)->first();

                if ($existing && $mode === 'skip') {
                    $skippedCount++;
                    continue;
                }

                // Resolve Category
                $catId = 1;
                if (!empty($pData['category']['name'])) {
                    $cat = Category::firstOrCreate(
                        ['name' => $pData['category']['name']],
                        ['slug' => Str::slug($pData['category']['name']) . '-' . rand(100, 999), 'status' => 'active']
                    );
                    $catId = $cat->id;
                }

                // Resolve Brand
                $brandId = null;
                if (!empty($pData['brand']['name'])) {
                    $b = Brand::firstOrCreate(
                        ['name' => $pData['brand']['name']],
                        ['slug' => Str::slug($pData['brand']['name']) . '-' . rand(100, 999), 'status' => 'active']
                    );
                    $brandId = $b->id;
                }

                $productAttributes = [
                    'category_id' => $catId,
                    'brand_id' => $brandId,
                    'name' => $pData['name'],
                    'slug' => Str::slug($pData['name']) . '-' . rand(1000, 9999),
                    'sku' => $sku,
                    'barcode' => $pData['barcode'] ?? null,
                    'hsn_code' => $pData['hsn_code'] ?? null,
                    'product_type' => $pData['product_type'] ?? 'simple',
                    'selling_price' => $pData['selling_price'] ?? 0,
                    'mrp' => $pData['mrp'] ?? $pData['selling_price'] ?? 0,
                    'wholesale_price' => $pData['wholesale_price'] ?? $pData['selling_price'] ?? 0,
                    'cost_price' => $pData['cost_price'] ?? null,
                    'gst_rate' => $pData['gst_rate'] ?? 18,
                    'gst_inclusive' => $pData['gst_inclusive'] ?? true,
                    'unit' => $pData['unit'] ?? 'Piece',
                    'min_order_qty' => $pData['min_order_qty'] ?? 1,
                    'weight' => $pData['weight'] ?? null,
                    'short_description' => $pData['short_description'] ?? null,
                    'description' => $pData['description'] ?? null,
                    'status' => $pData['status'] ?? 'active',
                    'approval_status' => 'approved',
                    'published_at' => now(),
                ];

                if ($existing && $mode === 'overwrite') {
                    $existing->update($productAttributes);
                    $product = $existing;
                } else {
                    $product = $vendor->products()->create($productAttributes);
                }

                // Restore Inventory
                $stockQty = $pData['inventory']['quantity_available'] ?? 50;
                if ($product->inventory) {
                    $product->inventory->update(['quantity_available' => $stockQty]);
                } else {
                    $product->inventory()->create([
                        'quantity_available' => $stockQty,
                        'low_stock_threshold' => 10,
                        'track_inventory' => true,
                    ]);
                }

                // Restore Specifications
                if (!empty($pData['specifications']) && is_array($pData['specifications'])) {
                    $product->specifications()->delete();
                    foreach ($pData['specifications'] as $s) {
                        $product->specifications()->create([
                            'spec_group' => $s['spec_group'] ?? 'General',
                            'spec_key' => $s['spec_key'],
                            'spec_value' => $s['spec_value'],
                            'sort_order' => $s['sort_order'] ?? 0,
                        ]);
                    }
                }

                $restoredCount++;
            }

            DB::commit();
            return back()->with('success', "Restore successful! {$restoredCount} products restored, {$skippedCount} skipped.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Restore failed: ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Manufacturer Multi-Dealer Delivery & F.O.R. Policy Formulation
    |--------------------------------------------------------------------------
    */
    public function logisticsPolicy()
    {
        $vendor = $this->getVendor();
        return view('vendor.products.logistics-policy', compact('vendor'));
    }

    public function brands()
    {
        $vendor = $this->getVendor();
        $brands = Brand::where('status', 'active')->orderBy('name')->paginate(16);
        $myRequests = CatalogRequest::where('vendor_id', $vendor->id)
            ->where('type', 'brand')
            ->latest()
            ->get();
        return view('vendor.products.brands', compact('vendor', 'brands', 'myRequests'));
    }

    public function categories()
    {
        $vendor = $this->getVendor();
        $categories = Category::where('status', 'active')->whereNull('parent_id')->with('children')->orderBy('name')->get();
        $myRequests = CatalogRequest::where('vendor_id', $vendor->id)
            ->whereIn('type', ['category', 'subcategory'])
            ->latest()
            ->get();
        return view('vendor.products.categories', compact('vendor', 'categories', 'myRequests'));
    }

    public function attributes()
    {
        $vendor = $this->getVendor();
        $attributes = Attribute::where('status', 'active')
            ->with(['values' => function ($q) {
                $q->orderBy('sort_order', 'asc');
            }])
            ->orderBy('sort_order')
            ->get();

        $myRequests = CatalogRequest::where('vendor_id', $vendor->id)
            ->where('type', 'attribute_spec')
            ->latest()
            ->get();

        return view('vendor.products.attributes', compact('vendor', 'attributes', 'myRequests'));
    }

    public function storeCatalogRequest(Request $request)
    {
        $vendor = $this->getVendor();

        $request->validate([
            'type'      => 'required|in:attribute_spec,category,subcategory,brand',
            'name'      => 'required|string|max:200',
            'parent_id' => 'nullable|exists:categories,id',
            'values'    => 'nullable|string|max:2000',
            'reason'    => 'required|string|min:5|max:2000',
        ], [
            'reason.required' => 'A valid reason / justification is required explaining why this item is needed.',
            'reason.min'      => 'Please provide a clear justification of at least 5 characters.',
        ]);

        $catalogRequest = CatalogRequest::create([
            'vendor_id' => $vendor->id,
            'type'      => $request->type,
            'name'      => trim($request->name),
            'parent_id' => $request->parent_id ?: null,
            'values'    => $request->values,
            'reason'    => $request->reason,
            'status'    => 'pending',
        ]);

        // Send Notification to Super Admins
        try {
            $typeLabels = [
                'attribute_spec' => 'Specification / Attribute',
                'category'       => 'Material Category',
                'subcategory'    => 'Material Subcategory',
                'brand'          => 'Brand / Manufacturer',
            ];
            $typeName = $typeLabels[$request->type] ?? 'Catalog Item';

            AdminNotificationService::send(
                'catalog_request',
                'New Catalog Request from ' . ($vendor->company_name ?? $vendor->name),
                "Vendor requested new {$typeName}: '{$request->name}' with reason: " . Str::limit($request->reason, 80),
                route('admin.catalog-requests.index', ['type' => $request->type, 'status' => 'pending']),
                'bi-card-checklist'
            );
        } catch (\Throwable $e) {
            // notification fail safe
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_request' => true,
                'message' => "Your request for '{$request->name}' has been submitted to the Super Admin team for verification. Once approved, it will be added to the marketplace catalog.",
                'request_id' => $catalogRequest->id,
                'request_name' => $request->name,
            ]);
        }

        return back()->with('success', "Your request for '{$request->name}' has been submitted to the Admin team for verification. You can track its status below.");
    }
}
