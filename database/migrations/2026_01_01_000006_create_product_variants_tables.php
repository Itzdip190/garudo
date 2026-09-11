<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ---------------------------------------------------------------
        // PRODUCT VARIANTS (for variable products)
        // ---------------------------------------------------------------
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('sku', 150)->unique();
            $table->string('barcode', 100)->nullable();

            // Pricing (overrides product base price)
            $table->decimal('mrp', 12, 2);
            $table->decimal('selling_price', 12, 2);
            $table->decimal('wholesale_price', 12, 2)->nullable();
            $table->decimal('cost_price', 12, 2)->nullable();

            // Physical
            $table->decimal('weight', 10, 3)->nullable();

            // Image link (which product_image to use for this variant)
            $table->unsignedBigInteger('image_id')->nullable();

            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('product_id');
            $table->index('sku');
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });

        // ---------------------------------------------------------------
        // PRODUCT VARIANT ATTRIBUTES
        // (which combination of attribute values makes this variant)
        // e.g. variant 1 = Color:Red + Size:XL
        // ---------------------------------------------------------------
        Schema::create('product_variant_attributes', function (Blueprint $table) {
            $table->unsignedBigInteger('variant_id');
            $table->unsignedBigInteger('attribute_id');
            $table->unsignedBigInteger('attribute_value_id');
            $table->primary(['variant_id', 'attribute_id']);

            $table->foreign('variant_id')->references('id')->on('product_variants')->cascadeOnDelete();
            $table->foreign('attribute_id')->references('id')->on('attributes');
            $table->foreign('attribute_value_id')->references('id')->on('attribute_values');
        });

        // ---------------------------------------------------------------
        // PRODUCT ATTRIBUTES
        // (general attributes directly on a product — not variant-specific)
        // e.g. Material:Steel, Brand:Bosch
        // ---------------------------------------------------------------
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('attribute_id');
            $table->unsignedBigInteger('attribute_value_id');

            $table->index('product_id');
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->foreign('attribute_id')->references('id')->on('attributes');
            $table->foreign('attribute_value_id')->references('id')->on('attribute_values');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_attributes');
        Schema::dropIfExists('product_variant_attributes');
        Schema::dropIfExists('product_variants');
    }
};
