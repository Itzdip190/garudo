<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ---------------------------------------------------------------
        // PASSWORD RESET REQUESTS TABLE (Offline / Admin Assisted)
        // ---------------------------------------------------------------
        if (!Schema::hasTable('password_reset_requests')) {
            Schema::create('password_reset_requests', function (Blueprint $table) {
                $table->id();
                $table->enum('user_type', ['customer', 'vendor', 'admin'])->default('customer');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('email', 200);
                $table->string('phone', 30)->nullable();
                $table->string('name', 200)->nullable();
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->string('temp_password', 255)->nullable()->comment('Plain demo password for admin to copy and share');
                $table->text('admin_notes')->nullable();
                $table->unsignedBigInteger('handled_by')->nullable()->comment('Admin ID who processed this request');
                $table->timestamp('handled_at')->nullable();
                $table->timestamps();

                $table->index('email');
                $table->index('status');
                $table->index('user_type');
            });
        }

        // ---------------------------------------------------------------
        // ADD MUST_CHANGE_PASSWORD TO CUSTOMERS
        // ---------------------------------------------------------------
        if (Schema::hasTable('customers') && !Schema::hasColumn('customers', 'must_change_password')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->boolean('must_change_password')->default(false)->after('password');
            });
        }

        // ---------------------------------------------------------------
        // ADD MUST_CHANGE_PASSWORD TO VENDORS
        // ---------------------------------------------------------------
        if (Schema::hasTable('vendors') && !Schema::hasColumn('vendors', 'must_change_password')) {
            Schema::table('vendors', function (Blueprint $table) {
                $table->boolean('must_change_password')->default(false)->after('password');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('password_reset_requests');

        if (Schema::hasTable('customers') && Schema::hasColumn('customers', 'must_change_password')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->dropColumn('must_change_password');
            });
        }

        if (Schema::hasTable('vendors') && Schema::hasColumn('vendors', 'must_change_password')) {
            Schema::table('vendors', function (Blueprint $table) {
                $table->dropColumn('must_change_password');
            });
        }
    }
};
