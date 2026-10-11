<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BrandTitle;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandTitleController extends Controller
{
    /**
     * List all Brand Titles / Tiers
     */
    public function index()
    {
        $titles = BrandTitle::withCount('brands')->orderBy('sort_order')->orderBy('name')->get();
        return view('admin.brands.titles.index', compact('titles'));
    }

    /**
     * Store new Brand Title
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:150|unique:brand_titles,name',
            'badge_color'    => 'required|string|in:success,primary,warning,info,purple,gold,dark',
            'description'    => 'nullable|string|max:500',
            'is_parent_tier' => 'nullable|boolean',
            'sort_order'     => 'nullable|integer|min:0',
        ]);

        $slug = Str::slug($validated['name']);
        $count = 1;
        while (BrandTitle::where('slug', $slug)->exists()) {
            $slug = Str::slug($validated['name']) . '-' . $count++;
        }

        $title = BrandTitle::create([
            'name'           => $validated['name'],
            'slug'           => $slug,
            'badge_color'    => $validated['badge_color'],
            'description'    => $validated['description'] ?? null,
            'is_parent_tier' => $request->boolean('is_parent_tier', false),
            'sort_order'     => $validated['sort_order'] ?? 0,
            'status'         => 'active',
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Brand Title '{$title->name}' created successfully!",
                'title'   => $title,
            ]);
        }

        return back()->with('success', "Brand Title '{$title->name}' created successfully!");
    }

    /**
     * AJAX Quick Store directly from Brand Create/Edit form
     */
    public function quickStore(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:150|unique:brand_titles,name',
            'badge_color'    => 'required|string|in:success,primary,warning,info,purple,gold,dark',
            'is_parent_tier' => 'nullable|boolean',
        ]);

        $slug = Str::slug($validated['name']);
        $count = 1;
        while (BrandTitle::where('slug', $slug)->exists()) {
            $slug = Str::slug($validated['name']) . '-' . $count++;
        }

        $title = BrandTitle::create([
            'name'           => $validated['name'],
            'slug'           => $slug,
            'badge_color'    => $validated['badge_color'],
            'is_parent_tier' => $request->boolean('is_parent_tier', false),
            'status'         => 'active',
        ]);

        return response()->json([
            'success' => true,
            'message' => "Brand Title '{$title->name}' added!",
            'title'   => [
                'id'             => $title->id,
                'name'           => $title->name,
                'slug'           => $title->slug,
                'badge_color'    => $title->badge_color,
                'is_parent_tier' => (bool) $title->is_parent_tier,
            ],
        ]);
    }

    /**
     * Update an existing Brand Title
     */
    public function update(Request $request, $id)
    {
        $title = BrandTitle::findOrFail($id);

        $validated = $request->validate([
            'name'           => 'required|string|max:150|unique:brand_titles,name,' . $title->id,
            'badge_color'    => 'required|string|in:success,primary,warning,info,purple,gold,dark',
            'description'    => 'nullable|string|max:500',
            'is_parent_tier' => 'nullable|boolean',
            'sort_order'     => 'nullable|integer|min:0',
            'status'         => 'required|in:active,inactive',
        ]);

        $title->update([
            'name'           => $validated['name'],
            'slug'           => Str::slug($validated['name']),
            'badge_color'    => $validated['badge_color'],
            'description'    => $validated['description'] ?? null,
            'is_parent_tier' => $request->boolean('is_parent_tier', false),
            'sort_order'     => $validated['sort_order'] ?? 0,
            'status'         => $validated['status'],
        ]);

        return back()->with('success', "Brand Title '{$title->name}' updated successfully!");
    }

    /**
     * Delete a Brand Title safely
     */
    public function destroy($id)
    {
        $title = BrandTitle::withCount('brands')->findOrFail($id);

        if ($title->brands_count > 0) {
            return back()->with('error', "Cannot delete title '{$title->name}' because {$title->brands_count} brands are currently assigned to it.");
        }

        $title->delete();

        return back()->with('success', "Brand Title deleted successfully.");
    }
}
