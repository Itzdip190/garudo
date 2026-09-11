<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ---------------------------------------------------------------
        // CATEGORIES (nested hierarchy — self-referencing)
        // ---------------------------------------------------------------
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable()->default(null);
            $table->string('name', 200);
            $table->string('slug', 250)->unique();
            $table->text('description')->nullable();
            $table->string('image', 500)->nullable();
            $table->string('icon', 200)->nullable()->comment('CSS icon class or SVG path');
            $table->string('meta_title', 300)->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->decimal('commission_rate', 5, 2)->default(0.00)->comment('Category-level commission %');
            $table->boolean('is_featured')->default(false);
            $table->boolean('show_in_menu')->default(true);
            $table->boolean('show_in_home')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->unsignedTinyInteger('depth')->default(0)->comment('0=root,1=sub,2=sub-sub');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index('parent_id');
            $table->index('slug');
            $table->index('status');
            $table->index('depth');
            $table->index(['is_featured', 'show_in_home']);

            $table->foreign('parent_id')->references('id')->on('categories')->nullOnDelete();
        });

        // ---------------------------------------------------------------
        // VENDOR <-> CATEGORY PIVOT (admin assigns categories to vendors)
        // ---------------------------------------------------------------
        Schema::create('vendor_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('category_id');
            $table->boolean('is_primary')->default(false);
            $table->timestamp('assigned_at')->useCurrent();
            $table->unsignedBigInteger('assigned_by')->nullable();
            $table->primary(['vendor_id', 'category_id']);

            $table->foreign('vendor_id')->references('id')->on('vendors')->cascadeOnDelete();
            $table->foreign('category_id')->references('id')->on('categories')->cascadeOnDelete();
        });

        // ---------------------------------------------------------------
        // BRANDS
        // ---------------------------------------------------------------
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('slug', 250)->unique();
            $table->string('logo', 500)->nullable();
            $table->text('description')->nullable();
            $table->string('website', 300)->nullable();
            $table->string('country_of_origin', 100)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('meta_title', 300)->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('slug');
            $table->index('status');
            $table->index('is_featured');
        });

        // ---------------------------------------------------------------
        // ATTRIBUTES (Color, Size, Material, etc.)
        // ---------------------------------------------------------------
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('display_name', 200);
            $table->enum('type', ['select', 'radio', 'color', 'checkbox', 'text'])->default('select');
            $table->boolean('is_filterable')->default(true);
            $table->boolean('is_required')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->index('status');
        });

        // ---------------------------------------------------------------
        // ATTRIBUTE VALUES (Red, Blue, XL, Cotton, etc.)
        // ---------------------------------------------------------------
        Schema::create('attribute_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attribute_id');
            $table->string('value', 200);
            $table->string('display_value', 200);
            $table->string('color_code', 10)->nullable()->comment('hex code for color swatches');
            $table->string('image', 500)->nullable()->comment('swatch image');
            $table->unsignedInteger('sort_order')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->index('attribute_id');
            $table->foreign('attribute_id')->references('id')->on('attributes')->cascadeOnDelete();
        });

        // ---------------------------------------------------------------
        // CATEGORY <-> ATTRIBUTE mapping
        // ---------------------------------------------------------------
        Schema::create('category_attributes', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('attribute_id');
            $table->boolean('is_required')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->primary(['category_id', 'attribute_id']);

            $table->foreign('category_id')->references('id')->on('categories')->cascadeOnDelete();
            $table->foreign('attribute_id')->references('id')->on('attributes')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_attributes');
        Schema::dropIfExists('attribute_values');
        Schema::dropIfExists('attributes');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('vendor_categories');
        Schema::dropIfExists('categories');
    }
};
