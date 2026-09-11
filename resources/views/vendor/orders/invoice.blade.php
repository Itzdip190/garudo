<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tax Invoice & E-Way Bill #{{ $item->order->order_number ?? $item->order_id }} - GARUDO B2B</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background: #F1F5F9;
            color: #0F172A;
            font-size: 11px;
            line-height: 1.35;
        }

        .invoice-wrapper {
            max-width: 900px;
            margin: 20px auto;
            background: #FFFFFF;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border-radius: 8px;
        }

        .copy-section {
            border: 1.5px solid #000;
            padding: 16px;
            margin-bottom: 24px;
            position: relative;
            background: #FFFFFF;
        }

        .copy-header-tag {
            position: absolute;
            top: -12px;
            right: 20px;
            background: #000;
            color: #FFF;
            font-weight: 800;
            font-size: 10px;
            padding: 2px 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-radius: 3px;
        }

        .cut-line {
            border-top: 1.5px dashed #64748B;
            margin: 28px 0;
            position: relative;
            text-align: center;
        }

        .cut-line::before {
            content: '✂ TEAR / CUT HERE';
            position: absolute;
            top: -9px;
            left: 50%;
            transform: translateX(-50%);
            background: #FFF;
            padding: 0 12px;
            font-size: 9px;
            font-weight: 700;
            color: #64748B;
            letter-spacing: 1px;
        }

        .barcode-box {
            font-family: 'Courier New', monospace;
            letter-spacing: 4px;
            font-weight: 800;
            font-size: 14px;
            background: #F8FAFC;
            padding: 6px 12px;
            border: 1px solid #CBD5E1;
            display: inline-block;
        }

        .table-invoice {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .table-invoice th, .table-invoice td {
            border: 1px solid #000;
            padding: 4px 8px;
            font-size: 10.5px;
        }

        .table-invoice th {
            background-color: #F1F5F9;
            font-weight: 700;
        }

        .brand-logo {
            font-size: 18px;
            font-weight: 900;
            letter-spacing: 1px;
            color: #0B4D3C;
        }

        @media print {
            body {
                background: #FFF;
                margin: 0;
                padding: 0;
            }
            .invoice-wrapper {
                box-shadow: none;
                padding: 0;
                max-width: 100%;
                margin: 0;
            }
            .no-print {
                display: none !important;
            }
            .copy-section {
                page-break-inside: avoid;
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>

    <!-- Action Bar (Hidden on Print) -->
    <div class="no-print bg-dark text-white py-2 px-4 sticky-top d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <span class="brand-logo text-white">GARUDO B2B</span>
            <span class="text-white-50">| 3-Way Triplicate GST Tax Invoice & E-Way Bill</span>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-success btn-sm fw-bold">
                <i class="bi bi-printer-fill me-1"></i> Print Invoice (All 3 Copies)
            </button>
            <button onclick="window.close()" class="btn btn-outline-light btn-sm">
                Close
            </button>
        </div>
    </div>

    <div class="invoice-wrapper">

        <!-- ============================================================= -->
        <!-- COPY 1: TRANSPORTER / COURIER COPY (Manifest & E-Way Bill)    -->
        <!-- ============================================================= -->
        <div class="copy-section">
            <div class="copy-header-tag bg-primary">COPY 1: TRANSPORTER / COURIER & E-WAY BILL COPY</div>
            
            <div class="row align-items-center mb-2 pb-2 border-bottom">
                <div class="col-7">
                    <span class="brand-logo">GARUDO B2B LOGISTICS MANIFEST</span>
                    <div class="small fw-bold text-muted">E-Way Bill & Transportation Consignment Note (Form GST EWB-01)</div>
                </div>
                <div class="col-5 text-end">
                    <div class="barcode-box">||| {{ $shipment->eway_bill_number ?? 'EB26082498214' }} |||</div>
                    <div style="font-size: 9px; font-weight: bold;">E-WAY BILL NO: {{ $shipment->eway_bill_number ?? 'EB26082498214' }}</div>
                </div>
            </div>

            <div class="row g-2 mb-2">
                <div class="col-4">
                    <strong>Order Number:</strong> #{{ $item->order->order_number ?? $item->order_id }}<br>
                    <strong>Docket / LR No:</strong> {{ $shipment->docket_lr_number ?? 'LR-BLU-49201' }}<br>
                    <strong>Carrier:</strong> {{ $shipment->deliveryPartner->name ?? ($shipment->delivery_type === 'transporter_hub_crossing' ? 'Transporter Hub Crossing' : 'Vendor Self Logistics') }}
                </div>
                <div class="col-4">
                    <strong>Package Weight:</strong> {{ $shipment->package_weight_kg ?? 10.00 }} Kg<br>
                    <strong>Box Count:</strong> {{ $shipment->package_boxes_count ?? 1 }} Box(es)<br>
                    <strong>Dimensions:</strong> {{ $shipment->package_dimensions ?? '45x35x25 cm' }}
                </div>
                <div class="col-4">
                    <strong>Handover OTP:</strong> <span class="badge bg-dark fs-6">{{ $shipment->pickup_otp ?? '482910' }}</span><br>
                    <strong>OTP Verified:</strong> {{ $shipment->pickup_otp_verified ? 'YES (' . $shipment->pickup_otp_verified_at?->format('d-m-Y H:i') . ')' : 'Pending Handover' }}
                </div>
            </div>

            <div class="row g-2 border-top pt-2">
                <div class="col-6">
                    <div class="fw-bold bg-light p-1 border">FROM (Consignor / Pickup Origin):</div>
                    <div class="p-1">
                        <strong>{{ $vendor->company_name ?? $vendor->name }}</strong><br>
                        @if($shipment->dispatchWarehouse)
                            <strong>Dispatch Depot:</strong> {{ $shipment->dispatchWarehouse->name }}<br>
                            {{ $shipment->dispatchWarehouse->address_line1 }}<br>
                            {{ $shipment->dispatchWarehouse->city }}, <strong>State: {{ $shipment->dispatchWarehouse->state }}</strong> - {{ $shipment->dispatchWarehouse->pincode }}<br>
                        @else
                            Pickup State: {{ $vendor->primary_dispatch_state ?? ($vendor->state ?? 'Maharashtra') }} &bull; Pincode: {{ $vendor->pincode ?? '400001' }}<br>
                        @endif
                        GSTIN: {{ $vendor->gstin ?? '27AAAAA0000A1Z5' }}<br>
                        Phone: {{ $vendor->phone ?? '+91 98200 12345' }}
                    </div>
                </div>
                <div class="col-6">
                    <div class="fw-bold bg-light p-1 border">TO (Consignee / Delivery Destination):</div>
                    <div class="p-1">
                        <strong>{{ $item->order->shipping_name }}</strong><br>
                        {{ $item->order->shipping_address }}<br>
                        {{ $item->order->shipping_city }}, {{ $item->order->shipping_state }} - <strong>{{ $item->order->shipping_pincode }}</strong><br>
                        Phone: {{ $item->order->shipping_phone }}
                    </div>
                </div>
            </div>

            <table class="table-invoice mt-2">
                <thead>
                    <tr>
                        <th>Material Description</th>
                        <th>SKU / HSN</th>
                        <th>Qty</th>
                        <th>Declared Value (₹)</th>
                        <th>Transporter Sign</th>
                        <th>Receiver OTP / Sign</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ $item->product_sku }} / HSN: 7308</td>
                        <td>{{ $item->quantity }} Units</td>
                        <td>₹{{ number_format($item->total_amount, 2) }}</td>
                        <td style="height: 30px;"></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="cut-line"></div>

        <!-- ============================================================= -->
        <!-- COPY 2: VENDOR / CONSIGNOR COPY (Tax & Accounts Record)       -->
        <!-- ============================================================= -->
        <div class="copy-section">
            <div class="copy-header-tag bg-dark">COPY 2: VENDOR / CONSIGNOR TAX & ACCOUNTS COPY</div>

            <div class="row align-items-center mb-2 pb-2 border-bottom">
                <div class="col-8">
                    <span class="brand-logo">{{ $vendor->company_name ?? 'GARUDO VENDOR PARTNER' }}</span>
                    <div class="small text-muted">Vendor Dispatch & Tax Accounts Copy</div>
                </div>
                <div class="col-4 text-end">
                    <strong>Invoice #:</strong> INV-{{ $item->order->order_number }}-V<br>
                    <strong>Date:</strong> {{ now()->format('d M Y') }}
                </div>
            </div>

            <div class="row g-2 mb-2">
                <div class="col-6">
                    <strong>Vendor GSTIN:</strong> {{ $vendor->gstin ?? '27AAAAA0000A1Z5' }}<br>
                    <strong>PAN:</strong> {{ $vendor->pan_number ?? 'ABCDE1234F' }}<br>
                    <strong>Bank A/C:</strong> {{ $vendor->bankDetails->account_number ?? 'XXXX-XXXX-8921' }}
                </div>
                <div class="col-6 text-end">
                    <strong>Item Total (Gross):</strong> ₹{{ number_format($item->total_amount, 2) }}<br>
                    <strong>Platform Commission ({{ $item->commission_rate }}%):</strong> -₹{{ number_format($item->commission_amount, 2) }}<br>
                    <strong class="text-success fs-6">Net Vendor Payout: ₹{{ number_format($item->vendor_payout ?? $item->total_amount, 2) }}</strong>
                </div>
            </div>

            <table class="table-invoice">
                <thead>
                    <tr>
                        <th>Item Description</th>
                        <th>Taxable Value</th>
                        <th>GST Rate</th>
                        <th>CGST (₹)</th>
                        <th>SGST (₹)</th>
                        <th>IGST (₹)</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $taxable = $item->total_amount - $item->gst_amount;
                        $halfGst = round($item->gst_amount / 2, 2);
                    @endphp
                    <tr>
                        <td>{{ $item->product_name }} (Qty: {{ $item->quantity }})</td>
                        <td>₹{{ number_format($taxable, 2) }}</td>
                        <td>{{ $item->gst_rate }}%</td>
                        <td>₹{{ number_format($halfGst, 2) }}</td>
                        <td>₹{{ number_format($halfGst, 2) }}</td>
                        <td>₹0.00</td>
                        <td><strong>₹{{ number_format($item->total_amount, 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>
            <div class="text-muted mt-2" style="font-size: 9px;">
                * This document serves as vendor sales record for GST output tax filing and platform settlement.
            </div>
        </div>

        <div class="cut-line"></div>

        <!-- ============================================================= -->
        <!-- COPY 3: CLIENT / CONSIGNEE COPY (Original Buyer Tax Invoice)  -->
        <!-- ============================================================= -->
        <div class="copy-section">
            <div class="copy-header-tag bg-success">COPY 3: CLIENT / CONSIGNEE ORIGINAL TAX INVOICE</div>

            <div class="row align-items-center mb-2 pb-2 border-bottom">
                <div class="col-8">
                    <span class="brand-logo">GARUDO B2B TAX INVOICE</span>
                    <div class="small text-muted">Original for Recipient (Section 31 of CGST Act, 2017)</div>
                </div>
                <div class="col-4 text-end">
                    <strong>Invoice #:</strong> INV-{{ $item->order->order_number }}<br>
                    <strong>Order Date:</strong> {{ $item->order->created_at?->format('d M Y') }}<br>
                    <strong>Payment:</strong> {{ strtoupper($item->order->payment_method ?? 'ONLINE') }} (PAID)
                </div>
            </div>

            <div class="row g-2 mb-2">
                <div class="col-6">
                    <div class="fw-bold bg-light p-1 border">Billed / Shipped To:</div>
                    <div class="p-1">
                        <strong>{{ $item->order->shipping_name }}</strong><br>
                        {{ $item->order->shipping_address }}<br>
                        {{ $item->order->shipping_city }}, {{ $item->order->shipping_state }} - {{ $item->order->shipping_pincode }}<br>
                        GSTIN: {{ $item->order->billing_gstin ?? 'Unregistered Consumer / B2B' }}<br>
                        Phone: {{ $item->order->shipping_phone }}
                    </div>
                </div>
                <div class="col-6">
                    <div class="fw-bold bg-light p-1 border">Fulfillment Center / Carrier:</div>
                    <div class="p-1">
                        @if($shipment->dispatchWarehouse)
                            <strong>Origin Depot:</strong> {{ $shipment->dispatchWarehouse->name }} ({{ $shipment->dispatchWarehouse->city }}, <strong>{{ $shipment->dispatchWarehouse->state }}</strong>)<br>
                        @else
                            <strong>{{ $item->masked_vendor_hub }}</strong><br>
                        @endif
                        Carrier: {{ $shipment->deliveryPartner->name ?? 'Garudo Express Carrier' }}<br>
                        Tracking / LR #: {{ $shipment->docket_lr_number ?? 'LR-981240' }}<br>
                        E-Way Bill: {{ $shipment->eway_bill_number ?? 'EB26082498214' }}
                    </div>
                </div>
            </div>

            <table class="table-invoice">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product & Specification</th>
                        <th>HSN</th>
                        <th>Unit Price</th>
                        <th>Qty</th>
                        <th>GST ({{ $item->gst_rate }}%)</th>
                        <th class="text-end">Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>
                            <strong>{{ $item->product_name }}</strong><br>
                            <span class="text-muted" style="font-size: 9px;">SKU: {{ $item->product_sku }}</span>
                        </td>
                        <td>7308</td>
                        <td>₹{{ number_format($item->unit_price, 2) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>₹{{ number_format($item->gst_amount, 2) }}</td>
                        <td class="text-end fw-bold">₹{{ number_format($item->total_amount, 2) }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6" class="text-end">Grand Total (Inclusive of Taxes):</th>
                        <th class="text-end">₹{{ number_format($item->total_amount, 2) }}</th>
                    </tr>
                </tfoot>
            </table>

            <div class="row align-items-end mt-3">
                <div class="col-8">
                    <div class="text-muted" style="font-size: 8.5px;">
                        <strong>Terms & Conditions:</strong><br>
                        1. Goods once sold are subject to manufacturer warranty and Garudo B2B marketplace replacement policy.<br>
                        2. This is a computer generated tax invoice and does not require physical signature under IT Act 2000.
                    </div>
                </div>
                <div class="col-4 text-center">
                    <div style="height: 35px; border-bottom: 1px solid #000;"></div>
                    <div class="fw-bold mt-1" style="font-size: 9px;">Authorized Signatory / Garudo Hub</div>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
