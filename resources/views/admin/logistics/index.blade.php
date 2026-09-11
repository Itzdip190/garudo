@extends('layouts.admin')

@section('title', 'Logistics & Carriers')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="page-header-title">Logistics & Freight Carriers</h1>
        <p class="page-header-subtitle mb-0">Manage integrated multi-modal logistics partners, surface cargo rates & freight safety scores.</p>
    </div>
</div>

@if(isset($migrationNeeded) && $migrationNeeded)
    <div class="alert alert-warning mb-4 rounded-3 border-0 shadow-sm p-3">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h6 class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>Logistics Tables Pending Migration</h6>
                <div class="small">The <code>delivery_partners</code> and shipping tables need to be created in your database.</div>
            </div>
            <a href="{{ url('/run-migrate?secret=' . env('MIGRATION_SECRET_KEY', 'GarudoB2bDeploy2026SecretKeySecure!')) }}" class="btn btn-warning btn-sm text-dark fw-bold rounded-pill px-3" target="_blank">
                <i class="bi bi-play-fill"></i> Run Migration Now
            </a>
        </div>
    </div>
@endif

<!-- ---------------- STATS PILLS ---------------- -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="garudo-card p-3">
            <div class="text-muted small fw-bold text-uppercase">Integrated Carriers</div>
            <div class="fs-4 fw-bold text-dark mt-1">{{ $stats['total_carriers'] }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="garudo-card p-3">
            <div class="text-success small fw-bold text-uppercase">Active Freight Lines</div>
            <div class="fs-4 fw-bold text-success mt-1">{{ $stats['active_carriers'] }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="garudo-card p-3">
            <div class="text-warning small fw-bold text-uppercase">Average Safety Score</div>
            <div class="fs-4 fw-bold text-warning mt-1">{{ $stats['avg_safety_score'] }}%</div>
        </div>
    </div>
</div>

<!-- ---------------- CARRIERS GRID ---------------- -->
<div class="row g-4">
    @foreach($partners as $partner)
        <div class="col-md-6 col-lg-4">
            <div class="garudo-card p-4 h-100 d-flex flex-direction-column justify-content-between">
                <div>
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary text-white rounded-3 d-flex align-items-center justify-content-center fs-4" style="width: 48px; height: 48px;">
                                <i class="bi bi-truck"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-0">{{ $partner->name }}</h6>
                                <div class="text-muted small">Code: <code>{{ $partner->code }}</code></div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between text-muted small mb-1">
                            <span>Safety Score</span>
                            <span class="fw-bold text-success">{{ $partner->safety_score ?? 98 }}%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success" style="width: {{ $partner->safety_score ?? 98 }}%;"></div>
                        </div>
                    </div>

                    <div class="p-3 bg-light rounded-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small">Base Rate:</span>
                            <span class="fw-bold fs-6 text-dark">₹{{ number_format($partner->base_rate_per_kg, 2) }} / kg</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Total Deliveries:</span>
                            <span class="fw-semibold text-dark">{{ number_format($partner->total_deliveries ?? 0) }}</span>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-3">
                    <span class="pill-status pill-{{ $partner->is_active ? 'active' : 'suspended' }}">
                        {{ $partner->is_active ? 'Active' : 'Disabled' }}
                    </span>

                    <div class="d-flex gap-2">
                        <!-- Rate Edit Form Button -->
                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#editRateModal{{ $partner->id }}">
                            <i class="bi bi-pencil me-1"></i> Rate
                        </button>

                        <!-- Status Toggle Form -->
                        <form action="{{ route('admin.logistics.toggle_status', $partner->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm rounded-pill px-3 {{ $partner->is_active ? 'btn-outline-danger' : 'btn-success' }}">
                                {{ $partner->is_active ? 'Disable' : 'Enable' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Rate Modal -->
        <div class="modal fade" id="editRateModal{{ $partner->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content border-0 shadow">
                    <form action="{{ route('admin.logistics.update_rate', $partner->id) }}" method="POST">
                        @csrf
                        <div class="modal-header bg-dark text-white">
                            <h5 class="modal-title fw-bold">Update Rate for {{ $partner->name }}</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Base Rate per Kilogram (₹)</label>
                                <input type="number" step="0.01" name="base_rate_per_kg" class="form-control" value="{{ $partner->base_rate_per_kg }}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-garudo-primary fw-bold">Update Rate</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
