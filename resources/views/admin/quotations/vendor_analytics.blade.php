@extends('layouts.admin')

@section('title', 'Vendor RFQ Performance Scorecard')

@section('content')
<div class="container-fluid p-0">

    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary-subtle text-primary fw-bold text-uppercase px-2 py-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">B2B Analytics</span>
                <span class="text-muted">•</span>
                <span class="text-muted small">Vendor Response & Conversion Scorecard</span>
            </div>
            <h1 class="h3 fw-bold text-dark mb-1">Vendor RFQ Performance</h1>
            <p class="text-muted mb-0" style="font-size: 0.875rem;">Monitor how swiftly and effectively suppliers respond to bulk quotation requests across Garudo.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.quotations.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
                <i class="bi bi-file-earmark-text"></i> Master Quotations
            </a>
            <a href="{{ route('admin.vendors.index') }}" class="btn btn-outline-primary d-flex align-items-center gap-2">
                <i class="bi bi-shop"></i> Manage Vendors
            </a>
        </div>
    </div>

    <!-- Global Performance KPI Cards -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 p-3" style="background: linear-gradient(135deg, #0B4D3C 0%, #166534 100%); color: #ffffff;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-white-50 small fw-semibold text-uppercase">Total RFQs Received</div>
                        <h2 class="fw-bold mb-0 mt-1 text-white">{{ number_format($totalMarketplaceRfqs) }}</h2>
                        <div class="text-white-50 mt-1" style="font-size: 0.75rem;">Across all categories</div>
                    </div>
                    <div class="p-2 rounded-3 bg-white bg-opacity-25">
                        <i class="bi bi-inbox fs-4 text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 p-3 bg-white">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Quoted Pipeline Value</div>
                        <h2 class="fw-bold mb-0 mt-1 text-dark">₹{{ number_format($globalQuotedVolume, 2) }}</h2>
                        <div class="text-success mt-1 small"><i class="bi bi-graph-up-arrow me-1"></i> Active Quotation Capital</div>
                    </div>
                    <div class="p-2 rounded-3 bg-success-subtle text-success">
                        <i class="bi bi-currency-rupee fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 p-3 bg-white">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Orders Converted</div>
                        <h2 class="fw-bold mb-0 mt-1 text-primary">{{ number_format($totalMarketplaceConversions) }}</h2>
                        <div class="text-muted mt-1 small">Closed as Bulk Orders</div>
                    </div>
                    <div class="p-2 rounded-3 bg-primary-subtle text-primary">
                        <i class="bi bi-check2-circle fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 p-3 bg-white">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Avg Conversion Rate</div>
                        <h2 class="fw-bold mb-0 mt-1 text-info">{{ $globalConversionRate }}%</h2>
                        <div class="text-muted mt-1 small">Decided RFQs to Orders</div>
                    </div>
                    <div class="p-2 rounded-3 bg-info-subtle text-info">
                        <i class="bi bi-pie-chart fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Sort Bar -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('admin.quotations.vendor-analytics') }}" class="row g-2 align-items-center">
                <div class="col-12 col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="q" value="{{ $search }}" class="form-control bg-light border-0" placeholder="Search by vendor name, company, email, phone...">
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <select name="sort" class="form-select bg-light border-0" onchange="this.form.submit()">
                        <option value="requests_desc" {{ $sortBy === 'requests_desc' ? 'selected' : '' }}>Sort by: Most RFQs Received</option>
                        <option value="pipeline_desc" {{ $sortBy === 'pipeline_desc' ? 'selected' : '' }}>Sort by: Highest Quoted Pipeline Value</option>
                        <option value="conversion_desc" {{ $sortBy === 'conversion_desc' ? 'selected' : '' }}>Sort by: Highest Conversion Rate %</option>
                        <option value="pending_desc" {{ $sortBy === 'pending_desc' ? 'selected' : '' }}>Sort by: Most Pending / Unanswered RFQs</option>
                    </select>
                </div>
                <div class="col-12 col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-1"></i> Apply Filter
                    </button>
                    @if($search || $sortBy !== 'requests_desc')
                    <a href="{{ route('admin.quotations.vendor-analytics') }}" class="btn btn-outline-secondary" title="Reset">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Vendor Scorecard Table -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
            <h5 class="card-title fw-bold text-dark mb-0">Supplier Quotation Efficiency & Volume</h5>
            <span class="badge bg-light text-secondary border">{{ $vendors->count() }} Vendors Evaluated</span>
        </div>
        <div class="card-body p-0">
            @if($vendors->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr style="font-size: 0.78rem; text-transform: uppercase; color: #64748B;">
                            <th class="ps-4 py-3">Supplier / Business</th>
                            <th class="py-3 text-center">RFQ Enabled SKUs</th>
                            <th class="py-3 text-center">Total Received</th>
                            <th class="py-3 text-center">Pending / Waiting</th>
                            <th class="py-3 text-center">Response Rate</th>
                            <th class="py-3 text-center">Converted Orders</th>
                            <th class="py-3 text-end">Quoted Pipeline</th>
                            <th class="pe-4 py-3 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vendors as $vendor)
                        @php
                            $rateColor = $vendor->response_rate >= 80 ? 'success' : ($vendor->response_rate >= 50 ? 'warning' : 'danger');
                        @endphp
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; background: #ECFDF5; color: #0B4D3C; font-size: 0.95rem;">
                                        {{ strtoupper(substr($vendor->company_name ?? $vendor->name ?? 'V', 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $vendor->company_name ?? $vendor->name }}</div>
                                        <div class="text-muted small">{{ $vendor->email }} • {{ $vendor->phone }}</div>
                                        @if($vendor->shop_name && $vendor->shop_name !== $vendor->company_name)
                                        <div class="text-muted" style="font-size: 0.75rem;"><i class="bi bi-shop me-1"></i>{{ $vendor->shop_name }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-center py-3">
                                <span class="badge bg-light text-dark border fw-medium px-2 py-1">
                                    <i class="bi bi-box-seam me-1 text-muted"></i>{{ $vendor->rfq_enabled_products }} Products
                                </span>
                            </td>
                            <td class="text-center py-3">
                                <span class="fw-bold text-dark fs-6">{{ $vendor->total_rfqs_received }}</span>
                            </td>
                            <td class="text-center py-3">
                                @if($vendor->pending_rfqs > 0)
                                    <span class="badge bg-danger-subtle text-danger fw-bold px-2 py-1">
                                        <i class="bi bi-clock me-1"></i>{{ $vendor->pending_rfqs }} Pending
                                    </span>
                                @else
                                    <span class="badge bg-success-subtle text-success fw-medium px-2 py-1">
                                        <i class="bi bi-check-all me-1"></i>All Answered
                                    </span>
                                @endif
                            </td>
                            <td class="text-center py-3" style="min-width: 140px;">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <div class="progress flex-grow-1" style="height: 6px; width: 60px;">
                                        <div class="progress-bar bg-{{ $rateColor }}" role="progressbar" style="width: {{ $vendor->response_rate }}%"></div>
                                    </div>
                                    <span class="fw-bold text-{{ $rateColor }} small">{{ $vendor->response_rate }}%</span>
                                </div>
                            </td>
                            <td class="text-center py-3">
                                <div class="fw-bold text-success">{{ $vendor->converted_rfqs }} Orders</div>
                                <div class="text-muted" style="font-size: 0.72rem;">{{ $vendor->conversion_rate }}% conv. rate</div>
                            </td>
                            <td class="text-end py-3">
                                <div class="fw-bold text-dark">₹{{ number_format($vendor->total_quoted_pipeline, 2) }}</div>
                            </td>
                            <td class="pe-4 py-3 text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('admin.quotations.index', ['vendor_id' => $vendor->id]) }}" class="btn btn-sm btn-outline-primary" title="View Vendor Quotes">
                                        <i class="bi bi-file-earmark-text"></i> Quotes
                                    </a>
                                    <a href="{{ route('admin.vendors.show', $vendor->id) }}" class="btn btn-sm btn-light border" title="Vendor Profile">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5 px-3">
                <div class="p-3 bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; color: #64748B;">
                    <i class="bi bi-people fs-3"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">No Vendors Match Filters</h5>
                <p class="text-muted mb-0" style="font-size: 0.85rem;">Try refining or clearing your search criteria.</p>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection
