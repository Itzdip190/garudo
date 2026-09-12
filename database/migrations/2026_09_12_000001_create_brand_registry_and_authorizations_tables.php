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
        // 1. Extend `brands` table with ownership, TM details, verification, and protection
        if (Schema::hasTable('brands')) {
            Schema::table('brands', function (Blueprint $table) {
                if (!Schema::hasColumn('brands', 'owner_vendor_id')) {
                    $table->foreignId('owner_vendor_id')->nullable()->after('id')->constrained('vendors')->nullOnDelete();
                }
                if (!Schema::hasColumn('brands', 'brand_type')) {
                    $table->string('brand_type', 50)->default('oem_manufacturer')->after('name');
                }
                if (!Schema::hasColumn('brands', 'banner')) {
                    $table->string('banner', 500)->nullable()->after('logo');
                }
                if (!Schema::hasColumn('brands', 'year_established')) {
                    $table->year('year_established')->nullable()->after('country_of_origin');
                }
                if (!Schema::hasColumn('brands', 'trademark_number')) {
                    $table->string('trademark_number', 100)->nullable()->after('year_established');
                }
                if (!Schema::hasColumn('brands', 'trademark_class')) {
                    $table->string('trademark_class', 50)->nullable()->after('trademark_number');
                }
                if (!Schema::hasColumn('brands', 'trademark_status')) {
                    $table->string('trademark_status', 50)->default('registered')->after('trademark_class');
                }
                if (!Schema::hasColumn('brands', 'trademark_doc')) {
                    $table->string('trademark_doc', 500)->nullable()->after('trademark_status');
                }
                if (!Schema::hasColumn('brands', 'verification_status')) {
                    $table->string('verification_status', 50)->default('authorized')->after('trademark_doc');
                }
                if (!Schema::hasColumn('brands', 'is_protected')) {
                    $table->boolean('is_protected')->default(false)->after('verification_status');
                }
                if (!Schema::hasColumn('brands', 'verified_at')) {
                    $table->timestamp('verified_at')->nullable()->after('is_protected');
                }
                if (!Schema::hasColumn('brands', 'verified_by')) {
                    $table->foreignId('verified_by')->nullable()->after('verified_at')->constrained('admins')->nullOnDelete();
                }
                if (!Schema::hasColumn('brands', 'rejection_reason')) {
                    $table->text('rejection_reason')->nullable()->after('verified_by');
                }
                if (!Schema::hasColumn('brands', 'admin_notes')) {
                    $table->text('admin_notes')->nullable()->after('rejection_reason');
                }
                if (!Schema::hasColumn('brands', 'support_email')) {
                    $table->string('support_email', 150)->nullable()->after('website');
                }
                if (!Schema::hasColumn('brands', 'support_phone')) {
                    $table->string('support_phone', 50)->nullable()->after('support_email');
                }
                if (!Schema::hasColumn('brands', 'warranty_policy')) {
                    $table->text('warranty_policy')->nullable()->after('description');
                }
            });
        }

        // 2. Brand Registrations (Brand Owner application intake)
        if (!Schema::hasTable('brand_registrations')) {
            Schema::create('brand_registrations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('vendor_id')->constrained('vendors')->cascadeOnDelete();
                $table->string('brand_name', 200);
                $table->string('brand_slug', 250);
                $table->string('brand_type', 50)->default('oem_manufacturer');
                $table->json('category_ids')->nullable();
                $table->string('logo_path', 500)->nullable();
                $table->string('banner_path', 500)->nullable();
                $table->text('brand_story')->nullable();
                $table->string('official_website', 300)->nullable();
                $table->string('official_email', 150)->nullable();
                $table->string('official_phone', 50)->nullable();
                $table->year('year_established')->nullable();
                $table->string('country_of_origin', 100)->default('India');
                
                // Legal & Compliance Documentation
                $table->string('trademark_number', 100)->nullable();
                $table->string('trademark_class', 50)->nullable();
                $table->string('trademark_status', 50)->default('registered');
                $table->string('trademark_doc_path', 500)->nullable();
                $table->string('company_incorporation_doc_path', 500)->nullable();
                $table->string('power_of_attorney_doc_path', 500)->nullable();
                $table->text('warranty_policy')->nullable();
                $table->boolean('is_exclusive_distribution')->default(true);

                // Review status
                $table->string('status', 50)->default('pending');
                $table->text('admin_notes')->nullable();
                $table->text('rejection_reason')->nullable();
                $table->foreignId('reviewed_by')->nullable()->constrained('admins')->nullOnDelete();
                $table->timestamp('reviewed_at')->nullable();
                $table->foreignId('created_brand_id')->nullable()->constrained('brands')->nullOnDelete();
                
                $table->timestamps();
                $table->softDeletes();

                $table->index(['vendor_id', 'status'], 'br_vendor_status_idx');
                $table->index('brand_slug', 'br_slug_idx');
            });
        }

        // 3. Brand Dealer & Distributor Authorizations
        if (!Schema::hasTable('brand_dealer_authorizations')) {
            Schema::create('brand_dealer_authorizations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('brand_id')->constrained('brands')->cascadeOnDelete();
                $table->foreignId('vendor_id')->constrained('vendors')->cascadeOnDelete();
                $table->string('authorization_type', 50)->default('authorized_dealer');
                
                $table->string('territory_type', 50)->default('state');
                $table->json('territory_states')->nullable();
                $table->json('territory_cities')->nullable();
                $table->json('territory_pincodes')->nullable();

                // Proof & Contract details
                $table->string('authorization_doc_path', 500)->nullable();
                $table->string('agreement_ref_no', 100)->nullable();
                $table->date('valid_from')->nullable();
                $table->date('valid_until')->nullable();
                $table->boolean('is_lifetime_valid')->default(false);

                // Approval Workflows
                $table->string('brand_owner_approval', 50)->default('not_required');
                $table->timestamp('brand_owner_approved_at')->nullable();
                
                $table->string('garudo_approval_status', 50)->default('pending');
                $table->timestamp('verified_at')->nullable();
                $table->foreignId('verified_by')->nullable()->constrained('admins')->nullOnDelete();
                $table->text('rejection_reason')->nullable();
                $table->text('admin_notes')->nullable();

                // Official Garudo Certificate & Security QR
                $table->string('certificate_number', 100)->unique()->nullable();
                $table->string('qr_token', 128)->nullable();

                $table->string('status', 50)->default('active');
                $table->timestamps();
                $table->softDeletes();

                $table->unique(['brand_id', 'vendor_id', 'authorization_type'], 'bda_unique_pair');
                $table->index(['brand_id', 'garudo_approval_status'], 'bda_brand_approval_idx');
                $table->index(['vendor_id', 'garudo_approval_status'], 'bda_vendor_approval_idx');
            });
        }

        // 4. Audit & Activity Logs for Brand Authorizations
        if (!Schema::hasTable('brand_authorization_logs')) {
            Schema::create('brand_authorization_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('authorization_id')->constrained('brand_dealer_authorizations')->cascadeOnDelete();
                $table->string('action', 100);
                $table->string('actor_type', 50);
                $table->unsignedBigInteger('actor_id')->nullable();
                $table->string('old_status', 50)->nullable();
                $table->string('new_status', 50)->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brand_authorization_logs');
        Schema::dropIfExists('brand_dealer_authorizations');
        Schema::dropIfExists('brand_registrations');
        
        if (Schema::hasTable('brands')) {
            Schema::table('brands', function (Blueprint $table) {
                if (Schema::hasColumn('brands', 'owner_vendor_id')) {
                    $table->dropForeign(['owner_vendor_id']);
                }
                if (Schema::hasColumn('brands', 'verified_by')) {
                    $table->dropForeign(['verified_by']);
                }
                $table->dropColumn([
                    'owner_vendor_id', 'brand_type', 'banner', 'year_established',
                    'trademark_number', 'trademark_class', 'trademark_status', 'trademark_doc',
                    'verification_status', 'is_protected', 'verified_at', 'verified_by',
                    'rejection_reason', 'admin_notes', 'support_email', 'support_phone', 'warranty_policy'
                ]);
            });
        }
    }
};
