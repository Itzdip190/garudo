<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ---------------------------------------------------------------
        // VENDORS
        // ---------------------------------------------------------------
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();

            // Auth
            $table->string('name', 200);
            $table->string('email', 200)->unique();
            $table->string('phone', 15);
            $table->string('password');
            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();

            // Company
            $table->string('company_name', 300);
            $table->string('company_slug', 300)->unique();
            $table->string('company_logo', 500)->nullable();
            $table->string('company_banner', 500)->nullable();
            $table->text('company_description')->nullable();
            $table->string('company_website', 300)->nullable();
            $table->enum('business_type', [
                'manufacturer', 'wholesaler', 'trader', 'retailer', 'distributor'
            ])->default('trader');
            $table->year('year_established')->nullable();

            // KYC & GST
            $table->string('gstin', 20)->nullable()->unique();
            $table->string('pan_number', 20)->nullable();
            $table->enum('kyc_status', ['pending', 'submitted', 'verified', 'rejected'])->default('pending');
            $table->timestamp('kyc_submitted_at')->nullable();
            $table->timestamp('kyc_verified_at')->nullable();
            $table->text('kyc_rejected_reason')->nullable();

            // Approval
            $table->enum('approval_status', [
                'pending', 'approved', 'rejected', 'suspended'
            ])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->text('rejection_reason')->nullable();

            // Meta / Stats (denormalized for performance)
            $table->unsignedInteger('total_products')->default(0);
            $table->unsignedInteger('total_orders')->default(0);
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->unsignedInteger('rating_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_verified')->default(false)->comment('Verification badge');

            $table->string('avatar', 500)->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip', 45)->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('email');
            $table->index('company_slug');
            $table->index('approval_status');
            $table->index('kyc_status');
            $table->index('status');

            $table->foreign('approved_by')->references('id')->on('admins')->nullOnDelete();
        });

        // ---------------------------------------------------------------
        // VENDOR PASSWORD RESET TOKENS
        // ---------------------------------------------------------------
        Schema::create('vendor_password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // ---------------------------------------------------------------
        // VENDOR OTP (email / phone verification)
        // ---------------------------------------------------------------
        Schema::create('vendor_otps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id')->nullable()->comment('null before registration complete');
            $table->string('identifier', 200)->comment('email or phone');
            $table->string('type', 50)->comment('email_verify, phone_verify, login');
            $table->string('otp', 10);
            $table->timestamp('expires_at');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->index(['identifier', 'type']);
        });

        // ---------------------------------------------------------------
        // VENDOR KYC DOCUMENTS
        // ---------------------------------------------------------------
        Schema::create('vendor_kyc_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->enum('document_type', [
                'pan', 'gstin_certificate', 'aadhar', 'bank_statement',
                'cancelled_cheque', 'incorporation_certificate', 'trade_license', 'other'
            ]);
            $table->string('file_path', 500);
            $table->string('file_name', 300);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('remarks')->nullable();
            $table->timestamp('uploaded_at')->useCurrent();
            $table->timestamp('verified_at')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamps();

            $table->index('vendor_id');
            $table->foreign('vendor_id')->references('id')->on('vendors')->cascadeOnDelete();
            $table->foreign('verified_by')->references('id')->on('admins')->nullOnDelete();
        });

        // ---------------------------------------------------------------
        // VENDOR BANK DETAILS
        // ---------------------------------------------------------------
        Schema::create('vendor_bank_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id')->unique();
            $table->string('account_holder_name', 200);
            $table->string('account_number', 50);
            $table->string('bank_name', 200);
            $table->string('branch_name', 200);
            $table->string('ifsc_code', 20);
            $table->enum('account_type', ['savings', 'current'])->default('current');
            $table->boolean('is_verified')->default(false);
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('vendors')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_bank_details');
        Schema::dropIfExists('vendor_kyc_documents');
        Schema::dropIfExists('vendor_otps');
        Schema::dropIfExists('vendor_password_reset_tokens');
        Schema::dropIfExists('vendors');
    }
};
