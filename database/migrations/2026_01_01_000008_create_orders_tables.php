<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ---------------------------------------------------------------
        // ORDERS
        // ---------------------------------------------------------------
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 50)->unique();
            $table->unsignedBigInteger('customer_id');

            // Shipping address snapshot (stored at order time — never changes)
            $table->string('shipping_name', 200);
            $table->string('shipping_phone', 15);
            $table->text('shipping_address');
            $table->string('shipping_city', 150);
            $table->string('shipping_state', 150);
            $table->string('shipping_country', 100)->default('India');
            $table->string('shipping_pincode', 10);

            // Billing address snapshot
            $table->string('billing_name', 200)->nullable();
            $table->text('billing_address')->nullable();
            $table->string('billing_city', 150)->nullable();
            $table->string('billing_state', 150)->nullable();
            $table->string('billing_country', 100)->nullable();
            $table->string('billing_pincode', 10)->nullable();
            $table->string('billing_gstin', 20)->nullable();

            // Amounts
            $table->decimal('subtotal', 12, 2);
            $table->decimal('discount_amount', 12, 2)->default(0.00);
            $table->string('coupon_code', 50)->nullable();
            $table->decimal('coupon_discount', 12, 2)->default(0.00);
            $table->decimal('shipping_amount', 12, 2)->default(0.00);
            $table->decimal('tax_amount', 12, 2)->default(0.00);
            $table->decimal('grand_total', 12, 2);

            // Payment
            $table->enum('payment_method', [
                'cod', 'online', 'bank_transfer', 'upi', 'wallet'
            ])->default('cod');
            $table->enum('payment_status', [
                'pending', 'paid', 'failed', 'refunded', 'partial_refund'
            ])->default('pending');
            $table->string('payment_reference', 200)->nullable();
            $table->timestamp('paid_at')->nullable();

            // Order status
            $table->enum('status', [
                'pending', 'confirmed', 'processing', 'shipped',
                'delivered', 'cancelled', 'returned', 'refunded'
            ])->default('pending');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index('customer_id');
            $table->index('status');
            $table->index('payment_status');
            $table->index('created_at');
            $table->foreign('customer_id')->references('id')->on('customers');
        });

        // ---------------------------------------------------------------
        // ORDER ITEMS (one per product line, vendor-tracked)
        // ---------------------------------------------------------------
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('variant_id')->nullable();

            // Snapshot at purchase time (prevents price change issues)
            $table->string('product_name', 500);
            $table->string('product_sku', 100);
            $table->json('variant_info')->nullable()->comment('{"Color":"Red","Size":"XL"}');
            $table->string('product_image', 500)->nullable();

            // Pricing snapshot
            $table->decimal('unit_price', 12, 2);
            $table->decimal('mrp', 12, 2);
            $table->decimal('discount_amount', 12, 2)->default(0.00);
            $table->decimal('gst_rate', 5, 2)->default(0.00);
            $table->decimal('gst_amount', 12, 2)->default(0.00);
            $table->unsignedInteger('quantity');
            $table->decimal('total_amount', 12, 2);

            // Per-item vendor tracking
            $table->enum('item_status', [
                'pending', 'confirmed', 'processing', 'shipped',
                'delivered', 'cancelled', 'returned'
            ])->default('pending');
            $table->string('tracking_number', 200)->nullable();
            $table->string('courier_partner', 200)->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();

            // Commission tracking
            $table->decimal('commission_rate', 5, 2)->default(0.00);
            $table->decimal('commission_amount', 12, 2)->default(0.00);
            $table->decimal('vendor_payout', 12, 2)->default(0.00);
            $table->enum('payout_status', ['pending', 'processing', 'paid'])->default('pending');
            $table->timestamp('payout_at')->nullable();

            $table->timestamps();

            $table->index('order_id');
            $table->index('vendor_id');
            $table->index('product_id');
            $table->index('item_status');
            $table->index('payout_status');

            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            $table->foreign('vendor_id')->references('id')->on('vendors');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
