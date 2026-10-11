@extends('layouts.admin')

@section('title', 'Master Quotations & B2B RFQ Monitor')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h4 class="page-header-title mb-0">Quotations & B2B RFQs</h4>
            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1 small fw-semibold">Master Trade Desk</span>
        </div>
        <p class="page-header-subtitle mb-0">Platform-wide oversight of all custom buyer price inquiries, vendor proposals, commercial terms, and deal conversions.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('admin.quotations.vendor-analytics') }}" class="btn btn-warning btn-sm px-3 fw-bold text-dark" style="border-radius: 10px;">
            <i class="bi bi-bar-chart-line-fill me-1"></i> Vendor RFQ Scorecard
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show p-3 small mb-4" role="alert" style="border-radius: 12px;">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill fs-5 text-success"></i>
            <div><strong>Success:</strong> {{ session('success') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Master KPI Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="garudo-card kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <span class="kpi-label text-muted">Total RFQs Submitted</span>
                <div class="kpi-icon-wrap bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-file-earmark-text-fill"></i>
                </div>
            </div>
            <div class="kpi-value">{{ number_format($totalQuotations) }}</div>
            <span class="text-muted small">Platform volume</span>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="garudo-card kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <span class="kpi-label text-muted">Pending Vendor Quote</span>
                <div class="kpi-icon-wrap bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>
            <div class="kpi-value text-warning">{{ number_format($pendingCount) }}</div>
            <span class="text-muted small">Awaiting merchant pricing</span>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="garudo-card kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <span class="kpi-label text-muted">Quoted Proposals Pipeline</span>
                <div class="kpi-icon-wrap bg-success bg-opacity-10 text-success">
                    <i class="bi bi-currency-rupee"></i>
                </div>
            </div>
            <div class="kpi-value text-success">₹{{ number_format($totalQuotedValue, 2) }}</div>
            <span class="text-muted small">{{ $quotedCount }} active vendor proposals</span>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="garudo-card kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <span class="kpi-label text-muted">RFQ Conversion Rate</span>
                <div class="kpi-icon-wrap bg-info bg-opacity-10 text-info">
                    <i class="bi bi-trophy-fill"></i>
                </div>
            </div>
            <div class="kpi-value text-dark">{{ $conversionRate }}%</div>
            <span class="text-muted small">{{ $convertedCount }} converted to bulk orders</span>
        </div>
    </div>
</div>

<!-- Filters & Search Toolbar -->
<div class="garudo-card p-3 mb-4">
    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
        <!-- Status Tabs -->
        <ul class="nav nav-pills gap-1">
            <li class="nav-item">
                <a class="nav-link btn-sm py-1.5 px-3 {{ $status === 'all' ? 'active bg-success' : 'text-dark bg-light' }}" href="{{ route('admin.quotations.index', ['status' => 'all', 'vendor_id' => $vendorId, 'q' => $search]) }}" style="border-radius: 8px;">
                    All ({{ $counts['all'] }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link btn-sm py-1.5 px-3 {{ $status === 'pending' ? 'active bg-warning text-dark' : 'text-dark bg-light' }}" href="{{ route('admin.quotations.index', ['status' => 'pending', 'vendor_id' => $vendorId, 'q' => $search]) }}" style="border-radius: 8px;">
                    Pending RFQs ({{ $counts['pending'] }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link btn-sm py-1.5 px-3 {{ $status === 'quoted' ? 'active bg-primary' : 'text-dark bg-light' }}" href="{{ route('admin.quotations.index', ['status' => 'quoted', 'vendor_id' => $vendorId, 'q' => $search]) }}" style="border-radius: 8px;">
                    Quoted ({{ $counts['quoted'] }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link btn-sm py-1.5 px-3 {{ $status === 'accepted' ? 'active bg-success' : 'text-dark bg-light' }}" href="{{ route('admin.quotations.index', ['status' => 'accepted', 'vendor_id' => $vendorId, 'q' => $search]) }}" style="border-radius: 8px;">
                    Accepted ({{ $counts['accepted'] }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link btn-sm py-1.5 px-3 {{ $status === 'converted_to_order' ? 'active bg-dark text-white' : 'text-dark bg-light' }}" href="{{ route('admin.quotations.index', ['status' => 'converted_to_order', 'vendor_id' => $vendorId, 'q' => $search]) }}" style="border-radius: 8px;">
                    Converted ({{ $counts['converted'] }})
                </a>
            </li>
        </ul>

        <!-- Multi-Vendor Filter & Search Form -->
        <form action="{{ route('admin.quotations.index') }}" method="GET" class="d-flex flex-wrap gap-2">
            <input type="hidden" name="status" value="{{ $status }}">

            <select name="vendor_id" class="form-select form-select-sm" style="min-width: 180px;" onchange="this.form.submit()">
                <option value="">All Vendors / Suppliers</option>
                @foreach($vendors as $vnd)
                    <option value="{{ $vnd->id }}" {{ $vendorId == $vnd->id ? 'selected' : '' }}>{{ \Illuminate\Support\Str::limit($vnd->company_name, 22) }}</option>
                @endforeach
            </select>

            <div class="input-group input-group-sm" style="min-width: 220px;">
                <input type="text" name="q" value="{{ $search }}" class="form-control" placeholder="Quote #, buyer, phone...">
                <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
            </div>

            @if($search || $vendorId)
                <a href="{{ route('admin.quotations.index', ['status' => $status]) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x"></i></a>
            @endif
        </form>
    </div>
</div>

<!-- Master Quotations Table -->
<div class="garudo-card p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-3 text-muted small">RFQ / QUOTE #</th>
                    <th class="text-muted small">BUYER DETAILS</th>
                    <th class="text-muted small">SUPPLIER / VENDOR</th>
                    <th class="text-muted small">PRODUCT & SPECS</th>
                    <th class="text-muted small">QUANTITY</th>
                    <th class="text-muted small">QUOTED TOTAL (₹)</th>
                    <th class="text-muted small">STATUS</th>
                    <th class="pe-3 text-end text-muted small">INSPECT</th>
                </tr>
            </thead>
            <tbody>
                @forelse($quotations as $q)
                    <tr>
                        <td class="ps-3">
                            <a href="{{ route('admin.quotations.show', $q->id) }}" class="fw-bold text-dark text-decoration-none d-block">
                                #{{ $q->quotation_number }}
                            </a>
                            <span class="text-muted" style="font-size: 0.72rem;">{{ $q->created_at->format('d M Y, h:i A') }}</span>
                        </td>
                        <td>
                            <strong class="d-block text-dark">{{ $q->buyer_name }}</strong>
                            <div class="small text-muted" style="font-size: 0.75rem;">
                                @if($q->company_name)
                                    <span class="text-dark">{{ $q->company_name }}</span><br>
                                @endif
                                <i class="bi bi-geo-alt me-1"></i>{{ $q->delivery_city ?? 'India' }}, {{ $q->delivery_state ?? '' }}
                            </div>
                        </td>
                        <td>
                            @if($q->vendor)
                                <a href="{{ route('admin.vendors.show', $q->vendor->id) }}" class="fw-semibold text-dark text-decoration-none d-block small">
                                    {{ $q->vendor->company_name }}
                                </a>
                                <span class="badge bg-light text-muted border" style="font-size: 0.7rem;">{{ $q->vendor->business_type ?? 'Supplier' }}</span>
                            @else
                                <span class="text-muted small">Vendor N/A</span>
                            @endif
                        </td>
                        <td>
                            @if($q->product)
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $q->product->primaryImage ? asset('storage/'.$q->product->primaryImage->file_path) : 'https://placehold.co/40x40?text=Item' }}" class="rounded border" style="width: 36px; height: 36px; object-fit: cover;" alt="">
                                    <div>
                                        <a href="{{ route('admin.products.show', $q->product->id) }}" class="fw-semibold text-dark text-decoration-none d-block small">
                                            {{ \Illuminate\Support\Str::limit($q->product->name, 26) }}
                                        </a>
                                        @if($q->selected_variant)
                                            <span class="badge bg-light text-dark border" style="font-size: 0.68rem;">{{ $q->selected_variant }}</span>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <span class="text-muted small">Custom Specification</span>
                            @endif
                        </td>
                        <td>
                            <strong class="text-dark">{{ number_format($q->quantity) }}</strong>
                            <span class="text-muted small">{{ $q->unit }}</span>
                        </td>
                        <td>
                            @if($q->total_amount > 0)
                                <strong class="text-success d-block">₹{{ number_format($q->total_amount, 2) }}</strong>
                                <span class="text-muted" style="font-size: 0.72rem;">@ ₹{{ number_format($q->unit_price, 2) }}/unit</span>
                            @else
                                <span class="badge bg-light text-muted border">Pending Quote</span>
                            @endif
                        </td>
                        <td>
                            {!! $q->getStatusBadge() !!}
                        </td>
                        <td class="pe-3 text-end">
                            <a href="{{ route('admin.quotations.show', $q->id) }}" class="btn btn-outline-dark btn-sm px-2.5 py-1" title="Inspect Request">
                                <i class="bi bi-eye"></i> Details
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-file-earmark-text fs-1 d-block mb-2 text-muted"></i>
                            <h6 class="fw-bold text-dark">No Quotation Requests Matching Filter</h6>
                            <p class="small text-muted mb-0">When buyers request custom quotes from vendors on Garudo, they will be tracked here in real time.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($quotations->hasPages())
        <div class="p-3 border-top bg-light">
            {{ $quotations->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection
