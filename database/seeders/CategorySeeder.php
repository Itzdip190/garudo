<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Cement & Concrete',
                'slug' => 'cement',
                'icon' => 'bi-box',
                'is_featured' => true,
                'show_in_menu' => true,
                'show_in_home' => true,
                'subcategories' => ['OPC 53 Grade', 'PPC Cement', 'White Cement', 'Ready-Mix Concrete', 'Dry Mortar']
            ],
            [
                'name' => 'Steel & TMT Bars',
                'slug' => 'steel',
                'icon' => 'bi-layers',
                'is_featured' => true,
                'show_in_menu' => true,
                'show_in_home' => true,
                'subcategories' => ['Fe 550D TMT', 'Structural Steel', 'MS Angles & Channels', 'Binding Wire', 'Beams & Joists']
            ],
            [
                'name' => 'Tiles, Marble & Granite',
                'slug' => 'tiles',
                'icon' => 'bi-grid',
                'is_featured' => true,
                'show_in_menu' => true,
                'show_in_home' => true,
                'subcategories' => ['Vitrified Tiles', 'Ceramic Wall Tiles', 'Natural Granite Slabs', 'Italian Marble', 'Tile Adhesives']
            ],
            [
                'name' => 'Pipes & Water Fittings',
                'slug' => 'pipes',
                'icon' => 'bi-droplet',
                'is_featured' => true,
                'show_in_menu' => true,
                'show_in_home' => true,
                'subcategories' => ['CPVC Pipes & Fittings', 'UPVC Plumbing', 'SWR Drainage', 'Water Storage Tanks', 'Brass Valves']
            ],
            [
                'name' => 'Electricals & Solar',
                'slug' => 'electricals',
                'icon' => 'bi-lightning-charge',
                'is_featured' => true,
                'show_in_menu' => true,
                'show_in_home' => true,
                'subcategories' => ['Copper Cables & Wires', 'Modular Switches & Sockets', 'Switchgear & MCBs', 'Solar Panels', 'LED Fixtures']
            ],
            [
                'name' => 'Sanitaryware & Bath',
                'slug' => 'sanitaryware',
                'icon' => 'bi-water',
                'is_featured' => true,
                'show_in_menu' => true,
                'show_in_home' => true,
                'subcategories' => ['Wash Basins', 'Water Closets', 'CP Faucets & Taps', 'Shower Panels', 'Bath Accessories']
            ],
            [
                'name' => 'Paints & Waterproofing',
                'slug' => 'paints',
                'icon' => 'bi-paint-bucket',
                'is_featured' => true,
                'show_in_menu' => true,
                'show_in_home' => true,
                'subcategories' => ['Interior Emulsions', 'Exterior Weathercoats', 'Waterproofing Chemicals', 'Wall Primers & Putty', 'Enamel Paints']
            ],
            [
                'name' => 'Tools, Fasteners & Safety',
                'slug' => 'hardware',
                'icon' => 'bi-tools',
                'is_featured' => true,
                'show_in_menu' => true,
                'show_in_home' => true,
                'subcategories' => ['Power Tools', 'Fasteners & Screws', 'Safety Helmets & Vests', 'Welding Equipment', 'Hand Tools']
            ],
            [
                'name' => 'Wood, Timber & Plywood',
                'slug' => 'plywood',
                'icon' => 'bi-tree',
                'is_featured' => true,
                'show_in_menu' => true,
                'show_in_home' => true,
                'subcategories' => ['Marine Plywood', 'Commercial Blockboards', 'Flush Doors', 'Timber Planks', 'Laminates & Veneers']
            ],
            [
                'name' => 'Bricks, Blocks & Aggregates',
                'slug' => 'bricks-blocks',
                'icon' => 'bi-building',
                'is_featured' => true,
                'show_in_menu' => true,
                'show_in_home' => true,
                'subcategories' => ['Red Clay Bricks', 'AAC Lightweight Blocks', 'Concrete Pavers', 'River Sand & Aggregate']
            ],
        ];

        foreach ($categories as $catData) {
            $parent = Category::updateOrCreate(
                ['slug' => Str::slug($catData['name'])],
                [
                    'name'         => $catData['name'],
                    'icon'         => $catData['icon'],
                    'is_featured'  => $catData['is_featured'],
                    'show_in_menu' => $catData['show_in_menu'],
                    'show_in_home' => $catData['show_in_home'],
                    'depth'        => 0,
                    'status'       => 'active',
                ]
            );

            foreach ($catData['subcategories'] as $subName) {
                Category::updateOrCreate(
                    ['slug' => Str::slug($subName)],
                    [
                        'parent_id'    => $parent->id,
                        'name'         => $subName,
                        'depth'        => 1,
                        'status'       => 'active',
                        'show_in_menu' => true,
                    ]
                );
            }
        }
    }
}
