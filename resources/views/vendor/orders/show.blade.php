@extends('layouts.vendor')

@section('title', 'Order Details - #' . ($item->order->order_number ?? $item->order_id))

@push('styles')
<style>
    .quote-card {
        border: 2px solid var(--garudo-border);
        border-radius: 12px;
        transition: all 0.2s ease-in-out;
        position: relative;
        background: #FFFFFF;
    }
    .quote-card:hover {
        border-color: var(--garudo-primary);
        box-shadow: 0 4px 14px rgba(11, 77, 60, 0.12);
        transform: translateY(-2px);
    }
    .quote-card.recommended {
        border-color: var(--garudo-accent);
        background: #F0FDF4;
    }
    .quote-card.selected {
        border-color: var(--garudo-primary);
        background: #ECFDF5;
    }
    .badge-pill-soft {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 0.25rem 0.65rem;
        border-radius: 9999px;
    }
    .otp-code-box {
        background: #0F172A;
        color: #38BDF8;
        font-family: 'Courier New', monospace;
        font-size: 2rem;
        font-weight: 800;
        letter-spacing: 6px;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        display: inline-block;
        border: 2px dashed #38BDF8;
    }
    .step-indicator {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 1.25rem;
    }
    .step-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--garudo-primary);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
    }
</style>
@endpush

@section('content')
<!-- Page Header -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2">
            <h4 class="fw-bold mb-0" style="color: var(--garudo-text-main);">Order Item #{{ $item->order->order_number ?? $item->order_id }}</h4>
            <span class="badge bg-{{ $item->item_status === 'delivered' ? 'success' : ($item->item_status === 'shipped' ? 'primary' : 'warning') }} bg-opacity-10 text-{{ $item->item_status === 'delivered' ? 'success' : ($item->item_status === 'shipped' ? 'primary' : 'warning') }} fs-6 px-2 py-1">
                {{ ucfirst(str_replace('_', ' ', $item->item_status)) }}
            </span>
        </div>
        <p class="text-muted mb-0 small mt-1">
            Placed on {{ $item->created_at->format('d M Y, h:i A') }} &bull; SKU: <strong>{{ $item->product_sku }}</strong>
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('vendor.orders.print-invoice', $item->id) }}" target="_blank" class="btn btn-outline-dark btn-sm d-flex align-items-center gap-2" style="border-radius: 8px;">
            <i class="bi bi-printer"></i> Print 3-Copy Tax Invoice & E-Way Bill
        </a>
        <a href="{{ route('vendor.orders.index') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1" style="border-radius: 8px;">
            <i class="bi bi-arrow-left"></i> Back to Orders
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Left Main Column -->
    <div class="col-lg-8">

        <!-- 1. Special / Custom Order Lead Time Manager -->
        <div class="card-custom mb-4 border-start border-4 border-warning">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-tools text-warning fs-5"></i>
                    <h6 class="fw-bold mb-0">Special / Custom Order Production Schedule</h6>
                </div>
                <span class="badge bg-warning bg-opacity-10 text-dark small fw-bold">Customer Facing (Identity Protected)</span>
            </div>

            <div class="alert alert-light border py-2 px-3 mb-3 small d-flex align-items-center gap-2" style="border-radius: 8px;">
                <i class="bi bi-shield-check text-success fs-5"></i>
                <div>
                    <strong>Privacy Protected:</strong> The buyer will only see the manufacturing duration & estimated dispatch date. Your vendor name & details remain 100% anonymous.
                </div>
            </div>

            <form method="POST" action="{{ route('vendor.orders.production-schedule', $item->id) }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Production Lead Time (Days)</label>
                        <div class="input-group input-group-sm">
                            <input type="number" name="production_lead_days" class="form-control" min="0" max="120" value="{{ old('production_lead_days', $item->production_lead_days ?? 3) }}" required>
                            <span class="input-group-text">Days</span>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label small fw-bold">Current Production Stage</label>
                        <select name="production_stage" class="form-select form-select-sm" required>
                            <option value="order_accepted" {{ $item->production_stage === 'order_accepted' ? 'selected' : '' }}>1. Order Accepted & In Queue</option>
                            <option value="material_procurement" {{ $item->production_stage === 'material_procurement' ? 'selected' : '' }}>2. Raw Material Procurement</option>
                            <option value="in_production" {{ $item->production_stage === 'in_production' ? 'selected' : '' }}>3. Custom Fabrication / Production</option>
                            <option value="quality_testing" {{ $item->production_stage === 'quality_testing' ? 'selected' : '' }}>4. Quality Inspection & Testing</option>
                            <option value="packing_completed" {{ $item->production_stage === 'packing_completed' ? 'selected' : '' }}>5. Packed & Ready for Dispatch</option>
                            <option value="ready_for_dispatch" {{ $item->production_stage === 'ready_for_dispatch' ? 'selected' : '' }}>6. Ready for Carrier Pickup</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-warning btn-sm w-100 fw-bold">
                            <i class="bi bi-save me-1"></i> Update Schedule
                        </button>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold">Production & Customization Notes for Tracking</label>
                        <input type="text" name="production_notes" class="form-control form-control-sm" placeholder="e.g. Cut-to-size zinc galvanized fabrication in progress..." value="{{ old('production_notes', $item->production_notes) }}">
                        @if($item->estimated_ready_date)
                            <div class="text-success small fw-semibold mt-1">
                                <i class="bi bi-calendar-check me-1"></i> Estimated Ready for Dispatch: {{ $item->estimated_ready_date->format('d M Y') }} ({{ $item->production_lead_days }} days from order)
                            </div>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- 2. Logistics & Delivery Dispatch Center -->
        <div class="card-custom mb-4">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2 border-bottom pb-3 mb-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-truck text-success fs-4"></i>
                    <div>
                        <h6 class="fw-bold mb-0">Logistics & Delivery Dispatch Center</h6>
                        <p class="text-muted small mb-0">Multi-Agency Bidding, Smart carrier recommendation, and Hub Crossing.</p>
                    </div>
                </div>
                <div>
                    <!-- Button trigger modal for re-quoting with specific dimensions -->
                    <button type="button" class="btn btn-success btn-sm fw-bold d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#triggerDeliveryModal">
                        <i class="bi bi-lightning-charge-fill"></i> Trigger Delivery / Fetch Quotes
                    </button>
                </div>
            </div>

            @if(!$shipment || $quotes->isEmpty())
                <!-- State: Delivery Not Yet Triggered -->
                <div class="text-center py-4 px-3 bg-light rounded-3 border border-dashed">
                    <i class="bi bi-box-seam fs-1 text-muted d-block mb-2"></i>
                    <h6 class="fw-bold text-dark mb-1">Delivery System Not Activated Yet</h6>
                    <p class="text-muted small mb-3">Once the product is packed or ready, trigger the delivery system to get instant quotes from verified logistics agencies (BlueDart, Delhivery, V-Trans, Garudo Fleet).</p>
                    <button type="button" class="btn btn-success btn-sm fw-bold px-4" data-bs-toggle="modal" data-bs-target="#triggerDeliveryModal">
                        <i class="bi bi-play-circle me-1"></i> Activate Delivery System Now
                    </button>
                </div>
            @else
                <!-- Dispatch Warehouse Info & Selector -->
                <div class="p-3 mb-3 bg-white rounded border" style="border-left: 4px solid var(--garudo-primary) !important;">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">
                        <div>
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <i class="bi bi-building-gear text-success fs-5"></i>
                                <strong class="text-dark small">Dispatching Warehouse & Origin State:</strong>
                                @if($shipment && $shipment->dispatchWarehouse)
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success fw-bold">
                                        {{ $shipment->dispatchWarehouse->name }} (State: {{ $shipment->dispatchWarehouse->state }})
                                    </span>
                                @elseif($item->product && $item->product->warehouse)
                                    <span class="badge bg-light text-dark border">
                                        {{ $item->product->warehouse->name }} (State: {{ $item->product->warehouse->state }})
                                    </span>
                                @else
                                    <span class="badge bg-light text-muted border">
                                        Primary: {{ $vendor->primary_dispatch_state ?? 'Standard Facility' }}
                                    </span>
                                @endif
                            </div>
                            <div class="text-muted small mt-1" style="font-size: 0.75rem;">
                                @if($shipment && $shipment->dispatchWarehouse)
                                    Facility: {{ $shipment->dispatchWarehouse->full_address }} | Supervisor: {{ $shipment->dispatchWarehouse->contact_person }} ({{ $shipment->dispatchWarehouse->phone }})
                                @else
                                    Consignment will be dispatched and billed from this selected depot.
                                @endif
                            </div>
                        </div>

                        @if(!$shipment->delivery_partner_id && $shipment->delivery_type !== 'vendor_self_transport')
                            <div style="min-width: 250px;">
                                <label class="form-label small fw-semibold text-muted mb-1" style="font-size: 0.72rem;">Pickup Facility / Origin State:</label>
                                <select id="globalWarehouseSelector" class="form-select form-select-sm" onchange="syncSelectedWarehouse(this.value)">
                                    @if(isset($warehouses) && $warehouses->count() > 0)
                                        @foreach($warehouses as $wh)
                                            <option value="{{ $wh->id }}" {{ ($shipment->dispatch_warehouse_id == $wh->id || (!$shipment->dispatch_warehouse_id && $wh->is_primary)) ? 'selected' : '' }}>
                                                {{ $wh->name }} ({{ $wh->state }}) {{ $wh->is_primary ? '★ Primary' : '' }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="">Default Vendor Depot ({{ $vendor->company_name }})</option>
                                    @endif
                                </select>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- State: Quotes Available & Analysis Done -->
                <div class="mb-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-bold text-dark small mb-0">
                            <i class="bi bi-graph-up-arrow text-primary me-1"></i> Smart Logistics Analysis & Carrier Bids ({{ $quotes->count() }} Agencies)
                        </h6>
                        <span class="text-muted small">Package: <strong>{{ $shipment->package_weight_kg }} Kg</strong> &bull; <strong>{{ $shipment->package_boxes_count }} Box(es)</strong></span>
                    </div>

                    <div class="row g-3">
                        @foreach($quotes as $quote)
                            @php
                                $isQuoteSelected = $shipment->delivery_partner_id === $quote->delivery_partner_id;
                            @endphp
                            <div class="col-md-6">
                                <div class="quote-card p-3 h-100 {{ $quote->is_recommended ? 'recommended' : '' }} {{ $isQuoteSelected ? 'selected' : '' }}">
                                    <!-- Badges -->
                                    <div class="d-flex flex-wrap gap-1 mb-2">
                                        @if($quote->is_recommended)
                                            <span class="badge bg-success text-white badge-pill-soft"><i class="bi bi-stars me-1"></i>AI Smart Choice (Best Value & Speed)</span>
                                        @endif
                                        @if($quote->is_lowest_price)
                                            <span class="badge bg-primary text-white badge-pill-soft"><i class="bi bi-tag-fill me-1"></i>Lowest Price</span>
                                        @endif
                                        @if($quote->is_fastest)
                                            <span class="badge bg-danger text-white badge-pill-soft"><i class="bi bi-lightning-fill me-1"></i>Fastest Express</span>
                                        @endif
                                    </div>

                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="fw-bold text-dark mb-0">{{ $quote->partner->name }}</h6>
                                            <div class="text-muted small">
                                                <i class="bi bi-star-fill text-warning"></i> {{ $quote->partner->rating }} Rating ({{ $quote->partner->total_deliveries }}+ trips)
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold fs-5 text-dark">₹{{ number_format($quote->quote_amount, 2) }}</div>
                                            <div class="text-muted" style="font-size: 0.7rem;">+ GST Included</div>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between small text-muted border-top pt-2 mt-2 mb-3">
                                        <span><i class="bi bi-stopwatch me-1 text-primary"></i> {{ $quote->estimated_transit_days }} Day(s) Transit</span>
                                        <span><i class="bi bi-shield-check me-1 text-success"></i> 100% Insured</span>
                                        <span><i class="bi bi-qr-code-scan me-1 text-dark"></i> OTP Protected</span>
                                    </div>

                                    @if($isQuoteSelected)
                                        <div class="bg-success text-white text-center py-1 rounded small fw-bold">
                                            <i class="bi bi-check-circle-fill me-1"></i> Active Carrier Assigned
                                        </div>
                                    @else
                                        <form method="POST" action="{{ route('vendor.orders.confirm-quote', $item->id) }}">
                                            @csrf
                                            <input type="hidden" name="quote_id" value="{{ $quote->id }}">
                                            <input type="hidden" name="dispatch_warehouse_id" class="quote-wh-input" value="{{ $shipment->dispatch_warehouse_id ?: ($warehouses->firstWhere('is_primary', true)->id ?? '') }}">
                                            <button type="submit" class="btn btn-outline-success btn-sm w-100 fw-bold">
                                                Confirm & Assign {{ $quote->partner->name }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Alternative: Vendor Self-Handled Transport / Hub Crossing -->
                <div class="border rounded-3 p-3 bg-light mb-2">
                    <div class="d-flex align-items-center justify-content-between" data-bs-toggle="collapse" href="#collapseSelfDelivery" role="button" style="cursor: pointer;">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-person-gear text-secondary fs-5"></i>
                            <div>
                                <h6 class="fw-bold mb-0 small text-dark">Alternative: Vendor Self Transport / Transporter Hub Crossing</h6>
                                <p class="text-muted mb-0" style="font-size: 0.75rem;">Prefer dropping goods at your local transport hub or delivering via your own vehicle?</p>
                            </div>
                        </div>
                        <i class="bi bi-chevron-down text-muted"></i>
                    </div>

                    <div class="collapse mt-3 {{ $shipment->delivery_type !== 'courier_bidding' ? 'show' : '' }}" id="collapseSelfDelivery">
                        <hr class="my-2">
                        <form method="POST" action="{{ route('vendor.orders.self-handled-delivery', $item->id) }}">
                            @csrf
                            <div class="row g-2 small">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Dispatch Method</label>
                                    <select name="delivery_type" class="form-select form-select-sm" required>
                                        <option value="transporter_hub_crossing" {{ $shipment->delivery_type === 'transporter_hub_crossing' ? 'selected' : '' }}>Drop-off at Transporter Hub / Crossing</option>
                                        <option value="vendor_self_transport" {{ $shipment->delivery_type === 'vendor_self_transport' ? 'selected' : '' }}>Vendor Self Dedicated Fleet</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Origin Dispatch Warehouse</label>
                                    <select name="dispatch_warehouse_id" class="form-select form-select-sm">
                                        @if(isset($warehouses) && $warehouses->count() > 0)
                                            @foreach($warehouses as $wh)
                                                <option value="{{ $wh->id }}" {{ ($shipment->dispatch_warehouse_id == $wh->id || (!$shipment->dispatch_warehouse_id && $wh->is_primary)) ? 'selected' : '' }}>
                                                    {{ $wh->name }} (State: {{ $wh->state }}) {{ $wh->is_primary ? '★ Primary' : '' }}
                                                </option>
                                            @endforeach
                                        @else
                                            <option value="">Default Vendor Depot</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Lorry Receipt (LR / Bilty / Docket #)</label>
                                    <input type="text" name="docket_lr_number" class="form-control form-control-sm" placeholder="e.g. LR-98124" value="{{ $shipment->docket_lr_number }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Transporter Name / Hub Details</label>
                                    <input type="text" name="transporter_hub_name" class="form-control form-control-sm" placeholder="e.g. V-Trans Transport Nagar Hub, Bhiwandi" value="{{ $shipment->transporter_hub_name }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Vehicle Number & Driver Contact</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="vehicle_number" class="form-control" placeholder="MH-04-AB-1234" value="{{ $shipment->vehicle_number }}">
                                        <input type="text" name="driver_phone" class="form-control" placeholder="Driver Phone" value="{{ $shipment->driver_phone }}">
                                    </div>
                                </div>
                                <div class="col-12 mt-3 text-end">
                                    <button type="submit" class="btn btn-secondary btn-sm fw-bold">
                                        <i class="bi bi-save me-1"></i> Save Self-Transport Details
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <script>
                function syncSelectedWarehouse(val) {
                    document.querySelectorAll('.quote-wh-input').forEach(input => {
                        input.value = val;
                    });
                }
                </script>
            @endif
        </div>

        <!-- 3. Purchased Material Item Snapshot -->
        <div class="card-custom mb-4">
            <h6 class="fw-bold mb-3 border-bottom pb-2">Purchased Material Item</h6>
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    @if($item->product_image)
                        <img src="{{ asset('storage/' . $item->product_image) }}" alt="{{ $item->product_name }}" style="width: 64px; height: 64px; border-radius: 8px; object-fit: cover; border: 1px solid #E2E8F0;" onerror="this.src='https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=120&auto=format&fit=crop&q=80'">
                    @else
                        <div style="width: 64px; height: 64px; border-radius: 8px; border: 1px solid #E2E8F0; background: #F8FAFC; display: flex; align-items: center; justify-content: center; color: #94A3B8;">
                            <i class="bi bi-box fs-3"></i>
                        </div>
                    @endif
                    <div>
                        <h5 class="fw-bold text-dark mb-1">{{ $item->product_name }}</h5>
                        <p class="text-muted small mb-0">SKU: {{ $item->product_sku }} | Unit Price: ₹{{ number_format($item->unit_price, 2) }}</p>
                        @if($item->customization_details)
                            <div class="badge bg-light text-dark border mt-1 small">
                                <i class="bi bi-gear me-1"></i> {{ $item->customization_details }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="text-md-end">
                    <div class="text-muted small">Quantity: <strong>{{ $item->quantity }} Units</strong></div>
                    <div class="fw-bold fs-4 text-dark">₹{{ number_format($item->total_amount, 2) }}</div>
                </div>
            </div>
        </div>

    </div>

    <!-- Right Sidebar Column -->
    <div class="col-lg-4">

        <!-- 1. Transporter 6-Digit Handover OTP Verification Card -->
        <div class="card-custom mb-4 border border-2 border-primary shadow-sm">
            <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-shield-lock-fill text-primary fs-4"></i>
                <h6 class="fw-bold mb-0">Transporter Pickup Verification</h6>
            </div>
            <p class="text-muted small mb-3">When the carrier / transporter arrives at your warehouse, verify this 6-digit OTP to authenticate physical handover of goods.</p>

            @if($shipment)
                <div class="text-center p-3 bg-light rounded-3 mb-3 border">
                    <div class="text-muted small mb-1 fw-bold text-uppercase">Handover Security OTP</div>
                    <div class="otp-code-box">{{ $shipment->pickup_otp ?? '482910' }}</div>
                    <div class="text-muted small mt-2">
                        @if($shipment->pickup_otp_verified)
                            <span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i> OTP Verified on {{ $shipment->pickup_otp_verified_at?->format('d M, h:i A') }}</span>
                        @else
                            <span class="badge bg-warning text-dark"><i class="bi bi-clock-history me-1"></i> Awaiting Transporter Verification</span>
                        @endif
                    </div>
                </div>

                @if(!$shipment->pickup_otp_verified)
                    <form method="POST" action="{{ route('vendor.orders.verify-pickup-otp', $item->id) }}" class="border-top pt-3">
                        @csrf
                        <label class="form-label small fw-bold">Enter Transporter OTP to Confirm Handover</label>
                        <div class="input-group mb-2">
                            <input type="text" name="pickup_otp" class="form-control text-center fw-bold fs-5" placeholder="Enter 6 Digits" maxlength="6" required>
                            <button type="submit" class="btn btn-primary fw-bold">
                                <i class="bi bi-check2-circle"></i> Verify
                            </button>
                        </div>
                        <div class="form-text small" style="font-size: 0.72rem;">Once verified, order status immediately changes to <strong>Shipped & In Transit</strong>.</div>
                    </form>
                @endif
            @else
                <div class="alert alert-secondary small mb-0">
                    Please trigger the delivery system first to generate the Handover Verification OTP.
                </div>
            @endif
        </div>

        <!-- 2. Shipping & Delivery Address Snapshot -->
        <div class="card-custom mb-4">
            <h6 class="fw-bold mb-3 border-bottom pb-2">Delivery Destination</h6>
            <div class="small">
                <div class="fw-bold text-dark mb-1">{{ $item->order->shipping_name ?? 'Client Representative' }}</div>
                <div class="text-muted mb-2">
                    {{ $item->order->shipping_address ?? 'N/A' }}<br>
                    {{ $item->order->shipping_city ?? '' }}, {{ $item->order->shipping_state ?? '' }} - <strong>{{ $item->order->shipping_pincode ?? '' }}</strong>
                </div>
                <div class="text-dark"><i class="bi bi-telephone text-muted me-1"></i> {{ $item->order->shipping_phone ?? 'N/A' }}</div>
            </div>
        </div>

        <!-- 3. Status & Financials -->
        <div class="card-custom mb-4">
            <h6 class="fw-bold mb-3 border-bottom pb-2">Status & Financials</h6>
            <div class="mb-3">
                <div class="d-flex justify-content-between small text-muted mb-1">
                    <span>Item Total:</span>
                    <span>₹{{ number_format($item->total_amount, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between small text-muted mb-1">
                    <span>GST ({{ $item->gst_rate }}%):</span>
                    <span>₹{{ number_format($item->gst_amount, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between small text-muted mb-1">
                    <span>Commission ({{ $item->commission_rate }}%):</span>
                    <span class="text-danger">-₹{{ number_format($item->commission_amount, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between fw-bold text-dark border-top pt-2 fs-6">
                    <span>Your Net Payout:</span>
                    <span class="text-success">₹{{ number_format($item->vendor_payout ?? $item->total_amount, 2) }}</span>
                </div>
            </div>

            <div class="p-2 bg-light rounded text-center small">
                <span class="text-muted">Payout Status:</span>
                <span class="badge bg-secondary text-uppercase ms-1">{{ $item->payout_status ?? 'Pending' }}</span>
            </div>
        </div>

    </div>
</div>

<!-- Trigger Delivery Modal -->
<div class="modal fade" id="triggerDeliveryModal" tabindex="-1" aria-labelledby="triggerDeliveryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('vendor.orders.trigger-delivery', $item->id) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="triggerDeliveryModalLabel"><i class="bi bi-truck me-2 text-success"></i>Trigger Delivery System</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">Enter package weight and dimensions to calculate real-time competitive quotes from all eligible logistics agencies.</p>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Total Weight (Kg)</label>
                            <input type="number" step="0.1" name="package_weight_kg" class="form-control form-control-sm" value="{{ $shipment->package_weight_kg ?? ($item->quantity * 5.0) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Number of Boxes / Bundles</label>
                            <input type="number" name="package_boxes_count" class="form-control form-control-sm" value="{{ $shipment->package_boxes_count ?? 1 }}" min="1" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Package Dimensions (L x W x H in cm)</label>
                            <input type="text" name="package_dimensions" class="form-control form-control-sm" value="{{ $shipment->package_dimensions ?? '45x35x25 cm' }}" placeholder="45x35x25 cm" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Handling Instructions / Notes</label>
                            <input type="text" name="notes" class="form-control form-control-sm" value="{{ $shipment->notes ?? 'Industrial B2B Fragile / Heavy Consignment' }}" placeholder="Fragile, Handle with Forklift, etc.">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm fw-bold">
                        <i class="bi bi-calculator me-1"></i> Analyze & Fetch Live Agency Quotes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
