<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::whereNull('parent_id')
            ->with(['children' => function($q) {
                $q->withCount('products');
            }])
            ->withCount('products')
            ->orderBy('sort_order')
            ->get();

        $stats = [
            'total_parents' => Category::whereNull('parent_id')->count(),
            'total_subcategories' => Category::whereNotNull('parent_id')->count(),
            'featured_categories' => Category::where('is_featured', true)->count(),
        ];

        return view('admin.categories.index', compact('categories', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'parent_id' => 'nullable|exists:categories,id',
            'icon' => 'nullable|string|max:50',
            'policy' => 'nullable|string|max:5000',
            'is_featured' => 'nullable|boolean',
            'show_in_menu' => 'nullable|boolean',
            'show_in_home' => 'nullable|boolean',
        ]);

        $slug = Str::slug($request->name);
        $count = Category::where('slug', 'like', "{$slug}%")->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        Category::create([
            'name' => $request->name,
            'slug' => $slug,
            'parent_id' => $request->parent_id ?: null,
            'icon' => $request->icon ?: 'bi-tags',
            'policy' => $request->policy,
            'depth' => $request->parent_id ? 1 : 0,
            'is_featured' => $request->boolean('is_featured'),
            'show_in_menu' => $request->boolean('show_in_menu', true),
            'show_in_home' => $request->boolean('show_in_home', true),
            'status' => 'active',
        ]);

        return back()->with('success', "Category '{$request->name}' created successfully.");
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:150',
            'icon' => 'nullable|string|max:50',
            'policy' => 'nullable|string|max:5000',
            'status' => 'required|in:active,inactive',
        ]);

        $category->update([
            'name' => $request->name,
            'icon' => $request->icon ?: $category->icon,
            'policy' => $request->policy,
            'is_featured' => $request->boolean('is_featured'),
            'show_in_menu' => $request->boolean('show_in_menu'),
            'show_in_home' => $request->boolean('show_in_home'),
            'status' => $request->status,
        ]);

        return back()->with('success', "Category '{$category->name}' updated successfully.");
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $name = $category->name;
        $category->delete();

        return back()->with('success', "Category '{$name}' has been deleted.");
    }
}
