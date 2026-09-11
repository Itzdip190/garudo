<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ---------------------------------------------------------------
        // 1. VENDOR CERTIFICATES (Verified Channel Partner Certificates)
        // ---------------------------------------------------------------
        if (!Schema::hasTable('vendor_certificates')) {
            Schema::create('vendor_certificates', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('vendor_id');
                $table->string('certificate_number', 60)->unique()->comment('e.g. GAR-VCP-2026-9F83A');
                $table->string('verification_token', 64)->unique()->comment('Secure token for public verification');
                $table->date('issue_date');
                $table->date('expiry_date')->index();
                $table->string('digital_signature_hash', 128)->comment('SHA-256 tamper-evident digital seal');
                $table->longText('qr_code_svg')->nullable()->comment('Standalone Vector SVG QR code');
                $table->enum('status', ['active', 'expired', 'revoked', 'suspended'])->default('active');
                $table->text('revocation_reason')->nullable();
                $table->json('metadata')->nullable()->comment('Snapshot of company legal name, GSTIN, categories at issuance');
                $table->unsignedInteger('download_count')->default(0);
                $table->timestamps();

                $table->index(['vendor_id', 'status']);
                $table->index('certificate_number');
                $table->index('verification_token');
                $table->foreign('vendor_id')->references('id')->on('vendors')->cascadeOnDelete();
            });
        }

        // ---------------------------------------------------------------
        // 2. EXPAND VENDORS TABLE FOR MULTI-STEP KYC & MEMBERSHIP INITIALIZATION
        // ---------------------------------------------------------------
        if (Schema::hasTable('vendors')) {
            Schema::table('vendors', function (Blueprint $table) {
                if (!Schema::hasColumn('vendors', 'kyc_step')) {
                    $table->unsignedTinyInteger('kyc_step')->default(1)->comment('Current step in 4-step KYC onboarding wizard');
                }
                if (!Schema::hasColumn('vendors', 'kyc_review_notes')) {
                    $table->text('kyc_review_notes')->nullable()->comment('Admin feedback & instructions during verification');
                }
                if (!Schema::hasColumn('vendors', 'membership_plan')) {
                    $table->string('membership_plan', 50)->default('1_year_free')->comment('Active plan identifier');
                }
                if (!Schema::hasColumn('vendors', 'membership_expires_at')) {
                    $table->date('membership_expires_at')->nullable()->index();
                }
            });
        }

        // ---------------------------------------------------------------
        // 3. EXPAND VENDOR KYC DOCUMENTS WITH REJECTION NOTES IF MISSING
        // ---------------------------------------------------------------
        if (Schema::hasTable('vendor_kyc_documents')) {
            Schema::table('vendor_kyc_documents', function (Blueprint $table) {
                if (!Schema::hasColumn('vendor_kyc_documents', 'rejection_notes')) {
                    $table->text('rejection_notes')->nullable()->after('remarks');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_certificates');

        if (Schema::hasTable('vendors')) {
            Schema::table('vendors', function (Blueprint $table) {
                if (Schema::hasColumn('vendors', 'kyc_step')) {
                    $table->dropColumn('kyc_step');
                }
                if (Schema::hasColumn('vendors', 'kyc_review_notes')) {
                    $table->dropColumn('kyc_review_notes');
                }
                if (Schema::hasColumn('vendors', 'membership_plan')) {
                    $table->dropColumn('membership_plan');
                }
                if (Schema::hasColumn('vendors', 'membership_expires_at')) {
                    $table->dropColumn('membership_expires_at');
                }
            });
        }

        if (Schema::hasTable('vendor_kyc_documents')) {
            Schema::table('vendor_kyc_documents', function (Blueprint $table) {
                if (Schema::hasColumn('vendor_kyc_documents', 'rejection_notes')) {
                    $table->dropColumn('rejection_notes');
                }
            });
        }
    }
};
