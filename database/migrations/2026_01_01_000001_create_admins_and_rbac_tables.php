<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ---------------------------------------------------------------
        // ADMINS
        // ---------------------------------------------------------------
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('email', 200)->unique();
            $table->string('phone', 15)->nullable();
            $table->string('password');
            $table->string('avatar', 500)->nullable();
            $table->boolean('is_super_admin')->default(false);
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip', 45)->nullable();
            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('email');
            $table->index('status');
        });

        // ---------------------------------------------------------------
        // ADMIN PASSWORD RESET TOKENS
        // ---------------------------------------------------------------
        Schema::create('admin_password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // ---------------------------------------------------------------
        // ROLES
        // ---------------------------------------------------------------
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('display_name', 150);
            $table->text('description')->nullable();
            $table->string('guard_name', 50)->default('admin');
            $table->boolean('is_system')->default(false)->comment('System roles cannot be deleted');
            $table->timestamps();
        });

        // ---------------------------------------------------------------
        // PERMISSIONS
        // ---------------------------------------------------------------
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->unique()->comment('e.g. products.create');
            $table->string('display_name', 200);
            $table->string('module', 100)->comment('e.g. Products, Vendors, Orders');
            $table->text('description')->nullable();
            $table->string('guard_name', 50)->default('admin');
            $table->timestamps();

            $table->index('module');
        });

        // ---------------------------------------------------------------
        // ROLE <-> PERMISSION PIVOT
        // ---------------------------------------------------------------
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('permission_id');
            $table->primary(['role_id', 'permission_id']);
            $table->foreign('role_id')->references('id')->on('roles')->cascadeOnDelete();
            $table->foreign('permission_id')->references('id')->on('permissions')->cascadeOnDelete();
        });

        // ---------------------------------------------------------------
        // ADMIN <-> ROLE PIVOT
        // ---------------------------------------------------------------
        Schema::create('admin_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('role_id');
            $table->primary(['admin_id', 'role_id']);
            $table->foreign('admin_id')->references('id')->on('admins')->cascadeOnDelete();
            $table->foreign('role_id')->references('id')->on('roles')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_roles');
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('admin_password_reset_tokens');
        Schema::dropIfExists('admins');
    }
};
