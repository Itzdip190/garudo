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
        // 1. Add sample configuration fields to products table
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'has_sample')) {
                $table->boolean('has_sample')->default(false)->after('cod_available');
            }
            if (!Schema::hasColumn('products', 'sample_type')) {
                $table->enum('sample_type', ['free', 'paid', 'partial_paid'])->default('free')->after('has_sample');
            }
            if (!Schema::hasColumn('products', 'sample_price')) {
                $table->decimal('sample_price', 10, 2)->default(0.00)->after('sample_type');
            }
            if (!Schema::hasColumn('products', 'sample_shipping_fee')) {
                $table->decimal('sample_shipping_fee', 10, 2)->default(0.00)->after('sample_price');
            }
            if (!Schema::hasColumn('products', 'sample_max_qty')) {
                $table->unsignedInteger('sample_max_qty')->default(1)->after('sample_shipping_fee');
            }
            if (!Schema::hasColumn('products', 'sample_unit_description')) {
                $table->string('sample_unit_description', 255)->nullable()->after('sample_max_qty');
            }
            if (!Schema::hasColumn('products', 'sample_instructions')) {
                $table->text('sample_instructions')->nullable()->after('sample_unit_description');
            }
            if (!Schema::hasColumn('products', 'sample_stock')) {
                $table->integer('sample_stock')->nullable()->after('sample_instructions');
            }
        });

        // 2. Create sample_requests table
        if (!Schema::hasTable('sample_requests')) {
            Schema::create('sample_requests', function (Blueprint $table) {
                $table->id();
                $table->string('request_number', 50)->unique();
                $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
                $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
                $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
                
                // Sample pricing & type at time of request
                $table->enum('sample_type', ['free', 'paid', 'partial_paid'])->default('free');
                $table->unsignedInteger('quantity')->default(1);
                $table->decimal('sample_price', 10, 2)->default(0.00);
                $table->decimal('sample_shipping_fee', 10, 2)->default(0.00);
                $table->decimal('total_amount', 10, 2)->default(0.00);

                // Payment info
                $table->enum('payment_status', ['free', 'pending', 'paid', 'waived', 'refunded'])->default('free');
                $table->string('payment_method', 50)->default('free');
                $table->string('transaction_id', 150)->nullable();

                // Status Lifecycle
                $table->enum('status', ['pending', 'approved', 'dispatched', 'delivered', 'rejected', 'converted_to_order'])->default('pending');
                $table->foreignId('converted_order_id')->nullable()->constrained('orders')->nullOnDelete();

                // Buyer details
                $table->string('buyer_name', 255);
                $table->string('buyer_email', 255);
                $table->string('buyer_phone', 50);
                $table->string('company_name', 255)->nullable();
                $table->text('delivery_address');
                $table->string('city', 100);
                $table->string('state', 100);
                $table->string('pincode', 20);
                $table->string('project_type', 150)->nullable();
                $table->text('notes')->nullable();

                // Logistics & Dispatch
                $table->string('courier_partner', 100)->nullable();
                $table->string('tracking_number', 100)->nullable();
                $table->timestamp('dispatched_at')->nullable();
                $table->timestamp('delivered_at')->nullable();

                // Vendor feedback & moderation
                $table->text('vendor_notes')->nullable();
                $table->text('rejection_reason')->nullable();

                $table->timestamps();
                $table->softDeletes();

                $table->index(['vendor_id', 'status']);
                $table->index(['customer_id', 'status']);
                $table->index(['product_id', 'status']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sample_requests');

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'has_sample',
                'sample_type',
                'sample_price',
                'sample_shipping_fee',
                'sample_max_qty',
                'sample_unit_description',
                'sample_instructions',
                'sample_stock',
            ]);
        });
    }
};
