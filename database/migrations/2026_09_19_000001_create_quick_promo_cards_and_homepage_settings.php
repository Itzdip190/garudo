<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Quick Promo Cards (4 Cards above Trending Now)
        if (!Schema::hasTable('quick_promo_cards')) {
            Schema::create('quick_promo_cards', function (Blueprint $table) {
                $table->id();
                $table->string('title_line1', 150)->default('BULK ORDER');
                $table->string('title_line2', 150)->default('SAVE MORE');
                $table->string('subtitle', 255)->default('Best Prices on Large Quantities');
                $table->string('button_text', 100)->default('Request Quote');
                $table->string('button_url', 500)->default('/quotations');
                $table->string('button_color', 50)->default('#ff5400');
                $table->string('image', 500)->nullable();
                $table->string('bg_gradient', 255)->default('linear-gradient(135deg, #091b3a 0%, #061226 100%)');
                $table->unsignedInteger('sort_order')->default(0);
                $table->boolean('is_active')->default(true)->index();
                $table->timestamps();
            });
        }

        // 2. Homepage Settings (Dynamic control for Trending Now & other sections)
        if (!Schema::hasTable('homepage_settings')) {
            Schema::create('homepage_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key', 100)->unique();
                $table->text('value')->nullable();
                $table->timestamps();
            });
        }

        // 3. Products Table: Add is_trending_now flag
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (!Schema::hasColumn('products', 'is_trending_now')) {
                    $table->boolean('is_trending_now')->default(false)->after('is_trending')->index();
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('quick_promo_cards');
        Schema::dropIfExists('homepage_settings');

        if (Schema::hasTable('products') && Schema::hasColumn('products', 'is_trending_now')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('is_trending_now');
            });
        }
    }
};
