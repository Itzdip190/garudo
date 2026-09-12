@extends('layouts.admin')

@section('title', 'Dealer & Distributor Authorizations')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="page-header-title">Dealer & Distributor Authorizations</h1>
        <p class="page-header-subtitle mb-0">Inspect dealership agreements, verify regional territories, and issue official Garudo Authorized Dealer digital certificates.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('admin.brands.registry.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
            <i class="bi bi-award me-1"></i> Brand Registry
        </a>
    </div>
</div>

<!-- ---------------- STATS PILLS ---------------- -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="garudo-card p-3 border-start border-4 border-warning">
            <div class="text-warning small fw-bold text-uppercase">Pending Verification</div>
            <div class="fs-4 fw-bold text-warning mt-1">{{ $stats['pending'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="garudo-card p-3 border-start border-4 border-success">
            <div class="text-success small fw-bold text-uppercase">Verified Dealers</div>
            <div class="fs-4 fw-bold text-success mt-1">{{ $stats['verified'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="garudo-card p-3 border-start border-4 border-danger">
            <div class="text-danger small fw-bold text-uppercase">Rejected Requests</div>
            <div class="fs-4 fw-bold text-danger mt-1">{{ $stats['rejected'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="garudo-card p-3 border-start border-4 border-primary">
            <div class="text-primary small fw-bold text-uppercase">Total Authorizations</div>
            <div class="fs-4 fw-bold text-dark mt-1">{{ $stats['total'] }}</div>
        </div>
    </div>
</div>

<!-- ---------------- FILTER TABS & SEARCH ---------------- -->
<div class="garudo-card p-3 mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <ul class="nav nav-pills gap-1">
            <li class="nav-item">
                <a class="nav-link {{ $statusFilter === 'all' ? 'active bg-primary' : 'text-dark' }} py-1 px-3 small rounded-pill" href="{{ route('admin.dealer-authorizations.index', ['status' => 'all', 'type' => $typeFilter]) }}">
                    All ({{ $stats['total'] }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $statusFilter === 'pending' ? 'active bg-warning text-dark fw-bold' : 'text-dark' }} py-1 px-3 small rounded-pill" href="{{ route('admin.dealer-authorizations.index', ['status' => 'pending', 'type' => $typeFilter]) }}">
                    Pending ({{ $stats['pending'] }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $statusFilter === 'verified' ? 'active bg-success text-white' : 'text-dark' }} py-1 px-3 small rounded-pill" href="{{ route('admin.dealer-authorizations.index', ['status' => 'verified', 'type' => $typeFilter]) }}">
                    Verified ({{ $stats['verified'] }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $statusFilter === 'rejected' ? 'active bg-danger text-white' : 'text-dark' }} py-1 px-3 small rounded-pill" href="{{ route('admin.dealer-authorizations.index', ['status' => 'rejected', 'type' => $typeFilter]) }}">
                    Rejected ({{ $stats['rejected'] }})
                </a>
            </li>
        </ul>

        <form action="{{ route('admin.dealer-authorizations.index') }}" method="GET" class="d-flex gap-2">
            <input type="hidden" name="status" value="{{ $statusFilter }}">
            <div class="input-group input-group-sm" style="min-width: 280px;">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Search dealer, brand, cert ID..." value="{{ $search }}">
            </div>
            <button type="submit" class="btn btn-sm btn-dark px-3 rounded-pill">Search</button>
            @if($search)
                <a href="{{ route('admin.dealer-authorizations.index', ['status' => $statusFilter]) }}" class="btn btn-sm btn-outline-secondary rounded-pill">Clear</a>
            @endif
        </form>
    </div>
</div>

<!-- ---------------- AUTHORIZATIONS TABLE ---------------- -->
<div class="garudo-card">
    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Brand</th>
                    <th>Dealer / Distributor</th>
                    <th>Authorization Tier</th>
                    <th>Territory Coverage</th>
                    <th>Validity</th>
                    <th>Status</th>
                    <th>Certificate ID</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($authorizations as $auth)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($auth->brand->logo)
                                    <img src="{{ asset('storage/' . $auth->brand->logo) }}" alt="{{ $auth->brand->name }}" class="rounded border p-1" style="width: 38px; height: 38px; object-fit: contain; background: #fff;">
                                @else
                                    <div class="rounded bg-light border d-flex align-items-center justify-content-center text-muted fw-bold" style="width: 38px; height: 38px;">
                                        {{ substr($auth->brand->name, 0, 2) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-bold text-dark">{{ $auth->brand->name }}</div>
                                    @if($auth->brand->is_protected)
                                        <small class="text-danger"><i class="bi bi-lock-fill"></i> Protected</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $auth->vendor->company_name ?? 'N/A' }}</div>
                            <small class="text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $auth->vendor->primary_dispatch_state ?? 'India' }}</small>
                        </td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                {{ $auth->formatted_type }}
                            </span>
                        </td>
                        <td>
                            <div class="small fw-semibold text-dark">{{ $auth->formatted_territory }}</div>
                        </td>
                        <td class="small">
                            @if($auth->is_lifetime_valid)
                                <span class="badge bg-success-subtle text-success">Lifetime Valid</span>
                            @elseif($auth->valid_until)
                                <span>Till {{ $auth->valid_until->format('d M Y') }}</span>
                                @if($auth->isExpired())
                                    <span class="badge bg-danger d-block mt-1">Expired</span>
                                @endif
                            @else
                                <span class="text-muted">Standard</span>
                            @endif
                        </td>
                        <td>
                            @if($auth->garudo_approval_status === 'verified')
                                <span class="badge bg-success px-2 py-1"><i class="bi bi-check-circle-fill me-1"></i>Verified</span>
                            @elseif($auth->garudo_approval_status === 'pending')
                                <span class="badge bg-warning text-dark px-2 py-1"><i class="bi bi-hourglass-split me-1"></i>Pending</span>
                            @elseif($auth->garudo_approval_status === 'suspended')
                                <span class="badge bg-danger px-2 py-1"><i class="bi bi-slash-circle-fill me-1"></i>Suspended</span>
                            @else
                                <span class="badge bg-danger px-2 py-1"><i class="bi bi-x-circle-fill me-1"></i>Rejected</span>
                            @endif
                        </td>
                        <td>
                            @if($auth->certificate_number)
                                <code class="small text-primary fw-bold">{{ $auth->certificate_number }}</code>
                            @else
                                <span class="text-muted small">Generated on Approval</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('admin.dealer-authorizations.show', $auth->id) }}" class="btn btn-sm btn-primary rounded-pill px-3">
                                <i class="bi bi-search me-1"></i>Inspect
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-diagram-3 fs-1 d-block mb-2 text-secondary"></i>
                            No dealer authorization requests found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($authorizations->hasPages())
        <div class="p-3 border-top d-flex justify-content-end">
            {{ $authorizations->links() }}
        </div>
    @endif
</div>
@endsection
