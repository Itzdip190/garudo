@extends('layouts.customer')

@section('title', 'My Quotations & RFQs')

@section('content')
<div class="quotes-page-container">

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
        <i class="bi bi-check-circle-fill fs-5"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
        <div>{{ session('error') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row md-align-items-center justify-content-between mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-success-subtle text-success fw-bold text-uppercase px-2 py-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">B2B Wholesale Procurement</span>
                <span class="text-muted">•</span>
                <span class="text-muted small">Direct Supplier Quotes</span>
            </div>
            <h1 class="h3 fw-bold text-dark mb-1">My Quotations & RFQs</h1>
            <p class="text-muted mb-0" style="font-size: 0.875rem;">Track custom bulk price estimates, review vendor proposals, and approve wholesale orders.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('products.index') }}" class="btn btn-primary d-flex align-items-center gap-2 px-3 py-2 fw-semibold">
                <i class="bi bi-search"></i> Browse Catalog & RFQs
            </a>
        </div>
    </div>

    <!-- Quick Metric Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="text-muted small fw-semibold text-uppercase">Total RFQs</div>
                <div class="d-flex justify-content-between align-items-end mt-2">
                    <h3 class="fw-bold mb-0 text-dark">{{ $counts['all'] ?? 0 }}</h3>
                    <i class="bi bi-file-earmark-text fs-4 text-secondary"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="text-muted small fw-semibold text-uppercase">Proposals Ready</div>
                <div class="d-flex justify-content-between align-items-end mt-2">
                    <h3 class="fw-bold mb-0 text-primary">{{ $counts['quoted'] ?? 0 }}</h3>
                    <i class="bi bi-tag-fill fs-4 text-primary"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="text-muted small fw-semibold text-uppercase">Approved / Accepted</div>
                <div class="d-flex justify-content-between align-items-end mt-2">
                    <h3 class="fw-bold mb-0 text-success">{{ $counts['accepted'] ?? 0 }}</h3>
                    <i class="bi bi-patch-check-fill fs-4 text-success"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="text-muted small fw-semibold text-uppercase">Converted Orders</div>
                <div class="d-flex justify-content-between align-items-end mt-2">
                    <h3 class="fw-bold mb-0 text-dark">{{ $counts['converted'] ?? 0 }}</h3>
                    <i class="bi bi-box2-fill fs-4 text-dark"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Status Tabs -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-2">
            <ul class="nav nav-pills gap-1">
                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ $status === 'all' ? 'active' : '' }}" href="{{ route('customer.quotes', ['status' => 'all']) }}">
                        All Quotes <span class="badge bg-secondary ms-1">{{ $counts['all'] ?? 0 }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ $status === 'pending' ? 'active' : '' }}" href="{{ route('customer.quotes', ['status' => 'pending']) }}">
                        Awaiting Response <span class="badge bg-secondary ms-1">{{ $counts['pending'] ?? 0 }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ $status === 'quoted' ? 'active' : '' }}" href="{{ route('customer.quotes', ['status' => 'quoted']) }}">
                        Proposals Ready <span class="badge bg-primary ms-1">{{ $counts['quoted'] ?? 0 }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ $status === 'accepted' ? 'active' : '' }}" href="{{ route('customer.quotes', ['status' => 'accepted']) }}">
                        Accepted <span class="badge bg-success ms-1">{{ $counts['accepted'] ?? 0 }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ $status === 'converted_to_order' ? 'active' : '' }}" href="{{ route('customer.quotes', ['status' => 'converted_to_order']) }}">
                        Converted Orders <span class="badge bg-dark ms-1">{{ $counts['converted'] ?? 0 }}</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Quotes List Card -->
    <div class="card border-0 shadow-sm rounded-3">
        @if($quotes->count() > 0)
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr style="font-size: 0.78rem; text-transform: uppercase; color: #64748B;">
                        <th class="ps-4 py-3">Quote # & Date</th>
                        <th class="py-3">Material / Product</th>
                        <th class="py-3">Supplier</th>
                        <th class="py-3 text-center">Quantity</th>
                        <th class="py-3 text-end">Quoted Price</th>
                        <th class="py-3 text-center">Status</th>
                        <th class="pe-4 py-3 text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quotes as $quote)
                    @php
                        $badgeClass = match($quote->status) {
                            'pending', 'new' => 'bg-warning-subtle text-warning border-warning-subtle',
                            'sent', 'quoted', 'viewed' => 'bg-primary-subtle text-primary border-primary-subtle',
                            'accepted' => 'bg-success-subtle text-success border-success-subtle',
                            'converted_to_order' => 'bg-info-subtle text-info border-info-subtle',
                            'rejected', 'expired' => 'bg-danger-subtle text-danger border-danger-subtle',
                            default => 'bg-light text-secondary border'
                        };
                    @endphp
                    <tr>
                        <td class="ps-4 py-3">
                            <div class="fw-bold text-dark">{{ $quote->quotation_number }}</div>
                            <div class="text-muted small">{{ $quote->created_at?->format('d M, Y') }}</div>
                        </td>
                        <td class="py-3">
                            <div class="d-flex align-items-center gap-2">
                                @if($quote->product && $quote->product->primaryImage)
                                    <img src="{{ asset('storage/' . $quote->product->primaryImage->image_path) }}" alt="{{ $quote->product->name }}" class="rounded border" style="width: 44px; height: 44px; object-fit: cover;">
                                @else
                                    <div class="rounded bg-light d-flex align-items-center justify-content-center text-muted border" style="width: 44px; height: 44px;">
                                        <i class="bi bi-box-seam"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-semibold text-dark">{{ $quote->product?->name ?? 'Custom Procurement Item' }}</div>
                                    @if($quote->custom_fields_data && is_array($quote->custom_fields_data))
                                        <div class="text-muted" style="font-size: 0.74rem;">
                                            @foreach(array_slice($quote->custom_fields_data, 0, 2) as $key => $val)
                                                <span class="badge bg-light text-dark border me-1">{{ $key }}: {{ is_array($val) ? implode(', ', $val) : $val }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="py-3">
                            <div class="fw-medium text-dark">{{ $quote->vendor?->company_name ?? $quote->vendor?->name ?? 'Garudo Supplier' }}</div>
                            <div class="text-muted small"><i class="bi bi-geo-alt me-1"></i>{{ $quote->vendor?->city ?? 'Verified Partner' }}</div>
                        </td>
                        <td class="text-center py-3">
                            <span class="fw-bold text-dark">{{ number_format($quote->quantity) }}</span>
                            <span class="text-muted small"> {{ $quote->product?->unit ?? 'Units' }}</span>
                        </td>
                        <td class="text-end py-3">
                            @if(in_array($quote->status, ['sent', 'quoted', 'viewed', 'accepted', 'converted_to_order']) && $quote->total_amount > 0)
                                <div class="fw-bold text-dark fs-6">₹{{ number_format($quote->total_amount, 2) }}</div>
                                <div class="text-muted" style="font-size: 0.72rem;">₹{{ number_format($quote->unit_price, 2) }}/unit</div>
                            @elseif($quote->target_price)
                                <div class="text-muted small">Target: ₹{{ number_format($quote->target_price, 2) }}</div>
                                <div class="text-warning" style="font-size: 0.72rem;">Pending Quote</div>
                            @else
                                <span class="text-muted small">Awaiting Proposal</span>
                            @endif
                        </td>
                        <td class="text-center py-3">
                            <span class="badge border fw-bold px-2 py-1 {{ $badgeClass }}">
                                {{ strtoupper(str_replace('_', ' ', $quote->status)) }}
                            </span>
                        </td>
                        <td class="pe-4 py-3 text-end">
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#quoteModal{{ $quote->id }}">
                                <i class="bi bi-eye me-1"></i> Details
                            </button>
                        </td>
                    </tr>

                    <!-- Proposal Inspection Modal for Quote #{{ $quote->id }} -->
                    <div class="modal fade" id="quoteModal{{ $quote->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header bg-light border-0 py-3">
                                    <div>
                                        <div class="d-flex align-items-center gap-2">
                                            <h5 class="modal-title fw-bold text-dark mb-0">Quotation #{{ $quote->quotation_number }}</h5>
                                            <span class="badge border fw-bold {{ $badgeClass }}">{{ strtoupper(str_replace('_', ' ', $quote->status)) }}</span>
                                        </div>
                                        <div class="text-muted small">Submitted on {{ $quote->created_at?->format('d M, Y \a\t h:i A') }}</div>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <!-- Material & Supplier Details -->
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-7">
                                            <div class="p-3 bg-light rounded-3">
                                                <div class="text-muted small fw-semibold text-uppercase mb-1">Material Requested</div>
                                                <div class="fw-bold text-dark fs-6">{{ $quote->product?->name ?? 'Custom Material Request' }}</div>
                                                <div class="text-muted small">Requested Quantity: <strong class="text-dark">{{ number_format($quote->quantity) }} {{ $quote->product?->unit ?? 'Units' }}</strong></div>
                                                @if($quote->target_price)
                                                <div class="text-muted small">Your Target Budget: <strong class="text-dark">₹{{ number_format($quote->target_price, 2) }}/unit</strong></div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="p-3 bg-light rounded-3">
                                                <div class="text-muted small fw-semibold text-uppercase mb-1">Supplier Information</div>
                                                <div class="fw-bold text-dark">{{ $quote->vendor?->company_name ?? $quote->vendor?->name }}</div>
                                                <div class="text-muted small">{{ $quote->vendor?->city ?? 'Verified Supplier' }}</div>
                                                <div class="text-muted small">{{ $quote->vendor?->email ?? '' }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Custom Specifications / Answers -->
                                    @if($quote->custom_fields_data && is_array($quote->custom_fields_data) && count($quote->custom_fields_data) > 0)
                                    <div class="mb-4">
                                        <h6 class="fw-bold text-dark mb-2"><i class="bi bi-sliders me-1 text-primary"></i> Customized Specifications Provided</h6>
                                        <div class="table-responsive border rounded-3">
                                            <table class="table table-sm table-borderless mb-0">
                                                <thead class="table-light">
                                                    <tr style="font-size: 0.75rem; text-transform: uppercase;">
                                                        <th class="ps-3 py-2">Specification Parameter</th>
                                                        <th class="pe-3 py-2">Requested Value</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($quote->custom_fields_data as $param => $val)
                                                    <tr class="border-top">
                                                        <td class="ps-3 py-2 text-muted fw-medium">{{ $param }}</td>
                                                        <td class="pe-3 py-2 fw-semibold text-dark">{{ is_array($val) ? implode(', ', $val) : $val }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- BOQ / Drawing Attachment -->
                                    @if($quote->attachment_path)
                                    <div class="mb-4 p-3 border rounded-3 bg-light d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-semibold text-dark"><i class="bi bi-file-earmark-arrow-down text-primary me-1"></i> Attached BOQ / Technical Drawing</div>
                                            <div class="text-muted small">Submitted with your quotation inquiry</div>
                                        </div>
                                        <a href="{{ asset('storage/' . $quote->attachment_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-download me-1"></i> View / Download
                                        </a>
                                    </div>
                                    @endif

                                    <!-- Commercial Proposal Breakdown (If Quoted) -->
                                    @if(in_array($quote->status, ['sent', 'quoted', 'viewed', 'accepted', 'converted_to_order']) && $quote->total_amount > 0)
                                    <div class="card border-primary border-opacity-25 rounded-3 mb-4" style="background: #F8FAFC;">
                                        <div class="card-header bg-white py-3 border-0">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="fw-bold text-dark mb-0"><i class="bi bi-calculator text-primary me-1"></i> Supplier Commercial Quotation</h6>
                                                @if($quote->valid_until)
                                                <span class="badge bg-warning-subtle text-dark border">
                                                    <i class="bi bi-calendar-event me-1"></i> Valid Until: {{ $quote->valid_until->format('d M, Y') }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="card-body p-3">
                                            <div class="row g-2 mb-2">
                                                <div class="col-7 text-muted">Offered Unit Price (excl. tax):</div>
                                                <div class="col-5 text-end fw-semibold text-dark">₹{{ number_format($quote->unit_price, 2) }} / unit</div>
                                            </div>
                                            <div class="row g-2 mb-2">
                                                <div class="col-7 text-muted">Subtotal ({{ number_format($quote->quantity) }} units):</div>
                                                <div class="col-5 text-end fw-semibold text-dark">₹{{ number_format($quote->subtotal, 2) }}</div>
                                            </div>
                                            @if($quote->discount_amount > 0)
                                            <div class="row g-2 mb-2">
                                                <div class="col-7 text-success">Volume Discount Applied:</div>
                                                <div class="col-5 text-end text-success fw-semibold">- ₹{{ number_format($quote->discount_amount, 2) }}</div>
                                            </div>
                                            @endif
                                            @if($quote->tax_amount > 0)
                                            <div class="row g-2 mb-2">
                                                <div class="col-7 text-muted">GST / Applicable Tax ({{ $quote->tax_rate }}%):</div>
                                                <div class="col-5 text-end fw-semibold text-dark">₹{{ number_format($quote->tax_amount, 2) }}</div>
                                            </div>
                                            @endif
                                            @if($quote->shipping_cost > 0)
                                            <div class="row g-2 mb-2">
                                                <div class="col-7 text-muted">F.O.R. Logistics / Freight:</div>
                                                <div class="col-5 text-end fw-semibold text-dark">₹{{ number_format($quote->shipping_cost, 2) }}</div>
                                            </div>
                                            @endif
                                            <hr class="my-2">
                                            <div class="row g-2 align-items-center">
                                                <div class="col-7 fw-bold text-dark fs-6">Net Payable Amount:</div>
                                                <div class="col-5 text-end fw-bold text-success fs-5">₹{{ number_format($quote->total_amount, 2) }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Supplier Commercial Terms -->
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <div class="p-3 border rounded-3 bg-white">
                                                <div class="text-muted small fw-semibold text-uppercase">Payment Terms</div>
                                                <div class="fw-medium text-dark mt-1">{{ $quote->payment_terms ?? '100% advance or as agreed' }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="p-3 border rounded-3 bg-white">
                                                <div class="text-muted small fw-semibold text-uppercase">Delivery Estimate</div>
                                                <div class="fw-medium text-dark mt-1">{{ $quote->delivery_timeline ?? '3-5 business days from order confirmation' }}</div>
                                            </div>
                                        </div>
                                        @if($quote->vendor_notes)
                                        <div class="col-12">
                                            <div class="p-3 border rounded-3 bg-white">
                                                <div class="text-muted small fw-semibold text-uppercase">Supplier Special Notes</div>
                                                <div class="text-dark small mt-1">{{ $quote->vendor_notes }}</div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    @endif

                                </div>
                                <div class="modal-footer bg-light border-0 py-3 d-flex justify-content-between">
                                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Close</button>
                                    
                                    <div class="d-flex gap-2">
                                        @if(in_array($quote->status, ['sent', 'quoted', 'viewed']))
                                            <form method="POST" action="{{ route('customer.quotes.reject', $quote->id) }}" onsubmit="return confirm('Are you sure you want to decline this quote proposal?');">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger">
                                                    <i class="bi bi-x-circle me-1"></i> Decline Quote
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('customer.quotes.accept', $quote->id) }}" onsubmit="return confirm('Accept this quotation proposal of ₹{{ number_format($quote->total_amount, 2) }}?');">
                                                @csrf
                                                <button type="submit" class="btn btn-success fw-bold px-3">
                                                    <i class="bi bi-check-circle me-1"></i> Accept & Confirm Proposal
                                                </button>
                                            </form>
                                        @elseif($quote->status === 'accepted')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle p-2 fs-6">
                                                <i class="bi bi-check2-circle me-1"></i> Proposal Accepted. Vendor is preparing order.
                                            </span>
                                        @elseif($quote->status === 'converted_to_order' && $quote->convertedOrder)
                                            <a href="{{ route('customer.orders.show', $quote->convertedOrder->id) }}" class="btn btn-primary">
                                                <i class="bi bi-receipt me-1"></i> View Converted Order #{{ $quote->convertedOrder->order_number }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top">
            {{ $quotes->links() }}
        </div>
        @else
        <div class="text-center py-5 px-3">
            <div class="p-3 bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; color: #0B4D3C;">
                <i class="bi bi-file-earmark-text fs-3"></i>
            </div>
            <h5 class="fw-bold text-dark mb-1">No Quotations Found</h5>
            <p class="text-muted mb-3" style="font-size: 0.85rem;">Request wholesale price estimates and custom formulations directly from verified manufacturers.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">
                <i class="bi bi-search me-1"></i> Browse Products to Request Quote
            </a>
        </div>
        @endif
    </div>

</div>
@endsection
