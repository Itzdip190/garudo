<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'is_sponsored')) {
                $table->boolean('is_sponsored')->default(false)->after('is_best_seller')->index();
            }
            if (!Schema::hasColumn('products', 'is_deal_of_the_day')) {
                $table->boolean('is_deal_of_the_day')->default(false)->after('is_sponsored')->index();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'is_sponsored')) {
                $table->dropColumn('is_sponsored');
            }
            if (Schema::hasColumn('products', 'is_deal_of_the_day')) {
                $table->dropColumn('is_deal_of_the_day');
            }
        });
    }
};
