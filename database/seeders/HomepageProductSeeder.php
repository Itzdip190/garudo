<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Vendor;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Banner;
use App\Models\QuickPromoCard;
use App\Models\HomepageSetting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class HomepageProductSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure at least one verified Vendor exists
        $vendor = Vendor::first();
        if (!$vendor) {
            $vendor = Vendor::create([
                'company_name'     => 'Garudo Direct Industrial Supplies',
                'contact_person'   => 'Rajesh Kumar',
                'email'            => 'industrial@garudo.com',
                'phone'            => '9876543210',
                'password'         => bcrypt('Garudo@2026'),
                'business_type'    => 'manufacturer',
                'gstin'            => '07AAAAA0000A1Z5',
                'pan'              => 'AAAAA0000A',
                'approval_status'  => 'approved',
                'status'           => 'active',
                'is_verified'      => true,
                'is_featured'      => true,
                'rating'           => 4.9,
                'rating_count'     => 420,
            ]);
        } else {
            $vendor->update(['approval_status' => 'approved', 'status' => 'active', 'is_verified' => true]);
        }

        // 2. Map Categories (Fallback create if missing)
        $catMap = [];
        $categoriesList = [
            'cement' => ['name' => 'Cement & Concrete', 'icon' => 'bi-box'],
            'steel' => ['name' => 'Steel & TMT Bars', 'icon' => 'bi-layers'],
            'tiles' => ['name' => 'Tiles, Marble & Granite', 'icon' => 'bi-grid'],
            'pipes' => ['name' => 'Pipes & Water Fittings', 'icon' => 'bi-droplet'],
            'paints' => ['name' => 'Paints & Waterproofing', 'icon' => 'bi-paint-bucket'],
            'sanitaryware' => ['name' => 'Sanitaryware & Bath', 'icon' => 'bi-water'],
            'bricks-blocks' => ['name' => 'Bricks, Blocks & Aggregates', 'icon' => 'bi-building'],
            'electricals' => ['name' => 'Electricals & Solar', 'icon' => 'bi-lightning-charge'],
            'hardware' => ['name' => 'Hardware & Tools', 'icon' => 'bi-tools'],
        ];

        foreach ($categoriesList as $slug => $data) {
            $cat = Category::where('slug', $slug)->first();
            if (!$cat) {
                $cat = Category::create([
                    'name'         => $data['name'],
                    'slug'         => $slug,
                    'icon'         => $data['icon'],
                    'is_featured'  => true,
                    'show_in_menu' => true,
                    'show_in_home' => true,
                    'status'       => 'active',
                ]);
            } else {
                $cat->update(['show_in_home' => true, 'status' => 'active']);
            }
            $catMap[$slug] = $cat->id;
        }

        // 3. Define the exact products matching the user's reference image
        $productsData = [
            // ================= SECTION 1: TRENDING PRODUCTS =================
            [
                'name'            => 'UltraTech Cement 43 Grade PPC',
                'slug'            => 'ultratech-cement-43-grade-ppc',
                'sku'             => 'UTC-PPC-43G',
                'category_slug'   => 'cement',
                'mrp'             => 450.00,
                'selling_price'   => 420.00,
                'unit'            => 'Bag',
                'rating'          => 4.8,
                'rating_count'    => 1540,
                'total_sold'      => 4500,
                'view_count'      => 8200,
                'is_trending'     => true,
                'is_featured'     => true,
                'is_best_seller'  => false,
                'is_new_arrival'  => false,
                'image_url'       => 'https://images.unsplash.com/photo-1590069261209-f8e9b8642343?w=500&auto=format&fit=crop&q=80',
                'description'     => 'Premium Portland Pozzolana Cement manufactured using superior quality clinker for ultra high structural strength and durability.',
            ],
            [
                'name'            => 'TATA Tiscon 550 SD TMT Bar',
                'slug'            => 'tata-tiscon-550-sd-tmt-bar',
                'sku'             => 'TATA-550SD-TMT',
                'category_slug'   => 'steel',
                'mrp'             => 70000.00,
                'selling_price'   => 65000.00,
                'unit'            => 'Ton',
                'rating'          => 4.7,
                'rating_count'    => 1820,
                'total_sold'      => 320,
                'view_count'      => 9100,
                'is_trending'     => true,
                'is_featured'     => true,
                'is_best_seller'  => true,
                'is_new_arrival'  => false,
                'image_url'       => 'https://images.unsplash.com/photo-1535813547-99c456a41d4a?w=500&auto=format&fit=crop&q=80',
                'description'     => 'Super ductile GreenPro certified high tensile TMT rebar engineered for earthquake resistant residential and industrial construction.',
            ],
            [
                'name'            => 'Red Bricks First Class',
                'slug'            => 'red-bricks-first-class-standard',
                'sku'             => 'RBK-CLASS1-STD',
                'category_slug'   => 'bricks-blocks',
                'mrp'             => 9.00,
                'selling_price'   => 7.50,
                'unit'            => 'Piece',
                'rating'          => 4.6,
                'rating_count'    => 950,
                'total_sold'      => 85000,
                'view_count'      => 5400,
                'is_trending'     => true,
                'is_featured'     => false,
                'is_best_seller'  => false,
                'is_new_arrival'  => false,
                'image_url'       => 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?w=500&auto=format&fit=crop&q=80',
                'description'     => 'Kiln burnt first class standard construction red clay bricks with uniform dimensions and superior compressive load strength.',
            ],
            [
                'name'            => 'Kajaria Ceramic Tiles 600x600mm',
                'slug'            => 'kajaria-ceramic-tiles-600x600mm',
                'sku'             => 'KAJ-CER-600',
                'category_slug'   => 'tiles',
                'mrp'             => 55.00,
                'selling_price'   => 45.00,
                'unit'            => 'Sq.ft',
                'rating'          => 4.9,
                'rating_count'    => 1210,
                'total_sold'      => 12000,
                'view_count'      => 7600,
                'is_trending'     => true,
                'is_featured'     => true,
                'is_best_seller'  => false,
                'is_new_arrival'  => false,
                'image_url'       => 'https://images.unsplash.com/photo-1502005229762-ea1b468ba670?w=500&auto=format&fit=crop&q=80',
                'description'     => 'Double charge polished ceramic glazed tiles designed for high traffic residential and commercial interior spaces.',
            ],
            [
                'name'            => 'Finolex PVC Pipe (20mm)',
                'slug'            => 'finolex-pvc-pipe-20mm-meter',
                'sku'             => 'FIN-PVC-20MM',
                'category_slug'   => 'pipes',
                'mrp'             => 35.00,
                'selling_price'   => 28.00,
                'unit'            => 'Meter',
                'rating'          => 4.8,
                'rating_count'    => 870,
                'total_sold'      => 45000,
                'view_count'      => 4900,
                'is_trending'     => true,
                'is_featured'     => false,
                'is_best_seller'  => false,
                'is_new_arrival'  => false,
                'image_url'       => 'https://images.unsplash.com/photo-1607472586893-edb57bdc0e39?w=500&auto=format&fit=crop&q=80',
                'description'     => 'Heavy duty lead-free rigid PVC electrical conduit and plumbing pipe with high impact resistance and flame retardant properties.',
            ],
            [
                'name'            => 'Asian Paints Interior Emulsion',
                'slug'            => 'asian-paints-interior-emulsion-20l',
                'sku'             => 'ASP-INT-EMU20',
                'category_slug'   => 'paints',
                'mrp'             => 1750.00,
                'selling_price'   => 1450.00,
                'unit'            => '20L',
                'rating'          => 4.7,
                'rating_count'    => 2100,
                'total_sold'      => 3400,
                'view_count'      => 9800,
                'is_trending'     => true,
                'is_featured'     => true,
                'is_best_seller'  => false,
                'is_new_arrival'  => false,
                'image_url'       => 'https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=500&auto=format&fit=crop&q=80',
                'description'     => 'Smooth matte finish acrylic interior wall emulsion with antifungal protection and rich color coverage.',
            ],

            // ================= SECTION 2: BEST SELLING PRODUCTS =================
            [
                'name'            => 'UltraTech Cement 50kg',
                'slug'            => 'ultratech-cement-50kg-bag',
                'sku'             => 'UTC-50KG-BEST',
                'category_slug'   => 'cement',
                'mrp'             => 495.00,
                'selling_price'   => 395.00, // 20% OFF
                'unit'            => 'Bag',
                'rating'          => 4.8,
                'rating_count'    => 2400,
                'total_sold'      => 15000,
                'view_count'      => 18500,
                'is_trending'     => true,
                'is_featured'     => true, // Top Rated
                'is_best_seller'  => true, // Most Sold
                'is_new_arrival'  => false,
                'image_url'       => 'https://images.unsplash.com/photo-1590069261209-f8e9b8642343?w=500&auto=format&fit=crop&q=80',
                'description'     => 'India’s No. 1 cement choice for solid slab and beam casting with enhanced micro-reinforcement technology.',
            ],
            [
                'name'            => 'Kajaria Vitrified Floor Tile',
                'slug'            => 'kajaria-vitrified-floor-tile-sqft',
                'sku'             => 'KAJ-VIT-FLR',
                'category_slug'   => 'tiles',
                'mrp'             => 49.00,
                'selling_price'   => 42.00, // 15% OFF
                'unit'            => 'sq.ft.',
                'rating'          => 4.7,
                'rating_count'    => 1600,
                'total_sold'      => 8200,
                'view_count'      => 14200,
                'is_trending'     => true,
                'is_featured'     => true, // Top Rated
                'is_best_seller'  => true, // Most Sold
                'is_new_arrival'  => false,
                'image_url'       => 'https://images.unsplash.com/photo-1502005229762-ea1b468ba670?w=500&auto=format&fit=crop&q=80',
                'description'     => 'High gloss nano-polished vitrified floor tiles with scratch and stain resistant crystalline surface.',
            ],
            [
                'name'            => 'TATA TMT Steel Bar 12mm',
                'slug'            => 'tata-tmt-steel-bar-12mm-kg',
                'sku'             => 'TATA-TMT-12MM',
                'category_slug'   => 'steel',
                'mrp'             => 75.00,
                'selling_price'   => 68.00, // 10% OFF
                'unit'            => 'kg',
                'rating'          => 4.8,
                'rating_count'    => 3200,
                'total_sold'      => 24000,
                'view_count'      => 21000,
                'is_trending'     => true,
                'is_featured'     => true, // Top Rated
                'is_best_seller'  => true, // Most Sold
                'is_new_arrival'  => false,
                'image_url'       => 'https://images.unsplash.com/photo-1535813547-99c456a41d4a?w=500&auto=format&fit=crop&q=80',
                'description'     => 'Primary steel billet rolled 12mm high grade rebars offering ultimate yield stress and superior weldability.',
            ],
            [
                'name'            => 'Hindware One Piece EWC',
                'slug'            => 'hindware-one-piece-ewc-commode',
                'sku'             => 'HND-EWC-OP1',
                'category_slug'   => 'sanitaryware',
                'mrp'             => 8500.00,
                'selling_price'   => 7250.00, // 15% OFF
                'unit'            => 'Piece',
                'rating'          => 4.6,
                'rating_count'    => 982,
                'total_sold'      => 1450,
                'view_count'      => 8900,
                'is_trending'     => false,
                'is_featured'     => true, // Top Rated
                'is_best_seller'  => true, // Most Sold
                'is_new_arrival'  => false,
                'image_url'       => 'https://images.unsplash.com/photo-1585314062340-f1a5a7c9328d?w=500&auto=format&fit=crop&q=80',
                'description'     => 'Vitreous china single piece European water closet with siphon 360 dual flush and soft close hydraulic seat.',
            ],
            [
                'name'            => 'Asian Paints Tractor Emulsion 20L',
                'slug'            => 'asian-paints-tractor-emulsion-20l',
                'sku'             => 'ASP-TRC-20L',
                'category_slug'   => 'paints',
                'mrp'             => 6200.00,
                'selling_price'   => 4650.00, // 25% OFF
                'unit'            => 'Bucket',
                'rating'          => 4.7,
                'rating_count'    => 1400,
                'total_sold'      => 4100,
                'view_count'      => 12000,
                'is_trending'     => true,
                'is_featured'     => true, // Top Rated
                'is_best_seller'  => true, // Most Sold
                'is_new_arrival'  => false,
                'image_url'       => 'https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=500&auto=format&fit=crop&q=80',
                'description'     => 'Economical and durable plastic emulsion giving smart walls with 1.5 times the coverage of distemper paints.',
            ],
            [
                'name'            => 'Jaquar Chrome Basin Mixer',
                'slug'            => 'jaquar-chrome-basin-mixer-faucet',
                'sku'             => 'JAQ-BAS-MIX-CHR',
                'category_slug'   => 'sanitaryware',
                'mrp'             => 3400.00,
                'selling_price'   => 2850.00, // 16% OFF
                'unit'            => 'Piece',
                'rating'          => 4.7,
                'rating_count'    => 1100,
                'total_sold'      => 2900,
                'view_count'      => 10500,
                'is_trending'     => true,
                'is_featured'     => true, // Top Rated
                'is_best_seller'  => true, // Most Sold
                'is_new_arrival'  => true, // New Arrivals
                'image_url'       => 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?w=500&auto=format&fit=crop&q=80',
                'description'     => 'Single lever tall body basin mixer tap with anti-corrosive multi-layer mirror chrome finish and aerated water stream.',
            ],
            // Additional New Arrivals / Best Sellers to ensure variety across tabs
            [
                'name'            => 'Astral CPVC Pro Pipe 1 Inch (3m)',
                'slug'            => 'astral-cpvc-pro-pipe-1-inch-3m',
                'sku'             => 'AST-CPVC-1IN',
                'category_slug'   => 'pipes',
                'mrp'             => 520.00,
                'selling_price'   => 440.00,
                'unit'            => 'Pipe',
                'rating'          => 4.9,
                'rating_count'    => 1350,
                'total_sold'      => 6200,
                'view_count'      => 7800,
                'is_trending'     => false,
                'is_featured'     => true,
                'is_best_seller'  => false,
                'is_new_arrival'  => true,
                'image_url'       => 'https://images.unsplash.com/photo-1607472586893-edb57bdc0e39?w=500&auto=format&fit=crop&q=80',
                'description'     => 'SDR 11 chlorinated polyvinyl chloride hot and cold water distribution pipe with NSF-61 certified potable safety.',
            ],
            [
                'name'            => 'Polycab 2.5 sq mm FRLS Copper Wire (90m)',
                'slug'            => 'polycab-2-5-sqmm-frls-copper-wire',
                'sku'             => 'PLY-FRLS-25MM',
                'category_slug'   => 'electricals',
                'mrp'             => 2350.00,
                'selling_price'   => 1890.00,
                'unit'            => 'Coil',
                'rating'          => 4.8,
                'rating_count'    => 2240,
                'total_sold'      => 5800,
                'view_count'      => 11500,
                'is_trending'     => true,
                'is_featured'     => true,
                'is_best_seller'  => true,
                'is_new_arrival'  => true,
                'image_url'       => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=500&auto=format&fit=crop&q=80',
                'description'     => 'Flame Retardant Low Smoke 100% pure electrolytic grade multi-strand annealed copper electrical conductor wire.',
            ],
            // ================= SCREENSHOT: TRENDING NOW PRODUCTS (18+ HIGH DEMAND ITEMS) =================
            [
                'name'            => 'Somany Wall Tiles (300x450mm)',
                'slug'            => 'somany-wall-tiles-sqft',
                'sku'             => 'SOM-WALL-TILE-36',
                'category_slug'   => 'tiles',
                'mrp'             => 48.00,
                'selling_price'   => 36.00,
                'unit'            => 'sq.ft.',
                'rating'          => 4.8,
                'rating_count'    => 1420,
                'total_sold'      => 9800,
                'view_count'      => 12500,
                'is_trending'     => true,
                'is_trending_now' => true,
                'is_featured'     => true,
                'is_best_seller'  => false,
                'is_new_arrival'  => false,
                'image_url'       => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=500&auto=format&fit=crop&q=80',
                'description'     => 'Premium ceramic digital glazed marble wall tiles for contemporary bathroom and kitchen walls.',
            ],
            [
                'name'            => 'Kajaria Floor Tiles (600x600mm)',
                'slug'            => 'kajaria-floor-tiles-sqft',
                'sku'             => 'KAJ-FLR-TILE-42',
                'category_slug'   => 'tiles',
                'mrp'             => 54.00,
                'selling_price'   => 42.00,
                'unit'            => 'sq.ft.',
                'rating'          => 4.9,
                'rating_count'    => 2150,
                'total_sold'      => 14200,
                'view_count'      => 16400,
                'is_trending'     => true,
                'is_trending_now' => true,
                'is_featured'     => true,
                'is_best_seller'  => true,
                'is_new_arrival'  => false,
                'image_url'       => 'https://images.unsplash.com/photo-1513694203232-719a280e022f?w=500&auto=format&fit=crop&q=80',
                'description'     => 'Double charge nano-polished vitrified floor tiles with heavy foot traffic durability and stain resistance.',
            ],
            [
                'name'            => 'Allen Cooper Copper Wires (90m)',
                'slug'            => 'allen-cooper-wires-roll',
                'sku'             => 'ALN-COP-WIRE-1850',
                'category_slug'   => 'electricals',
                'mrp'             => 2250.00,
                'selling_price'   => 1850.00,
                'unit'            => 'roll',
                'rating'          => 4.8,
                'rating_count'    => 890,
                'total_sold'      => 3400,
                'view_count'      => 8700,
                'is_trending'     => true,
                'is_trending_now' => true,
                'is_featured'     => true,
                'is_best_seller'  => false,
                'is_new_arrival'  => false,
                'image_url'       => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=500&auto=format&fit=crop&q=80',
                'description'     => 'Flame retardant multi-strand copper industrial building wires conforming to BIS safety standards.',
            ],
            [
                'name'            => 'Finolex CPVC Plumbing Pipes (10ft)',
                'slug'            => 'finolex-pipes-meter',
                'sku'             => 'FIN-PIPE-MTR-120',
                'category_slug'   => 'pipes',
                'mrp'             => 150.00,
                'selling_price'   => 120.00,
                'unit'            => 'meter',
                'rating'          => 4.7,
                'rating_count'    => 1670,
                'total_sold'      => 18900,
                'view_count'      => 9400,
                'is_trending'     => true,
                'is_trending_now' => true,
                'is_featured'     => true,
                'is_best_seller'  => true,
                'is_new_arrival'  => false,
                'image_url'       => 'https://images.unsplash.com/photo-1585771724684-38269d6639fd?w=500&auto=format&fit=crop&q=80',
                'description'     => 'Heavy duty rigid CPVC plumbing and hot/cold water drainage pipes engineered for leak-proof longevity.',
            ],
            [
                'name'            => 'Havells Modular Switches & Sockets',
                'slug'            => 'havells-switches-piece',
                'sku'             => 'HAV-SWT-PC-85',
                'category_slug'   => 'electricals',
                'mrp'             => 115.00,
                'selling_price'   => 85.00,
                'unit'            => 'piece',
                'rating'          => 4.8,
                'rating_count'    => 3120,
                'total_sold'      => 24000,
                'view_count'      => 14200,
                'is_trending'     => true,
                'is_trending_now' => true,
                'is_featured'     => true,
                'is_best_seller'  => false,
                'is_new_arrival'  => false,
                'image_url'       => 'https://images.unsplash.com/photo-1558002038-1055907df827?w=500&auto=format&fit=crop&q=80',
                'description'     => 'Designer modular electrical switches with smooth click mechanism and flame-resistant casing.',
            ],
            [
                'name'            => 'Crompton High-Speed Ceiling Fan',
                'slug'            => 'crompton-fan-piece',
                'sku'             => 'CRM-FAN-PC-1650',
                'category_slug'   => 'electricals',
                'mrp'             => 2100.00,
                'selling_price'   => 1650.00,
                'unit'            => 'piece',
                'rating'          => 4.8,
                'rating_count'    => 2890,
                'total_sold'      => 7600,
                'view_count'      => 13100,
                'is_trending'     => true,
                'is_trending_now' => true,
                'is_featured'     => true,
                'is_best_seller'  => false,
                'is_new_arrival'  => false,
                'image_url'       => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=500&auto=format&fit=crop&q=80',
                'description'     => 'High air delivery energy efficient ceiling fan with 100% copper motor and aerodynamic blades.',
            ],
            [
                'name'            => 'Parryware Table Top Ceramic Basin',
                'slug'            => 'parryware-basin-piece',
                'sku'             => 'PRW-BSN-PC-1250',
                'category_slug'   => 'sanitaryware',
                'mrp'             => 1750.00,
                'selling_price'   => 1250.00,
                'unit'            => 'piece',
                'rating'          => 4.7,
                'rating_count'    => 1150,
                'total_sold'      => 3200,
                'view_count'      => 6800,
                'is_trending'     => true,
                'is_trending_now' => true,
                'is_featured'     => true,
                'is_best_seller'  => false,
                'is_new_arrival'  => false,
                'image_url'       => 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?w=500&auto=format&fit=crop&q=80',
                'description'     => 'Vitreous china ceramic wash basin with stain-free glossy glaze for luxury contemporary bathrooms.',
            ],
            [
                'name'            => 'Astral 4-Layer Water Storage Tank',
                'slug'            => 'astral-water-tank-piece',
                'sku'             => 'AST-WTK-PC-2450',
                'category_slug'   => 'pipes',
                'mrp'             => 3100.00,
                'selling_price'   => 2450.00,
                'unit'            => 'piece',
                'rating'          => 4.9,
                'rating_count'    => 1420,
                'total_sold'      => 4100,
                'view_count'      => 9800,
                'is_trending'     => true,
                'is_trending_now' => true,
                'is_featured'     => true,
                'is_best_seller'  => false,
                'is_new_arrival'  => false,
                'image_url'       => 'https://images.unsplash.com/photo-1581092335397-9583fe92d232?w=500&auto=format&fit=crop&q=80',
                'description'     => 'Multi-layer UV stabilized anti-bacterial overhead polymer water storage tank for clean water supply.',
            ],
            [
                'name'            => 'Ultratech Super Premium Cement 50kg',
                'slug'            => 'ultratech-cement-trending-bag',
                'sku'             => 'UTC-CMT-BG-395',
                'category_slug'   => 'cement',
                'mrp'             => 460.00,
                'selling_price'   => 395.00,
                'unit'            => 'bag',
                'rating'          => 4.9,
                'rating_count'    => 6400,
                'total_sold'      => 38000,
                'view_count'      => 22000,
                'is_trending'     => true,
                'is_trending_now' => true,
                'is_featured'     => true,
                'is_best_seller'  => true,
                'is_new_arrival'  => false,
                'image_url'       => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=500&auto=format&fit=crop&q=80',
                'description'     => 'India’s top heavy-grade structural construction cement delivering high early compressive strength.',
            ],
            [
                'name'            => 'Dr. Fixit Fastflex Waterproofing Compound',
                'slug'            => 'dr-fixit-waterproofing-piece',
                'sku'             => 'DFX-WPF-PC-1250',
                'category_slug'   => 'paints',
                'mrp'             => 1550.00,
                'selling_price'   => 1250.00,
                'unit'            => 'piece',
                'rating'          => 4.8,
                'rating_count'    => 2300,
                'total_sold'      => 8500,
                'view_count'      => 14100,
                'is_trending'     => true,
                'is_trending_now' => true,
                'is_featured'     => true,
                'is_best_seller'  => false,
                'is_new_arrival'  => false,
                'image_url'       => 'https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=500&auto=format&fit=crop&q=80',
                'description'     => 'Advanced elastomeric polymer liquid waterproofing compound for slab, terrace and wet area protection.',
            ],
            [
                'name'            => 'Asian Paints Apex Ultima Weather Paint (20L)',
                'slug'            => 'asian-paints-apex-ultima-20l',
                'sku'             => 'ASP-APX-ULT-4600',
                'category_slug'   => 'paints',
                'mrp'             => 5400.00,
                'selling_price'   => 4600.00,
                'unit'            => '20L',
                'rating'          => 4.9,
                'rating_count'    => 1890,
                'total_sold'      => 5200,
                'view_count'      => 11200,
                'is_trending'     => true,
                'is_trending_now' => true,
                'is_featured'     => true,
                'is_best_seller'  => true,
                'is_new_arrival'  => false,
                'image_url'       => 'https://images.unsplash.com/photo-1562259949-e8e7689d7828?w=500&auto=format&fit=crop&q=80',
                'description'     => 'High performance exterior acrylic wall emulsion with silicone additives for long-lasting weather protection.',
            ],
            [
                'name'            => 'Tata Tiscon 550D TMT Rebars (12mm)',
                'slug'            => 'tata-tiscon-550d-tmt-rebars-12mm',
                'sku'             => 'TATA-TMT-550D-68',
                'category_slug'   => 'steel',
                'mrp'             => 78.00,
                'selling_price'   => 68.00,
                'unit'            => 'kg',
                'rating'          => 4.9,
                'rating_count'    => 4100,
                'total_sold'      => 42000,
                'view_count'      => 28000,
                'is_trending'     => true,
                'is_trending_now' => true,
                'is_featured'     => true,
                'is_best_seller'  => true,
                'is_new_arrival'  => false,
                'image_url'       => 'https://images.unsplash.com/photo-1535813547-99c456a41d4a?w=500&auto=format&fit=crop&q=80',
                'description'     => 'Super ductile seismic-resistant primary steel 12mm TMT bars for building robust earthquake-safe structures.',
            ],
            [
                'name'            => 'Jaquar Chrome Basin Mixer Tap',
                'slug'            => 'jaquar-florentine-chrome-basin-mixer',
                'sku'             => 'JQR-FLR-MXR-3150',
                'category_slug'   => 'sanitaryware',
                'mrp'             => 3850.00,
                'selling_price'   => 3150.00,
                'unit'            => 'piece',
                'rating'          => 4.8,
                'rating_count'    => 980,
                'total_sold'      => 2400,
                'view_count'      => 7500,
                'is_trending'     => true,
                'is_trending_now' => true,
                'is_featured'     => true,
                'is_best_seller'  => false,
                'is_new_arrival'  => false,
                'image_url'       => 'https://images.unsplash.com/photo-1584622781564-1d987f7333c1?w=500&auto=format&fit=crop&q=80',
                'description'     => 'Solid brass forged single lever basin mixer faucet with mirror chrome electroplating and aerated soft flow.',
            ],
            [
                'name'            => 'Godrej Nav-Tal 7 Levers Brass Padlock',
                'slug'            => 'godrej-nav-tal-brass-padlock',
                'sku'             => 'GDJ-NVT-LCK-540',
                'category_slug'   => 'hardware',
                'mrp'             => 650.00,
                'selling_price'   => 540.00,
                'unit'            => 'piece',
                'rating'          => 4.9,
                'rating_count'    => 3560,
                'total_sold'      => 12000,
                'view_count'      => 9300,
                'is_trending'     => true,
                'is_trending_now' => true,
                'is_featured'     => true,
                'is_best_seller'  => true,
                'is_new_arrival'  => false,
                'image_url'       => 'https://images.unsplash.com/photo-1510519138161-58474dfab9c7?w=500&auto=format&fit=crop&q=80',
                'description'     => 'High security solid extruded brass padlock with hacksaw resistant steel shackle and 3 nickel-plated keys.',
            ],
            [
                'name'            => 'Anchor Roma 6A 1-Way Modular Switch',
                'slug'            => 'anchor-roma-6a-modular-switch',
                'sku'             => 'ANC-RMA-SWT-32',
                'category_slug'   => 'electricals',
                'mrp'             => 45.00,
                'selling_price'   => 32.00,
                'unit'            => 'piece',
                'rating'          => 4.8,
                'rating_count'    => 4800,
                'total_sold'      => 54000,
                'view_count'      => 16000,
                'is_trending'     => true,
                'is_trending_now' => true,
                'is_featured'     => true,
                'is_best_seller'  => true,
                'is_new_arrival'  => false,
                'image_url'       => 'https://images.unsplash.com/photo-1558346490-a72e53ae2d4f?w=500&auto=format&fit=crop&q=80',
                'description'     => 'Classic pure white modular electrical switch with captive screws and silver alloy contacts for 100k+ clicks.',
            ],
            [
                'name'            => 'Supreme PVC Agricultural Pipe 4" (6m)',
                'slug'            => 'supreme-pvc-pressure-pipe-4inch',
                'sku'             => 'SUP-PVC-4IN-720',
                'category_slug'   => 'pipes',
                'mrp'             => 890.00,
                'selling_price'   => 720.00,
                'unit'            => 'piece',
                'rating'          => 4.7,
                'rating_count'    => 1340,
                'total_sold'      => 6800,
                'view_count'      => 8100,
                'is_trending'     => true,
                'is_trending_now' => true,
                'is_featured'     => true,
                'is_best_seller'  => false,
                'is_new_arrival'  => false,
                'image_url'       => 'https://images.unsplash.com/photo-1541888946425-d0fbb18086f6?w=500&auto=format&fit=crop&q=80',
                'description'     => 'Heavy duty elastomeric socketed PVC pressure pipe designed for agricultural irrigation and water conveyance.',
            ],
            [
                'name'            => 'JK White Cement Super Smooth (25kg)',
                'slug'            => 'jk-white-cement-25kg-bag',
                'sku'             => 'JK-WHT-CMT-480',
                'category_slug'   => 'cement',
                'mrp'             => 580.00,
                'selling_price'   => 480.00,
                'unit'            => 'bag',
                'rating'          => 4.8,
                'rating_count'    => 2100,
                'total_sold'      => 14500,
                'view_count'      => 10800,
                'is_trending'     => true,
                'is_trending_now' => true,
                'is_featured'     => true,
                'is_best_seller'  => false,
                'is_new_arrival'  => false,
                'image_url'       => 'https://images.unsplash.com/photo-1590069261209-f8e9b8642343?w=500&auto=format&fit=crop&q=80',
                'description'     => 'Refined Portland white cement for floor terrazzo, decorative casting, tile grouting and primer coats.',
            ],
            [
                'name'            => 'Bosch Professional Angle Grinder 100mm',
                'slug'            => 'bosch-professional-angle-grinder-100mm',
                'sku'             => 'BSH-GWS-GRD-2250',
                'category_slug'   => 'hardware',
                'mrp'             => 2850.00,
                'selling_price'   => 2250.00,
                'unit'            => 'piece',
                'rating'          => 4.9,
                'rating_count'    => 1940,
                'total_sold'      => 4900,
                'view_count'      => 15400,
                'is_trending'     => true,
                'is_trending_now' => true,
                'is_featured'     => true,
                'is_best_seller'  => true,
                'is_new_arrival'  => false,
                'image_url'       => 'https://images.unsplash.com/photo-1504148455328-c376907d081c?w=500&auto=format&fit=crop&q=80',
                'description'     => 'Ergonomic 710W high performance angle grinder for precision metal cutting, masonry grinding and surface deburring.',
            ],
        ];

        // Ensure section columns and is_trending_now exist before inserting
        try {
            if (!Schema::hasColumn('products', 'sec1_trending')) {
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
            } elseif (!Schema::hasColumn('products', 'is_trending_now')) {
                Schema::table('products', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->boolean('is_trending_now')->default(false)->index();
                });
            }
        } catch (\Throwable $e) {
            // Silently proceed
        }

        foreach ($productsData as $index => $data) {
            $catId = $catMap[$data['category_slug']] ?? $catMap['cement'];

            $product = Product::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'vendor_id'         => $vendor->id,
                    'category_id'       => $catId,
                    'name'              => $data['name'],
                    'sku'               => $data['sku'],
                    'mrp'               => $data['mrp'],
                    'selling_price'     => $data['selling_price'],
                    'wholesale_price'   => $data['selling_price'] * 0.92,
                    'unit'              => $data['unit'],
                    'min_order_qty'     => 1,
                    'short_description' => $data['description'],
                    'description'       => $data['description'],
                    'rating'            => $data['rating'],
                    'rating_count'      => $data['rating_count'],
                    'view_count'        => $data['view_count'],
                    'is_featured'       => $data['is_featured'],
                    'is_trending'       => $data['is_trending'],
                    'is_best_seller'    => $data['is_best_seller'],
                    'is_new_arrival'    => $data['is_new_arrival'],
                    'sec1_trending'     => ($index < 6),
                    'sec2_best_seller'  => ($index >= 2 && $index < 8),
                    'sec3_trending'     => ($index >= 4 && $index < 10) || ($data['is_trending'] ?? false),
                    'sec4_best_seller'  => ($index >= 6) || ($data['is_best_seller'] ?? false),
                    'is_trending_now'   => ($data['is_trending_now'] ?? false),
                    'is_popular'        => true,
                    'approval_status'   => 'approved',
                    'status'            => 'active',
                    'approved_at'       => now(),
                    'published_at'      => now(),
                ]
            );

            // Create or update primary image
            ProductImage::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'is_primary' => true,
                ],
                [
                    'file_path'  => $data['image_url'],
                    'alt_text'   => $data['name'],
                    'sort_order' => 0,
                ]
            );
        }

        // 4. Seed Homepage Promotional Banners if table exists
        if (Schema::hasTable('banners')) {
            $defaultBanners = [
                // Row 1 Banners (Middle Placement: Between Sec 2 & Sec 3)
                [
                    'title'            => "MEGA\nCONSTRUCTION\nSALE",
                    'subtitle'         => 'UP TO 40%',
                    'image'            => 'images/banners/mega_construction_sale.jpg',
                    'position'         => 'mid_promo',
                    'section_row'      => 1,
                    'button_text'      => 'Explore Now',
                    'link'             => '/products?category=cement',
                    'background_color' => '#0b0f19',
                    'text_color'       => '#ffffff',
                    'sort_order'       => 1,
                    'status'           => 'active',
                ],
                [
                    'title'            => "Kajaria\nIndia's No. 1 Tile Company",
                    'subtitle'         => 'Elegant Tiles For Every Space',
                    'image'            => 'images/banners/kajaria_tiles_banner.jpg',
                    'position'         => 'mid_promo',
                    'section_row'      => 1,
                    'button_text'      => 'Explore Collection',
                    'link'             => '/products?category=tiles',
                    'background_color' => '#112328',
                    'text_color'       => '#ffffff',
                    'sort_order'       => 2,
                    'status'           => 'active',
                ],
                [
                    'title'            => "Astral Pipes\nTrust of Quality",
                    'subtitle'         => 'Complete Plumbing & Drainage',
                    'image'            => 'images/banners/astral_pipes_banner.jpg',
                    'position'         => 'mid_promo',
                    'section_row'      => 1,
                    'button_text'      => 'Shop Astral',
                    'link'             => '/products?category=pipes',
                    'background_color' => '#081730',
                    'text_color'       => '#ffffff',
                    'sort_order'       => 3,
                    'status'           => 'active',
                ],
                // Row 2 Banners (Lower Placement: Above Bottom Highlights)
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

            $hasSectionRow = Schema::hasColumn('banners', 'section_row');
            foreach ($defaultBanners as $b) {
                if ($hasSectionRow) {
                    Banner::updateOrCreate(
                        ['title' => $b['title'], 'section_row' => $b['section_row']],
                        $b
                    );
                } else {
                    Banner::updateOrCreate(
                        ['title' => $b['title'], 'sort_order' => $b['sort_order']],
                        $b
                    );
                }
            }
        }

        // 5. Seed Quick Promo Cards (4 Action Banners above Trending Now)
        try {
            if (!Schema::hasTable('quick_promo_cards')) {
                Schema::create('quick_promo_cards', function (Blueprint $table) {
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

            $promoCards = [
                [
                    'title_line1'   => 'BULK ORDER',
                    'title_line2'   => 'SAVE MORE',
                    'subtitle'      => 'Best Prices on Large Quantities',
                    'button_text'   => 'Request Quote',
                    'button_url'    => '/quotations',
                    'button_color'  => '#ff5400',
                    'image'         => 'images/quick_promos/bulk_order_pallet.jpg',
                    'bg_gradient'   => 'linear-gradient(135deg, #091b3a 0%, #061226 100%)',
                    'sort_order'    => 1,
                    'is_active'     => true,
                ],
                [
                    'title_line1'   => 'READY STOCK',
                    'title_line2'   => 'FAST DELIVERY',
                    'subtitle'      => 'Pan India Delivery At Your Site',
                    'button_text'   => 'Track Order',
                    'button_url'    => '/orders',
                    'button_color'  => '#ff5400',
                    'image'         => 'images/quick_promos/ready_stock_truck.jpg',
                    'bg_gradient'   => 'linear-gradient(135deg, #091b3a 0%, #061226 100%)',
                    'sort_order'    => 2,
                    'is_active'     => true,
                ],
                [
                    'title_line1'   => 'COMBO OFFERS',
                    'title_line2'   => 'MORE FOR LESS',
                    'subtitle'      => 'Best Combos for Your Projects',
                    'button_text'   => 'Explore Combos',
                    'button_url'    => '/products?filter=trending',
                    'button_color'  => '#ff5400',
                    'image'         => 'images/quick_promos/combo_offers_materials.jpg',
                    'bg_gradient'   => 'linear-gradient(135deg, #091b3a 0%, #061226 100%)',
                    'sort_order'    => 3,
                    'is_active'     => true,
                ],
                [
                    'title_line1'   => 'POST YOUR',
                    'title_line2'   => 'REQUIREMENT',
                    'subtitle'      => 'Get Multiple Quotes From Verified Sellers',
                    'button_text'   => 'Post Requirement',
                    'button_url'    => '/quotations',
                    'button_color'  => '#ff5400',
                    'image'         => 'images/quick_promos/post_requirement_rfq.jpg',
                    'bg_gradient'   => 'linear-gradient(135deg, #091b3a 0%, #061226 100%)',
                    'sort_order'    => 4,
                    'is_active'     => true,
                ],
            ];

            foreach ($promoCards as $idx => $cardData) {
                QuickPromoCard::updateOrCreate(
                    ['sort_order' => $cardData['sort_order']],
                    $cardData
                );
            }
        } catch (\Throwable $e) {
            // Keep resilient
        }

        // 6. Seed Homepage Settings (Trending Now heading, button, link)
        try {
            if (!Schema::hasTable('homepage_settings')) {
                Schema::create('homepage_settings', function (Blueprint $table) {
                    $table->id();
                    $table->string('key', 100)->unique();
                    $table->text('value')->nullable();
                    $table->timestamps();
                });
            }

            HomepageSetting::set('trending_now_title', 'TRENDING NOW');
            HomepageSetting::set('trending_now_view_all_text', 'View All →');
            HomepageSetting::set('trending_now_view_all_url', '/products?filter=trending');
            HomepageSetting::set('trending_now_active', '1');
        } catch (\Throwable $e) {
            // Keep resilient
        }
    }
}
