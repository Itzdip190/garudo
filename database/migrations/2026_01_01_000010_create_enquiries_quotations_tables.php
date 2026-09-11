<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ---------------------------------------------------------------
        // ENQUIRIES (B2B RFQ)
        // ---------------------------------------------------------------
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('enquiry_number', 50)->unique();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('name', 200);
            $table->string('email', 200);
            $table->string('phone', 15);
            $table->string('company_name', 300)->nullable();
            $table->text('message');
            $table->unsignedInteger('quantity')->nullable();
            $table->string('unit', 50)->nullable();
            $table->enum('status', [
                'new', 'read', 'replied', 'closed', 'converted'
            ])->default('new');
            $table->boolean('is_spam')->default(false);
            $table->timestamps();

            $table->index('vendor_id');
            $table->index('customer_id');
            $table->index('status');
            $table->index('created_at');

            $table->foreign('vendor_id')->references('id')->on('vendors');
            $table->foreign('customer_id')->references('id')->on('customers')->nullOnDelete();
            $table->foreign('product_id')->references('id')->on('products')->nullOnDelete();
        });

        // ---------------------------------------------------------------
        // ENQUIRY REPLIES (threaded messaging)
        // ---------------------------------------------------------------
        Schema::create('enquiry_replies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enquiry_id');
            $table->enum('sender_type', ['vendor', 'customer', 'admin']);
            $table->unsignedBigInteger('sender_id');
            $table->text('message');
            $table->string('attachment', 500)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('enquiry_id');
            $table->foreign('enquiry_id')->references('id')->on('enquiries')->cascadeOnDelete();
        });

        // ---------------------------------------------------------------
        // QUOTATIONS
        // ---------------------------------------------------------------
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('quotation_number', 50)->unique();
            $table->unsignedBigInteger('enquiry_id')->nullable();
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();

            // Pricing
            $table->unsignedInteger('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('gst_rate', 5, 2)->default(0.00);
            $table->decimal('gst_amount', 12, 2)->default(0.00);
            $table->decimal('total_amount', 12, 2);
            $table->decimal('discount_amount', 12, 2)->default(0.00);
            $table->decimal('shipping_amount', 12, 2)->default(0.00);

            // Terms
            $table->text('payment_terms')->nullable();
            $table->text('delivery_terms')->nullable();
            $table->unsignedInteger('validity_days')->default(30);
            $table->date('valid_until')->nullable();
            $table->text('notes')->nullable();

            $table->enum('status', [
                'draft', 'sent', 'viewed', 'accepted', 'rejected', 'expired'
            ])->default('draft');
            $table->timestamps();

            $table->index('vendor_id');
            $table->index('customer_id');
            $table->index('status');

            $table->foreign('vendor_id')->references('id')->on('vendors');
            $table->foreign('customer_id')->references('id')->on('customers')->nullOnDelete();
            $table->foreign('enquiry_id')->references('id')->on('enquiries')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotations');
        Schema::dropIfExists('enquiry_replies');
        Schema::dropIfExists('enquiries');
    }
};
