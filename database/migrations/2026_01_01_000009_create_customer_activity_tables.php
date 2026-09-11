<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ---------------------------------------------------------------
        // CARTS (persistent — works for guests too)
        // ---------------------------------------------------------------
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable()->comment('null = guest');
            $table->string('session_id', 100)->nullable();
            $table->string('coupon_code', 50)->nullable();
            $table->timestamps();

            $table->index('customer_id');
            $table->index('session_id');
        });

        // ---------------------------------------------------------------
        // CART ITEMS
        // ---------------------------------------------------------------
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cart_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_price', 12, 2)->comment('Snapshot at add-to-cart time');
            $table->timestamps();

            $table->index('cart_id');
            $table->foreign('cart_id')->references('id')->on('carts')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });

        // ---------------------------------------------------------------
        // WISHLISTS
        // ---------------------------------------------------------------
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['customer_id', 'product_id', 'variant_id'], 'uniq_wishlist');
            $table->index('customer_id');

            $table->foreign('customer_id')->references('id')->on('customers')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });

        // ---------------------------------------------------------------
        // COMPARE PRODUCTS (session-based)
        // ---------------------------------------------------------------
        Schema::create('compare_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('session_id', 100)->nullable();
            $table->unsignedBigInteger('product_id');
            $table->timestamp('added_at')->useCurrent();

            $table->index('customer_id');
            $table->index('session_id');
        });

        // ---------------------------------------------------------------
        // REVIEWS
        // ---------------------------------------------------------------
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('order_item_id')->nullable()->comment('verified purchase');
            $table->unsignedTinyInteger('rating')->comment('1-5');
            $table->string('title', 300)->nullable();
            $table->text('review')->nullable();
            $table->text('pros')->nullable();
            $table->text('cons')->nullable();
            $table->boolean('is_verified_purchase')->default(false);
            $table->enum('status', ['pending', 'approved', 'rejected', 'hidden'])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedInteger('helpful_count')->default(0);
            $table->unsignedInteger('reported_count')->default(0);
            $table->timestamps();

            $table->index('product_id');
            $table->index('customer_id');
            $table->index('vendor_id');
            $table->index('status');
            $table->index('rating');

            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('approved_by')->references('id')->on('admins')->nullOnDelete();
        });

        // ---------------------------------------------------------------
        // REVIEW IMAGES
        // ---------------------------------------------------------------
        Schema::create('review_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('review_id');
            $table->string('file_path', 500);
            $table->unsignedInteger('sort_order')->default(0);

            $table->foreign('review_id')->references('id')->on('reviews')->cascadeOnDelete();
        });

        // ---------------------------------------------------------------
        // PRODUCT Q&A
        // ---------------------------------------------------------------
        Schema::create('product_qna', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('customer_id');
            $table->text('question');
            $table->text('answer')->nullable();
            $table->string('answered_by_type', 100)->nullable()->comment('Vendor, Admin, Customer');
            $table->unsignedBigInteger('answered_by_id')->nullable();
            $table->timestamp('answered_at')->nullable();
            $table->enum('status', ['pending', 'answered', 'hidden'])->default('pending');
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            $table->index('product_id');
            $table->index('customer_id');
            $table->index('status');

            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_qna');
        Schema::dropIfExists('review_images');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('compare_products');
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
    }
};
