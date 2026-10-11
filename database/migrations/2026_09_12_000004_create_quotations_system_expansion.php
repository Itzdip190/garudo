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
        // 1. Add Quotation / RFQ Configuration columns to products table
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'allow_quotation')) {
                $table->boolean('allow_quotation')->default(true)->after('sample_stock');
            }
            if (!Schema::hasColumn('products', 'quotation_min_qty')) {
                $table->unsignedInteger('quotation_min_qty')->nullable()->after('allow_quotation');
            }
            if (!Schema::hasColumn('products', 'quotation_custom_fields')) {
                $table->text('quotation_custom_fields')->nullable()->after('quotation_min_qty');
            }
            if (!Schema::hasColumn('products', 'quotation_notes')) {
                $table->text('quotation_notes')->nullable()->after('quotation_custom_fields');
            }
            if (!Schema::hasColumn('products', 'quotation_attachments_allowed')) {
                $table->boolean('quotation_attachments_allowed')->default(true)->after('quotation_notes');
            }
        });

        // 2. Expand / Create quotations table
        if (!Schema::hasTable('quotations')) {
            Schema::create('quotations', function (Blueprint $table) {
                $table->id();
                $table->string('quotation_number', 50)->unique();
                $table->unsignedBigInteger('enquiry_id')->nullable();
                $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
                $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
                $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();

                // Buyer Contact Details
                $table->string('buyer_name', 200);
                $table->string('buyer_email', 200);
                $table->string('buyer_phone', 50);
                $table->string('company_name', 300)->nullable();
                $table->string('gstin', 50)->nullable();
                $table->text('delivery_address')->nullable();
                $table->string('delivery_city', 100)->nullable();
                $table->string('delivery_state', 100)->nullable();
                $table->string('delivery_pincode', 20)->nullable();
                $table->string('project_type', 150)->nullable();

                // Request Specification & Variations
                $table->unsignedInteger('quantity')->default(1);
                $table->string('unit', 50)->default('Units');
                $table->string('selected_variant', 255)->nullable();
                $table->text('custom_variations')->nullable();
                $table->decimal('target_price', 12, 2)->nullable();
                $table->string('urgency_level', 50)->default('Standard (1-2 Weeks)');
                $table->date('expected_delivery_date')->nullable();
                $table->text('buyer_notes')->nullable();
                $table->string('attachment_path', 500)->nullable();

                // Vendor Pricing Proposal
                $table->decimal('unit_price', 12, 2)->default(0.00);
                $table->decimal('gst_rate', 5, 2)->default(18.00);
                $table->decimal('gst_amount', 12, 2)->default(0.00);
                $table->decimal('shipping_amount', 12, 2)->default(0.00);
                $table->decimal('discount_amount', 12, 2)->default(0.00);
                $table->decimal('total_amount', 12, 2)->default(0.00);

                // Commercial Terms
                $table->text('payment_terms')->nullable();
                $table->text('delivery_terms')->nullable();
                $table->unsignedInteger('validity_days')->default(15);
                $table->date('valid_until')->nullable();
                $table->text('vendor_proposal_notes')->nullable();
                $table->string('vendor_attachment_path', 500)->nullable();
                $table->timestamp('quoted_at')->nullable();

                // Lifecycle & Administrative Moderation
                $table->string('status', 50)->default('pending');
                $table->foreignId('converted_order_id')->nullable()->constrained('orders')->nullOnDelete();
                $table->text('admin_notes')->nullable();
                $table->text('rejection_reason')->nullable();

                $table->timestamps();
                $table->softDeletes();

                $table->index(['vendor_id', 'status']);
                $table->index(['customer_id', 'status']);
                $table->index(['product_id', 'status']);
                $table->index('created_at');
            });
        } else {
            Schema::table('quotations', function (Blueprint $table) {
                if (!Schema::hasColumn('quotations', 'buyer_name')) {
                    $table->string('buyer_name', 200)->nullable()->after('product_id');
                }
                if (!Schema::hasColumn('quotations', 'buyer_email')) {
                    $table->string('buyer_email', 200)->nullable()->after('buyer_name');
                }
                if (!Schema::hasColumn('quotations', 'buyer_phone')) {
                    $table->string('buyer_phone', 50)->nullable()->after('buyer_email');
                }
                if (!Schema::hasColumn('quotations', 'company_name')) {
                    $table->string('company_name', 300)->nullable()->after('buyer_phone');
                }
                if (!Schema::hasColumn('quotations', 'gstin')) {
                    $table->string('gstin', 50)->nullable()->after('company_name');
                }
                if (!Schema::hasColumn('quotations', 'delivery_address')) {
                    $table->text('delivery_address')->nullable()->after('gstin');
                }
                if (!Schema::hasColumn('quotations', 'delivery_city')) {
                    $table->string('delivery_city', 100)->nullable()->after('delivery_address');
                }
                if (!Schema::hasColumn('quotations', 'delivery_state')) {
                    $table->string('delivery_state', 100)->nullable()->after('delivery_city');
                }
                if (!Schema::hasColumn('quotations', 'delivery_pincode')) {
                    $table->string('delivery_pincode', 20)->nullable()->after('delivery_state');
                }
                if (!Schema::hasColumn('quotations', 'project_type')) {
                    $table->string('project_type', 150)->nullable()->after('delivery_pincode');
                }
                if (!Schema::hasColumn('quotations', 'unit')) {
                    $table->string('unit', 50)->default('Units')->after('quantity');
                }
                if (!Schema::hasColumn('quotations', 'selected_variant')) {
                    $table->string('selected_variant', 255)->nullable()->after('unit');
                }
                if (!Schema::hasColumn('quotations', 'custom_variations')) {
                    $table->text('custom_variations')->nullable()->after('selected_variant');
                }
                if (!Schema::hasColumn('quotations', 'target_price')) {
                    $table->decimal('target_price', 12, 2)->nullable()->after('custom_variations');
                }
                if (!Schema::hasColumn('quotations', 'urgency_level')) {
                    $table->string('urgency_level', 50)->default('Standard (1-2 Weeks)')->after('target_price');
                }
                if (!Schema::hasColumn('quotations', 'expected_delivery_date')) {
                    $table->date('expected_delivery_date')->nullable()->after('urgency_level');
                }
                if (!Schema::hasColumn('quotations', 'buyer_notes')) {
                    $table->text('buyer_notes')->nullable()->after('expected_delivery_date');
                }
                if (!Schema::hasColumn('quotations', 'attachment_path')) {
                    $table->string('attachment_path', 500)->nullable()->after('buyer_notes');
                }
                if (!Schema::hasColumn('quotations', 'vendor_proposal_notes')) {
                    $table->text('vendor_proposal_notes')->nullable()->after('notes');
                }
                if (!Schema::hasColumn('quotations', 'vendor_attachment_path')) {
                    $table->string('vendor_attachment_path', 500)->nullable()->after('vendor_proposal_notes');
                }
                if (!Schema::hasColumn('quotations', 'quoted_at')) {
                    $table->timestamp('quoted_at')->nullable()->after('vendor_attachment_path');
                }
                if (!Schema::hasColumn('quotations', 'converted_order_id')) {
                    $table->foreignId('converted_order_id')->nullable()->constrained('orders')->nullOnDelete()->after('status');
                }
                if (!Schema::hasColumn('quotations', 'admin_notes')) {
                    $table->text('admin_notes')->nullable()->after('converted_order_id');
                }
                if (!Schema::hasColumn('quotations', 'rejection_reason')) {
                    $table->text('rejection_reason')->nullable()->after('admin_notes');
                }
                if (!Schema::hasColumn('quotations', 'deleted_at')) {
                    $table->softDeletes()->after('updated_at');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'allow_quotation',
                'quotation_min_qty',
                'quotation_custom_fields',
                'quotation_notes',
                'quotation_attachments_allowed',
            ]);
        });

        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn([
                'buyer_name',
                'buyer_email',
                'buyer_phone',
                'company_name',
                'gstin',
                'delivery_address',
                'delivery_city',
                'delivery_state',
                'delivery_pincode',
                'project_type',
                'unit',
                'selected_variant',
                'custom_variations',
                'target_price',
                'urgency_level',
                'expected_delivery_date',
                'buyer_notes',
                'attachment_path',
                'vendor_proposal_notes',
                'vendor_attachment_path',
                'quoted_at',
                'converted_order_id',
                'admin_notes',
                'rejection_reason',
            ]);
        });
    }
};
