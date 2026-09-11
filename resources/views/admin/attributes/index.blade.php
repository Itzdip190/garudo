@extends('layouts.admin')

@section('title', 'Standard Specifications & Attributes')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="page-header-title">Standard Specifications & Attributes</h1>
        <p class="page-header-subtitle mb-0">Configure standard material grades, dimensions, technical specs, and filterable properties across building materials.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('admin.catalog-requests.index', ['type' => 'attribute_spec']) }}" class="btn btn-outline-warning btn-sm rounded-pill px-3 fw-bold position-relative">
            <i class="bi bi-clock-history me-1"></i> Vendor Requests
            @if($stats['pending_requests'] > 0)
                <span class="badge bg-danger rounded-pill ms-1">{{ $stats['pending_requests'] }}</span>
            @endif
        </a>
        <button class="btn btn-garudo-primary btn-sm rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#addAttributeModal">
            <i class="bi bi-plus-lg me-1"></i> Add New Specification
        </button>
    </div>
</div>

<!-- ---------------- STATS CARDS ---------------- -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="garudo-card p-3">
            <div class="text-muted small fw-bold text-uppercase">Standard Specs</div>
            <div class="fs-4 fw-bold text-dark mt-1">{{ $stats['total_attributes'] }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="garudo-card p-3">
            <div class="text-success small fw-bold text-uppercase">Total Values / Grades</div>
            <div class="fs-4 fw-bold text-success mt-1">{{ $stats['total_values'] }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="garudo-card p-3">
            <div class="text-primary small fw-bold text-uppercase">Active Specs</div>
            <div class="fs-4 fw-bold text-primary mt-1">{{ $stats['active_attributes'] }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="garudo-card p-3">
            <div class="text-warning small fw-bold text-uppercase">Pending Vendor Requests</div>
            <div class="fs-4 fw-bold text-warning mt-1">{{ $stats['pending_requests'] }}</div>
        </div>
    </div>
</div>

<!-- ---------------- SEARCH & FILTER ---------------- -->
<div class="garudo-card p-3 mb-4">
    <form method="GET" action="{{ route('admin.attributes.index') }}" class="row g-2 align-items-center">
        <div class="col-md-9">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Search specifications (e.g. Steel Grades, Cement, 500D, etc.)..." value="{{ $search }}">
            </div>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-sm btn-garudo-primary flex-grow-1">Filter</button>
            @if($search)
                <a href="{{ route('admin.attributes.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            @endif
        </div>
    </form>
</div>

<!-- ---------------- SPECIFICATIONS CARDS GRID ---------------- -->
<div class="row g-3">
    @forelse($attributes as $attr)
        <div class="col-md-6 col-lg-4">
            <div class="garudo-card p-3 h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-start justify-content-between mb-2">
                        <div>
                            <h6 class="fw-bold text-dark mb-0">{{ $attr->display_name }}</h6>
                            <span class="text-muted small">Key: <code>{{ $attr->name }}</code></span>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            @if($attr->status === 'active')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 small">Active</span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 small">Inactive</span>
                            @endif

                            <button type="button" class="btn btn-link text-secondary p-1 border-0" onclick="openEditAttrModal({{ json_encode($attr) }})" title="Edit Specification">
                                <i class="bi bi-pencil-square fs-6"></i>
                            </button>

                            <form action="{{ route('admin.attributes.destroy', $attr->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete \'{{ $attr->display_name }}\' and all its {{ $attr->values_count }} values?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link text-danger p-1 border-0" title="Delete">
                                    <i class="bi bi-trash fs-6"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-light text-dark border small">{{ ucfirst($attr->type) }}</span>
                        <span class="text-muted small">{{ $attr->values_count }} Options Available</span>
                    </div>

                    <div class="pt-2 border-top">
                        <div class="d-flex flex-wrap gap-1">
                            @forelse($attr->values as $val)
                                <span class="badge bg-white text-dark border shadow-xs" style="font-size: 0.75rem;">{{ $val->display_value }}</span>
                            @empty
                                <span class="text-muted small fst-italic">No values defined yet</span>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="mt-3 pt-2 border-top text-muted small d-flex justify-content-between">
                    <span>Filterable: {{ $attr->is_filterable ? 'Yes' : 'No' }}</span>
                    <span>Order: #{{ $attr->sort_order }}</span>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="garudo-card text-center py-5">
                <i class="bi bi-sliders text-muted fs-1 mb-2 d-block"></i>
                <h6 class="text-dark">No Standard Specifications Found</h6>
                <p class="text-muted small">Click "Add New Specification" above to define material grades, sizes, and technical attributes.</p>
            </div>
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $attributes->links() }}
</div>

<!-- ---------------- ADD SPECIFICATION MODAL ---------------- -->
<div class="modal fade" id="addAttributeModal" tabindex="-1" aria-labelledby="addAttributeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.attributes.store') }}" method="POST">
                @csrf
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold text-dark" id="addAttributeModalLabel">
                        <i class="bi bi-plus-circle text-success me-2"></i>Add Standard Specification
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Specification Name / Title <span class="text-danger">*</span></label>
                        <input type="text" name="display_name" class="form-control" placeholder="e.g. Steel Grades, Cement Types, Brick Sizes" required>
                        <small class="text-muted">Display title that appears in vendor forms and filter tabs.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Input Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-select" required>
                            <option value="select">Dropdown Select (Single Choice)</option>
                            <option value="checkbox">Multi-Select Checkboxes</option>
                            <option value="radio">Radio Buttons</option>
                            <option value="text">Text Input</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Specification Values / Grades <span class="text-danger">*</span></label>
                        <textarea name="values" class="form-control" rows="4" placeholder="Enter comma or new line separated values, e.g.&#10;Fe 500D, Fe 550D, Fe 600, CRS Grade" required></textarea>
                        <small class="text-muted">Separate multiple grades or options by commas or new lines.</small>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold small text-dark">Status</label>
                            <select name="status" class="form-select">
                                <option value="active">Active (Visible)</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small text-dark">Display Order</label>
                            <input type="number" name="sort_order" class="form-control" value="0">
                        </div>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_filterable" value="1" id="isFilterableSwitch" checked>
                        <label class="form-check-label small text-dark" for="isFilterableSwitch">Show in Search Filters (Buyers can filter products by this spec)</label>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-garudo-primary fw-bold px-4">Create Specification</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ---------------- EDIT SPECIFICATION MODAL ---------------- -->
<div class="modal fade" id="editAttributeModal" tabindex="-1" aria-labelledby="editAttributeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="editAttributeForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold text-dark" id="editAttributeModalLabel">
                        <i class="bi bi-pencil-square text-primary me-2"></i>Edit Standard Specification
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Specification Name / Title <span class="text-danger">*</span></label>
                        <input type="text" name="display_name" id="edit_display_name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Input Type <span class="text-danger">*</span></label>
                        <select name="type" id="edit_type" class="form-select" required>
                            <option value="select">Dropdown Select (Single Choice)</option>
                            <option value="checkbox">Multi-Select Checkboxes</option>
                            <option value="radio">Radio Buttons</option>
                            <option value="text">Text Input</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Specification Values / Grades <span class="text-danger">*</span></label>
                        <textarea name="values" id="edit_values" class="form-control" rows="4" required></textarea>
                        <small class="text-muted">Separate multiple grades or options by commas.</small>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold small text-dark">Status</label>
                            <select name="status" id="edit_status" class="form-select">
                                <option value="active">Active (Visible)</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small text-dark">Display Order</label>
                            <input type="number" name="sort_order" id="edit_sort_order" class="form-control">
                        </div>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_filterable" value="1" id="edit_is_filterable">
                        <label class="form-check-label small text-dark" for="edit_is_filterable">Show in Search Filters</label>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-garudo-primary fw-bold px-4">Update Specification</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openEditAttrModal(attr) {
    const form = document.getElementById('editAttributeForm');
    form.action = `/admin/attributes/${attr.id}`;

    document.getElementById('edit_display_name').value = attr.display_name;
    document.getElementById('edit_type').value = attr.type;
    document.getElementById('edit_status').value = attr.status;
    document.getElementById('edit_sort_order').value = attr.sort_order;
    document.getElementById('edit_is_filterable').checked = !!attr.is_filterable;

    if (attr.values && attr.values.length > 0) {
        document.getElementById('edit_values').value = attr.values.map(v => v.display_value).join(', ');
    } else {
        document.getElementById('edit_values').value = '';
    }

    new bootstrap.Modal(document.getElementById('editAttributeModal')).show();
}
</script>
@endpush
