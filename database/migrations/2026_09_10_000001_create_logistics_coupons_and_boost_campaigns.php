<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ---------------------------------------------------------------
        // 1. VENDORS: Add Logistics & F.O.R. Delivery Configuration
        // ---------------------------------------------------------------
        if (Schema::hasTable('vendors')) {
            Schema::table('vendors', function (Blueprint $table) {
                if (!Schema::hasColumn('vendors', 'dispatch_time_type')) {
                    $table->string('dispatch_time_type', 50)->default('2-4')->after('primary_dispatch_state');
                }
                if (!Schema::hasColumn('vendors', 'custom_dispatch_min_days')) {
                    $table->unsignedInteger('custom_dispatch_min_days')->nullable()->after('dispatch_time_type');
                }
                if (!Schema::hasColumn('vendors', 'custom_dispatch_max_days')) {
                    $table->unsignedInteger('custom_dispatch_max_days')->nullable()->after('custom_dispatch_min_days');
                }
                if (!Schema::hasColumn('vendors', 'custom_dispatch_notes')) {
                    $table->string('custom_dispatch_notes', 255)->nullable()->after('custom_dispatch_max_days');
                }
                if (!Schema::hasColumn('vendors', 'is_for_enabled')) {
                    $table->boolean('is_for_enabled')->default(true)->after('custom_dispatch_notes');
                }
                if (!Schema::hasColumn('vendors', 'for_condition_type')) {
                    $table->enum('for_condition_type', ['amount', 'quantity'])->default('amount')->after('is_for_enabled');
                }
                if (!Schema::hasColumn('vendors', 'for_min_amount')) {
                    $table->decimal('for_min_amount', 12, 2)->default(50000.00)->after('for_condition_type');
                }
                if (!Schema::hasColumn('vendors', 'for_min_qty')) {
                    $table->unsignedInteger('for_min_qty')->nullable()->default(100)->after('for_min_amount');
                }
                if (!Schema::hasColumn('vendors', 'for_qty_unit')) {
                    $table->string('for_qty_unit', 50)->default('Bags')->after('for_min_qty');
                }
                if (!Schema::hasColumn('vendors', 'for_scope')) {
                    $table->enum('for_scope', ['city', 'state', 'pan_india'])->default('pan_india')->after('for_qty_unit');
                }
                if (!Schema::hasColumn('vendors', 'for_scope_details')) {
                    $table->string('for_scope_details', 255)->nullable()->after('for_scope');
                }
            });
        }

        // ---------------------------------------------------------------
        // 2. COUPONS: Vendor Promotional Discount Coupons
        // ---------------------------------------------------------------
        if (!Schema::hasTable('coupons')) {
            Schema::create('coupons', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('vendor_id')->nullable()->comment('null = platform wide, vendor_id = vendor specific');
                $table->string('code', 50)->unique();
                $table->string('title', 200)->nullable();
                $table->text('description')->nullable();
                $table->enum('discount_type', ['percentage', 'fixed'])->default('percentage');
                $table->decimal('discount_value', 10, 2);
                $table->decimal('min_order_amount', 12, 2)->default(0.00);
                $table->decimal('max_discount_amount', 12, 2)->nullable();
                $table->unsignedInteger('usage_limit')->nullable();
                $table->unsignedInteger('used_count')->default(0);
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->enum('status', ['active', 'inactive', 'expired'])->default('active');
                $table->timestamps();

                $table->index('vendor_id');
                $table->index('status');
                $table->foreign('vendor_id')->references('id')->on('vendors')->cascadeOnDelete();
            });
        }

        // ---------------------------------------------------------------
        // 3. PROMOTIONAL CAMPAIGNS / BOOSTS (With Admin Verification Flow)
        // ---------------------------------------------------------------
        if (!Schema::hasTable('promotional_campaigns')) {
            Schema::create('promotional_campaigns', function (Blueprint $table) {
                $table->id();
                $table->string('campaign_code', 50)->unique();
                $table->unsignedBigInteger('vendor_id');
                $table->string('plan_tier', 100)->comment('category_top, homepage_spotlight, pan_india_boost');
                $table->string('plan_title', 200);
                $table->unsignedInteger('duration_days')->default(7);
                $table->json('product_ids')->nullable()->comment('Array of boosted product IDs');
                $table->decimal('amount', 10, 2);
                $table->decimal('gst_amount', 10, 2)->default(0.00);
                $table->decimal('total_payable', 10, 2);
                $table->string('payment_mode', 50)->default('upi_qr');
                $table->string('payment_reference_utr', 100)->nullable();
                $table->enum('status', [
                    'pending_payment_approval',
                    'active',
                    'paused',
                    'completed',
                    'rejected'
                ])->default('pending_payment_approval');
                $table->timestamp('requested_at')->useCurrent();
                $table->timestamp('activated_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->text('admin_notes')->nullable();
                $table->unsignedInteger('impressions_count')->default(0);
                $table->unsignedInteger('clicks_count')->default(0);
                $table->timestamps();

                $table->index('vendor_id');
                $table->index('status');
                $table->foreign('vendor_id')->references('id')->on('vendors')->cascadeOnDelete();
                $table->foreign('approved_by')->references('id')->on('admins')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('promotional_campaigns');
        Schema::dropIfExists('coupons');

        if (Schema::hasTable('vendors')) {
            Schema::table('vendors', function (Blueprint $table) {
                $columns = [
                    'dispatch_time_type', 'custom_dispatch_min_days', 'custom_dispatch_max_days',
                    'custom_dispatch_notes', 'is_for_enabled', 'for_condition_type',
                    'for_min_amount', 'for_min_qty', 'for_qty_unit', 'for_scope', 'for_scope_details'
                ];
                foreach ($columns as $col) {
                    if (Schema::hasColumn('vendors', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
