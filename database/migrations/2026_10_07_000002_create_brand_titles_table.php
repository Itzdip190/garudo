<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('brand_titles')) {
            Schema::create('brand_titles', function (Blueprint $table) {
                $table->id();
                $table->string('name', 150);
                $table->string('slug', 150)->unique();
                $table->string('badge_color', 50)->default('success'); // success, primary, warning, info, dark, purple, gold
                $table->text('description')->nullable();
                $table->boolean('is_parent_tier')->default(false); // Can act as parent super brand
                $table->unsignedInteger('sort_order')->default(0);
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->timestamps();
            });

            // Seed default standard titles
            $defaultTitles = [
                [
                    'name'           => 'Super Brand',
                    'slug'           => 'super-brand',
                    'badge_color'    => 'success',
                    'description'    => 'Top-tier master entity (e.g. Tata Group, UltraTech, JSW)',
                    'is_parent_tier' => true,
                    'sort_order'     => 1,
                    'status'         => 'active',
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ],
                [
                    'name'           => 'Sub Brand',
                    'slug'           => 'sub-brand',
                    'badge_color'    => 'primary',
                    'description'    => 'Child variant / product line under a parent Super Brand',
                    'is_parent_tier' => false,
                    'sort_order'     => 2,
                    'status'         => 'active',
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ],
                [
                    'name'           => 'National Brand',
                    'slug'           => 'national-brand',
                    'badge_color'    => 'purple',
                    'description'    => 'Nationwide certified building material brand with pan-India distribution',
                    'is_parent_tier' => true,
                    'sort_order'     => 3,
                    'status'         => 'active',
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ],
                [
                    'name'           => 'Premium Brand',
                    'slug'           => 'premium-brand',
                    'badge_color'    => 'gold',
                    'description'    => 'Luxury / high-specification architectural materials brand',
                    'is_parent_tier' => false,
                    'sort_order'     => 4,
                    'status'         => 'active',
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ],
                [
                    'name'           => 'OEM Manufacturer',
                    'slug'           => 'oem-manufacturer',
                    'badge_color'    => 'info',
                    'description'    => 'Direct industrial manufacturer / factory brand',
                    'is_parent_tier' => false,
                    'sort_order'     => 5,
                    'status'         => 'active',
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ],
            ];

            try {
                DB::table('brand_titles')->insert($defaultTitles);
            } catch (\Throwable $e) {
                // Ignore if DB not connected at migration compile time
            }
        }

        // Add brand_title_id to brands table
        if (Schema::hasTable('brands')) {
            Schema::table('brands', function (Blueprint $table) {
                if (!Schema::hasColumn('brands', 'brand_title_id')) {
                    $table->foreignId('brand_title_id')
                          ->nullable()
                          ->after('parent_id')
                          ->constrained('brand_titles')
                          ->nullOnDelete();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('brands') && Schema::hasColumn('brands', 'brand_title_id')) {
            Schema::table('brands', function (Blueprint $table) {
                $table->dropForeign(['brand_title_id']);
                $table->dropColumn('brand_title_id');
            });
        }

        Schema::dropIfExists('brand_titles');
    }
};
