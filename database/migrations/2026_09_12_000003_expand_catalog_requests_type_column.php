<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('catalog_requests')) {
            Schema::table('catalog_requests', function (Blueprint $table) {
                $table->string('type', 50)->default('category')->change();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('catalog_requests')) {
            Schema::table('catalog_requests', function (Blueprint $table) {
                $table->string('type', 50)->default('category')->change();
            });
        }
    }
};
