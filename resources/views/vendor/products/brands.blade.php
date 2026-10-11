@extends('layouts.vendor')

@section('title', 'Approved Brands & Brand Requests')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--garudo-text-main);">Building Material Brands</h4>
        <p class="text-muted mb-0 small">Select from verified national and regional manufacturers on Garudo or request new brand listings.</p>
    </div>
    <div>
        <button class="btn btn-garudo-primary btn-sm rounded-pill px-3 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#requestBrandModal" style="background-color: var(--garudo-primary); color: #fff;">
            <i class="bi bi-send-plus me-1"></i> Request New Brand
        </button>
    </div>
</div>

<!-- ---------------- APPROVED BRANDS CARDS ---------------- -->
<div class="row g-3 mb-4">
    @forelse($brands as $brand)
        <div class="col-md-4 col-lg-3">
            <div class="card-custom text-center p-3 h-100 shadow-sm">
                <div class="bg-light rounded p-3 mb-2 d-flex align-items-center justify-content-center" style="height: 70px;">
                    <i class="bi bi-patch-check text-success fs-2"></i>
                </div>
                <h6 class="fw-bold mb-1 text-dark">{{ $brand->name }}</h6>
                <div class="d-flex align-items-center justify-content-center gap-1">
                    <span class="badge bg-success bg-opacity-10 text-success small">Verified Manufacturer</span>
                </div>
                @if($brand->country_of_origin)
                    <div class="text-muted" style="font-size: 0.75rem;"><i class="bi bi-geo-alt me-1"></i>{{ $brand->country_of_origin }}</div>
                @endif
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card-custom text-center py-5">
                <i class="bi bi-tags text-muted fs-1 mb-2 d-block"></i>
                <h6>No Approved Brands Found</h6>
                <p class="text-muted small">Brands will appear here as they are verified by the Super Admin.</p>
            </div>
        </div>
    @endforelse
</div>

@if($brands instanceof \Illuminate\Pagination\LengthAwarePaginator && $brands->hasPages())
    <div class="mb-4">
        {{ $brands->links() }}
    </div>
@endif

<!-- ---------------- VENDOR'S SUBMITTED BRAND REQUESTS ---------------- -->
<div class="card-custom">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h6 class="fw-bold mb-0 text-dark">My Brand Requests</h6>
            <p class="text-muted small mb-0">Track manufacturer and brand additions requested from the Super Admin team.</p>
        </div>
        <span class="badge bg-light text-dark border small">{{ count($myRequests ?? []) }} Requests</span>
    </div>

    @if(isset($myRequests) && count($myRequests) > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0" style="font-size: 0.85rem;">
                <thead class="bg-light text-muted text-uppercase small">
                    <tr>
                        <th>Requested Brand Name</th>
                        <th>Origin & Web Details</th>
                        <th style="min-width: 250px;">Reason & Justification</th>
                        <th>Submitted On</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($myRequests as $req)
                        <tr>
                            <td class="fw-bold text-dark">
                                <i class="bi bi-patch-check text-primary me-1"></i> {{ $req->name }}
                            </td>
                            <td>
                                @if($req->values)
                                    <span class="badge bg-light text-dark border">{{ $req->values }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <div class="text-dark small" style="white-space: pre-wrap;">{{ $req->reason }}</div>
                                @if($req->admin_notes)
                                    <div class="mt-1 small {{ $req->status === 'approved' ? 'text-success' : 'text-danger' }}">
                                        <strong>Admin Note:</strong> {{ $req->admin_notes }}
                                    </div>
                                @endif
                            </td>
                            <td class="text-muted small">{{ $req->created_at->format('d M Y, h:i A') }}</td>
                            <td>
                                @if($req->status === 'pending')
                                    <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i>Pending Admin Verification</span>
                                @elseif($req->status === 'approved')
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Approved & Live</span>
                                @else
                                    <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Declined</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="p-4 bg-light rounded text-center text-muted small">
            <i class="bi bi-patch-question fs-4 d-block mb-1 text-secondary"></i>
            You haven't requested any custom brands yet. If you distribute or manufacture a brand not listed above, click <strong>"Request New Brand"</strong>.
        </div>
    @endif
</div>

<!-- ---------------- REQUEST BRAND MODAL ---------------- -->
<div class="modal fade" id="requestBrandModal" tabindex="-1" aria-labelledby="requestBrandModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 16px;">
            <form action="{{ route('vendor.catalog-requests.store') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="brand">

                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold text-dark" id="requestBrandModalLabel">
                        <i class="bi bi-patch-check text-success me-2"></i>Request New Brand Listing
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info py-2 px-3 small border-0 mb-3" style="background-color: #EBF5FF; color: #1E429F; border-radius: 8px;">
                        <i class="bi bi-shield-check me-1"></i> <strong>Admin Verification:</strong> All brand additions are verified by the Super Admin to maintain catalog integrity and prevent duplicates.
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Brand / Manufacturer Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Tata Tiscon, Ambuja Cement, JSW Neo Steel" required value="{{ old('name') }}">
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold small text-dark">Country of Origin</label>
                            <input type="text" name="values" class="form-control" placeholder="e.g. India / Germany" value="{{ old('values', 'India') }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small text-dark">Official Website (Optional)</label>
                            <input type="text" name="website" class="form-control" placeholder="https://brand.com">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Reason / Authorization Note <span class="text-danger">*</span></label>
                        <textarea name="reason" rows="3" class="form-control" placeholder="Explain your products under this brand, whether you are an authorized dealer/manufacturer, etc." required>{{ old('reason') }}</textarea>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light">
                    <button type="button" class="btn btn-light border btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm px-4 fw-bold" style="background-color: var(--garudo-primary); border: none;">
                        <i class="bi bi-send-check me-1"></i> Submit Request to Admin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
