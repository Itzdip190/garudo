<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\CatalogRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AttributeController extends Controller
{
    /**
     * Display listing of all Standard Specifications & Attributes
     */
    public function index(Request $request)
    {
        $search = trim($request->query('search', ''));

        $query = Attribute::with(['values' => function ($q) {
            $q->orderBy('sort_order', 'asc');
        }])->withCount('values');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('display_name', 'like', "%{$search}%")
                  ->orWhereHas('values', function ($vq) use ($search) {
                      $vq->where('value', 'like', "%{$search}%");
                  });
            });
        }

        $attributes = $query->orderBy('sort_order', 'asc')->paginate(12)->withQueryString();

        $stats = [
            'total_attributes' => Attribute::count(),
            'total_values' => AttributeValue::count(),
            'active_attributes' => Attribute::where('status', 'active')->count(),
            'pending_requests' => Schema::hasTable('catalog_requests')
                ? CatalogRequest::where('type', 'attribute_spec')->where('status', 'pending')->count()
                : 0,
        ];

        return view('admin.attributes.index', compact('attributes', 'stats', 'search'));
    }

    /**
     * Store a newly created Standard Specification / Attribute
     */
    public function store(Request $request)
    {
        $request->validate([
            'display_name'  => 'required|string|max:200',
            'type'          => 'required|in:select,radio,color,checkbox,text',
            'values'        => 'required|string',
            'is_filterable' => 'nullable|boolean',
            'is_required'   => 'nullable|boolean',
            'sort_order'    => 'nullable|integer|min:0',
            'status'        => 'required|in:active,inactive',
        ]);

        $baseName = Str::slug($request->display_name, '_');
        $name = $baseName;
        $count = 1;
        while (Attribute::where('name', $name)->exists()) {
            $name = $baseName . '_' . $count++;
        }

        DB::beginTransaction();
        try {
            $attribute = Attribute::create([
                'name'          => $name,
                'display_name'  => $request->display_name,
                'type'          => $request->type,
                'is_filterable' => $request->boolean('is_filterable', true),
                'is_required'   => $request->boolean('is_required', false),
                'sort_order'    => $request->sort_order ?? 0,
                'status'        => $request->status,
            ]);

            // Parse values from comma or newline separated string
            $rawValues = preg_split('/[,\r\n]+/', $request->values);
            $order = 0;
            foreach ($rawValues as $val) {
                $val = trim($val);
                if ($val !== '') {
                    AttributeValue::create([
                        'attribute_id'  => $attribute->id,
                        'value'         => $val,
                        'display_value' => $val,
                        'sort_order'    => $order++,
                        'status'        => 'active',
                    ]);
                }
            }

            DB::commit();
            return back()->with('success', "Standard Specification '{$attribute->display_name}' created successfully with {$order} values.");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create specification: ' . $e->getMessage());
        }
    }

    /**
     * Update an existing Standard Specification / Attribute
     */
    public function update(Request $request, $id)
    {
        $attribute = Attribute::findOrFail($id);

        $request->validate([
            'display_name'  => 'required|string|max:200',
            'type'          => 'required|in:select,radio,color,checkbox,text',
            'values'        => 'required|string',
            'is_filterable' => 'nullable|boolean',
            'is_required'   => 'nullable|boolean',
            'sort_order'    => 'nullable|integer|min:0',
            'status'        => 'required|in:active,inactive',
        ]);

        DB::beginTransaction();
        try {
            $attribute->update([
                'display_name'  => $request->display_name,
                'type'          => $request->type,
                'is_filterable' => $request->boolean('is_filterable'),
                'is_required'   => $request->boolean('is_required'),
                'sort_order'    => $request->sort_order ?? $attribute->sort_order,
                'status'        => $request->status,
            ]);

            // Replace or append values
            $rawValues = preg_split('/[,\r\n]+/', $request->values);
            $parsedValues = [];
            foreach ($rawValues as $val) {
                $trimmed = trim($val);
                if ($trimmed !== '') {
                    $parsedValues[] = $trimmed;
                }
            }

            // Sync values: remove existing and re-insert
            $attribute->values()->delete();
            foreach ($parsedValues as $order => $val) {
                AttributeValue::create([
                    'attribute_id'  => $attribute->id,
                    'value'         => $val,
                    'display_value' => $val,
                    'sort_order'    => $order,
                    'status'        => 'active',
                ]);
            }

            DB::commit();
            return back()->with('success', "Specification '{$attribute->display_name}' updated successfully.");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update specification: ' . $e->getMessage());
        }
    }

    /**
     * Delete an attribute and its values
     */
    public function destroy($id)
    {
        $attribute = Attribute::findOrFail($id);
        $name = $attribute->display_name;

        DB::beginTransaction();
        try {
            $attribute->values()->delete();
            $attribute->delete();

            DB::commit();
            return back()->with('success', "Specification '{$name}' has been deleted.");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete specification: ' . $e->getMessage());
        }
    }
}
