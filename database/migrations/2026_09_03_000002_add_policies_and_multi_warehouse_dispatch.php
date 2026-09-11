<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ---------------------------------------------------------------
        // 1. CATEGORIES: Add Policy column (Category / Sub-category specific)
        // ---------------------------------------------------------------
        if (Schema::hasTable('categories') && !Schema::hasColumn('categories', 'policy')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->mediumText('policy')->nullable()->comment('Category or Subcategory specific return, warranty & handling policy');
            });
        }

        // ---------------------------------------------------------------
        // 2. PRODUCTS: Add Policy Type & Custom Policy columns
        // ---------------------------------------------------------------
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (!Schema::hasColumn('products', 'policy_type')) {
                    $table->enum('policy_type', ['inherit', 'custom'])->default('inherit')->comment('Inherit from category or use custom product policy');
                }
                if (!Schema::hasColumn('products', 'custom_policy')) {
                    $table->mediumText('custom_policy')->nullable()->comment('Custom product-specific policy');
                }
                if (!Schema::hasColumn('products', 'warehouse_id')) {
                    $table->unsignedBigInteger('warehouse_id')->nullable()->comment('Default storing warehouse');
                }
            });
        }

        // ---------------------------------------------------------------
        // 3. WAREHOUSES: Create or ensure full multi-warehouse schema
        // ---------------------------------------------------------------
        if (!Schema::hasTable('warehouses')) {
            Schema::create('warehouses', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('vendor_id');
                $table->string('name', 200);
                $table->string('code', 50)->nullable();
                $table->string('contact_person', 150)->nullable();
                $table->string('phone', 25)->nullable();
                $table->string('email', 150)->nullable();
                $table->string('address_line1', 500)->nullable();
                $table->string('address_line2', 500)->nullable();
                $table->string('city', 100)->nullable();
                $table->string('state', 100)->nullable();
                $table->string('pincode', 20)->nullable();
                $table->boolean('is_primary')->default(false);
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->index('vendor_id');
                $table->index('state');
                $table->index('status');
                $table->foreign('vendor_id')->references('id')->on('vendors')->cascadeOnDelete();
            });
        } else {
            Schema::table('warehouses', function (Blueprint $table) {
                if (!Schema::hasColumn('warehouses', 'contact_person')) {
                    $table->string('contact_person', 150)->nullable();
                }
                if (!Schema::hasColumn('warehouses', 'phone')) {
                    $table->string('phone', 25)->nullable();
                }
                if (!Schema::hasColumn('warehouses', 'email')) {
                    $table->string('email', 150)->nullable();
                }
                if (!Schema::hasColumn('warehouses', 'address_line1')) {
                    $table->string('address_line1', 500)->nullable();
                }
                if (!Schema::hasColumn('warehouses', 'address_line2')) {
                    $table->string('address_line2', 500)->nullable();
                }
                if (!Schema::hasColumn('warehouses', 'city')) {
                    $table->string('city', 100)->nullable();
                }
                if (!Schema::hasColumn('warehouses', 'state')) {
                    $table->string('state', 100)->nullable();
                }
                if (!Schema::hasColumn('warehouses', 'pincode')) {
                    $table->string('pincode', 20)->nullable();
                }
                if (!Schema::hasColumn('warehouses', 'notes')) {
                    $table->text('notes')->nullable();
                }
            });
        }

        // ---------------------------------------------------------------
        // 4. VENDORS: Add Primary Dispatch Warehouse & State
        // ---------------------------------------------------------------
        if (Schema::hasTable('vendors')) {
            Schema::table('vendors', function (Blueprint $table) {
                if (!Schema::hasColumn('vendors', 'primary_dispatch_warehouse_id')) {
                    $table->unsignedBigInteger('primary_dispatch_warehouse_id')->nullable();
                }
                if (!Schema::hasColumn('vendors', 'primary_dispatch_state')) {
                    $table->string('primary_dispatch_state', 100)->nullable()->comment('State of primary dispatch warehouse');
                }
            });
        }

        // ---------------------------------------------------------------
        // 5. DELIVERY SHIPMENTS: Add Dispatch Warehouse ID
        // ---------------------------------------------------------------
        if (Schema::hasTable('delivery_shipments') && !Schema::hasColumn('delivery_shipments', 'dispatch_warehouse_id')) {
            Schema::table('delivery_shipments', function (Blueprint $table) {
                $table->unsignedBigInteger('dispatch_warehouse_id')->nullable()->comment('Originating dispatch warehouse');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('categories') && Schema::hasColumn('categories', 'policy')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('policy');
            });
        }

        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (Schema::hasColumn('products', 'policy_type')) $table->dropColumn('policy_type');
                if (Schema::hasColumn('products', 'custom_policy')) $table->dropColumn('custom_policy');
                if (Schema::hasColumn('products', 'warehouse_id')) $table->dropColumn('warehouse_id');
            });
        }

        if (Schema::hasTable('vendors')) {
            Schema::table('vendors', function (Blueprint $table) {
                if (Schema::hasColumn('vendors', 'primary_dispatch_warehouse_id')) $table->dropColumn('primary_dispatch_warehouse_id');
                if (Schema::hasColumn('vendors', 'primary_dispatch_state')) $table->dropColumn('primary_dispatch_state');
            });
        }

        if (Schema::hasTable('delivery_shipments') && Schema::hasColumn('delivery_shipments', 'dispatch_warehouse_id')) {
            Schema::table('delivery_shipments', function (Blueprint $table) {
                $table->dropColumn('dispatch_warehouse_id');
            });
        }
    }
};
