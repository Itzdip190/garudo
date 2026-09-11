<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ---------------------------------------------------------------
        // 1. VENDOR REVIEWS (Direct Store & Supplier Ratings)
        // ---------------------------------------------------------------
        if (!Schema::hasTable('vendor_reviews')) {
            Schema::create('vendor_reviews', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('vendor_id');
                $table->unsignedBigInteger('customer_id')->nullable()->comment('Null for guest/direct QR scan');
                $table->string('reviewer_name', 150);
                $table->string('reviewer_company', 200)->nullable();
                $table->string('reviewer_phone', 30)->nullable();
                $table->string('reviewer_email', 150)->nullable();
                $table->unsignedTinyInteger('rating')->default(5)->comment('1-5 stars');
                $table->string('title', 255)->nullable();
                $table->text('review');
                $table->unsignedTinyInteger('material_quality_rating')->default(5)->comment('1-5 stars');
                $table->unsignedTinyInteger('delivery_rating')->default(5)->comment('1-5 stars');
                $table->unsignedTinyInteger('pricing_rating')->default(5)->comment('1-5 stars');
                $table->boolean('is_verified_buyer')->default(false);
                $table->enum('status', ['approved', 'pending', 'hidden'])->default('approved');
                $table->text('admin_notes')->nullable();
                $table->timestamps();

                $table->index('vendor_id');
                $table->index('customer_id');
                $table->index('status');
                $table->index('rating');

                $table->foreign('vendor_id')->references('id')->on('vendors')->cascadeOnDelete();
            });
        }

        // ---------------------------------------------------------------
        // 2. PLATFORM FEEDBACKS (Garudo Software & Marketplace Reviews)
        // ---------------------------------------------------------------
        if (!Schema::hasTable('platform_feedbacks')) {
            Schema::create('platform_feedbacks', function (Blueprint $table) {
                $table->id();
                $table->enum('user_type', ['customer', 'vendor', 'visitor'])->default('customer');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('name', 150);
                $table->string('company_name', 200)->nullable();
                $table->string('email', 150)->nullable();
                $table->string('phone', 30)->nullable();
                $table->unsignedTinyInteger('rating')->default(5)->comment('1-5 stars');
                $table->enum('category', [
                    'overall',
                    'software_ui',
                    'procurement_speed',
                    'supplier_quality',
                    'delivery_logistics',
                    'customer_support'
                ])->default('overall');
                $table->string('title', 255)->nullable();
                $table->text('feedback');
                $table->text('pros')->nullable();
                $table->text('suggested_improvements')->nullable();
                $table->boolean('is_featured')->default(false);
                $table->enum('status', ['approved', 'pending', 'hidden'])->default('approved');
                $table->timestamps();

                $table->index('user_type');
                $table->index('status');
                $table->index('rating');
                $table->index('category');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_feedbacks');
        Schema::dropIfExists('vendor_reviews');
    }
};
