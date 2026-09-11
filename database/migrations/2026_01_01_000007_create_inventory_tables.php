<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ---------------------------------------------------------------
        // WAREHOUSES (multi-warehouse support — future-proof)
        // ---------------------------------------------------------------
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->string('name', 200);
            $table->string('code', 50)->nullable();
            $table->unsignedBigInteger('address_id')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->index('vendor_id');
            $table->foreign('vendor_id')->references('id')->on('vendors')->cascadeOnDelete();
        });

        // ---------------------------------------------------------------
        // INVENTORIES
        // One record per product (simple) OR per variant (variable)
        // Optionally per warehouse
        // ---------------------------------------------------------------
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->unsignedBigInteger('warehouse_id')->nullable();

            // Stock levels
            $table->integer('quantity_available')->default(0);
            $table->integer('quantity_reserved')->default(0)->comment('In pending orders');
            $table->integer('quantity_incoming')->default(0)->comment('Purchase orders in transit');

            // Alerts
            $table->unsignedInteger('low_stock_threshold')->default(10);
            $table->boolean('low_stock_alert_sent')->default(false);

            // Tracking config
            $table->boolean('track_inventory')->default(true);
            $table->boolean('allow_backorder')->default(false);

            $table->timestamp('last_updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->timestamps();

            $table->unique(['product_id', 'variant_id', 'warehouse_id'], 'uniq_inv_product_variant_wh');
            $table->index('product_id');
            $table->index('variant_id');

            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->foreign('variant_id')->references('id')->on('product_variants')->nullOnDelete();
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->nullOnDelete();
        });

        // ---------------------------------------------------------------
        // INVENTORY LOGS (full audit trail — every stock change recorded)
        // ---------------------------------------------------------------
        Schema::create('inventory_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->enum('action', [
                'add', 'reduce', 'adjust', 'reserve', 'release',
                'incoming', 'transfer', 'return'
            ]);
            $table->integer('quantity_before');
            $table->integer('quantity_changed');
            $table->integer('quantity_after');
            $table->string('reference_type', 100)->nullable()->comment('order, purchase, manual, return');
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('note')->nullable();
            $table->string('created_by_type', 100)->nullable()->comment('Admin, Vendor');
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('inventory_id');
            $table->index('product_id');
            $table->index('created_at');
            $table->index(['reference_type', 'reference_id']);

            $table->foreign('inventory_id')->references('id')->on('inventories')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_logs');
        Schema::dropIfExists('inventories');
        Schema::dropIfExists('warehouses');
    }
};
