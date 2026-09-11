<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Customer;
use App\Models\DeliveryPartner;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Vendor;
use App\Services\DeliveryQuoteService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DeliveryPartnerSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Logistics Carriers
        $partners = [
            [
                'name' => 'Garudo Prime Logistics (Fastest & Insured)',
                'code' => 'GARUDO_FLEET',
                'logo' => null,
                'contact_phone' => '+91 1800 200 8899',
                'contact_email' => 'logistics@garudo.com',
                'rating' => 4.95,
                'total_deliveries' => 8420,
                'safety_score' => 100,
                'base_rate_per_kg' => 13.50,
                'transit_speed_score' => 99,
                'is_active' => true,
                'supports_otp_verification' => true,
                'provides_transit_insurance' => true,
                'service_types' => ['express', 'dedicated_fleet', 'door_pickup', 'b2b_crossing'],
            ],
            [
                'name' => 'BlueDart Apex Freight & Courier',
                'code' => 'BLUEDART_EXP',
                'logo' => null,
                'contact_phone' => '+91 22 6260 1234',
                'contact_email' => 'corporate@bluedart.com',
                'rating' => 4.90,
                'total_deliveries' => 15400,
                'safety_score' => 99,
                'base_rate_per_kg' => 17.00,
                'transit_speed_score' => 98,
                'is_active' => true,
                'supports_otp_verification' => true,
                'provides_transit_insurance' => true,
                'service_types' => ['express', 'air_cargo', 'door_pickup'],
            ],
            [
                'name' => 'Delhivery Heavy Surface Express',
                'code' => 'DELHIVERY_SURFACE',
                'logo' => null,
                'contact_phone' => '+91 124 6719500',
                'contact_email' => 'support@delhivery.com',
                'rating' => 4.75,
                'total_deliveries' => 29000,
                'safety_score' => 96,
                'base_rate_per_kg' => 10.50,
                'transit_speed_score' => 92,
                'is_active' => true,
                'supports_otp_verification' => true,
                'provides_transit_insurance' => true,
                'service_types' => ['surface', 'heavy_freight', 'b2b_part_truck'],
            ],
            [
                'name' => 'V-Trans Logistics & Crossing Hubs',
                'code' => 'VTRANS_B2B',
                'logo' => null,
                'contact_phone' => '+91 22 6789 5555',
                'contact_email' => 'info@vtransgroup.com',
                'rating' => 4.65,
                'total_deliveries' => 12100,
                'safety_score' => 95,
                'base_rate_per_kg' => 8.00,
                'transit_speed_score' => 88,
                'is_active' => true,
                'supports_otp_verification' => true,
                'provides_transit_insurance' => true,
                'service_types' => ['b2b_crossing', 'full_truck_load', 'hub_delivery'],
            ],
            [
                'name' => 'TCI Express Nationwide Freight',
                'code' => 'TCI_EXPRESS',
                'logo' => null,
                'contact_phone' => '+91 124 4099999',
                'contact_email' => 'customercare@tciexpress.in',
                'rating' => 4.70,
                'total_deliveries' => 18900,
                'safety_score' => 96,
                'base_rate_per_kg' => 9.50,
                'transit_speed_score' => 90,
                'is_active' => true,
                'supports_otp_verification' => true,
                'provides_transit_insurance' => true,
                'service_types' => ['surface', 'heavy_freight', 'hub_crossing'],
            ],
        ];

        foreach ($partners as $partner) {
            DeliveryPartner::updateOrCreate(['code' => $partner['code']], $partner);
        }

        // 2. Ensure Vendor exists
        $vendor = Vendor::first();
        if (!$vendor) {
            $vendor = Vendor::create([
                'name' => 'Kailash Industrial Steels',
                'email' => 'vendor@garudo.com',
                'phone' => '+91 98200 98765',
                'password' => Hash::make('password123'),
                'company_name' => 'Kailash Industrial Steels Ltd',
                'company_slug' => 'kailash-industrial-steels',
                'business_type' => 'manufacturer',
                'year_established' => 2012,
                'gstin' => '27AAACK1234F1Z5',
                'pan_number' => 'AAACK1234F',
                'kyc_status' => 'verified',
                'approval_status' => 'approved',
                'status' => 'active',
                'rating' => 4.90,
                'rating_count' => 128,
            ]);
        }

        // 3. Ensure Customer exists
        $customer = Customer::first();
        if (!$customer) {
            $customer = Customer::create([
                'name' => 'Rajesh Sharma (Apex Infra)',
                'email' => 'customer@garudo.com',
                'phone' => '+91 98201 54321',
                'password' => Hash::make('password123'),
                'company_name' => 'Apex Infrastructure Pvt Ltd',
                'gstin' => '24AAACA9876Q1ZT',
                'status' => 'active',
            ]);
        }
    }
}

