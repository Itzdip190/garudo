<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\Customer;
use App\Models\Order;
use App\Models\SampleRequest;
use Illuminate\Support\Facades\Schema;

class SampleSystemSeeder extends Seeder
{
    /**
     * Seeds initial sample configuration strictly on demo vendor products.
     * Prevents pollution of real/original vendor data.
     */
    public function run(): void
    {
        if (!Schema::hasTable('sample_requests') || !Schema::hasColumn('products', 'has_sample')) {
            return;
        }

        // Find the primary demo vendor (e.g. UltraTech, Tata Tiscon, or first vendor in test environment)
        $demoVendor = Vendor::where('email', 'like', '%demo%')
            ->orWhere('company_name', 'like', '%Demo%')
            ->orWhere('company_name', 'like', '%UltraTech%')
            ->orWhere('company_name', 'like', '%Tata%')
            ->first();

        // Fallback to first vendor if no named demo vendor
        if (!$demoVendor) {
            $demoVendor = Vendor::first();
        }

        if (!$demoVendor) {
            return;
        }

        // Fetch only products belonging strictly to THIS specific demo vendor
        $demoProducts = Product::where('vendor_id', $demoVendor->id)
            ->where('status', 'active')
            ->take(3)
            ->get();

        if ($demoProducts->isEmpty()) {
            // Fallback: take up to 3 products only if they belong to the same single vendor
            $firstProduct = Product::where('status', 'active')->first();
            if ($firstProduct) {
                $demoVendor = $firstProduct->vendor;
                $demoProducts = Product::where('vendor_id', $demoVendor->id)->where('status', 'active')->take(3)->get();
            }
        }

        if ($demoProducts->isEmpty()) {
            return;
        }

        // 1. Configure sample options on ONLY this specific vendor's products
        if (isset($demoProducts[0])) {
            $demoProducts[0]->update([
                'has_sample'              => true,
                'sample_type'             => 'free',
                'sample_price'            => 0.00,
                'sample_shipping_fee'     => 0.00,
                'sample_max_qty'          => 1,
                'sample_unit_description' => '500g Test Kit Pouch (IS:1489 Certified)',
                'sample_instructions'     => 'Air-sealed in moisture-proof packaging. Includes factory NABL test certificate and batch report.',
                'sample_stock'            => 100,
            ]);
        }

        if (isset($demoProducts[1])) {
            $demoProducts[1]->update([
                'has_sample'              => true,
                'sample_type'             => 'paid',
                'sample_price'            => 149.00,
                'sample_shipping_fee'     => 50.00,
                'sample_max_qty'          => 2,
                'sample_unit_description' => '300mm Cut Specimen Bar (Fe 550D TMT Grade)',
                'sample_instructions'     => 'Includes mill test certificate with chemical composition analysis.',
                'sample_stock'            => 50,
            ]);
        }

        if (isset($demoProducts[2])) {
            $demoProducts[2]->update([
                'has_sample'              => true,
                'sample_type'             => 'partial_paid',
                'sample_price'            => 0.00,
                'sample_shipping_fee'     => 99.00,
                'sample_max_qty'          => 1,
                'sample_unit_description' => '1 Full Tile Swatch (300x300mm Matte Specimen)',
                'sample_instructions'     => 'Double foam-padded safety box for zero transit damage.',
                'sample_stock'            => 40,
            ]);
        }

        $customer = Customer::first();
        $sampleProduct = $demoProducts[0];
        $order = Order::whereHas('items', function ($q) use ($demoVendor) {
            $q->where('vendor_id', $demoVendor->id);
        })->first() ?? Order::first();

        // 2. Create sample requests strictly assigned to this single demo vendor only
        // Request 1: Converted to commercial bulk order
        SampleRequest::firstOrCreate(
            ['request_number' => 'SMP-2026-10145'],
            [
                'product_id'          => $sampleProduct->id,
                'vendor_id'           => $demoVendor->id,
                'customer_id'         => $customer?->id,
                'sample_type'         => 'free',
                'quantity'            => 1,
                'sample_price'        => 0.00,
                'sample_shipping_fee' => 0.00,
                'total_amount'        => 0.00,
                'payment_status'      => 'free',
                'payment_method'      => 'free',
                'status'              => 'converted_to_order',
                'converted_order_id'  => $order?->id,
                'buyer_name'          => 'Vikram Singhania',
                'buyer_email'         => 'vikram@singhaniainfra.com',
                'buyer_phone'         => '+91 98201 54321',
                'company_name'        => 'Singhania Infra Projects Ltd',
                'delivery_address'    => 'Tower 4, Sector 62, Site Office',
                'city'                => 'Noida',
                'state'               => 'Uttar Pradesh',
                'pincode'             => '201309',
                'project_type'        => 'Commercial High-Rise',
                'notes'               => 'Tested compressive strength at our site lab. Approved for 2,000 Bag batch purchase.',
                'courier_partner'     => 'BlueDart Air',
                'tracking_number'     => 'BLUEDART-8829104',
                'dispatched_at'       => now()->subDays(10),
                'delivered_at'        => now()->subDays(7),
                'vendor_notes'        => 'High priority corporate lead. Converted to commercial PO.',
            ]
        );

        // Request 2: Delivered sample
        if (isset($demoProducts[1])) {
            SampleRequest::firstOrCreate(
                ['request_number' => 'SMP-2026-29182'],
                [
                    'product_id'          => $demoProducts[1]->id,
                    'vendor_id'           => $demoVendor->id,
                    'customer_id'         => $customer?->id,
                    'sample_type'         => 'paid',
                    'quantity'            => 1,
                    'sample_price'        => 149.00,
                    'sample_shipping_fee' => 50.00,
                    'total_amount'        => 199.00,
                    'payment_status'      => 'paid',
                    'payment_method'      => 'online_demo',
                    'transaction_id'      => 'TXN-SMP-89218392',
                    'status'              => 'delivered',
                    'buyer_name'          => 'Ananya Deshmukh',
                    'buyer_email'         => 'ananya@deshmukhconstructions.in',
                    'buyer_phone'         => '+91 99876 12345',
                    'company_name'        => 'Deshmukh & Associates Developers',
                    'delivery_address'    => 'Plot 45, Baner Road, Near Highway',
                    'city'                => 'Pune',
                    'state'               => 'Maharashtra',
                    'pincode'             => '411045',
                    'project_type'        => 'Residential Villa Project',
                    'notes'               => 'Need specimen for client aesthetic & structural approval.',
                    'courier_partner'     => 'Delhivery Express',
                    'tracking_number'     => 'DELHIVERY-7740192',
                    'dispatched_at'       => now()->subDays(4),
                    'delivered_at'        => now()->subDays(1),
                    'vendor_notes'        => 'Specimen delivered safely. Follow-up WhatsApp sent.',
                ]
            );
        }

        // Request 3: Dispatched (In Transit)
        if (isset($demoProducts[2])) {
            SampleRequest::firstOrCreate(
                ['request_number' => 'SMP-2026-44819'],
                [
                    'product_id'          => $demoProducts[2]->id,
                    'vendor_id'           => $demoVendor->id,
                    'customer_id'         => $customer?->id,
                    'sample_type'         => 'partial_paid',
                    'quantity'            => 1,
                    'sample_price'        => 0.00,
                    'sample_shipping_fee' => 99.00,
                    'total_amount'        => 99.00,
                    'payment_status'      => 'paid',
                    'payment_method'      => 'online_demo',
                    'transaction_id'      => 'TXN-SMP-44819201',
                    'status'              => 'dispatched',
                    'buyer_name'          => 'Rohan Kulkarni',
                    'buyer_email'         => 'rohan@kulkarnigroup.com',
                    'buyer_phone'         => '+91 97654 32109',
                    'company_name'        => 'Kulkarni Builders',
                    'delivery_address'    => 'Shubham Heights Site Office, Ring Road',
                    'city'                => 'Nagpur',
                    'state'               => 'Maharashtra',
                    'pincode'             => '440015',
                    'project_type'        => 'Architectural Specification',
                    'notes'               => 'Architect requested swatch for floor finish selection.',
                    'courier_partner'     => 'DTDC Courier',
                    'tracking_number'     => 'DTDC-9920184',
                    'dispatched_at'       => now()->subDays(1),
                    'vendor_notes'        => 'Dispatched via Air Express. Expected delivery tomorrow.',
                ]
            );
        }

        // Request 4: Pending review
        SampleRequest::firstOrCreate(
            ['request_number' => 'SMP-2026-73190'],
            [
                'product_id'          => $sampleProduct->id,
                'vendor_id'           => $demoVendor->id,
                'customer_id'         => $customer?->id,
                'sample_type'         => 'free',
                'quantity'            => 1,
                'sample_price'        => 0.00,
                'sample_shipping_fee' => 0.00,
                'total_amount'        => 0.00,
                'payment_status'      => 'free',
                'payment_method'      => 'free',
                'status'              => 'pending',
                'buyer_name'          => 'Deepak Chawla',
                'buyer_email'         => 'deepak@chawlacontractors.com',
                'buyer_phone'         => '+91 98111 22334',
                'company_name'        => 'Chawla Turnkey Projects',
                'delivery_address'    => 'Plot 88, Okhla Industrial Area Phase 3',
                'city'                => 'New Delhi',
                'state'               => 'Delhi',
                'pincode'             => '110020',
                'project_type'        => 'Industrial Warehouse Construction',
                'notes'               => 'Urgent sample requirement for government tender qualification.',
            ]
        );
    }
}
