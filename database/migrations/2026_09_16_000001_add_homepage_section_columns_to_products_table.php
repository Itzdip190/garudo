<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'sec1_trending')) {
                $table->boolean('sec1_trending')->default(false)->after('is_best_seller')->index();
            }
            if (!Schema::hasColumn('products', 'sec2_best_seller')) {
                $table->boolean('sec2_best_seller')->default(false)->after('sec1_trending')->index();
            }
            if (!Schema::hasColumn('products', 'sec3_trending')) {
                $table->boolean('sec3_trending')->default(false)->after('sec2_best_seller')->index();
            }
            if (!Schema::hasColumn('products', 'sec4_best_seller')) {
                $table->boolean('sec4_best_seller')->default(false)->after('sec3_trending')->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $cols = ['sec1_trending', 'sec2_best_seller', 'sec3_trending', 'sec4_best_seller'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('products', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
