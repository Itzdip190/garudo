@extends('layouts.vendor')

@section('title', 'Marketplace Categories')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--garudo-text-main);">Material Categories</h4>
        <p class="text-muted mb-0 small">Explore product hierarchy and category commission rates.</p>
    </div>
    <div>
        <button class="btn btn-garudo-primary btn-sm rounded-pill px-3 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#requestCategoryModal" style="background-color: var(--garudo-primary); color: #fff;">
            <i class="bi bi-plus-circle me-1"></i> Request New Category
        </button>
    </div>
</div>

<!-- ---------------- CATEGORIES CARDS ---------------- -->
<div class="row g-3 mb-4">
    @forelse($categories as $category)
        <div class="col-md-6 col-lg-4">
            <div class="card-custom h-100">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="p-2 rounded bg-success bg-opacity-10 text-success">
                        <i class="bi {{ $category->icon ?: 'bi-diagram-3' }} fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">{{ $category->name }}</h6>
                        <span class="text-muted small">{{ $category->children->count() }} Sub-categories</span>
                    </div>
                </div>
                @if($category->children->count() > 0)
                    <div class="pt-2 border-top mt-2">
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($category->children as $child)
                                <span class="badge bg-light text-dark border small">{{ $child->name }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card-custom text-center py-5">
                <i class="bi bi-grid text-muted fs-1 mb-2 d-block"></i>
                <h6>No Categories Found</h6>
            </div>
        </div>
    @endforelse
</div>

<!-- ---------------- VENDOR'S SUBMITTED REQUESTS ---------------- -->
<div class="card-custom">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h6 class="fw-bold mb-0 text-dark">My Category Requests</h6>
            <p class="text-muted small mb-0">Track category and subcategory additions you requested from the Admin team.</p>
        </div>
        <span class="badge bg-light text-dark border small">{{ count($myRequests) }} Requests</span>
    </div>

    @if(count($myRequests) > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0" style="font-size: 0.85rem;">
                <thead class="bg-light text-muted text-uppercase small">
                    <tr>
                        <th>Requested Category Name</th>
                        <th>Hierarchy / Parent</th>
                        <th>Proposed Subcategories</th>
                        <th style="min-width: 250px;">Reason / Justification</th>
                        <th>Submitted On</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($myRequests as $req)
                        <tr>
                            <td class="fw-bold text-dark">{{ $req->name }}</td>
                            <td>
                                @if($req->parentCategory)
                                    <span class="badge bg-light text-primary border"><i class="bi bi-diagram-2 me-1"></i>Under: {{ $req->parentCategory->name }}</span>
                                @else
                                    <span class="badge bg-light text-dark border">Root / Main Category</span>
                                @endif
                            </td>
                            <td>
                                @if($req->values)
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach(preg_split('/[,\r\n]+/', $req->values) as $val)
                                            @if(trim($val) !== '')
                                                <span class="badge bg-light text-dark border small">{{ trim($val) }}</span>
                                            @endif
                                        @endforeach
                                    </div>
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
                                    <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i>Pending Review</span>
                                @elseif($req->status === 'approved')
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Approved & Added</span>
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
            <i class="bi bi-diagram-3 fs-4 d-block mb-1 text-secondary"></i>
            You haven't requested any custom categories yet. If your products belong to a new industrial category or subcategory not shown above, click <strong>"Request New Category"</strong>.
        </div>
    @endif
</div>

<!-- ---------------- REQUEST CATEGORY MODAL ---------------- -->
<div class="modal fade" id="requestCategoryModal" tabindex="-1" aria-labelledby="requestCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('vendor.catalog-requests.store') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="category">

                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold text-dark" id="requestCategoryModalLabel">
                        <i class="bi bi-diagram-3 text-success me-2"></i>Request New Material Category
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info py-2 px-3 small border-0 mb-3" style="background-color: #EBF5FF; color: #1E429F; border-radius: 8px;">
                        <i class="bi bi-info-circle me-1"></i> New categories and subcategories are curated and verified by the Super Admin to maintain marketplace standards.
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Category / Subcategory Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Waterproofing & Chemicals, Solar Panels, False Ceiling" required value="{{ old('name') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Placement in Catalog Hierarchy</label>
                        <select name="parent_id" class="form-select">
                            <option value="">Create as Root Category (New Main Industry Sector)</option>
                            <optgroup label="Or add as Subcategory under:">
                                @foreach($categories as $parent)
                                    <option value="{{ $parent->id }}">Under: {{ $parent->name }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                        <small class="text-muted">Select an existing category if this is a subcategory, or leave as root.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Suggested Subcategories or Product Types <span class="text-muted">(Optional)</span></label>
                        <textarea name="values" class="form-control" rows="2" placeholder="e.g. Bituminous Membranes, Liquid Sealants, Crystalline Waterproofing">{{ old('values') }}</textarea>
                        <small class="text-muted">Separate multiple subcategories with commas.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Reason for Request <span class="text-danger">*</span></label>
                        <textarea name="reason" class="form-control" rows="3" placeholder="Please describe why this category is necessary for your product line (e.g. We supply industrial polyurethane sealants which do not fit into general paints or cement categories)..." required minlength="10">{{ old('reason') }}</textarea>
                        <small class="text-muted">A clear reason helps the admin team approve your request quickly.</small>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-garudo-primary fw-bold px-4" style="background-color: var(--garudo-primary); color: #fff;">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
