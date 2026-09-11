<?php

namespace App\Services;

use App\Models\DeliveryPartner;
use App\Models\DeliveryQuote;
use App\Models\DeliveryShipment;
use App\Models\OrderItem;
use Illuminate\Support\Str;

class DeliveryQuoteService
{
    /**
     * Trigger logistics quotes generation for an OrderItem
     */
    public function generateQuotesForOrderItem(OrderItem $item, array $packageParams = []): DeliveryShipment
    {
        // 1. Find or create the active shipment for this item
        $shipment = DeliveryShipment::firstOrNew([
            'order_item_id' => $item->id,
        ]);

        $weight = (float) ($packageParams['weight_kg'] ?? ($item->quantity * 5.00)); // Approx 5kg per unit fallback
        if ($weight <= 0) $weight = 5.00;

        $dimensions = $packageParams['dimensions'] ?? '45x35x25 cm';
        $boxesCount = (int) ($packageParams['boxes_count'] ?? ceil($item->quantity / 5));
        if ($boxesCount <= 0) $boxesCount = 1;

        if (!$shipment->exists) {
            $shipment->order_id = $item->order_id;
            $shipment->vendor_id = $item->vendor_id;
            $shipment->shipment_number = 'GRD-SHP-' . date('Ymd') . '-' . strtoupper(Str::random(5));
            $shipment->delivery_type = 'courier_bidding';
            $shipment->pickup_otp = (string) rand(100000, 999999);
            $shipment->delivery_otp = (string) rand(100000, 999999);
            $shipment->eway_bill_number = 'EB' . date('ymd') . rand(10000000, 99999999);
            $shipment->eway_bill_generated_at = now();
            $shipment->status = 'quote_requested';
        }

        $shipment->package_weight_kg = $weight;
        $shipment->package_dimensions = $dimensions;
        $shipment->package_boxes_count = $boxesCount;
        $shipment->notes = $packageParams['notes'] ?? 'Priority Industrial B2B Consignment';
        $shipment->save();

        // 2. Fetch all active delivery partners
        $partners = DeliveryPartner::where('is_active', true)->get();

        if ($partners->isEmpty()) {
            // Seed default partners if none exist
            $this->seedDefaultPartners();
            $partners = DeliveryPartner::where('is_active', true)->get();
        }

        // Delete any old pending quotes for clean bidding recalculation
        $shipment->quotes()->delete();

        $generatedQuotes = [];

        foreach ($partners as $partner) {
            // Calculate base cost based on partner parameters and package weight
            $ratePerKg = (float) $partner->base_rate_per_kg;
            $handlingFee = 150.00;
            $rawQuote = round(($weight * $ratePerKg) + $handlingFee + rand(-30, 40), 2);
            if ($rawQuote < 180) $rawQuote = 180.00;

            // Determine estimated transit days (1 to 4 days based on partner speed)
            $transitDays = match ($partner->code) {
                'BLUEDART_EXP', 'GARUDO_FLEET' => 1,
                'DELHIVERY_SURFACE', 'TCI_EXPRESS' => 2,
                default => 3,
            };

            $generatedQuotes[] = [
                'shipment_id' => $shipment->id,
                'delivery_partner_id' => $partner->id,
                'quote_amount' => $rawQuote,
                'estimated_transit_days' => $transitDays,
                'safety_rating' => $partner->rating,
                'insurance_included' => $partner->provides_transit_insurance,
                'smart_score' => 0, // Will compute below
                'is_recommended' => false,
                'is_lowest_price' => false,
                'is_fastest' => false,
                'status' => 'pending',
                'quote_notes' => "Direct pickup from Vendor warehouse. Verified GPS tracking & OTP security.",
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // 3. Multi-factor Smart Scoring & Analysis
        $minPrice = min(array_column($generatedQuotes, 'quote_amount'));
        $maxPrice = max(array_column($generatedQuotes, 'quote_amount'));
        $minDays = min(array_column($generatedQuotes, 'estimated_transit_days'));
        $maxDays = max(array_column($generatedQuotes, 'estimated_transit_days'));

        foreach ($generatedQuotes as &$quote) {
            // Normalized Price Score (40% weight): Lower price gives higher score
            $priceDiff = ($maxPrice - $minPrice) > 0 ? ($maxPrice - $minPrice) : 1;
            $priceScore = (1 - (($quote['quote_amount'] - $minPrice) / $priceDiff)) * 40;

            // Normalized Speed Score (35% weight): Fewer transit days give higher score
            $daysDiff = ($maxDays - $minDays) > 0 ? ($maxDays - $minDays) : 1;
            $speedScore = (1 - (($quote['estimated_transit_days'] - $minDays) / $daysDiff)) * 35;

            // Safety Rating Score (25% weight): e.g. 4.9 / 5.0 * 25
            $safetyScore = ($quote['safety_rating'] / 5.0) * 25;

            $totalScore = round($priceScore + $speedScore + $safetyScore, 2);
            $quote['smart_score'] = $totalScore;

            if ($quote['quote_amount'] == $minPrice) {
                $quote['is_lowest_price'] = true;
            }
            if ($quote['estimated_transit_days'] == $minDays) {
                $quote['is_fastest'] = true;
            }
        }
        unset($quote);

        // Find highest smart score for "Recommended"
        usort($generatedQuotes, function ($a, $b) {
            return $b['smart_score'] <=> $a['smart_score'];
        });

        if (!empty($generatedQuotes)) {
            $generatedQuotes[0]['is_recommended'] = true;
        }

        // Insert quotes
        DeliveryQuote::insert($generatedQuotes);

        // Update item status
        $item->update([
            'delivery_trigger_status' => 'quotes_requested',
            'item_status' => 'processing',
        ]);

        return $shipment->fresh(['quotes.partner']);
    }

    /**
     * Confirm a selected delivery partner quote for the shipment
     */
    public function confirmQuote(DeliveryShipment $shipment, int $quoteId): DeliveryShipment
    {
        $quote = DeliveryQuote::where('shipment_id', $shipment->id)->findOrFail($quoteId);

        // Update quote statuses
        DeliveryQuote::where('shipment_id', $shipment->id)->update(['status' => 'rejected']);
        $quote->update(['status' => 'selected']);

        // Update shipment
        $shipment->update([
            'delivery_partner_id' => $quote->delivery_partner_id,
            'delivery_type' => 'courier_bidding',
            'quoted_delivery_cost' => $quote->quote_amount,
            'estimated_pickup_date' => now()->addHours(3),
            'estimated_delivery_date' => now()->addDays($quote->estimated_transit_days),
            'docket_lr_number' => 'LR-' . strtoupper(Str::random(3)) . '-' . rand(100000, 999999),
            'vehicle_number' => 'MH-' . rand(10, 48) . '-' . chr(rand(65, 90)) . chr(rand(65, 90)) . '-' . rand(1000, 9999),
            'driver_name' => 'Rameshwar Logistics Driver',
            'driver_phone' => '+91 98' . rand(10000000, 99999999),
            'status' => 'carrier_assigned',
        ]);

        // Update order item
        $shipment->orderItem->update([
            'courier_partner' => $quote->partner->name,
            'tracking_number' => $shipment->docket_lr_number,
            'delivery_trigger_status' => 'carrier_assigned',
        ]);

        return $shipment->fresh(['deliveryPartner', 'selectedQuote']);
    }

    /**
     * Vendor Self-Handled Transport or Drop-off at Hub Crossing
     */
    public function setSelfHandledTransport(DeliveryShipment $shipment, array $data): DeliveryShipment
    {
        $deliveryType = $data['delivery_type'] ?? 'vendor_self_transport'; // or transporter_hub_crossing

        $shipment->update([
            'delivery_type' => $deliveryType,
            'delivery_partner_id' => null,
            'docket_lr_number' => $data['docket_lr_number'] ?? ('LR-SELF-' . rand(100000, 999999)),
            'vehicle_number' => $data['vehicle_number'] ?? null,
            'driver_name' => $data['driver_name'] ?? null,
            'driver_phone' => $data['driver_phone'] ?? null,
            'transporter_hub_name' => $data['transporter_hub_name'] ?? ($deliveryType === 'transporter_hub_crossing' ? 'Local Transporter Booking Hub' : 'Vendor Direct Fleet'),
            'transporter_hub_crossing_address' => $data['transporter_hub_crossing_address'] ?? null,
            'estimated_delivery_date' => !empty($data['estimated_delivery_date']) ? $data['estimated_delivery_date'] : now()->addDays(2),
            'status' => 'ready_for_pickup',
            'notes' => $data['notes'] ?? 'Vendor Self Handled / Direct Transporter Crossing',
        ]);

        $shipment->orderItem->update([
            'courier_partner' => $deliveryType === 'transporter_hub_crossing' ? 'Transporter Hub Crossing' : 'Vendor Self Delivery',
            'tracking_number' => $shipment->docket_lr_number,
            'delivery_trigger_status' => 'self_managed',
        ]);

        return $shipment->fresh();
    }

    /**
     * Verify Handover OTP when transporter receives goods from vendor
     */
    public function verifyHandoverOtp(DeliveryShipment $shipment, string $inputOtp): array
    {
        if (trim($inputOtp) !== trim($shipment->pickup_otp)) {
            return [
                'success' => false,
                'message' => 'Invalid OTP code. Please enter the correct 6-digit handover OTP provided on the invoice / screen.',
            ];
        }

        $shipment->update([
            'pickup_otp_verified' => true,
            'pickup_otp_verified_at' => now(),
            'actual_pickup_at' => now(),
            'status' => 'picked_up',
        ]);

        $shipment->orderItem->update([
            'item_status' => 'shipped',
            'delivery_trigger_status' => 'dispatched',
            'shipped_at' => now(),
        ]);

        // If all items in the order are shipped, update main order status
        $order = $shipment->order;
        if ($order && $order->items()->where('item_status', '!=', 'shipped')->where('item_status', '!=', 'delivered')->count() === 0) {
            $order->update(['status' => 'shipped']);
        }

        return [
            'success' => true,
            'message' => 'Handover OTP verified successfully! Goods are marked as Picked Up & In Transit.',
        ];
    }

    /**
     * Fallback seeder for default top Indian B2B logistics partners
     */
    private function seedDefaultPartners(): void
    {
        $partners = [
            [
                'name' => 'BlueDart Express Logistics',
                'code' => 'BLUEDART_EXP',
                'rating' => 4.90,
                'safety_score' => 99,
                'base_rate_per_kg' => 18.50,
                'transit_speed_score' => 98,
                'is_active' => true,
                'supports_otp_verification' => true,
                'provides_transit_insurance' => true,
                'service_types' => json_encode(['express', 'air_cargo', 'door_pickup']),
            ],
            [
                'name' => 'Delhivery Heavy Surface & Freight',
                'code' => 'DELHIVERY_SURFACE',
                'rating' => 4.75,
                'safety_score' => 96,
                'base_rate_per_kg' => 11.00,
                'transit_speed_score' => 92,
                'is_active' => true,
                'supports_otp_verification' => true,
                'provides_transit_insurance' => true,
                'service_types' => json_encode(['surface', 'heavy_freight', 'b2b_part_truck']),
            ],
            [
                'name' => 'Garudo Prime Fleet (Direct Dedicated)',
                'code' => 'GARUDO_FLEET',
                'rating' => 4.95,
                'safety_score' => 100,
                'base_rate_per_kg' => 14.00,
                'transit_speed_score' => 99,
                'is_active' => true,
                'supports_otp_verification' => true,
                'provides_transit_insurance' => true,
                'service_types' => json_encode(['express', 'dedicated_truck', 'guaranteed_same_day']),
            ],
            [
                'name' => 'V-Trans Logistics & Hub Crossing',
                'code' => 'VTRANS_B2B',
                'rating' => 4.65,
                'safety_score' => 95,
                'base_rate_per_kg' => 8.50,
                'transit_speed_score' => 88,
                'is_active' => true,
                'supports_otp_verification' => true,
                'provides_transit_insurance' => true,
                'service_types' => json_encode(['b2b_crossing', 'full_truck_load', 'hub_delivery']),
            ],
            [
                'name' => 'TCI Express B2B Freight',
                'code' => 'TCI_EXPRESS',
                'rating' => 4.70,
                'safety_score' => 96,
                'base_rate_per_kg' => 10.00,
                'transit_speed_score' => 90,
                'is_active' => true,
                'supports_otp_verification' => true,
                'provides_transit_insurance' => true,
                'service_types' => json_encode(['surface', 'heavy_freight', 'hub_crossing']),
            ],
        ];

        foreach ($partners as $p) {
            DeliveryPartner::updateOrCreate(['code' => $p['code']], $p);
        }
    }
}
