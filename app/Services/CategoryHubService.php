<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class CategoryHubService
{
    /**
     * Get all 16 Category & Subcategory cards matching the storefront visual grid
     */
    public static function getHubCategories(): array
    {
        // 1. Definition of the 16 standard Visual Categories & Subcategories matching Image 1
        $schema = self::getDefaultCategorySchema();

        // 2. Query database parent categories with their active subcategories
        $dbCategories = collect();
        try {
            if (Schema::hasTable('categories')) {
                $dbCategories = Category::whereNull('parent_id')
                    ->with(['children' => function ($q) {
                        $q->where('status', 'active')->orderBy('sort_order', 'asc');
                    }])
                    ->get()
                    ->keyBy(fn($c) => Str::slug($c->name));
            }
        } catch (\Throwable $e) {
            // Keep resilient if database has temporary connection issue
        }

        // 3. Build the 16 hub cards, merging DB data with default visual data
        $hubCards = [];

        foreach ($schema as $catKey => $def) {
            $catSlug = Str::slug($def['title']);
            
            // Check if matching parent category exists in DB
            $dbParent = $dbCategories->get($catSlug) 
                ?: $dbCategories->first(fn($c) => str_contains($catSlug, Str::slug($c->name)) || str_contains(Str::slug($c->name), $catSlug));

            $parentName = $dbParent ? $dbParent->name : $def['title'];
            $parentSlug = $dbParent ? $dbParent->slug : $catSlug;
            $parentIcon = ($dbParent && !empty($dbParent->icon)) ? $dbParent->icon : $def['icon'];
            $viewAllUrl = route('products.index', ['category' => $parentSlug]);

            // Match subcategories
            $subItems = [];
            $dbChildren = $dbParent ? $dbParent->children->keyBy(fn($sc) => Str::slug($sc->name)) : collect();

            foreach ($def['subcategories'] as $subDef) {
                $subSlug = Str::slug($subDef['name']);
                $dbSub = $dbChildren->get($subSlug)
                    ?: $dbChildren->first(fn($sc) => str_contains(Str::slug($sc->name), $subSlug) || str_contains($subSlug, Str::slug($sc->name)));

                $subName = $dbSub ? $dbSub->name : $subDef['name'];
                $finalSubSlug = $dbSub ? $dbSub->slug : $subSlug;
                
                // Priority for Image: 
                // 1) DB subcategory uploaded image (ignoring broken legacy Unsplash links)
                // 2) Default curated visual URL from schema
                $image = $subDef['image'];
                if ($dbSub && !empty($dbSub->image)) {
                    $brokenIds = ['photo-1502005229762', 'photo-1527018606416', 'photo-1541888946425', 'photo-1578873375969', 'photo-1533460004989', 'photo-1581092335397'];
                    $isBroken = false;
                    foreach ($brokenIds as $bId) {
                        if (str_contains($dbSub->image, $bId)) {
                            $isBroken = true;
                            break;
                        }
                    }
                    if (!$isBroken) {
                        $image = str_starts_with($dbSub->image, 'http') || str_starts_with($dbSub->image, '/')
                            ? $dbSub->image 
                            : asset('storage/' . $dbSub->image);
                    }
                }

                $subUrl = $dbSub
                    ? route('products.index', ['category' => $finalSubSlug])
                    : route('products.index', ['category' => $parentSlug, 'search' => $subName]);

                $subItems[] = [
                    'id'       => $dbSub ? $dbSub->id : null,
                    'name'     => $subName,
                    'slug'     => $finalSubSlug,
                    'image'    => $image,
                    'url'      => $subUrl,
                    'is_db'    => (bool) $dbSub,
                ];
            }

            // Append any additional custom subcategories added by admin in DB that weren't in default list
            if ($dbParent) {
                foreach ($dbParent->children as $extraSub) {
                    $extraSlug = Str::slug($extraSub->name);
                    $alreadyExists = collect($subItems)->contains(fn($si) => $si['slug'] === $extraSlug || $si['name'] === $extraSub->name);
                    if (!$alreadyExists) {
                        $subItems[] = [
                            'id'    => $extraSub->id,
                            'name'  => $extraSub->name,
                            'slug'  => $extraSub->slug,
                            'image' => !empty($extraSub->image) 
                                ? (str_starts_with($extraSub->image, 'http') ? $extraSub->image : asset('storage/' . $extraSub->image)) 
                                : asset('images/product-placeholder.png'),
                            'url'   => route('products.index', ['category' => $extraSub->slug]),
                            'is_db' => true,
                        ];
                    }
                }
            }

            $hubCards[] = [
                'id'            => $dbParent ? $dbParent->id : null,
                'title'         => strtoupper($parentName),
                'slug'          => $parentSlug,
                'icon'          => $parentIcon,
                'view_all_url'  => $viewAllUrl,
                'subcategories' => $subItems,
                'is_db'         => (bool) $dbParent,
            ];
        }

        return $hubCards;
    }

    /**
     * Default 16-card Visual Category Hub definition matching Image 1
     */
    public static function getDefaultCategorySchema(): array
    {
        return [
            // 1. STRUCTURAL MATERIALS
            'structural_materials' => [
                'title' => 'STRUCTURAL MATERIALS',
                'icon'  => 'bi-building',
                'subcategories' => [
                    ['name' => 'Cement', 'image' => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=200&auto=format&fit=crop&q=80'],
                    ['name' => 'TMT Bars', 'image' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=200&auto=format&fit=crop&q=80'],
                    ['name' => 'AAC Blocks', 'image' => 'https://images.unsplash.com/photo-1590069261209-f8e9b8642343?w=200&auto=format&fit=crop&q=80'],
                    ['name' => 'Bricks', 'image' => 'https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=200&auto=format&fit=crop&q=80'],
                    ['name' => 'Fly Ash Bricks', 'image' => 'https://images.unsplash.com/photo-1518640467707-6811f4a6ab73?w=200&auto=format&fit=crop&q=80'],
                    ['name' => 'Concrete Blocks', 'image' => 'https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=300&auto=format&fit=crop&q=80'],
                ]
            ],

            // 2. PLUMBING MATERIALS
            'plumbing_materials' => [
                'title' => 'PLUMBING MATERIALS',
                'icon'  => 'bi-droplet',
                'subcategories' => [
                    ['name' => 'CPVC Pipes', 'image' => 'https://images.unsplash.com/photo-1607472586893-edb57bdc0e39?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'UPVC Pipes', 'image' => 'https://images.unsplash.com/photo-1585704032915-c3400ca199e7?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Fittings', 'image' => 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Sanitary Ware', 'image' => 'https://images.unsplash.com/photo-1585060544812-6b45742d762f?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Water Tanks', 'image' => 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Valves', 'image' => 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?w=300&auto=format&fit=crop&q=80'],
                ]
            ],

            // 3. ELECTRICAL MATERIALS
            'electrical_materials' => [
                'title' => 'ELECTRICAL MATERIALS',
                'icon'  => 'bi-lightning-charge',
                'subcategories' => [
                    ['name' => 'Wires & Cables', 'image' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Switches', 'image' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'MCB & DB', 'image' => 'https://images.unsplash.com/photo-1581092580497-e0d23cbdf1dc?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'LED Lights', 'image' => 'https://images.unsplash.com/photo-1513506003901-1e6a229e2d15?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Fans', 'image' => 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Conduits & Acc.', 'image' => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=300&auto=format&fit=crop&q=80'],
                ]
            ],

            // 4. PAINTS & COATINGS
            'paints_coatings' => [
                'title' => 'PAINTS & COATINGS',
                'icon'  => 'bi-paint-bucket',
                'subcategories' => [
                    ['name' => 'Interior Paints', 'image' => 'https://images.unsplash.com/photo-1562259949-e8e7689d7828?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Exterior Paints', 'image' => 'https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Waterproofing', 'image' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Wood Coatings', 'image' => 'https://images.unsplash.com/photo-1513694203232-719a280e022f?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Primers', 'image' => 'https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Wall Putty', 'image' => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=300&auto=format&fit=crop&q=80'],
                ]
            ],

            // 5. FLOORING MATERIALS
            'flooring_materials' => [
                'title' => 'FLOORING MATERIALS',
                'icon'  => 'bi-grid',
                'subcategories' => [
                    ['name' => 'Tiles', 'image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Vitrified Tiles', 'image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Marble', 'image' => 'https://images.unsplash.com/photo-1600566753376-12c8ab7fb75b?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Wooden Flooring', 'image' => 'https://images.unsplash.com/photo-1513694203232-719a280e022f?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Laminate Flooring', 'image' => 'https://images.unsplash.com/photo-1581858726788-75bc0f6a952d?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Granite', 'image' => 'https://images.unsplash.com/photo-1590069261209-f8e9b8642343?w=300&auto=format&fit=crop&q=80'],
                ]
            ],

            // 6. ROOFING SOLUTIONS
            'roofing_solutions' => [
                'title' => 'ROOFING SOLUTIONS',
                'icon'  => 'bi-house-door',
                'subcategories' => [
                    ['name' => 'Clay Roof Tiles', 'image' => 'https://images.unsplash.com/photo-1513694203232-719a280e022f?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Metal Roofing Sheets', 'image' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Polycarbonate Sheets', 'image' => 'https://images.unsplash.com/photo-1518640467707-6811f4a6ab73?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'PUF Panels', 'image' => 'https://images.unsplash.com/photo-1590069261209-f8e9b8642343?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Roof Accessories', 'image' => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Waterproof Membrane', 'image' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=300&auto=format&fit=crop&q=80'],
                ]
            ],

            // 7. CLADDING & ELEVATION
            'cladding_elevation' => [
                'title' => 'CLADDING & ELEVATION',
                'icon'  => 'bi-layers',
                'subcategories' => [
                    ['name' => 'ACP Sheets', 'image' => 'https://images.unsplash.com/photo-1513694203232-719a280e022f?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'HPL Panels', 'image' => 'https://images.unsplash.com/photo-1581858726788-75bc0f6a952d?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Stone Cladding', 'image' => 'https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'WPC Louvers', 'image' => 'https://images.unsplash.com/photo-1513694203232-719a280e022f?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'MS Railings', 'image' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Aluminium Cladding', 'image' => 'https://images.unsplash.com/photo-1590069261209-f8e9b8642343?w=300&auto=format&fit=crop&q=80'],
                ]
            ],

            // 8. FALSE CEILING
            'false_ceiling' => [
                'title' => 'FALSE CEILING',
                'icon'  => 'bi-aspect-ratio',
                'subcategories' => [
                    ['name' => 'Gypsum Boards', 'image' => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'PVC Panels', 'image' => 'https://images.unsplash.com/photo-1607472586893-edb57bdc0e39?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Metal Ceiling', 'image' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Ceiling Tiles', 'image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Grid Systems', 'image' => 'https://images.unsplash.com/photo-1581092580497-e0d23cbdf1dc?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Accessories', 'image' => 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?w=300&auto=format&fit=crop&q=80'],
                ]
            ],

            // 9. INTERIOR & FURNITURE
            'interior_furniture' => [
                'title' => 'INTERIOR & FURNITURE',
                'icon'  => 'bi-lamp',
                'subcategories' => [
                    ['name' => 'Modular Kitchen', 'image' => 'https://images.unsplash.com/photo-1556911220-e15b29be8c8f?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Wardrobes', 'image' => 'https://images.unsplash.com/photo-1595428774223-ef52624120d2?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Sofas', 'image' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Tables & Chairs', 'image' => 'https://images.unsplash.com/photo-1530018607912-eff2daa1bac4?w=300&auto=format&fit=crop&q=80'],
                ]
            ],

            // 10. DOORS, WINDOWS & GLASS
            'doors_windows_glass' => [
                'title' => 'DOORS, WINDOWS & GLASS',
                'icon'  => 'bi-door-open',
                'subcategories' => [
                    ['name' => 'Doors', 'image' => 'https://images.unsplash.com/photo-1513694203232-719a280e022f?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Windows', 'image' => 'https://images.unsplash.com/photo-1509644851169-2acc08aa25b5?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Aluminium Systems', 'image' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Glass', 'image' => 'https://images.unsplash.com/photo-1513506003901-1e6a229e2d15?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Hardware', 'image' => 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Locks', 'image' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=300&auto=format&fit=crop&q=80'],
                ]
            ],

            // 11. BREEZE BLOCKS & JALI
            'breeze_blocks_jali' => [
                'title' => 'BREEZE BLOCKS & JALI',
                'icon'  => 'bi-grid-3x3',
                'subcategories' => [
                    ['name' => 'Breeze Blocks', 'image' => 'https://images.unsplash.com/photo-1590069261209-f8e9b8642343?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Terracotta Jali', 'image' => 'https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Cement Jali', 'image' => 'https://images.unsplash.com/photo-1518640467707-6811f4a6ab73?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Decorative Blocks', 'image' => 'https://images.unsplash.com/photo-1518640467707-6811f4a6ab73?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Ventilation Blocks', 'image' => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Designer Blocks', 'image' => 'https://images.unsplash.com/photo-1590069261209-f8e9b8642343?w=300&auto=format&fit=crop&q=80'],
                ]
            ],

            // 12. CHEMICALS & ADHESIVES
            'chemicals_adhesives' => [
                'title' => 'CHEMICALS & ADHESIVES',
                'icon'  => 'bi-capsule',
                'subcategories' => [
                    ['name' => 'Tile Adhesives', 'image' => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Grouts', 'image' => 'https://images.unsplash.com/photo-1562259949-e8e7689d7828?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Sealants', 'image' => 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Waterproofing', 'image' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Flooring Chemicals', 'image' => 'https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=300&auto=format&fit=crop&q=80'],
                ]
            ],

            // 13. PEST CONTROL
            'pest_control' => [
                'title' => 'PEST CONTROL',
                'icon'  => 'bi-shield-check',
                'subcategories' => [
                    ['name' => 'Termite Control', 'image' => 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Cockroach Control', 'image' => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Mosquito Control', 'image' => 'https://images.unsplash.com/photo-1562259949-e8e7689d7828?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Rodent Control', 'image' => 'https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Spray Equipment', 'image' => 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?w=300&auto=format&fit=crop&q=80'],
                ]
            ],

            // 14. TOOLS & HARDWARE
            'tools_hardware' => [
                'title' => 'TOOLS & HARDWARE',
                'icon'  => 'bi-tools',
                'subcategories' => [
                    ['name' => 'Power Tools', 'image' => 'https://images.unsplash.com/photo-1504148455328-c376907d081c?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Hand Tools', 'image' => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Measuring Tools', 'image' => 'https://images.unsplash.com/photo-1581092580497-e0d23cbdf1dc?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Ladders', 'image' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Tool Kits', 'image' => 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?w=300&auto=format&fit=crop&q=80'],
                ]
            ],

            // 15. SAFETY & PROTECTION
            'safety_protection' => [
                'title' => 'SAFETY & PROTECTION',
                'icon'  => 'bi-shield-shaded',
                'subcategories' => [
                    ['name' => 'Safety Helmets', 'image' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Safety Shoes', 'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Gloves', 'image' => 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Reflective Jackets', 'image' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Masks', 'image' => 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'First Aid Kits', 'image' => 'https://images.unsplash.com/photo-1603398938378-e54eab446dde?w=300&auto=format&fit=crop&q=80'],
                ]
            ],

            // 16. EXTERIOR & LANDSCAPING
            'exterior_landscaping' => [
                'title' => 'EXTERIOR & LANDSCAPING',
                'icon'  => 'bi-tree',
                'subcategories' => [
                    ['name' => 'Garden Furniture', 'image' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Artificial Grass', 'image' => 'https://images.unsplash.com/photo-1518640467707-6811f4a6ab73?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Pavers', 'image' => 'https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Planters', 'image' => 'https://images.unsplash.com/photo-1485955900006-10f4d324d411?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Boundary Walls', 'image' => 'https://images.unsplash.com/photo-1590069261209-f8e9b8642343?w=300&auto=format&fit=crop&q=80'],
                    ['name' => 'Outdoor Lights', 'image' => 'https://images.unsplash.com/photo-1513506003901-1e6a229e2d15?w=300&auto=format&fit=crop&q=80'],
                ]
            ],
        ];
    }

    /**
     * Ensure these 16 categories and their subcategories exist in the database
     */
    public static function seedTaxonomyToDatabase(): void
    {
        $schema = self::getDefaultCategorySchema();
        $order = 1;

        foreach ($schema as $catKey => $def) {
            $slug = Str::slug($def['title']);
            $parent = Category::firstOrCreate(
                ['slug' => $slug],
                [
                    'name'         => ucwords(strtolower($def['title'])),
                    'icon'         => $def['icon'],
                    'show_in_menu' => true,
                    'show_in_home' => true,
                    'is_featured'  => true,
                    'depth'        => 0,
                    'sort_order'   => $order++,
                    'status'       => 'active',
                ]
            );

            $subOrder = 1;
            foreach ($def['subcategories'] as $sub) {
                $subSlug = Str::slug($parent->slug . '-' . $sub['name']);
                $subCat = Category::where('slug', $subSlug)->first();
                if ($subCat) {
                    $subCat->update([
                        'image'        => $sub['image'],
                        'show_in_menu' => true,
                        'depth'        => 1,
                        'status'       => 'active',
                    ]);
                } else {
                    Category::create([
                        'slug'         => $subSlug,
                        'parent_id'    => $parent->id,
                        'name'         => $sub['name'],
                        'image'        => $sub['image'],
                        'show_in_menu' => true,
                        'depth'        => 1,
                        'sort_order'   => $subOrder++,
                        'status'       => 'active',
                    ]);
                }
            }
        }
    }
}
