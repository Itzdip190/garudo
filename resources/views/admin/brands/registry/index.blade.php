@extends('layouts.admin')

@section('title', 'Brand Registry & Trademark Applications')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="page-header-title">Brand Registry Command Center</h1>
        <p class="page-header-subtitle mb-0">Review, inspect trademark certificates, and grant official Garudo Verified Brand OEM accreditation.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('admin.dealer-authorizations.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
            <i class="bi bi-diagram-3-fill me-1"></i> Dealer Authorizations
        </a>
    </div>
</div>

<!-- ---------------- STATS PILLS ---------------- -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="garudo-card p-3 border-start border-4 border-warning">
            <div class="text-warning small fw-bold text-uppercase">Pending Review</div>
            <div class="fs-4 fw-bold text-warning mt-1">{{ $stats['pending'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="garudo-card p-3 border-start border-4 border-success">
            <div class="text-success small fw-bold text-uppercase">Authorized Brands</div>
            <div class="fs-4 fw-bold text-success mt-1">{{ $stats['approved'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="garudo-card p-3 border-start border-4 border-danger">
            <div class="text-danger small fw-bold text-uppercase">Rejected / Inactive</div>
            <div class="fs-4 fw-bold text-danger mt-1">{{ $stats['rejected'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="garudo-card p-3 border-start border-4 border-primary">
            <div class="text-primary small fw-bold text-uppercase">Total Applications</div>
            <div class="fs-4 fw-bold text-dark mt-1">{{ $stats['total'] }}</div>
        </div>
    </div>
</div>

<!-- ---------------- FILTER TABS & SEARCH ---------------- -->
<div class="garudo-card p-3 mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <ul class="nav nav-pills gap-1">
            <li class="nav-item">
                <a class="nav-link {{ $statusFilter === 'all' ? 'active bg-primary' : 'text-dark' }} py-1 px-3 small rounded-pill" href="{{ route('admin.brands.registry.index', ['status' => 'all']) }}">
                    All ({{ $stats['total'] }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $statusFilter === 'pending' ? 'active bg-warning text-dark fw-bold' : 'text-dark' }} py-1 px-3 small rounded-pill" href="{{ route('admin.brands.registry.index', ['status' => 'pending']) }}">
                    Pending ({{ $stats['pending'] }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $statusFilter === 'approved' ? 'active bg-success text-white' : 'text-dark' }} py-1 px-3 small rounded-pill" href="{{ route('admin.brands.registry.index', ['status' => 'approved']) }}">
                    Authorized ({{ $stats['approved'] }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $statusFilter === 'rejected' ? 'active bg-danger text-white' : 'text-dark' }} py-1 px-3 small rounded-pill" href="{{ route('admin.brands.registry.index', ['status' => 'rejected']) }}">
                    Rejected ({{ $stats['rejected'] }})
                </a>
            </li>
        </ul>

        <form action="{{ route('admin.brands.registry.index') }}" method="GET" class="d-flex gap-2">
            <input type="hidden" name="status" value="{{ $statusFilter }}">
            <div class="input-group input-group-sm" style="min-width: 260px;">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Search brand, TM No, vendor..." value="{{ $search }}">
            </div>
            <button type="submit" class="btn btn-sm btn-dark px-3 rounded-pill">Search</button>
            @if($search)
                <a href="{{ route('admin.brands.registry.index', ['status' => $statusFilter]) }}" class="btn btn-sm btn-outline-secondary rounded-pill">Clear</a>
            @endif
        </form>
    </div>
</div>

<!-- ---------------- APPLICATIONS TABLE ---------------- -->
<div class="garudo-card">
    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 70px;">Logo</th>
                    <th>Brand Name & Type</th>
                    <th>Applicant Vendor (OEM)</th>
                    <th>Trademark & Class</th>
                    <th>Gated Policy</th>
                    <th>Status</th>
                    <th>Applied Date</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $app)
                    <tr>
                        <td>
                            @if($app->logo_path)
                                <img src="{{ asset('storage/' . $app->logo_path) }}" alt="{{ $app->brand_name }}" class="rounded border p-1" style="width: 48px; height: 48px; object-fit: contain; background: #fff;">
                            @else
                                <div class="rounded bg-light border d-flex align-items-center justify-content-center text-muted fw-bold" style="width: 48px; height: 48px; font-size: 1.1rem;">
                                    {{ substr($app->brand_name, 0, 2) }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $app->brand_name }}</div>
                            <span class="badge bg-light text-dark border small mt-1">
                                <i class="bi bi-shield-check text-success me-1"></i>{{ ucwords(str_replace('_', ' ', $app->brand_type)) }}
                            </span>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $app->vendor->company_name ?? 'N/A' }}</div>
                            <small class="text-muted"><i class="bi bi-file-text me-1"></i>GSTIN: {{ $app->vendor->gstin ?? 'Unregistered' }}</small>
                        </td>
                        <td>
                            <div class="small fw-semibold text-dark">TM No: {{ $app->trademark_number ?? 'N/A' }}</div>
                            <small class="text-muted">Class {{ $app->trademark_class ?? '-' }} • {{ ucfirst($app->trademark_status) }}</small>
                            @if($app->trademark_doc_path)
                                <div>
                                    <a href="{{ asset('storage/' . $app->trademark_doc_path) }}" target="_blank" class="small text-primary text-decoration-none">
                                        <i class="bi bi-file-earmark-pdf-fill me-1 text-danger"></i>View TM Doc
                                    </a>
                                </div>
                            @endif
                        </td>
                        <td>
                            @if($app->is_exclusive_distribution)
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                    <i class="bi bi-lock-fill me-1"></i>Protected (Gated)
                                </span>
                            @else
                                <span class="badge bg-success-subtle text-success border border-success-subtle">
                                    <i class="bi bi-unlock-fill me-1"></i>Open Catalog
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($app->status === 'approved')
                                <span class="badge bg-success px-2 py-1"><i class="bi bi-check-circle-fill me-1"></i>Authorized OEM</span>
                            @elseif($app->status === 'pending')
                                <span class="badge bg-warning text-dark px-2 py-1"><i class="bi bi-hourglass-split me-1"></i>Pending Review</span>
                            @elseif($app->status === 'under_review')
                                <span class="badge bg-info text-dark px-2 py-1"><i class="bi bi-eye-fill me-1"></i>Under Review</span>
                            @else
                                <span class="badge bg-danger px-2 py-1"><i class="bi bi-x-circle-fill me-1"></i>Rejected</span>
                            @endif
                        </td>
                        <td class="text-muted small">
                            {{ $app->created_at->format('d M Y') }}<br>
                            <span class="text-muted" style="font-size: 0.75rem;">{{ $app->created_at->diffForHumans() }}</span>
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('admin.brands.registry.show', $app->id) }}" class="btn btn-sm btn-primary rounded-pill px-3">
                                <i class="bi bi-search me-1"></i>Review
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-patch-question fs-1 d-block mb-2 text-secondary"></i>
                            No brand registration applications found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($applications->hasPages())
        <div class="p-3 border-top d-flex justify-content-end">
            {{ $applications->links() }}
        </div>
    @endif
</div>
@endsection
