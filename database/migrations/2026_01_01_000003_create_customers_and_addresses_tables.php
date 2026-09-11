<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ---------------------------------------------------------------
        // CUSTOMERS
        // ---------------------------------------------------------------
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('email', 200)->unique();
            $table->string('phone', 15)->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('avatar', 500)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('gstin', 20)->nullable()->comment('B2B customers may have GST');
            $table->string('company_name', 300)->nullable();
            $table->enum('status', ['active', 'inactive', 'blocked'])->default('active');
            $table->timestamp('last_login_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('email');
            $table->index('status');
        });

        // ---------------------------------------------------------------
        // CUSTOMER PASSWORD RESET TOKENS
        // ---------------------------------------------------------------
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // ---------------------------------------------------------------
        // CUSTOMER OTP
        // ---------------------------------------------------------------
        Schema::create('customer_otps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('identifier', 200)->comment('email or phone');
            $table->string('type', 50)->comment('email_verify, phone_verify, login');
            $table->string('otp', 10);
            $table->timestamp('expires_at');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->index(['identifier', 'type']);
        });

        // ---------------------------------------------------------------
        // ADDRESSES (polymorphic — vendor & customer)
        // ---------------------------------------------------------------
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->morphs('addressable'); // addressable_type + addressable_id
            $table->string('label', 100)->nullable()->comment('Home, Office, Warehouse');
            $table->string('contact_name', 200);
            $table->string('contact_phone', 15);
            $table->string('address_line1', 500);
            $table->string('address_line2', 500)->nullable();
            $table->string('landmark', 300)->nullable();
            $table->string('city', 150);
            $table->string('state', 150);
            $table->string('country', 100)->default('India');
            $table->string('pincode', 10);
            $table->boolean('is_default')->default(false);
            $table->enum('address_type', ['billing', 'shipping', 'both'])->default('both');
            $table->timestamps();

            $table->index('pincode');
        });

        // ---------------------------------------------------------------
        // SESSIONS TABLE
        // ---------------------------------------------------------------
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('customer_otps');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('customers');
    }
};
