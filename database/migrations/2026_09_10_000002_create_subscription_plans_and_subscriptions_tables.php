<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. SUBSCRIPTION PLANS TABLE
        if (!Schema::hasTable('subscription_plans')) {
            Schema::create('subscription_plans', function (Blueprint $table) {
                $table->id();
                $table->string('name', 200);
                $table->string('slug', 220)->unique();
                $table->enum('target_role', ['vendor', 'customer'])->default('vendor');
                $table->string('badge_title', 100)->nullable(); // e.g. "Most Popular", "Enterprise", "Free"
                $table->text('description')->nullable();
                $table->decimal('monthly_price', 10, 2)->default(0.00);
                $table->decimal('yearly_price', 10, 2)->default(0.00);
                $table->enum('billing_interval', ['monthly', 'quarterly', 'yearly', 'lifetime'])->default('monthly');
                $table->boolean('has_24_7_support')->default(false); // 24/7 Dedicated Support
                $table->unsignedInteger('sponsored_products_quota')->default(0); // Monthly boost/sponsored tokens
                $table->decimal('commission_rate', 5, 2)->default(5.00); // e.g. 0.00%, 2.50%, 5.00%
                $table->integer('product_limit')->default(10); // -1 for unlimited
                $table->boolean('custom_logistics_enabled')->default(false);
                $table->boolean('verified_badge_included')->default(false);
                $table->string('rfq_access_level', 50)->default('standard'); // standard, priority, unlimited_instant
                $table->string('analytics_access', 50)->default('basic');
                $table->json('features')->nullable(); // Dynamic array of feature bullet points
                $table->boolean('is_featured')->default(false);
                $table->boolean('is_active')->default(true);
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // 2. SUBSCRIPTIONS (Memberships) TABLE
        if (!Schema::hasTable('subscriptions')) {
            Schema::create('subscriptions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('subscription_plan_id')->constrained('subscription_plans')->onDelete('cascade');
                $table->morphs('subscribable'); // Vendor or Customer
                $table->string('plan_name', 200);
                $table->enum('billing_cycle', ['monthly', 'quarterly', 'yearly'])->default('monthly');
                $table->decimal('amount_paid', 10, 2)->default(0.00);
                $table->timestamp('starts_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->enum('status', ['active', 'pending_approval', 'expired', 'cancelled'])->default('active');
                $table->enum('payment_method', ['online', 'bank_transfer_utr', 'admin_assigned', 'free'])->default('free');
                $table->string('payment_reference', 255)->nullable(); // UTR / Transaction ID
                $table->unsignedInteger('remaining_boost_tokens')->default(0);
                $table->text('admin_notes')->nullable();
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->timestamps();

                $table->index(['subscribable_type', 'subscribable_id', 'status']);
            });
        }

        // 3. Seed Default Vendor & Customer Plans if table is empty
        if (Schema::hasTable('subscription_plans') && DB::table('subscription_plans')->count() === 0) {
            DB::table('subscription_plans')->insert([
                [
                    'name'                    => 'Free Starter Plan',
                    'slug'                    => 'vendor-free-starter',
                    'target_role'             => 'vendor',
                    'badge_title'             => 'Free Forever',
                    'description'             => 'Ideal for local suppliers and small traders starting their B2B journey on Garudo.',
                    'monthly_price'           => 0.00,
                    'yearly_price'            => 0.00,
                    'billing_interval'        => 'monthly',
                    'has_24_7_support'        => false,
                    'sponsored_products_quota'=> 0,
                    'commission_rate'         => 5.00,
                    'product_limit'           => 10,
                    'custom_logistics_enabled'=> false,
                    'verified_badge_included' => false,
                    'rfq_access_level'        => 'standard',
                    'analytics_access'        => 'basic',
                    'features'                => json_encode([
                        'Up to 10 Active Product Listings',
                        'Standard 5% Marketplace Commission',
                        'Direct Buyer Messages & Inquiries',
                        'Standard Email Support (10 AM - 6 PM)',
                        'Basic Store Profile & Catalog Page',
                    ]),
                    'is_featured'             => false,
                    'is_active'               => true,
                    'sort_order'              => 1,
                    'created_at'              => now(),
                    'updated_at'              => now(),
                ],
                [
                    'name'                    => 'Growth Pro Seller',
                    'slug'                    => 'vendor-growth-pro',
                    'target_role'             => 'vendor',
                    'badge_title'             => 'Most Popular',
                    'description'             => 'For growing manufacturers and distributors scaling volume across multiple cities.',
                    'monthly_price'           => 2499.00,
                    'yearly_price'            => 24990.00,
                    'billing_interval'        => 'monthly',
                    'has_24_7_support'        => false,
                    'sponsored_products_quota'=> 5,
                    'commission_rate'         => 2.50,
                    'product_limit'           => 100,
                    'custom_logistics_enabled'=> true,
                    'verified_badge_included' => true,
                    'rfq_access_level'        => 'priority',
                    'analytics_access'        => 'advanced_reports',
                    'features'                => json_encode([
                        'Up to 100 Active Product Listings',
                        'Reduced 2.5% Platform Commission',
                        '5 Free Sponsored Product Boosts / month',
                        'Official Garudo Verified Partner Badge',
                        'Custom F.O.R. & Dispatch Logistics Configuration',
                        'Priority Access to High-Volume Buyer RFQs',
                        'Priority Helpdesk & WhatsApp Support Desk',
                    ]),
                    'is_featured'             => true,
                    'is_active'               => true,
                    'sort_order'              => 2,
                    'created_at'              => now(),
                    'updated_at'              => now(),
                ],
                [
                    'name'                    => 'Enterprise Titanium',
                    'slug'                    => 'vendor-enterprise-titanium',
                    'target_role'             => 'vendor',
                    'badge_title'             => 'Zero Commission',
                    'description'             => 'Full-scale industrial enterprises & national brands requiring 24/7 dedicated support & 0% commission.',
                    'monthly_price'           => 6999.00,
                    'yearly_price'            => 69990.00,
                    'billing_interval'        => 'monthly',
                    'has_24_7_support'        => true,
                    'sponsored_products_quota'=> 20,
                    'commission_rate'         => 0.00,
                    'product_limit'           => -1, // Unlimited
                    'custom_logistics_enabled'=> true,
                    'verified_badge_included' => true,
                    'rfq_access_level'        => 'unlimited_instant',
                    'analytics_access'        => 'realtime_export',
                    'features'                => json_encode([
                        'Unlimited Catalog & Product Listings',
                        '0% ZERO Platform Commission (Keep 100% Revenue)',
                        '24/7 Dedicated Key Account Manager & Direct Phone Line',
                        '20 Monthly Sponsored Product Boost Tokens (Top Homepage Ranking)',
                        'Multi-Warehouse Hubs & Pan-India Priority Dispatch',
                        'Instant Direct Contractor Quotations & Bulk RFP Matching',
                        'Custom Storefront Branding & Digital Partner Certificate',
                        'Real-time Payout Settlements & Exportable Tax Reports',
                    ]),
                    'is_featured'             => false,
                    'is_active'               => true,
                    'sort_order'              => 3,
                    'created_at'              => now(),
                    'updated_at'              => now(),
                ],
                [
                    'name'                    => 'Garudo B2B Corporate Buyer Pro',
                    'slug'                    => 'customer-corporate-buyer-pro',
                    'target_role'             => 'customer',
                    'badge_title'             => 'Wholesale Pro',
                    'description'             => 'For general contractors, builders, and infrastructure developers.',
                    'monthly_price'           => 1999.00,
                    'yearly_price'            => 19990.00,
                    'billing_interval'        => 'monthly',
                    'has_24_7_support'        => true,
                    'sponsored_products_quota'=> 0,
                    'commission_rate'         => 0.00,
                    'product_limit'           => 0,
                    'custom_logistics_enabled'=> true,
                    'verified_badge_included' => true,
                    'rfq_access_level'        => 'unlimited_instant',
                    'analytics_access'        => 'advanced_reports',
                    'features'                => json_encode([
                        'Dedicated Material Procurement Specialist',
                        'Direct Manufacturer Factory Prices',
                        'Escrow Protection & Credit Billing Assistance',
                        'Priority Site Delivery Tracking Across India',
                        '24/7 Construction Emergency Hotline',
                    ]),
                    'is_featured'             => true,
                    'is_active'               => true,
                    'sort_order'              => 1,
                    'created_at'              => now(),
                    'updated_at'              => now(),
                ],
            ]);
        }

        // 4. Seed Standard Certified Brands if table is empty
        if (Schema::hasTable('brands') && DB::table('brands')->count() === 0) {
            $defaultBrands = [
                ['name' => 'UltraTech Cement', 'slug' => 'ultratech-cement', 'description' => 'India\'s No.1 Cement manufacturer delivering premium OPC, PPC, and composite cement.', 'is_featured' => true],
                ['name' => 'TATA Tiscon', 'slug' => 'tata-tiscon', 'description' => 'India\'s leading GreenPro certified high-yield Super Ductile TMT rebars.', 'is_featured' => true],
                ['name' => 'Jindal Panther', 'slug' => 'jindal-panther', 'description' => 'Superior strength Fe 550D TMT rebar manufacturer with cutting-edge rolling tech.', 'is_featured' => true],
                ['name' => 'Kajaria Ceramics', 'slug' => 'kajaria-ceramics', 'description' => 'Largest manufacturer of ceramic and vitrified tiles in India with world-class finish.', 'is_featured' => true],
                ['name' => 'Astral Pipes', 'slug' => 'astral-pipes', 'description' => 'Pioneer in plumbing & drainage systems, CPVC, UPVC, and agricultural pipes.', 'is_featured' => true],
                ['name' => 'Havells India', 'slug' => 'havells-india', 'description' => 'Fast moving electrical goods, industrial switchgear, cables, and lighting solutions.', 'is_featured' => true],
                ['name' => 'Asian Paints', 'slug' => 'asian-paints', 'description' => 'India\'s leading paint and decor company offering waterproofing and coatings.', 'is_featured' => true],
                ['name' => 'ACC Cement', 'slug' => 'acc-cement', 'description' => 'Over 85 years of trusted legacy in high-durability cement and ready-mix concrete.', 'is_featured' => true],
                ['name' => 'JSW Steel', 'slug' => 'jsw-steel', 'description' => 'Leading integrated steel producer supplying structural steel and Neosteel TMT.', 'is_featured' => true],
                ['name' => 'Polycab India', 'slug' => 'polycab-india', 'description' => 'India\'s largest wire and cable manufacturer with flame-retardant safety tech.', 'is_featured' => true],
                ['name' => 'Finolex Pipes', 'slug' => 'finolex-pipes', 'description' => 'High quality PVC pipes and fittings for residential and commercial plumbing.', 'is_featured' => false],
                ['name' => 'Dr. Fixit', 'slug' => 'dr-fixit', 'description' => 'Pidilite\'s comprehensive waterproofing solutions for roofs, basements, and structures.', 'is_featured' => false],
            ];

            foreach ($defaultBrands as $b) {
                DB::table('brands')->insert([
                    'name'        => $b['name'],
                    'slug'        => $b['slug'],
                    'description' => $b['description'],
                    'status'      => 'active',
                    'is_featured' => $b['is_featured'],
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('subscription_plans');
    }
};
