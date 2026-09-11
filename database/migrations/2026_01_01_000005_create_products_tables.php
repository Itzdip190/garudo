<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ---------------------------------------------------------------
        // PRODUCTS (core table)
        // ---------------------------------------------------------------
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('brand_id')->nullable();

            // Identity
            $table->string('name', 500);
            $table->string('slug', 550)->unique();
            $table->string('sku', 100)->unique();
            $table->string('barcode', 100)->nullable();
            $table->string('hsn_code', 20)->nullable();

            // Type
            $table->enum('product_type', ['simple', 'variable'])->default('simple');

            // Pricing (base — overridden by variants for variable products)
            $table->decimal('mrp', 12, 2)->default(0.00);
            $table->decimal('selling_price', 12, 2)->default(0.00);
            $table->decimal('wholesale_price', 12, 2)->nullable();
            $table->decimal('retail_price', 12, 2)->nullable();
            $table->decimal('cost_price', 12, 2)->nullable()->comment('Internal only');

            // GST
            $table->decimal('gst_rate', 5, 2)->default(0.00);
            $table->boolean('gst_inclusive')->default(true);

            // Quantity
            $table->unsignedInteger('min_order_qty')->default(1);
            $table->unsignedInteger('max_order_qty')->nullable();
            $table->string('unit', 50)->default('piece')->comment('piece, kg, litre, meter, box');

            // Physical dimensions
            $table->decimal('weight', 10, 3)->nullable();
            $table->enum('weight_unit', ['kg', 'g', 'lb', 'oz'])->default('kg');
            $table->decimal('length', 10, 2)->nullable();
            $table->decimal('width', 10, 2)->nullable();
            $table->decimal('height', 10, 2)->nullable();
            $table->enum('dimension_unit', ['cm', 'mm', 'inch', 'ft', 'm'])->default('cm');

            // Content
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->text('features')->nullable();

            // Shipping
            $table->boolean('free_shipping')->default(false);
            $table->decimal('shipping_charges', 10, 2)->default(0.00);
            $table->boolean('cod_available')->default(true);
            $table->unsignedSmallInteger('shipping_days_min')->default(3);
            $table->unsignedSmallInteger('shipping_days_max')->default(7);

            // Warranty
            $table->boolean('has_warranty')->default(false);
            $table->enum('warranty_type', ['manufacturer', 'seller', 'no_warranty'])->nullable();
            $table->string('warranty_period', 100)->nullable()->comment('1 Year, 6 Months');
            $table->text('warranty_description')->nullable();

            // SEO
            $table->string('meta_title', 300)->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();

            // Flags / Labels
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_trending')->default(false);
            $table->boolean('is_new_arrival')->default(false);
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_best_seller')->default(false);

            // Approval & Status
            $table->enum('approval_status', [
                'pending', 'approved', 'rejected', 'needs_revision'
            ])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->enum('status', [
                'active', 'inactive', 'out_of_stock', 'discontinued'
            ])->default('inactive');

            // Denormalized counters
            $table->unsignedInteger('view_count')->default(0);
            $table->unsignedInteger('wishlist_count')->default(0);
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->unsignedInteger('rating_count')->default(0);
            $table->unsignedInteger('total_sold')->default(0);

            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('vendor_id');
            $table->index('category_id');
            $table->index('brand_id');
            $table->index('sku');
            $table->index(['status', 'approval_status']);
            $table->index(['is_featured', 'is_trending', 'is_new_arrival', 'is_popular']);
            $table->index('published_at');

            // Conditional fulltext for MySQL
            if (DB::getDriverName() === 'mysql') {
                $table->fullText(['name', 'short_description']);
            }

            $table->foreign('vendor_id')->references('id')->on('vendors')->cascadeOnDelete();
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('brand_id')->references('id')->on('brands')->nullOnDelete();
            $table->foreign('approved_by')->references('id')->on('admins')->nullOnDelete();
        });

        // ---------------------------------------------------------------
        // PRODUCT IMAGES
        // ---------------------------------------------------------------
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->string('file_path', 500);
            $table->string('thumbnail_path', 500)->nullable();
            $table->string('alt_text', 300)->nullable();
            $table->boolean('is_primary')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('product_id');
            $table->index(['product_id', 'is_primary']);
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });

        // ---------------------------------------------------------------
        // PRODUCT VIDEOS
        // ---------------------------------------------------------------
        Schema::create('product_videos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->enum('video_type', ['upload', 'youtube', 'vimeo'])->default('youtube');
            $table->string('file_path', 500)->nullable();
            $table->string('video_url', 500)->nullable();
            $table->string('thumbnail', 500)->nullable();
            $table->string('title', 300)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });

        // ---------------------------------------------------------------
        // PRODUCT DOCUMENTS
        // ---------------------------------------------------------------
        Schema::create('product_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->enum('document_type', [
                'brochure', 'certificate', 'manual', 'datasheet',
                'warranty_card', 'test_report', 'other'
            ])->default('brochure');
            $table->string('file_path', 500);
            $table->string('file_name', 300);
            $table->unsignedInteger('file_size')->nullable();
            $table->boolean('is_public')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });

        // ---------------------------------------------------------------
        // PRODUCT SPECIFICATIONS
        // ---------------------------------------------------------------
        Schema::create('product_specifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('spec_group', 150)->nullable();
            $table->string('spec_key', 200);
            $table->text('spec_value');
            $table->unsignedInteger('sort_order')->default(0);

            $table->index('product_id');
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });

        // ---------------------------------------------------------------
        // PRODUCT TAGS
        // ---------------------------------------------------------------
        Schema::create('product_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('tag', 150);

            $table->index('product_id');
            $table->index('tag');
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });

        // ---------------------------------------------------------------
        // PRODUCT BULK PRICES
        // ---------------------------------------------------------------
        Schema::create('product_bulk_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->string('label', 150)->nullable();
            $table->unsignedInteger('min_qty');
            $table->unsignedInteger('max_qty')->nullable();
            $table->decimal('price', 12, 2);
            $table->enum('price_type', ['fixed', 'percent_discount'])->default('fixed');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('product_id');
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_bulk_prices');
        Schema::dropIfExists('product_tags');
        Schema::dropIfExists('product_specifications');
        Schema::dropIfExists('product_documents');
        Schema::dropIfExists('product_videos');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('products');
    }
};
