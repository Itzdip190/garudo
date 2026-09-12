@extends('layouts.vendor')

@section('title', 'Application Status - ' . $application->brand_name)

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('vendor.brand-registry.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle p-2" style="width: 36px; height: 36px;">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="fw-bold mb-0 text-dark">Brand Application: {{ $application->brand_name }}</h4>
            <p class="text-muted mb-0 small">Tracking ID: <strong>#APP-BRD-{{ $application->id }}</strong> • Submitted {{ $application->created_at->format('d M Y, h:i A') }}</p>
        </div>
    </div>
    <div>
        @if($application->status === 'approved')
            <span class="badge bg-success fs-6 px-3 py-2"><i class="bi bi-patch-check-fill me-1"></i> Authorized Brand OEM</span>
        @elseif($application->status === 'pending')
            <span class="badge bg-warning text-dark fs-6 px-3 py-2"><i class="bi bi-hourglass-split me-1"></i> Under Legal Verification</span>
        @elseif($application->status === 'rejected')
            <span class="badge bg-danger fs-6 px-3 py-2"><i class="bi bi-x-circle-fill me-1"></i> Application Rejected</span>
        @endif
    </div>
</div>

<div class="row g-4">
    <!-- LEFT: Application Summary -->
    <div class="col-lg-8">
        <!-- Status Tracker Banner -->
        @if($application->status === 'approved')
            <div class="card border-0 shadow-sm rounded-3 p-4 mb-4 border-start border-4 border-success" style="background: #F0FDF4;">
                <div class="d-flex align-items-start gap-3">
                    <div class="display-6 text-success"><i class="bi bi-patch-check-fill"></i></div>
                    <div>
                        <h5 class="fw-bold text-success mb-1">Congratulations! Your Brand is Officially Authorized</h5>
                        <p class="text-dark small mb-2">Garudo Compliance Team has verified your Trademark documentation and granted official Verified OEM accreditation.</p>
                        @if($application->createdBrand)
                            <div class="d-flex gap-2">
                                <a href="{{ route('brands.show', $application->createdBrand->slug) }}" target="_blank" class="btn btn-sm btn-success rounded-pill px-3">
                                    <i class="bi bi-box-arrow-up-right me-1"></i> View Live Brand Showcase
                                </a>
                                <a href="{{ route('vendor.brand-registry.dealers', $application->createdBrand->id) }}" class="btn btn-sm btn-outline-dark rounded-pill px-3">
                                    <i class="bi bi-people-fill me-1"></i> Manage Authorized Dealers
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @elseif($application->status === 'rejected')
            <div class="card border-0 shadow-sm rounded-3 p-4 mb-4 border-start border-4 border-danger" style="background: #FEF2F2;">
                <h6 class="fw-bold text-danger mb-1"><i class="bi bi-x-circle-fill me-2"></i>Application Rejection Details</h6>
                <p class="text-dark small mb-0">{{ $application->rejection_reason ?? 'Please resubmit with clear, unexpired trademark certificates matching your business legal entity.' }}</p>
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
            <h5 class="fw-bold mb-3 text-dark">Brand Profile Overview</h5>
            <div class="d-flex gap-3 align-items-start mb-4">
                @if($application->logo_path)
                    <img src="{{ asset('storage/' . $application->logo_path) }}" alt="{{ $application->brand_name }}" class="rounded border p-2 bg-white" style="width: 80px; height: 80px; object-fit: contain;">
                @else
                    <div class="rounded border bg-light d-flex align-items-center justify-content-center text-muted fw-bold" style="width: 80px; height: 80px; font-size: 1.8rem;">
                        {{ substr($application->brand_name, 0, 2) }}
                    </div>
                @endif
                <div>
                    <h4 class="fw-bold text-dark mb-1">{{ $application->brand_name }}</h4>
                    <span class="badge bg-light text-dark border small">{{ ucwords(str_replace('_', ' ', $application->brand_type)) }}</span>
                    <span class="badge bg-light text-dark border small">{{ $application->country_of_origin ?? 'India' }}</span>
                </div>
            </div>

            @if($application->brand_story)
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold text-uppercase">Brand Story</label>
                    <p class="text-secondary small">{{ $application->brand_story }}</p>
                </div>
            @endif

            @if($application->warranty_policy)
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold text-uppercase">Warranty & Guarantee Policy</label>
                    <p class="text-secondary small">{{ $application->warranty_policy }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- RIGHT: Compliance & Attached Documents -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
            <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-file-earmark-check text-primary me-2"></i>Attached Documents</h5>
            
            <div class="p-3 bg-light rounded border mb-3">
                <div class="small text-muted">Trademark Number</div>
                <div class="fw-bold text-dark">{{ $application->trademark_number ?? 'N/A' }}</div>
                <div class="small text-muted mt-2">Class & Status</div>
                <div class="fw-bold text-dark">Class {{ $application->trademark_class ?? '-' }} • {{ ucfirst($application->trademark_status) }}</div>
            </div>

            <div class="list-group list-group-flush border rounded">
                @if($application->trademark_doc_path)
                    <a href="{{ asset('storage/' . $application->trademark_doc_path) }}" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2">
                        <span class="small fw-semibold"><i class="bi bi-file-pdf text-danger me-2"></i>Trademark Proof</span>
                        <i class="bi bi-box-arrow-up-right text-muted"></i>
                    </a>
                @endif
                @if($application->company_incorporation_doc_path)
                    <a href="{{ asset('storage/' . $application->company_incorporation_doc_path) }}" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2">
                        <span class="small fw-semibold"><i class="bi bi-file-earmark-check text-primary me-2"></i>Incorporation Doc</span>
                        <i class="bi bi-box-arrow-up-right text-muted"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
