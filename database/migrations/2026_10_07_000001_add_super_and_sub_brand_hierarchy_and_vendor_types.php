<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. brands table updates: Hierarchy (Super Brand vs Sub Brand)
        if (Schema::hasTable('brands')) {
            Schema::table('brands', function (Blueprint $table) {
                if (!Schema::hasColumn('brands', 'parent_id')) {
                    $table->foreignId('parent_id')
                          ->nullable()
                          ->after('id')
                          ->constrained('brands')
                          ->nullOnDelete();
                }
                if (!Schema::hasColumn('brands', 'brand_tier')) {
                    $table->string('brand_tier', 50)
                          ->default('super_brand')
                          ->after('name');
                }
            });
        }

        // 2. brand_registrations table updates: Target parent brand if registering a sub-brand
        if (Schema::hasTable('brand_registrations')) {
            Schema::table('brand_registrations', function (Blueprint $table) {
                if (!Schema::hasColumn('brand_registrations', 'parent_brand_id')) {
                    $table->foreignId('parent_brand_id')
                          ->nullable()
                          ->after('brand_type')
                          ->constrained('brands')
                          ->nullOnDelete();
                }
                if (!Schema::hasColumn('brand_registrations', 'brand_tier')) {
                    $table->string('brand_tier', 50)
                          ->default('super_brand')
                          ->after('parent_brand_id');
                }
            });
        }

        // 3. vendors table updates: Account Type (Standard Vendor vs Brand Owner / Manufacturer)
        if (Schema::hasTable('vendors')) {
            Schema::table('vendors', function (Blueprint $table) {
                if (!Schema::hasColumn('vendors', 'vendor_type')) {
                    $table->string('vendor_type', 50)
                          ->default('standard_vendor')
                          ->after('business_type');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('brands')) {
            Schema::table('brands', function (Blueprint $table) {
                if (Schema::hasColumn('brands', 'parent_id')) {
                    $table->dropForeign(['parent_id']);
                    $table->dropColumn('parent_id');
                }
                if (Schema::hasColumn('brands', 'brand_tier')) {
                    $table->dropColumn('brand_tier');
                }
            });
        }

        if (Schema::hasTable('brand_registrations')) {
            Schema::table('brand_registrations', function (Blueprint $table) {
                if (Schema::hasColumn('brand_registrations', 'parent_brand_id')) {
                    $table->dropForeign(['parent_brand_id']);
                    $table->dropColumn('parent_brand_id');
                }
                if (Schema::hasColumn('brand_registrations', 'brand_tier')) {
                    $table->dropColumn('brand_tier');
                }
            });
        }

        if (Schema::hasTable('vendors')) {
            Schema::table('vendors', function (Blueprint $table) {
                if (Schema::hasColumn('vendors', 'vendor_type')) {
                    $table->dropColumn('vendor_type');
                }
            });
        }
    }
};
