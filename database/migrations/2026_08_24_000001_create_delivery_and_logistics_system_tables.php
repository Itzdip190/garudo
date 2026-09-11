<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ---------------------------------------------------------------
        // 1. DELIVERY / LOGISTICS PARTNERS
        // ---------------------------------------------------------------
        Schema::create('delivery_partners', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('code', 50)->unique();
            $table->string('logo', 500)->nullable();
            $table->string('contact_phone', 20)->nullable();
            $table->string('contact_email', 150)->nullable();
            $table->json('service_types')->nullable()->comment('["express", "surface", "heavy_freight", "b2b_crossing"]');
            $table->decimal('rating', 3, 2)->default(4.80);
            $table->unsignedInteger('total_deliveries')->default(1500);
            $table->unsignedTinyInteger('safety_score')->default(98)->comment('Percentage score out of 100');
            $table->decimal('base_rate_per_kg', 8, 2)->default(12.00);
            $table->unsignedTinyInteger('transit_speed_score')->default(95)->comment('Score out of 100');
            $table->boolean('is_active')->default(true);
            $table->boolean('supports_otp_verification')->default(true);
            $table->boolean('provides_transit_insurance')->default(true);
            $table->timestamps();

            $table->index('code');
            $table->index('is_active');
        });

        // ---------------------------------------------------------------
        // 2. DELIVERY SHIPMENTS
        // ---------------------------------------------------------------
        Schema::create('delivery_shipments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_item_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('vendor_id');
            $table->string('shipment_number', 60)->unique();

            // Logistics Strategy
            $table->enum('delivery_type', [
                'courier_bidding',
                'direct_carrier',
                'vendor_self_transport',
                'transporter_hub_crossing'
            ])->default('courier_bidding');

            $table->unsignedBigInteger('delivery_partner_id')->nullable();

            // Handover & Verification OTPs
            $table->string('pickup_otp', 10)->nullable();
            $table->boolean('pickup_otp_verified')->default(false);
            $table->timestamp('pickup_otp_verified_at')->nullable();

            $table->string('delivery_otp', 10)->nullable();
            $table->boolean('delivery_otp_verified')->default(false);
            $table->timestamp('delivery_otp_verified_at')->nullable();

            // GST E-Way Bill & Transport Documents
            $table->string('eway_bill_number', 50)->nullable();
            $table->timestamp('eway_bill_generated_at')->nullable();
            $table->string('docket_lr_number', 100)->nullable()->comment('Lorry Receipt (LR) / Bilty / Docket Number');
            $table->string('vehicle_number', 50)->nullable();
            $table->string('driver_name', 150)->nullable();
            $table->string('driver_phone', 20)->nullable();
            $table->string('transporter_hub_name', 250)->nullable();
            $table->text('transporter_hub_crossing_address')->nullable();

            // Package Details
            $table->decimal('package_weight_kg', 10, 2)->default(10.00);
            $table->string('package_dimensions', 100)->default('40x30x20 cm');
            $table->unsignedInteger('package_boxes_count')->default(1);
            $table->decimal('quoted_delivery_cost', 12, 2)->default(0.00);

            // Timeline
            $table->dateTime('estimated_pickup_date')->nullable();
            $table->dateTime('estimated_delivery_date')->nullable();
            $table->timestamp('actual_pickup_at')->nullable();
            $table->timestamp('actual_delivered_at')->nullable();

            // Shipment Lifecycle Status
            $table->enum('status', [
                'quote_requested',
                'carrier_assigned',
                'ready_for_pickup',
                'picked_up',
                'in_transit',
                'out_for_delivery',
                'delivered',
                'cancelled'
            ])->default('quote_requested');

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('order_item_id');
            $table->index('order_id');
            $table->index('vendor_id');
            $table->index('delivery_partner_id');
            $table->index('status');
            $table->index('shipment_number');

            $table->foreign('order_item_id')->references('id')->on('order_items')->cascadeOnDelete();
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            $table->foreign('vendor_id')->references('id')->on('vendors');
            $table->foreign('delivery_partner_id')->references('id')->on('delivery_partners')->nullOnDelete();
        });

        // ---------------------------------------------------------------
        // 3. DELIVERY QUOTES (Competitive Agency Bids & Smart Analysis)
        // ---------------------------------------------------------------
        Schema::create('delivery_quotes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shipment_id');
            $table->unsignedBigInteger('delivery_partner_id');
            $table->decimal('quote_amount', 10, 2);
            $table->unsignedInteger('estimated_transit_days')->default(2);
            $table->decimal('safety_rating', 3, 2)->default(4.80);
            $table->boolean('insurance_included')->default(true);
            $table->decimal('smart_score', 5, 2)->default(90.00)->comment('Multi-factor optimization score (0-100)');
            $table->boolean('is_recommended')->default(false);
            $table->boolean('is_lowest_price')->default(false);
            $table->boolean('is_fastest')->default(false);
            $table->enum('status', ['pending', 'selected', 'rejected'])->default('pending');
            $table->text('quote_notes')->nullable();
            $table->timestamps();

            $table->index('shipment_id');
            $table->index('delivery_partner_id');
            $table->index('status');

            $table->foreign('shipment_id')->references('id')->on('delivery_shipments')->cascadeOnDelete();
            $table->foreign('delivery_partner_id')->references('id')->on('delivery_partners')->cascadeOnDelete();
        });

        // ---------------------------------------------------------------
        // 4. EXTEND ORDER ITEMS TABLE FOR PRODUCTION & CUSTOM ORDERS
        // ---------------------------------------------------------------
        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'is_custom_order')) {
                $table->boolean('is_custom_order')->default(false)->after('product_sku');
            }
            if (!Schema::hasColumn('order_items', 'customization_details')) {
                $table->text('customization_details')->nullable()->after('is_custom_order');
            }
            if (!Schema::hasColumn('order_items', 'production_lead_days')) {
                $table->unsignedInteger('production_lead_days')->nullable()->after('customization_details');
            }
            if (!Schema::hasColumn('order_items', 'estimated_ready_date')) {
                $table->date('estimated_ready_date')->nullable()->after('production_lead_days');
            }
            if (!Schema::hasColumn('order_items', 'production_stage')) {
                $table->enum('production_stage', [
                    'not_applicable',
                    'order_accepted',
                    'material_procurement',
                    'in_production',
                    'quality_testing',
                    'packing_completed',
                    'ready_for_dispatch'
                ])->default('not_applicable')->after('estimated_ready_date');
            }
            if (!Schema::hasColumn('order_items', 'production_notes')) {
                $table->text('production_notes')->nullable()->after('production_stage');
            }
            if (!Schema::hasColumn('order_items', 'delivery_trigger_status')) {
                $table->enum('delivery_trigger_status', [
                    'not_triggered',
                    'quotes_requested',
                    'carrier_assigned',
                    'self_managed',
                    'picked_up',
                    'dispatched'
                ])->default('not_triggered')->after('item_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $columns = [
                'is_custom_order',
                'customization_details',
                'production_lead_days',
                'estimated_ready_date',
                'production_stage',
                'production_notes',
                'delivery_trigger_status'
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('order_items', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::dropIfExists('delivery_quotes');
        Schema::dropIfExists('delivery_shipments');
        Schema::dropIfExists('delivery_partners');
    }
};
