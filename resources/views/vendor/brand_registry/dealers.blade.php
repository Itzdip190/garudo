@extends('layouts.vendor')

@section('title', 'Manage Authorized Dealers - ' . $brand->name)

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('vendor.brand-registry.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle p-2" style="width: 36px; height: 36px;">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="fw-bold mb-0 text-dark">Authorized Dealer Network: {{ $brand->name }}</h4>
            <p class="text-muted mb-0 small">Review and approve distributors/dealers requesting authorization to sell {{ $brand->name }} products.</p>
        </div>
    </div>
    <div>
        <a href="{{ route('brands.show', $brand->slug) }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill px-3">
            <i class="bi bi-eye me-1"></i> Public Brand Showcase
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4 rounded-3 border-0 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- DEALER TABLE -->
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0 text-dark">
            <i class="bi bi-people-fill text-primary me-2"></i>Dealer Authorization Applications
        </h6>
        <span class="badge bg-light text-muted border">{{ $dealers->total() }} Total</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Dealer Business Entity</th>
                        <th>Dealership Tier</th>
                        <th>Assigned Territory</th>
                        <th>Validity Period</th>
                        <th>OEM Status</th>
                        <th>Garudo Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dealers as $dealerAuth)
                        <tr>
                            <td>
                                <div class="fw-bold text-dark">{{ $dealerAuth->vendor->company_name ?? 'N/A' }}</div>
                                <small class="text-muted"><i class="bi bi-file-text me-1"></i>GSTIN: {{ $dealerAuth->vendor->gstin ?? 'Unregistered' }}</small>
                                @if($dealerAuth->authorization_doc_path)
                                    <div>
                                        <a href="{{ asset('storage/' . $dealerAuth->authorization_doc_path) }}" target="_blank" class="small text-primary text-decoration-none">
                                            <i class="bi bi-file-earmark-pdf me-1 text-danger"></i>View Agreement
                                        </a>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                    {{ $dealerAuth->formatted_type }}
                                </span>
                            </td>
                            <td>
                                <div class="small fw-semibold text-dark">{{ $dealerAuth->formatted_territory }}</div>
                            </td>
                            <td class="small">
                                @if($dealerAuth->is_lifetime_valid)
                                    <span class="badge bg-success-subtle text-success">Lifetime</span>
                                @elseif($dealerAuth->valid_until)
                                    <span>Till {{ $dealerAuth->valid_until->format('d M Y') }}</span>
                                @else
                                    <span class="text-muted">Standard</span>
                                @endif
                            </td>
                            <td>
                                @if($dealerAuth->brand_owner_approval === 'approved')
                                    <span class="badge bg-success"><i class="bi bi-check-lg me-1"></i>Approved by You</span>
                                @elseif($dealerAuth->brand_owner_approval === 'rejected')
                                    <span class="badge bg-danger"><i class="bi bi-x-lg me-1"></i>Rejected</span>
                                @else
                                    <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i>Pending Your Review</span>
                                @endif
                            </td>
                            <td>
                                @if($dealerAuth->garudo_approval_status === 'verified')
                                    <span class="badge bg-success"><i class="bi bi-patch-check-fill me-1"></i>Garudo Verified</span>
                                    @if($dealerAuth->certificate_number)
                                        <code class="d-block small mt-1 text-primary fw-bold">{{ $dealerAuth->certificate_number }}</code>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($dealerAuth->garudo_approval_status) }}</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                @if($dealerAuth->brand_owner_approval !== 'approved')
                                    <form action="{{ route('vendor.brand-registry.dealers.approve', [$brand->id, $dealerAuth->id]) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success rounded-pill px-3">
                                            <i class="bi bi-check-lg me-1"></i>Approve
                                        </button>
                                    </form>
                                @endif
                                @if($dealerAuth->brand_owner_approval !== 'rejected')
                                    <form action="{{ route('vendor.brand-registry.dealers.reject', [$brand->id, $dealerAuth->id]) }}" method="POST" class="d-inline ms-1">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2" onclick="return confirm('Reject this dealership application?');">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-diagram-3 fs-1 d-block mb-2 text-secondary"></i>
                                No distributors or dealers have applied for this brand yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($dealers->hasPages())
            <div class="p-3 border-top d-flex justify-content-end">
                {{ $dealers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
