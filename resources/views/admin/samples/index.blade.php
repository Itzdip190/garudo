@extends('layouts.admin')

@section('title', 'Product Samples & Trials Command Center')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h4 class="page-header-title mb-0">Product Samples & Testing Command Center</h4>
            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 small fw-semibold">
                <i class="bi bi-box2-heart-fill me-1"></i>Material Trials
            </span>
        </div>
        <p class="page-header-subtitle mb-0">Monitor buyer sample requests, inspect customer details, track vendor dispatch speed, and supervise sample-to-bulk purchase conversions.</p>
    </div>
    <div class="d-flex flex-wrap align-items-center gap-2">
        <a href="{{ route('admin.samples.vendor-analytics') }}" class="btn btn-warning btn-sm px-3 fw-bold text-dark" style="border-radius: 10px;">
            <i class="bi bi-graph-up-arrow me-1"></i> Vendor Conversion Analytics Scorecard
        </a>
        <a href="{{ route('admin.products.index') }}" class="btn btn-light border btn-sm px-3" style="border-radius: 10px;">
            <i class="bi bi-box-seam me-1"></i> Catalog Products
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4 rounded-3 border-0 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- KPI STATS -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-xl-2">
        <div class="garudo-card kpi-card">
            <div class="kpi-label">Total Sample Requests</div>
            <div class="kpi-value">{{ $totalRequests }}</div>
            <small class="text-muted" style="font-size: 0.72rem;">Marketplace Lifetime</small>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="garudo-card kpi-card" style="border-left: 4px solid #F59E0B;">
            <div class="kpi-label text-warning">Pending Review</div>
            <div class="kpi-value text-warning">{{ $pendingCount }}</div>
            <small class="text-muted" style="font-size: 0.72rem;">Awaiting Vendor Action</small>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="garudo-card kpi-card" style="border-left: 4px solid #0284C7;">
            <div class="kpi-label text-primary">In Transit / Dispatched</div>
            <div class="kpi-value text-primary">{{ $dispatchedCount }}</div>
            <small class="text-muted" style="font-size: 0.72rem;">With Courier Partners</small>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="garudo-card kpi-card" style="border-left: 4px solid #10B981;">
            <div class="kpi-label text-success">Delivered to Buyers</div>
            <div class="kpi-value text-success">{{ $deliveredCount }}</div>
            <small class="text-muted" style="font-size: 0.72rem;">Specimens Tested</small>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="garudo-card kpi-card" style="border-left: 4px solid #7C3AED;">
            <div class="kpi-label" style="color: #7C3AED;">Bulk Orders Won</div>
            <div class="kpi-value" style="color: #6D28D9;">{{ $convertedCount }}</div>
            <small class="text-muted" style="font-size: 0.72rem;">Converted from Sample</small>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="garudo-card kpi-card" style="border-left: 4px solid #059669;">
            <div class="kpi-label text-success">Conversion Rate</div>
            <div class="kpi-value text-success">{{ $conversionRate }}%</div>
            <small class="text-muted" style="font-size: 0.72rem;">Sample to Full Order</small>
        </div>
    </div>
</div>

<!-- FILTERS & SEARCH -->
<div class="garudo-card mb-4">
    <div class="p-3 border-bottom">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
            <!-- Status Tabs -->
            <ul class="nav nav-pills flex-nowrap overflow-auto pb-2 pb-lg-0" style="gap: 6px;">
                <li class="nav-item">
                    <a class="nav-link btn-sm py-1.5 px-3 {{ $status === 'all' ? 'active bg-success' : 'text-dark bg-light' }}" href="{{ route('admin.samples.index', ['status' => 'all', 'vendor_id' => $vendorId, 'sample_type' => $sampleType, 'q' => $search]) }}" style="border-radius: 8px;">
                        All <span class="badge {{ $status === 'all' ? 'bg-white text-success' : 'bg-secondary' }} ms-1">{{ $counts['all'] }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn-sm py-1.5 px-3 {{ $status === 'pending' ? 'active bg-warning text-dark' : 'text-dark bg-light' }}" href="{{ route('admin.samples.index', ['status' => 'pending', 'vendor_id' => $vendorId, 'sample_type' => $sampleType, 'q' => $search]) }}" style="border-radius: 8px;">
                        Pending <span class="badge {{ $status === 'pending' ? 'bg-dark text-white' : 'bg-warning text-dark' }} ms-1">{{ $counts['pending'] }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn-sm py-1.5 px-3 {{ $status === 'approved' ? 'active bg-info text-white' : 'text-dark bg-light' }}" href="{{ route('admin.samples.index', ['status' => 'approved', 'vendor_id' => $vendorId, 'sample_type' => $sampleType, 'q' => $search]) }}" style="border-radius: 8px;">
                        Approved <span class="badge {{ $status === 'approved' ? 'bg-white text-info' : 'bg-info text-white' }} ms-1">{{ $counts['approved'] }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn-sm py-1.5 px-3 {{ $status === 'dispatched' ? 'active bg-primary' : 'text-dark bg-light' }}" href="{{ route('admin.samples.index', ['status' => 'dispatched', 'vendor_id' => $vendorId, 'sample_type' => $sampleType, 'q' => $search]) }}" style="border-radius: 8px;">
                        Dispatched <span class="badge {{ $status === 'dispatched' ? 'bg-white text-primary' : 'bg-primary' }} ms-1">{{ $counts['dispatched'] }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn-sm py-1.5 px-3 {{ $status === 'delivered' ? 'active bg-success' : 'text-dark bg-light' }}" href="{{ route('admin.samples.index', ['status' => 'delivered', 'vendor_id' => $vendorId, 'sample_type' => $sampleType, 'q' => $search]) }}" style="border-radius: 8px;">
                        Delivered <span class="badge {{ $status === 'delivered' ? 'bg-white text-success' : 'bg-success' }} ms-1">{{ $counts['delivered'] }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn-sm py-1.5 px-3 {{ $status === 'converted_to_order' ? 'active bg-dark text-white' : 'text-dark bg-light' }}" href="{{ route('admin.samples.index', ['status' => 'converted_to_order', 'vendor_id' => $vendorId, 'sample_type' => $sampleType, 'q' => $search]) }}" style="border-radius: 8px;">
                        Converted <span class="badge {{ $status === 'converted_to_order' ? 'bg-warning text-dark' : 'bg-secondary' }} ms-1">{{ $counts['converted'] }}</span>
                    </a>
                </li>
            </ul>

            <!-- Filter Dropdowns -->
            <form action="{{ route('admin.samples.index') }}" method="GET" class="d-flex flex-wrap gap-2">
                <input type="hidden" name="status" value="{{ $status }}">

                <select name="vendor_id" class="form-select form-select-sm" style="max-width: 180px;" onchange="this.form.submit()">
                    <option value="">All Vendors</option>
                    @foreach($vendors as $v)
                        <option value="{{ $v->id }}" {{ $vendorId == $v->id ? 'selected' : '' }}>{{ $v->company_name }}</option>
                    @endforeach
                </select>

                <select name="sample_type" class="form-select form-select-sm" style="max-width: 150px;" onchange="this.form.submit()">
                    <option value="">All Sample Types</option>
                    <option value="free" {{ $sampleType === 'free' ? 'selected' : '' }}>100% Free</option>
                    <option value="paid" {{ $sampleType === 'paid' ? 'selected' : '' }}>Paid Sample</option>
                    <option value="partial_paid" {{ $sampleType === 'partial_paid' ? 'selected' : '' }}>Partial Paid</option>
                </select>

                <div class="input-group input-group-sm" style="min-width: 220px;">
                    <input type="text" name="q" class="form-control" placeholder="Search Buyer, Phone, Request #..." value="{{ $search }}">
                    <button type="submit" class="btn btn-dark"><i class="bi bi-search"></i></button>
                    @if($search || $vendorId || $sampleType)
                        <a href="{{ route('admin.samples.index', ['status' => $status]) }}" class="btn btn-outline-secondary"><i class="bi bi-x"></i></a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- MASTER SAMPLES TABLE -->
    <div class="table-responsive">
        <table class="table admin-table align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-3">Request #</th>
                    <th>Product & Specimen</th>
                    <th>Customer / Buyer Profile</th>
                    <th>Vendor / Supplier</th>
                    <th>Type & Pricing</th>
                    <th>Status</th>
                    <th>Dispatch Logistics</th>
                    <th class="text-end pe-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($samples as $sample)
                    <tr>
                        <td class="ps-3">
                            <a href="{{ route('admin.samples.show', $sample->id) }}" class="fw-bold text-dark text-decoration-none d-block">
                                {{ $sample->request_number }}
                            </a>
                            <small class="text-muted" style="font-size: 0.72rem;">{{ $sample->created_at->format('d M Y, h:i A') }}</small>
                        </td>

                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $sample->product->primaryImage ? asset('storage/'.$sample->product->primaryImage->file_path) : 'https://placehold.co/80x80?text=Material' }}" 
                                     class="rounded border" style="width: 42px; height: 42px; object-fit: cover;" alt="{{ $sample->product->name ?? 'Sample' }}">
                                <div>
                                    <a href="{{ route('admin.products.show', $sample->product->id) }}" class="fw-semibold text-dark text-decoration-none small d-block" style="max-width: 180px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $sample->product->name ?? 'Product' }}
                                    </a>
                                    <span class="text-muted small" style="font-size: 0.7rem;">Qty: {{ $sample->quantity }} | {{ $sample->product->sample_unit_description ?? 'Standard' }}</span>
                                </div>
                            </div>
                        </td>

                        <td>
                            <div class="fw-bold text-dark small">{{ $sample->buyer_name }}</div>
                            @if($sample->company_name)
                                <div class="text-muted small" style="font-size: 0.72rem;"><i class="bi bi-building me-1"></i>{{ $sample->company_name }}</div>
                            @endif
                            <div class="text-muted small" style="font-size: 0.72rem;">
                                <i class="bi bi-telephone text-success me-1"></i>{{ $sample->buyer_phone }} | <i class="bi bi-geo-alt me-1"></i>{{ $sample->city }}, {{ $sample->state }}
                            </div>
                            @if($sample->project_type)
                                <span class="badge bg-secondary-subtle text-dark border small mt-1" style="font-size: 0.68rem;">{{ $sample->project_type }}</span>
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('admin.vendors.show', $sample->vendor->id) }}" class="fw-semibold text-dark text-decoration-none small d-block">
                                {{ $sample->vendor->company_name ?? $sample->vendor->name }}
                            </a>
                            <span class="text-muted small" style="font-size: 0.72rem;"><i class="bi bi-telephone me-1"></i>{{ $sample->vendor->phone ?? 'N/A' }}</span>
                        </td>

                        <td>
                            {!! $sample->getSampleTypeBadge() !!}
                            <div class="small fw-semibold mt-1">
                                {{ $sample->total_amount > 0 ? '₹' . number_format($sample->total_amount, 2) : '₹0.00 (Free)' }}
                            </div>
                        </td>

                        <td>
                            {!! $sample->getStatusBadge() !!}
                            @if($sample->status === 'converted_to_order' && $sample->convertedOrder)
                                <a href="{{ route('admin.orders.show', $sample->convertedOrder->id) }}" class="small text-success d-block fw-semibold mt-1">
                                    <i class="bi bi-link-45deg"></i> Bulk PO #{{ $sample->convertedOrder->order_number }}
                                </a>
                            @endif
                        </td>

                        <td>
                            @if($sample->tracking_number)
                                <div class="small fw-semibold text-dark"><i class="bi bi-truck text-primary me-1"></i>{{ $sample->courier_partner }}</div>
                                <code class="small text-muted" style="font-size: 0.73rem;">{{ $sample->tracking_number }}</code>
                            @else
                                <span class="badge bg-light text-muted border small">Not Dispatched</span>
                            @endif
                        </td>

                        <td class="text-end pe-3">
                            <a href="{{ route('admin.samples.show', $sample->id) }}" class="btn btn-outline-dark btn-sm px-2.5 py-1" title="Inspect Request">
                                <i class="bi bi-eye"></i> Details
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-box2 text-muted" style="font-size: 3rem;"></i>
                            <h6 class="fw-bold mt-2 text-dark">No Sample Requests Found</h6>
                            <p class="small mb-0">No sample orders matching the selected filter criteria.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($samples->hasPages())
        <div class="p-3 border-top">
            {{ $samples->links() }}
        </div>
    @endif
</div>
@endsection
