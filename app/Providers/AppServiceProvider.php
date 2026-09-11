<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(
            \App\Events\VendorVerifiedEvent::class,
            \App\Listeners\GenerateVendorCertificateListener::class
        );

        // Share dynamic categories with all frontend views
        View::composer(['layouts.frontend', 'frontend.*'], function ($view) {
            try {
                if (Schema::hasTable('categories')) {
                    $defaultCategories = [
                        [
                            'name' => 'Cement & Concrete',
                            'slug' => 'cement',
                            'icon' => 'bi-box',
                            'subcategories' => ['OPC 53 Grade', 'PPC Cement', 'White Cement', 'Ready-Mix Concrete']
                        ],
                        [
                            'name' => 'Steel & TMT Bars',
                            'slug' => 'steel',
                            'icon' => 'bi-layers',
                            'subcategories' => ['Fe 550D TMT', 'Structural Steel', 'MS Angles & Channels', 'Binding Wire']
                        ],
                        [
                            'name' => 'Tiles, Marble & Granite',
                            'slug' => 'tiles',
                            'icon' => 'bi-grid',
                            'subcategories' => ['Vitrified Tiles', 'Ceramic Wall Tiles', 'Natural Granite Slabs', 'Italian Marble']
                        ],
                        [
                            'name' => 'Pipes & Water Fittings',
                            'slug' => 'pipes',
                            'icon' => 'bi-droplet',
                            'subcategories' => ['CPVC Pipes & Fittings', 'UPVC Plumbing', 'SWR Drainage', 'Water Storage Tanks']
                        ],
                        [
                            'name' => 'Electricals & Solar',
                            'slug' => 'electricals',
                            'icon' => 'bi-lightning-charge',
                            'subcategories' => ['Cables & Wires', 'Switches & Sockets', 'Switchgear & MCBs', 'Solar Panels']
                        ],
                        [
                            'name' => 'Sanitaryware & Bath',
                            'slug' => 'sanitaryware',
                            'icon' => 'bi-water',
                            'subcategories' => ['Wash Basins', 'Water Closets', 'CP Faucets & Taps', 'Shower Panels']
                        ],
                        [
                            'name' => 'Paints & Waterproofing',
                            'slug' => 'paints',
                            'icon' => 'bi-paint-bucket',
                            'subcategories' => ['Interior Emulsions', 'Exterior Paints', 'Waterproofing Chemicals', 'Primers & Putty']
                        ],
                        [
                            'name' => 'Tools, Fasteners & Safety',
                            'slug' => 'hardware',
                            'icon' => 'bi-tools',
                            'subcategories' => ['Power Tools', 'Fasteners & Screws', 'Safety Helmets & Vests', 'Hand Tools']
                        ],
                        [
                            'name' => 'Wood, Timber & Plywood',
                            'slug' => 'plywood',
                            'icon' => 'bi-tree',
                            'subcategories' => ['Marine Plywood', 'Commercial Blockboards', 'Flush Doors', 'Timber Planks']
                        ],
                    ];

                    // If no top-level categories exist, seed defaults into main category
                    if (Category::whereNull('parent_id')->count() === 0) {
                        foreach ($defaultCategories as $idx => $cat) {
                            $parent = Category::create([
                                'name'         => $cat['name'],
                                'slug'         => $cat['slug'],
                                'icon'         => $cat['icon'],
                                'depth'        => 0,
                                'parent_id'    => null,
                                'sort_order'   => $idx + 1,
                                'status'       => 'active',
                                'show_in_menu' => true,
                                'show_in_home' => true,
                            ]);

                            foreach ($cat['subcategories'] as $subIdx => $subName) {
                                Category::create([
                                    'name'         => $subName,
                                    'slug'         => \Illuminate\Support\Str::slug($subName),
                                    'icon'         => 'bi-dot',
                                    'depth'        => 1,
                                    'parent_id'    => $parent->id,
                                    'sort_order'   => $subIdx + 1,
                                    'status'       => 'active',
                                    'show_in_menu' => true,
                                    'show_in_home' => true,
                                ]);
                            }
                        }
                    }

                    $headerCategories = Category::whereNull('parent_id')
                        ->where('status', 'active')
                        ->with(['children' => function ($q) {
                            $q->where('status', 'active')->orderBy('sort_order', 'asc');
                        }])
                        ->withCount('products')
                        ->orderBy('sort_order', 'asc')
                        ->orderBy('name', 'asc')
                        ->get();
                } else {
                    $headerCategories = collect();
                }
            } catch (\Throwable $e) {
                $headerCategories = collect();
            }

            $view->with('headerCategories', $headerCategories);
        });
    }
}

