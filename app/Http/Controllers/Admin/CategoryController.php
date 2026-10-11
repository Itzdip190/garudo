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

        // 16 Visual Category & Subcategory Hub live view data
        $visualCategoryHub = \App\Services\CategoryHubService::getHubCategories();

        return view('admin.categories.index', compact('categories', 'stats', 'visualCategoryHub'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'parent_id' => 'nullable|exists:categories,id',
            'icon' => 'nullable|string|max:50',
            'image' => 'nullable|file|image|max:3072',
            'image_url' => 'nullable|string|max:500',
            'policy' => 'nullable|string|max:5000',
            'is_featured' => 'nullable|boolean',
            'show_in_menu' => 'nullable|boolean',
            'show_in_home' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $slug = Str::slug($request->name);
        $count = Category::where('slug', 'like', "{$slug}%")->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $uploadDir = public_path('uploads/categories');
            if (!file_exists($uploadDir)) {
                @mkdir($uploadDir, 0755, true);
            }
            $file = $request->file('image');
            $filename = 'cat_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $filename);
            $imagePath = '/uploads/categories/' . $filename;
        } elseif ($request->filled('image_url')) {
            $imagePath = $request->input('image_url');
        }

        Category::create([
            'name' => $request->name,
            'slug' => $slug,
            'parent_id' => $request->parent_id ?: null,
            'icon' => $request->icon ?: 'bi-tags',
            'image' => $imagePath,
            'policy' => $request->policy,
            'depth' => $request->parent_id ? 1 : 0,
            'is_featured' => $request->boolean('is_featured'),
            'show_in_menu' => $request->boolean('show_in_menu', true),
            'show_in_home' => $request->boolean('show_in_home', true),
            'sort_order' => $request->input('sort_order', 0),
            'status' => 'active',
        ]);

        $typeLabel = $request->parent_id ? 'Subcategory' : 'Parent Category';
        return back()->with('success', "{$typeLabel} '{$request->name}' created successfully.");
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:150',
            'parent_id' => 'nullable|exists:categories,id',
            'icon' => 'nullable|string|max:50',
            'image' => 'nullable|file|image|max:3072',
            'image_url' => 'nullable|string|max:500',
            'policy' => 'nullable|string|max:5000',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('image')) {
            $uploadDir = public_path('uploads/categories');
            if (!file_exists($uploadDir)) {
                @mkdir($uploadDir, 0755, true);
            }
            $file = $request->file('image');
            $filename = 'cat_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $filename);
            $category->image = '/uploads/categories/' . $filename;
        } elseif ($request->filled('image_url')) {
            $category->image = $request->input('image_url');
        }

        if ($request->has('parent_id') && $request->parent_id != $category->id) {
            $category->parent_id = $request->parent_id ?: null;
            $category->depth = $request->parent_id ? 1 : 0;
        }

        $category->name = $request->name;
        $category->icon = $request->icon ?: $category->icon;
        $category->policy = $request->policy;
        $category->is_featured = $request->boolean('is_featured');
        $category->show_in_menu = $request->boolean('show_in_menu');
        $category->show_in_home = $request->boolean('show_in_home');
        $category->sort_order = $request->input('sort_order', $category->sort_order ?? 0);
        $category->status = $request->status;
        $category->save();

        return back()->with('success', "Category '{$category->name}' updated successfully.");
    }

    public function toggleFeatured($id)
    {
        $category = Category::findOrFail($id);
        $category->is_featured = !$category->is_featured;
        $category->show_in_home = $category->is_featured;
        $category->save();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'is_featured' => $category->is_featured,
                'message' => "Category '{$category->name}' homepage showcase updated."
            ]);
        }

        return back()->with('success', "Category '{$category->name}' homepage visibility updated.");
    }

    public function updateHomepageOrder(Request $request)
    {
        $orders = $request->input('orders', []);
        if (is_array($orders)) {
            foreach ($orders as $item) {
                if (isset($item['id']) && isset($item['sort_order'])) {
                    Category::where('id', $item['id'])->update(['sort_order' => (int)$item['sort_order']]);
                }
            }
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Homepage category display order updated successfully.']);
        }

        return back()->with('success', 'Homepage category display order updated successfully.');
    }

    public function syncHub()
    {
        try {
            \App\Services\CategoryHubService::seedTaxonomyToDatabase();
            return back()->with('success', '16 Visual Category & Subcategory hub cards synced to database successfully.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Error syncing hub categories: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $name = $category->name;
        $category->delete();

        return back()->with('success', "Category '{$name}' has been deleted.");
    }
}
