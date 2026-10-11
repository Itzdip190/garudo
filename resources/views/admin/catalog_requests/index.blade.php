@extends('layouts.admin')

@section('title', 'Vendor Catalog & Brand Requests')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="page-header-title">Vendor Catalog, Category & Brand Requests</h1>
        <p class="page-header-subtitle mb-0">Review, verify and approve vendor requests for new brands, product categories, and specifications before they go live.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('admin.brands.registry.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-patch-check me-1"></i> Brand Registry
        </a>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-diagram-3 me-1"></i> Category Tree
        </a>
        <a href="{{ route('admin.attributes.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-sliders me-1"></i> Standard Specs
        </a>
    </div>
</div>

<!-- ---------------- STATS PILLS ---------------- -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="garudo-card p-3">
            <div class="text-warning small fw-bold text-uppercase">Pending Review</div>
            <div class="fs-4 fw-bold text-warning mt-1">{{ $stats['pending'] }}</div>
            <div class="text-muted small mt-1">
                <span>{{ $stats['brands_pending'] ?? 0 }} Brands</span> &bull; 
                <span>{{ $stats['cats_pending'] ?? 0 }} Categories</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="garudo-card p-3">
            <div class="text-success small fw-bold text-uppercase">Approved & Added</div>
            <div class="fs-4 fw-bold text-success mt-1">{{ $stats['approved'] }}</div>
            <div class="text-muted small mt-1">Active in marketplace</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="garudo-card p-3">
            <div class="text-danger small fw-bold text-uppercase">Rejected Requests</div>
            <div class="fs-4 fw-bold text-danger mt-1">{{ $stats['rejected'] }}</div>
            <div class="text-muted small mt-1">Declined with reasons</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="garudo-card p-3">
            <div class="text-muted small fw-bold text-uppercase">Total Requests</div>
            <div class="fs-4 fw-bold text-dark mt-1">{{ $stats['total'] }}</div>
            <div class="text-muted small mt-1">All-time submissions</div>
        </div>
    </div>
</div>

<!-- ---------------- FILTER TABS & SEARCH ---------------- -->
<div class="garudo-card p-3 mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <!-- Status Tabs -->
        <ul class="nav nav-pills gap-1">
            <li class="nav-item">
                <a class="nav-link {{ $statusFilter === 'all' ? 'active bg-primary text-white' : 'text-dark' }} py-1 px-3 small" href="{{ route('admin.catalog-requests.index', ['status' => 'all', 'type' => $typeFilter]) }}">
                    All ({{ $stats['total'] }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $statusFilter === 'pending' ? 'active bg-warning text-dark fw-bold' : 'text-dark' }} py-1 px-3 small" href="{{ route('admin.catalog-requests.index', ['status' => 'pending', 'type' => $typeFilter]) }}">
                    Pending ({{ $stats['pending'] }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $statusFilter === 'approved' ? 'active bg-success text-white' : 'text-dark' }} py-1 px-3 small" href="{{ route('admin.catalog-requests.index', ['status' => 'approved', 'type' => $typeFilter]) }}">
                    Approved ({{ $stats['approved'] }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $statusFilter === 'rejected' ? 'active bg-danger text-white' : 'text-dark' }} py-1 px-3 small" href="{{ route('admin.catalog-requests.index', ['status' => 'rejected', 'type' => $typeFilter]) }}">
                    Rejected ({{ $stats['rejected'] }})
                </a>
            </li>
        </ul>

        <!-- Type Filter and Search -->
        <form method="GET" action="{{ route('admin.catalog-requests.index') }}" class="d-flex align-items-center gap-2">
            <input type="hidden" name="status" value="{{ $statusFilter }}">
            <select name="type" class="form-select form-select-sm" onchange="this.form.submit()" style="width: 180px;">
                <option value="all" {{ $typeFilter === 'all' ? 'selected' : '' }}>All Request Types</option>
                <option value="brand" {{ $typeFilter === 'brand' ? 'selected' : '' }}>Brands & Manufacturers</option>
                <option value="category" {{ $typeFilter === 'category' ? 'selected' : '' }}>Categories & Subcategories</option>
                <option value="attribute_spec" {{ $typeFilter === 'attribute_spec' ? 'selected' : '' }}>Specifications & Specs</option>
            </select>
            <div class="input-group input-group-sm" style="width: 220px;">
                <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ $search }}">
                <button class="btn btn-garudo-primary" type="submit"><i class="bi bi-search"></i></button>
            </div>
            @if($search)
                <a href="{{ route('admin.catalog-requests.index', ['status' => $statusFilter, 'type' => $typeFilter]) }}" class="btn btn-sm btn-outline-secondary">Clear</a>
            @endif
        </form>
    </div>
</div>

<!-- ---------------- REQUESTS CARDS / LIST ---------------- -->
<div class="row g-3">
    @forelse($requests as $req)
        <div class="col-12">
            <div class="garudo-card p-4 border {{ $req->status === 'pending' ? 'border-warning border-opacity-50' : '' }}">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-3">
                    <div class="d-flex align-items-start gap-3 flex-grow-1">
                        <!-- Icon Circle -->
                        @php
                            $iconBg = match($req->type) {
                                'brand' => '#2563EB',
                                'attribute_spec' => '#0B4D3C',
                                default => '#059669',
                            };
                            $iconClass = match($req->type) {
                                'brand' => 'bi-patch-check',
                                'attribute_spec' => 'bi-sliders',
                                'subcategory' => 'bi-diagram-2',
                                default => 'bi-diagram-3',
                            };
                        @endphp
                        <div class="rounded-3 p-3 text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px; background: {{ $iconBg }};">
                            <i class="bi {{ $iconClass }} fs-4"></i>
                        </div>

                        <!-- Details -->
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                {!! $req->getTypeBadge() !!}
                                {!! $req->getStatusBadge() !!}

                                <span class="text-muted small ms-auto">Submitted: {{ $req->created_at->format('d M Y, h:i A') }} ({{ $req->created_at->diffForHumans() }})</span>
                            </div>

                            <h5 class="fw-bold text-dark mb-1">{{ $req->name }}</h5>

                            @if($req->type === 'brand')
                                <div class="text-muted small mb-2">
                                    <span class="badge bg-light text-dark border me-1"><i class="bi bi-globe me-1"></i>{{ $req->values ?: 'India' }}</span>
                                </div>
                            @elseif($req->parentCategory)
                                <div class="text-muted small mb-2">
                                    <i class="bi bi-diagram-2 me-1"></i>Parent Category: <strong class="text-dark">{{ $req->parentCategory->name }}</strong>
                                </div>
                            @endif

                            @if($req->values && $req->type !== 'brand')
                                <div class="mb-2">
                                    <span class="text-muted small fw-semibold">Proposed Values / Subcategories:</span>
                                    <div class="d-flex flex-wrap gap-1 mt-1">
                                        @foreach(preg_split('/[,\r\n]+/', $req->values) as $v)
                                            @if(trim($v) !== '')
                                                <span class="badge bg-light text-dark border small">{{ trim($v) }}</span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Vendor's Mandatory Reason -->
                            <div class="p-3 bg-light rounded-3 border mt-2">
                                <div class="d-flex align-items-center gap-2 text-primary fw-bold small mb-1">
                                    <i class="bi bi-chat-quote-fill fs-6"></i> Vendor's Reason & Specification Details:
                                </div>
                                <div class="text-dark small" style="white-space: pre-wrap;">{{ $req->reason }}</div>
                            </div>

                            @if($req->admin_notes)
                                <div class="p-2 rounded mt-2 small {{ $req->status === 'approved' ? 'bg-success bg-opacity-10 text-success' : 'bg-danger bg-opacity-10 text-danger' }}">
                                    <strong>Admin Note:</strong> {{ $req->admin_notes }}
                                    @if($req->handler)
                                        <span class="text-muted small ms-1">(by {{ $req->handler->name }})</span>
                                    @endif
                                </div>
                            @endif

                            <!-- Vendor Metadata -->
                            <div class="d-flex align-items-center gap-3 text-muted small mt-3 pt-2 border-top">
                                <span><i class="bi bi-shop me-1"></i><strong>{{ $req->vendor->company_name ?? ($req->vendor->business_name ?? 'Unknown Vendor') }}</strong></span>
                                <span><i class="bi bi-person me-1"></i>{{ $req->vendor->name ?? ($req->vendor->contact_person ?? 'N/A') }}</span>
                                <span><i class="bi bi-telephone me-1"></i>{{ $req->vendor->phone ?? 'N/A' }}</span>
                                <span><i class="bi bi-envelope me-1"></i>{{ $req->vendor->email ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    @if($req->status === 'pending')
                        <div class="d-flex flex-column gap-2 flex-shrink-0 align-self-stretch align-self-md-start">
                            <form action="{{ route('admin.catalog-requests.approve', $req->id) }}" method="POST" onsubmit="return confirm('Approve this request and immediately add \'{{ $req->name }}\' to the live marketplace catalog?');">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm w-100 fw-bold px-3 shadow-xs">
                                    <i class="bi bi-check-lg me-1"></i> Approve & Add
                                </button>
                            </form>

                            <button type="button" class="btn btn-outline-danger btn-sm w-100 fw-bold px-3" onclick="openRejectModal({{ $req->id }}, '{{ addslashes($req->name) }}')">
                                <i class="bi bi-x-lg me-1"></i> Reject
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="garudo-card text-center py-5">
                <i class="bi bi-inbox text-muted fs-1 mb-2 d-block"></i>
                <h6 class="text-dark">No Vendor Requests Found</h6>
                <p class="text-muted small">Vendor requests for new brands, categories or specifications will appear here for review.</p>
            </div>
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $requests->links() }}
</div>

<!-- ---------------- REJECT MODAL ---------------- -->
<div class="modal fade" id="rejectRequestModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="rejectRequestForm" method="POST">
                @csrf
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold text-dark">
                        <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>Reject Catalog Request
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted small">You are declining the request for: <strong class="text-dark" id="rejectItemName"></strong></p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Rejection Reason / Note for Vendor</label>
                        <textarea name="admin_notes" class="form-control" rows="3" placeholder="Provide reason why this request cannot be approved (e.g. Already covered under existing specifications)..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-danger fw-bold px-4">Confirm Rejection</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openRejectModal(id, name) {
    const form = document.getElementById('rejectRequestForm');
    form.action = `/admin/catalog-requests/${id}/reject`;
    document.getElementById('rejectItemName').innerText = name;
    new bootstrap.Modal(document.getElementById('rejectRequestModal')).show();
}
</script>
@endpush
