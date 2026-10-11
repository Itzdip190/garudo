<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Banner;
use App\Models\QuickPromoCard;
use App\Models\HomepageSetting;
use Database\Seeders\HomepageProductSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class HomepageController extends Controller
{
    /**
     * Display the Homepage Control Center
     */
    public function index(Request $request)
    {
        $this->ensureSectionColumnsExist();

        // 1. Stats Counters for All Product Sections + Banners + Quick Promo Cards
        $stats = [
            'is_trending_now'  => Schema::hasColumn('products', 'is_trending_now') ? Product::where('is_trending_now', true)->count() : 0,
            'quick_promos'     => Schema::hasTable('quick_promo_cards') ? QuickPromoCard::count() : 0,
            'sec1_trending'    => Schema::hasColumn('products', 'sec1_trending') ? Product::where('sec1_trending', true)->count() : Product::where('is_featured', true)->count(),
            'sec2_best_seller' => Schema::hasColumn('products', 'sec2_best_seller') ? Product::where('sec2_best_seller', true)->count() : Product::where('is_deal_of_the_day', true)->count(),
            'sec3_trending'    => Schema::hasColumn('products', 'sec3_trending') ? Product::where('sec3_trending', true)->count() : Product::where('is_trending', true)->count(),
            'sec4_best_seller' => Schema::hasColumn('products', 'sec4_best_seller') ? Product::where('sec4_best_seller', true)->count() : Product::where('is_best_seller', true)->count(),
            'banners'          => Schema::hasTable('banners') ? Banner::count() : 0,
            'is_new_arrival'   => Schema::hasColumn('products', 'is_new_arrival') ? Product::where('is_new_arrival', true)->count() : 0,
            'total_active'     => Product::where('status', 'active')->where('approval_status', 'approved')->count(),
        ];

        // 2. Catalog Products Query with Search & Section Filter
        $productQuery = Product::with(['category', 'vendor', 'primaryImage']);

        if ($request->filled('search')) {
            $search = $request->search;
            $productQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhereHas('category', fn($cq) => $cq->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('category_id')) {
            $productQuery->where('category_id', $request->category_id);
        }

        if ($request->filled('section')) {
            match ($request->section) {
                'trending_now' => $productQuery->where('is_trending_now', true),
                'sec1'         => $productQuery->where('sec1_trending', true),
                'sec2'         => $productQuery->where('sec2_best_seller', true),
                'sec3'         => $productQuery->where('sec3_trending', true),
                'sec4'         => $productQuery->where('sec4_best_seller', true),
                'trending'     => $productQuery->where('is_trending', true),
                'best_seller'  => $productQuery->where('is_best_seller', true),
                'featured'     => $productQuery->where('is_featured', true),
                'new_arrival'  => $productQuery->where('is_new_arrival', true),
                default        => null,
            };
        }

        $catalogProducts = $productQuery->latest()->paginate(20)->withQueryString();

        // 3. Quick Action Promo Cards (4 Cards above Trending Now)
        $quickPromoCards = collect();
        if (Schema::hasTable('quick_promo_cards')) {
            $quickPromoCards = QuickPromoCard::orderBy('sort_order', 'asc')->get();
        }

        // 4. Homepage Settings (Trending Now heading, button, link)
        $homepageSettings = [
            'trending_now_title'        => HomepageSetting::get('trending_now_title', 'TRENDING NOW'),
            'trending_now_view_all_text' => HomepageSetting::get('trending_now_view_all_text', 'View All →'),
            'trending_now_view_all_url'  => HomepageSetting::get('trending_now_view_all_url', '/products?filter=trending'),
            'trending_now_active'       => HomepageSetting::get('trending_now_active', '1'),
        ];

        // 5. Banners & Promotional Ads (Row 1 and Row 2 Sections)
        $banners = collect();
        $bannersRow1 = collect();
        $bannersRow2 = collect();
        if (Schema::hasTable('banners')) {
            if (Schema::hasColumn('banners', 'section_row')) {
                $bannersRow1 = Banner::where(function ($q) {
                    $q->where('section_row', 1)->orWhereNull('section_row');
                })->orderBy('sort_order', 'asc')->get();

                $bannersRow2 = Banner::where('section_row', 2)
                    ->orderBy('sort_order', 'asc')->get();
            } else {
                $allBanners = Banner::orderBy('sort_order', 'asc')->latest()->get();
                $bannersRow1 = $allBanners->take(3);
                $bannersRow2 = $allBanners->skip(3)->take(3);
            }
            $banners = Banner::orderBy('sort_order', 'asc')->latest()->get();
        }

        // 6. Categories for homepage filter pills
        $categories = Category::whereNull('parent_id')->orderBy('name', 'asc')->get();

        // 7. Hero Special Cards Settings (Image 3 Section)
        $heroCardSettings = [
            // Card 3: Bulk Order Special
            'hero_bulk_badge'     => HomepageSetting::get('hero_bulk_badge', 'B2B WHOLESALE'),
            'hero_bulk_min'       => HomepageSetting::get('hero_bulk_min', 'Min. ₹50K+'),
            'hero_bulk_title'     => HomepageSetting::get('hero_bulk_title', 'BULK ORDER SPECIAL'),
            'hero_bulk_subhead'   => HomepageSetting::get('hero_bulk_subhead', 'Save Up To 40% On Factory Loads'),
            'hero_bulk_desc'      => HomepageSetting::get('hero_bulk_desc', 'Direct wholesale rates for commercial contractors & builders.'),
            'hero_bulk_features'  => HomepageSetting::get('hero_bulk_features', "Dedicated Account Manager\nPriority Fleet Dispatch\nCustom GST Quotations\nFlexible Net-30 Terms"),
            'hero_bulk_btn_text'  => HomepageSetting::get('hero_bulk_btn_text', 'Request Bulk Quote'),
            'hero_bulk_btn_url'   => HomepageSetting::get('hero_bulk_btn_url', '/quotations'),
            'hero_bulk_image'     => HomepageSetting::get('hero_bulk_image', asset('images/hero/bulk_boxes.png')),

            // Card 4: Auction Zone
            'hero_auction_badge'    => HomepageSetting::get('hero_auction_badge', 'LIVE AUCTIONS'),
            'hero_auction_discount' => HomepageSetting::get('hero_auction_discount', 'Up To 60% OFF'),
            'hero_auction_title'    => HomepageSetting::get('hero_auction_title', 'AUCTION ZONE'),
            'hero_auction_subhead'  => HomepageSetting::get('hero_auction_subhead', 'Bid & Save On Surplus Materials'),
            'hero_auction_desc'     => HomepageSetting::get('hero_auction_desc', 'Acquire surplus steel, tiles, electricals & plant equipment at floor rates.'),
            'hero_auction_features' => HomepageSetting::get('hero_auction_features', "Real-Time Live Bidding\n100% Verified Quality Lots\nEscrow Protected Checkout"),
            'hero_auction_btn_text' => HomepageSetting::get('hero_auction_btn_text', 'Explore Auctions'),
            'hero_auction_btn_url'  => HomepageSetting::get('hero_auction_btn_url', '/products?filter=trending'),
            'hero_auction_image'    => HomepageSetting::get('hero_auction_image', asset('images/hero/auction_gavel.png')),
        ];

        // 8. New Arrivals Showcase Settings (Image 3 Section)
        $newArrivalsSettings = [
            'card1_title' => HomepageSetting::get('na_card1_title', 'Smart Lock Door Handle'),
            'card1_price' => HomepageSetting::get('na_card1_price', '2,450'),
            'card1_unit'  => HomepageSetting::get('na_card1_unit', ''),
            'card1_url'   => HomepageSetting::get('na_card1_url', '/products?search=smart+lock'),
            'card1_image' => HomepageSetting::get('na_card1_image', asset('images/new_arrivals/smart_lock.jpg')),

            'card2_title' => HomepageSetting::get('na_card2_title', 'WPC Wall Panel'),
            'card2_price' => HomepageSetting::get('na_card2_price', '320'),
            'card2_unit'  => HomepageSetting::get('na_card2_unit', 'Sq.Ft.'),
            'card2_url'   => HomepageSetting::get('na_card2_url', '/products?search=wpc+wall+panel'),
            'card2_image' => HomepageSetting::get('na_card2_image', asset('images/new_arrivals/wpc_panel.jpg')),

            'card3_title' => HomepageSetting::get('na_card3_title', 'Designer Basin'),
            'card3_price' => HomepageSetting::get('na_card3_price', '3,980'),
            'card3_unit'  => HomepageSetting::get('na_card3_unit', ''),
            'card3_url'   => HomepageSetting::get('na_card3_url', '/products?search=designer+basin'),
            'card3_image' => HomepageSetting::get('na_card3_image', asset('images/new_arrivals/designer_basin.jpg')),

            'card4_title' => HomepageSetting::get('na_card4_title', 'False Ceiling Grid'),
            'card4_price' => HomepageSetting::get('na_card4_price', '65'),
            'card4_unit'  => HomepageSetting::get('na_card4_unit', 'Sq.Ft.'),
            'card4_url'   => HomepageSetting::get('na_card4_url', '/products?search=false+ceiling'),
            'card4_image' => HomepageSetting::get('na_card4_image', asset('images/new_arrivals/false_ceiling.jpg')),

            'card5_title' => HomepageSetting::get('na_card5_title', 'Epoxy Flooring'),
            'card5_price' => HomepageSetting::get('na_card5_price', '120'),
            'card5_unit'  => HomepageSetting::get('na_card5_unit', 'Sq.Ft.'),
            'card5_url'   => HomepageSetting::get('na_card5_url', '/products?search=epoxy+flooring'),
            'card5_image' => HomepageSetting::get('na_card5_image', asset('images/new_arrivals/epoxy_flooring.jpg')),

            'card6_title' => HomepageSetting::get('na_card6_title', 'Exterior Cladding Tile'),
            'card6_price' => HomepageSetting::get('na_card6_price', '65'),
            'card6_unit'  => HomepageSetting::get('na_card6_unit', 'Sq.Ft.'),
            'card6_url'   => HomepageSetting::get('na_card6_url', '/products?search=cladding+tile'),
            'card6_image' => HomepageSetting::get('na_card6_image', asset('images/new_arrivals/stone_cladding.jpg')),

            'why_choose_title'    => HomepageSetting::get('na_why_choose_title', 'Why Choose Garudo?'),
            'why_choose_features' => HomepageSetting::get('na_why_choose_features', "10,000+ Verified Products\nPan India Delivery Network\n1000+ Verified Vendors\nSecure Payments & GST\nBest Price Guarantee\nExpert Support 24/7"),

            'stat1_number' => HomepageSetting::get('na_stat1_number', '25K+'),
            'stat1_label'  => HomepageSetting::get('na_stat1_label', 'Happy Customers'),
            'stat2_number' => HomepageSetting::get('na_stat2_number', '10K+'),
            'stat2_label'  => HomepageSetting::get('na_stat2_label', 'Projects Served'),
            'stat3_number' => HomepageSetting::get('na_stat3_number', '2500+'),
            'stat3_label'  => HomepageSetting::get('na_stat3_label', 'Vendors'),
        ];

        return view('admin.homepage.index', compact(
            'stats',
            'catalogProducts',
            'quickPromoCards',
            'homepageSettings',
            'banners',
            'bannersRow1',
            'bannersRow2',
            'categories',
            'heroCardSettings',
            'newArrivalsSettings'
        ));
    }

    /**
     * Ensure the 4 numbered section columns and trending now exist in database
     */
    protected function ensureSectionColumnsExist(): void
    {
        try {
            if (!Schema::hasColumn('products', 'sec1_trending') || !Schema::hasColumn('products', 'is_trending_now')) {
                Schema::table('products', function (\Illuminate\Database\Schema\Blueprint $table) {
                    if (!Schema::hasColumn('products', 'sec1_trending')) {
                        $table->boolean('sec1_trending')->default(false)->index();
                    }
                    if (!Schema::hasColumn('products', 'sec2_best_seller')) {
                        $table->boolean('sec2_best_seller')->default(false)->index();
                    }
                    if (!Schema::hasColumn('products', 'sec3_trending')) {
                        $table->boolean('sec3_trending')->default(false)->index();
                    }
                    if (!Schema::hasColumn('products', 'sec4_best_seller')) {
                        $table->boolean('sec4_best_seller')->default(false)->index();
                    }
                    if (!Schema::hasColumn('products', 'is_trending_now')) {
                        $table->boolean('is_trending_now')->default(false)->index();
                    }
                });

                // Set initial default selections so storefront has active products immediately
                $prods = Product::active()->take(16)->get();
                if ($prods->count() >= 4) {
                    // Sec 1 (Above Banners): 1st batch
                    $prods->slice(0, 4)->each->update(['sec1_trending' => true]);
                    // Sec 2 (Above Banners): 2nd batch
                    $prods->slice(2, 4)->each->update(['sec2_best_seller' => true]);
                    // Sec 3 (Below Banners): 3rd batch
                    $prods->slice(4, 6)->each->update(['sec3_trending' => true]);
                    // Sec 4 (Below Banners): 4th batch
                    $prods->slice(8, 6)->each->update(['sec4_best_seller' => true]);
                    // Trending Now
                    $prods->slice(0, 10)->each->update(['is_trending_now' => true]);
                }
            }

            if (!Schema::hasTable('quick_promo_cards')) {
                Schema::create('quick_promo_cards', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->id();
                    $table->string('title_line1', 150)->default('BULK ORDER');
                    $table->string('title_line2', 150)->default('SAVE MORE');
                    $table->string('subtitle', 255)->default('Best Prices on Large Quantities');
                    $table->string('button_text', 100)->default('Request Quote');
                    $table->string('button_url', 500)->default('/quotations');
                    $table->string('button_color', 50)->default('#ff5400');
                    $table->string('image', 500)->nullable();
                    $table->string('bg_gradient', 255)->default('linear-gradient(135deg, #091b3a 0%, #061226 100%)');
                    $table->unsignedInteger('sort_order')->default(0);
                    $table->boolean('is_active')->default(true)->index();
                    $table->timestamps();
                });
            }

            if (!Schema::hasTable('homepage_settings')) {
                Schema::create('homepage_settings', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->id();
                    $table->string('key', 100)->unique();
                    $table->text('value')->nullable();
                    $table->timestamps();
                });
            }

            if (Schema::hasTable('banners')) {
                if (!Schema::hasColumn('banners', 'section_row')) {
                    Schema::table('banners', function (\Illuminate\Database\Schema\Blueprint $table) {
                        $table->unsignedTinyInteger('section_row')->default(1)->after('position')->index();
                    });
                }

                // If existing banners have null section_row, default to row 1
                Banner::whereNull('section_row')->orWhere('section_row', 0)->update(['section_row' => 1]);

                // Ensure Row 2 has 3 default banners so admin immediately displays both rows
                if (Banner::where('section_row', 2)->count() === 0) {
                    $row2Defaults = [
                        [
                            'title'            => "MEGA\nCONSTRUCTION\nSALE",
                            'subtitle'         => 'UP TO 40%',
                            'image'            => 'images/banners/mega_construction_sale.jpg',
                            'position'         => 'mid_promo',
                            'section_row'      => 2,
                            'button_text'      => 'Explore Now',
                            'link'             => '/products?category=cement',
                            'background_color' => '#0b0f19',
                            'text_color'       => '#ffffff',
                            'sort_order'       => 4,
                            'status'           => 'active',
                        ],
                        [
                            'title'            => "Kajaria\nIndia's No. 1 Tile Company",
                            'subtitle'         => 'Elegant Tiles For Every Space',
                            'image'            => 'images/banners/kajaria_tiles_banner.jpg',
                            'position'         => 'mid_promo',
                            'section_row'      => 2,
                            'button_text'      => 'Explore Collection',
                            'link'             => '/products?category=tiles',
                            'background_color' => '#112328',
                            'text_color'       => '#ffffff',
                            'sort_order'       => 5,
                            'status'           => 'active',
                        ],
                        [
                            'title'            => "Astral Pipes\nTrust of Quality",
                            'subtitle'         => 'Complete Plumbing & Drainage',
                            'image'            => 'images/banners/astral_pipes_banner.jpg',
                            'position'         => 'mid_promo',
                            'section_row'      => 2,
                            'button_text'      => 'Shop Astral',
                            'link'             => '/products?category=pipes',
                            'background_color' => '#081730',
                            'text_color'       => '#ffffff',
                            'sort_order'       => 6,
                            'status'           => 'active',
                        ],
                    ];

                    foreach ($row2Defaults as $b) {
                        Banner::create($b);
                    }
                }
            }

            if (QuickPromoCard::count() === 0 || Product::where('is_trending_now', true)->count() < 16) {
                (new HomepageProductSeeder())->run();
            }
        } catch (\Throwable $e) {
            // Keep resilient if database connection is restricted
        }
    }

    /**
     * Toggle a product placement flag
     */
    public function toggleProductFlag(Request $request)
    {
        $this->ensureSectionColumnsExist();

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'flag'       => 'required|in:sec1_trending,sec2_best_seller,sec3_trending,sec4_best_seller,is_trending_now,is_trending,is_best_seller,is_featured,is_new_arrival,is_deal_of_the_day,is_sponsored',
        ]);

        $product = Product::findOrFail($request->product_id);
        $flag = $request->flag;
        $product->$flag = !$product->$flag;
        $product->save();

        $labels = [
            'is_trending_now'    => 'Trending Now Showcase',
            'sec1_trending'      => 'Section 1: Trending (Above Banners)',
            'sec2_best_seller'   => 'Section 2: Best Selling (Above Banners)',
            'sec3_trending'      => 'Section 3: Trending (Below Banners)',
            'sec4_best_seller'   => 'Section 4: Best Selling (Below Banners)',
            'is_trending'        => 'Trending Products',
            'is_best_seller'     => 'Most Sold / Best Seller',
            'is_featured'        => 'Top Rated / Featured',
            'is_new_arrival'     => 'New Arrivals',
            'is_deal_of_the_day' => 'Deal of the Day',
            'is_sponsored'       => 'Sponsored Ad',
        ];

        $label = $labels[$flag] ?? $flag;
        $statusMsg = $product->$flag ? "Added to {$label}" : "Removed from {$label}";

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'active'  => (bool) $product->$flag,
                'message' => "{$product->name}: {$statusMsg}",
            ]);
        }

        return back()->with('success', "{$product->name}: {$statusMsg}");
    }

    /**
     * Store a new Quick Action Promo Card
     */
    public function storePromoCard(Request $request)
    {
        $request->validate([
            'title_line1'   => 'required|string|max:150',
            'title_line2'   => 'required|string|max:150',
            'subtitle'      => 'nullable|string|max:255',
            'button_text'   => 'nullable|string|max:100',
            'button_url'    => 'nullable|string|max:500',
            'button_color'  => 'nullable|string|max:50',
            'promo_image'   => 'nullable|image|max:4096',
            'image_url'     => 'nullable|string|max:500',
            'bg_gradient'   => 'nullable|string|max:255',
            'sort_order'    => 'nullable|integer',
        ]);

        $imagePath = 'images/quick_promos/bulk_order_pallet.jpg';
        if ($request->hasFile('promo_image')) {
            $imagePath = $request->file('promo_image')->store('quick_promos', 'public');
        } elseif ($request->filled('image_url')) {
            $imagePath = $request->image_url;
        }

        QuickPromoCard::create([
            'title_line1'   => $request->title_line1,
            'title_line2'   => $request->title_line2,
            'subtitle'      => $request->subtitle ?: '',
            'button_text'   => $request->button_text ?: 'Request Quote',
            'button_url'    => $request->button_url ?: '/quotations',
            'button_color'  => $request->button_color ?: '#ff5400',
            'image'         => $imagePath,
            'bg_gradient'   => $request->bg_gradient ?: 'linear-gradient(135deg, #091b3a 0%, #061226 100%)',
            'sort_order'    => $request->sort_order ?? (QuickPromoCard::max('sort_order') + 1),
            'is_active'     => true,
        ]);

        return back()->with('success', 'Quick Action Promo Card created successfully!');
    }

    /**
     * Update an existing Quick Action Promo Card
     */
    public function updatePromoCard(Request $request, $id)
    {
        $card = QuickPromoCard::findOrFail($id);

        $request->validate([
            'title_line1'   => 'required|string|max:150',
            'title_line2'   => 'required|string|max:150',
            'subtitle'      => 'nullable|string|max:255',
            'button_text'   => 'nullable|string|max:100',
            'button_url'    => 'nullable|string|max:500',
            'button_color'  => 'nullable|string|max:50',
            'promo_image'   => 'nullable|image|max:4096',
            'image_url'     => 'nullable|string|max:500',
            'bg_gradient'   => 'nullable|string|max:255',
            'sort_order'    => 'nullable|integer',
            'is_active'     => 'nullable',
        ]);

        $updateData = [
            'title_line1'  => $request->title_line1,
            'title_line2'  => $request->title_line2,
            'subtitle'     => $request->subtitle ?: '',
            'button_text'  => $request->button_text ?: $card->button_text,
            'button_url'   => $request->button_url ?: $card->button_url,
            'button_color' => $request->button_color ?: $card->button_color,
            'bg_gradient'  => $request->bg_gradient ?: $card->bg_gradient,
            'sort_order'   => $request->filled('sort_order') ? $request->sort_order : $card->sort_order,
            'is_active'    => $request->has('is_active') ? (bool) $request->is_active : $card->is_active,
        ];

        if ($request->hasFile('promo_image')) {
            $updateData['image'] = $request->file('promo_image')->store('quick_promos', 'public');
        } elseif ($request->filled('image_url')) {
            $updateData['image'] = $request->image_url;
        }

        $card->update($updateData);

        return back()->with('success', "Quick Action Card #{$card->id} ('{$card->title_line1} {$card->title_line2}') updated successfully!");
    }

    /**
     * Delete a Quick Action Promo Card
     */
    public function deletePromoCard($id)
    {
        $card = QuickPromoCard::findOrFail($id);
        $card->delete();

        return back()->with('success', 'Quick Action Promo Card deleted successfully.');
    }

    /**
     * Toggle active status of Quick Action Promo Card
     */
    public function togglePromoCardStatus($id)
    {
        $card = QuickPromoCard::findOrFail($id);
        $card->is_active = !$card->is_active;
        $card->save();

        $status = $card->is_active ? 'active' : 'inactive';
        return back()->with('success', "Card #{$card->id} is now {$status}.");
    }

    /**
     * Update Trending Now Section Header & View All Destination Settings
     */
    public function updateTrendingSettings(Request $request)
    {
        $request->validate([
            'trending_now_title'         => 'required|string|max:150',
            'trending_now_view_all_text'  => 'required|string|max:100',
            'trending_now_view_all_url'   => 'required|string|max:500',
            'trending_now_active'        => 'nullable',
        ]);

        HomepageSetting::set('trending_now_title', $request->trending_now_title);
        HomepageSetting::set('trending_now_view_all_text', $request->trending_now_view_all_text);
        HomepageSetting::set('trending_now_view_all_url', $request->trending_now_view_all_url);
        HomepageSetting::set('trending_now_active', $request->has('trending_now_active') ? '1' : '0');

        return back()->with('success', 'Trending Now section settings updated successfully!');
    }

    /**
     * Store a new promotional banner/ad
     */
    public function storeBanner(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:300',
            'subtitle'     => 'nullable|string|max:300',
            'button_text'  => 'nullable|string|max:100',
            'link'         => 'nullable|string|max:500',
            'position'     => 'nullable|string|max:50',
            'section_row'  => 'nullable|integer|in:1,2',
            'banner_image' => 'nullable|image|max:4096',
            'image_url'    => 'nullable|string|max:500',
        ]);

        $imagePath = 'images/banners/mega_construction_sale.jpg';

        if ($request->hasFile('banner_image')) {
            $imagePath = $request->file('banner_image')->store('banners', 'public');
        } elseif ($request->filled('image_url')) {
            $imagePath = $request->image_url;
        }

        $sectionRow = (int) ($request->section_row ?: 1);

        Banner::create([
            'title'            => $request->title,
            'subtitle'         => $request->subtitle,
            'button_text'      => $request->button_text ?: 'Explore Now',
            'link'             => $request->link ?: '/products',
            'position'         => $request->position ?: 'mid_promo',
            'section_row'      => $sectionRow,
            'image'            => $imagePath,
            'background_color' => $request->background_color ?: '#0f172a',
            'status'           => 'active',
            'sort_order'       => Banner::max('sort_order') + 1,
        ]);

        return back()->with('success', "Promotional ad banner created for Row {$sectionRow} successfully!");
    }

    /**
     * Update an existing promotional banner/ad
     */
    public function updateBanner(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $request->validate([
            'title'        => 'required|string|max:300',
            'subtitle'     => 'nullable|string|max:300',
            'button_text'  => 'nullable|string|max:100',
            'link'         => 'nullable|string|max:500',
            'position'     => 'nullable|string|max:50',
            'section_row'  => 'nullable|integer|in:1,2',
            'banner_image' => 'nullable|image|max:4096',
            'image_url'    => 'nullable|string|max:500',
        ]);

        $updateData = [
            'title'       => $request->title,
            'subtitle'    => $request->subtitle,
            'button_text' => $request->button_text ?: $banner->button_text,
            'link'        => $request->link ?: $banner->link,
        ];

        if ($request->hasFile('banner_image')) {
            $updateData['image'] = $request->file('banner_image')->store('banners', 'public');
        } elseif ($request->filled('image_url')) {
            $updateData['image'] = $request->image_url;
        }

        if ($request->filled('background_color')) {
            $updateData['background_color'] = $request->background_color;
        }

        if ($request->filled('position')) {
            $updateData['position'] = $request->position;
        }

        if ($request->filled('section_row')) {
            $updateData['section_row'] = (int) $request->section_row;
        }

        $banner->update($updateData);

        return back()->with('success', 'Promotional banner ad updated successfully!');
    }

    /**
     * Toggle banner active/inactive status
     */
    public function toggleBannerStatus($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->status = $banner->status === 'active' ? 'inactive' : 'active';
        $banner->save();

        return back()->with('success', "Banner status changed to {$banner->status}.");
    }

    /**
     * Delete banner
     */
    public function deleteBanner($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->delete();

        return back()->with('success', 'Banner ad deleted successfully.');
    }

    /**
     * Toggle category show on homepage
     */
    public function toggleCategoryHomepage($id)
    {
        $cat = Category::findOrFail($id);
        $cat->show_in_home = !$cat->show_in_home;
        $cat->save();

        $status = $cat->show_in_home ? 'shown on homepage' : 'hidden from homepage';
        return back()->with('success', "Category '{$cat->name}' is now {$status}.");
    }

    /**
     * Seed or re-sync default homepage products from database
     */
    public function syncDefaultProducts()
    {
        try {
            $seeder = new HomepageProductSeeder();
            $seeder->run();
            return back()->with('success', 'Default homepage products & ads successfully synced to database!');
        } catch (\Throwable $e) {
            return back()->with('error', 'Sync failed: ' . $e->getMessage());
        }
    }

    /**
     * Update Hero Special Cards (Bulk Order Special & Auction Zone - Image 3)
     */
    public function updateHeroCards(Request $request)
    {
        $request->validate([
            'hero_bulk_title'       => 'required|string|max:150',
            'hero_bulk_subhead'     => 'nullable|string|max:200',
            'hero_bulk_desc'        => 'nullable|string|max:300',
            'hero_bulk_badge'       => 'nullable|string|max:100',
            'hero_bulk_min'         => 'nullable|string|max:100',
            'hero_bulk_btn_text'    => 'nullable|string|max:100',
            'hero_bulk_btn_url'     => 'nullable|string|max:500',
            'hero_bulk_image_file'  => 'nullable|image|max:4096',
            'hero_bulk_image'       => 'nullable|string|max:500',
            'hero_bulk_features'    => 'nullable|string|max:1000',

            'hero_auction_title'      => 'required|string|max:150',
            'hero_auction_subhead'    => 'nullable|string|max:200',
            'hero_auction_desc'       => 'nullable|string|max:300',
            'hero_auction_badge'      => 'nullable|string|max:100',
            'hero_auction_discount'   => 'nullable|string|max:100',
            'hero_auction_btn_text'   => 'nullable|string|max:100',
            'hero_auction_btn_url'    => 'nullable|string|max:500',
            'hero_auction_image_file' => 'nullable|image|max:4096',
            'hero_auction_image'      => 'nullable|string|max:500',
            'hero_auction_features'   => 'nullable|string|max:1000',
        ]);

        // Card 3: Bulk Order Special
        HomepageSetting::set('hero_bulk_title', $request->hero_bulk_title);
        HomepageSetting::set('hero_bulk_subhead', $request->hero_bulk_subhead ?? '');
        HomepageSetting::set('hero_bulk_desc', $request->hero_bulk_desc ?? '');
        HomepageSetting::set('hero_bulk_badge', $request->hero_bulk_badge ?? 'B2B WHOLESALE');
        HomepageSetting::set('hero_bulk_min', $request->hero_bulk_min ?? 'Min. ₹50K+');
        HomepageSetting::set('hero_bulk_btn_text', $request->hero_bulk_btn_text ?? 'Request Bulk Quote');
        HomepageSetting::set('hero_bulk_btn_url', $request->hero_bulk_btn_url ?? '/quotations');
        HomepageSetting::set('hero_bulk_features', $request->hero_bulk_features ?? '');

        if ($request->hasFile('hero_bulk_image_file')) {
            $path = $request->file('hero_bulk_image_file')->store('hero', 'public');
            HomepageSetting::set('hero_bulk_image', asset('storage/' . $path));
        } elseif ($request->filled('hero_bulk_image')) {
            HomepageSetting::set('hero_bulk_image', $request->hero_bulk_image);
        }

        // Card 4: Auction Zone
        HomepageSetting::set('hero_auction_title', $request->hero_auction_title);
        HomepageSetting::set('hero_auction_subhead', $request->hero_auction_subhead ?? '');
        HomepageSetting::set('hero_auction_desc', $request->hero_auction_desc ?? '');
        HomepageSetting::set('hero_auction_badge', $request->hero_auction_badge ?? 'LIVE AUCTIONS');
        HomepageSetting::set('hero_auction_discount', $request->hero_auction_discount ?? 'Up To 60% OFF');
        HomepageSetting::set('hero_auction_btn_text', $request->hero_auction_btn_text ?? 'Explore Auctions');
        HomepageSetting::set('hero_auction_btn_url', $request->hero_auction_btn_url ?? '/products?filter=trending');
        HomepageSetting::set('hero_auction_features', $request->hero_auction_features ?? '');

        if ($request->hasFile('hero_auction_image_file')) {
            $path = $request->file('hero_auction_image_file')->store('hero', 'public');
            HomepageSetting::set('hero_auction_image', asset('storage/' . $path));
        } elseif ($request->filled('hero_auction_image')) {
            HomepageSetting::set('hero_auction_image', $request->hero_auction_image);
        }

        return back()->with('success', 'Hero Special Cards (Bulk Order Special & Auction Zone) updated successfully!');
    }

    /**
     * Update Image 3 New Arrivals & Why Choose Garudo section settings
     */
    public function updateNewArrivalsSettings(Request $request)
    {
        $rules = [
            'why_choose_title'    => 'nullable|string|max:150',
            'why_choose_features' => 'nullable|string|max:2000',
            'stat1_number'        => 'nullable|string|max:50',
            'stat1_label'         => 'nullable|string|max:100',
            'stat2_number'        => 'nullable|string|max:50',
            'stat2_label'         => 'nullable|string|max:100',
            'stat3_number'        => 'nullable|string|max:50',
            'stat3_label'         => 'nullable|string|max:100',
        ];

        for ($i = 1; $i <= 6; $i++) {
            $rules["card{$i}_title"]      = 'nullable|string|max:150';
            $rules["card{$i}_price"]      = 'nullable|string|max:50';
            $rules["card{$i}_unit"]       = 'nullable|string|max:50';
            $rules["card{$i}_url"]        = 'nullable|string|max:500';
            $rules["card{$i}_image"]      = 'nullable|string|max:500';
            $rules["card{$i}_image_file"] = 'nullable|image|mimes:jpeg,png,jpg,webp,avif|max:4096';
        }

        $request->validate($rules);

        for ($i = 1; $i <= 6; $i++) {
            if ($request->has("card{$i}_title")) {
                HomepageSetting::set("na_card{$i}_title", $request->input("card{$i}_title"));
            }
            if ($request->has("card{$i}_price")) {
                HomepageSetting::set("na_card{$i}_price", $request->input("card{$i}_price"));
            }
            if ($request->has("card{$i}_unit")) {
                HomepageSetting::set("na_card{$i}_unit", $request->input("card{$i}_unit") ?? '');
            }
            if ($request->has("card{$i}_url")) {
                HomepageSetting::set("na_card{$i}_url", $request->input("card{$i}_url") ?? '/products');
            }

            if ($request->hasFile("card{$i}_image_file")) {
                $path = $request->file("card{$i}_image_file")->store('new_arrivals', 'public');
                HomepageSetting::set("na_card{$i}_image", asset('storage/' . $path));
            } elseif ($request->filled("card{$i}_image")) {
                HomepageSetting::set("na_card{$i}_image", $request->input("card{$i}_image"));
            }
        }

        HomepageSetting::set('na_why_choose_title', $request->why_choose_title ?? 'Why Choose Garudo?');
        HomepageSetting::set('na_why_choose_features', $request->why_choose_features ?? '');

        HomepageSetting::set('na_stat1_number', $request->stat1_number ?? '25K+');
        HomepageSetting::set('na_stat1_label', $request->stat1_label ?? 'Happy Customers');
        HomepageSetting::set('na_stat2_number', $request->stat2_number ?? '10K+');
        HomepageSetting::set('na_stat2_label', $request->stat2_label ?? 'Projects Served');
        HomepageSetting::set('na_stat3_number', $request->stat3_number ?? '2500+');
        HomepageSetting::set('na_stat3_label', $request->stat3_label ?? 'Vendors');

        return back()->with('success', 'New Arrivals (Image 3) showcase & Why Choose Garudo section updated successfully!');
    }
}

