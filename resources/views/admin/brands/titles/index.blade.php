@extends('layouts.admin')

@section('title', 'Manage Brand Titles & Tiers')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle p-2" style="width: 36px; height: 36px;">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="page-header-title mb-1">
                <i class="bi bi-tags-fill text-primary me-2"></i>Brand Titles & Custom Tiers
            </h1>
            <p class="page-header-subtitle mb-0">Define custom classification titles like <strong>Super Brand</strong>, <strong>National Brand</strong>, <strong>Premium Brand</strong>, or <strong>Sub Brand</strong>.</p>
        </div>
    </div>
    <div>
        <a href="{{ route('admin.brands.create') }}" class="btn btn-garudo-primary btn-sm rounded-pill px-3 fw-bold">
            <i class="bi bi-plus-circle me-1"></i> Add Brand With Title
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-4">
    <!-- LEFT: List of Existing Brand Titles -->
    <div class="col-lg-8">
        <div class="garudo-card overflow-hidden">
            <div class="p-3 bg-light border-bottom d-flex align-items-center justify-content-between">
                <span class="fw-bold text-dark small text-uppercase">Configured Brand Titles ({{ $titles->count() }})</span>
                <span class="text-muted small">Titles appear as badges and filter categories</span>
            </div>

            <div class="table-responsive">
                <table class="table admin-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Title / Label</th>
                            <th>Slug / Key</th>
                            <th class="text-center">Classification Role</th>
                            <th class="text-center">Assigned Brands</th>
                            <th class="text-center">Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($titles as $t)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge rounded-pill {{ $t->badge_class }} px-3 py-1 fw-bold fs-6">
                                            {{ $t->name }}
                                        </span>
                                    </div>
                                    @if($t->description)
                                        <div class="text-muted small mt-1">{{ $t->description }}</div>
                                    @endif
                                </td>
                                <td>
                                    <code>{{ $t->slug }}</code>
                                </td>
                                <td class="text-center">
                                    @if($t->is_parent_tier)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle small" title="Can have Sub Brands under it">
                                            <i class="bi bi-diagram-2-fill me-1"></i> Parent Umbrella
                                        </span>
                                    @else
                                        <span class="badge bg-light text-muted border small">
                                            Product Line / Standard
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.brands.index', ['title' => $t->id]) }}" class="badge rounded-pill bg-light text-dark border px-2 py-1 text-decoration-none fw-bold">
                                        {{ $t->brands_count }} Brands
                                    </a>
                                </td>
                                <td class="text-center">
                                    @if($t->status === 'active')
                                        <span class="pill-status pill-active">Active</span>
                                    @else
                                        <span class="pill-status pill-rejected">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-primary rounded-circle p-1" 
                                                style="width: 32px; height: 32px;" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editTitleModal{{ $t->id }}" 
                                                title="Edit Title">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>

                                        @if($t->brands_count == 0)
                                            <form action="{{ route('admin.brand-titles.destroy', $t->id) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Delete this brand title?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-danger rounded-circle p-1" 
                                                        style="width: 32px; height: 32px;" 
                                                        title="Delete Title">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    <!-- EDIT MODAL -->
                                    <div class="modal fade text-start" id="editTitleModal{{ $t->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('admin.brand-titles.update', $t->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h6 class="modal-title fw-bold text-dark">Edit Brand Title: {{ $t->name }}</h6>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold small">Title Name <span class="text-danger">*</span></label>
                                                            <input type="text" name="name" class="form-control" value="{{ $t->name }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold small">Badge Color Style <span class="text-danger">*</span></label>
                                                            <select name="badge_color" class="form-select">
                                                                <option value="success" {{ $t->badge_color == 'success' ? 'selected' : '' }}>Emerald Green (Super Brand style)</option>
                                                                <option value="primary" {{ $t->badge_color == 'primary' ? 'selected' : '' }}>Navy Blue (Sub Brand style)</option>
                                                                <option value="purple" {{ $t->badge_color == 'purple' ? 'selected' : '' }}>Royal Purple (National Brand style)</option>
                                                                <option value="gold" {{ $t->badge_color == 'gold' ? 'selected' : '' }}>Amber Gold (Premium Brand style)</option>
                                                                <option value="info" {{ $t->badge_color == 'info' ? 'selected' : '' }}>Teal / Sky Blue (OEM style)</option>
                                                                <option value="warning" {{ $t->badge_color == 'warning' ? 'selected' : '' }}>Warm Orange</option>
                                                                <option value="dark" {{ $t->badge_color == 'dark' ? 'selected' : '' }}>Dark Slate</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" name="is_parent_tier" value="1" id="parentSwitch{{ $t->id }}" {{ $t->is_parent_tier ? 'checked' : '' }}>
                                                                <label class="form-check-label fw-semibold small" for="parentSwitch{{ $t->id }}">
                                                                    Acts as Parent Umbrella (can have Sub-brands under it)
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold small">Description</label>
                                                            <textarea name="description" rows="2" class="form-control">{{ $t->description }}</textarea>
                                                        </div>
                                                        <div class="row g-2">
                                                            <div class="col-6">
                                                                <label class="form-label fw-semibold small">Sort Order</label>
                                                                <input type="number" name="sort_order" class="form-control" value="{{ $t->sort_order }}">
                                                            </div>
                                                            <div class="col-6">
                                                                <label class="form-label fw-semibold small">Status</label>
                                                                <select name="status" class="form-select">
                                                                    <option value="active" {{ $t->status == 'active' ? 'selected' : '' }}>Active</option>
                                                                    <option value="inactive" {{ $t->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-sm btn-garudo-primary">Save Changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No custom brand titles found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- RIGHT: Create New Brand Title Form -->
    <div class="col-lg-4">
        <div class="garudo-card p-4">
            <h5 class="fw-bold text-dark mb-1"><i class="bi bi-plus-circle-fill text-success me-2"></i>Create New Title</h5>
            <p class="text-muted small mb-3">Add titles like <em>Mega Brand</em>, <em>Regional Leader</em>, <em>Imported</em>, or <em>Flagship</em>.</p>

            <form action="{{ route('admin.brand-titles.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Title / Tier Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. National Brand / Mega Brand" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Badge Color Style <span class="text-danger">*</span></label>
                    <select name="badge_color" class="form-select" required>
                        <option value="success">Emerald Green (Super Brand style)</option>
                        <option value="primary">Navy Blue (Sub Brand style)</option>
                        <option value="purple" selected>Royal Purple (National Brand style)</option>
                        <option value="gold">Amber Gold (Premium Brand style)</option>
                        <option value="info">Teal / Sky Blue (OEM style)</option>
                        <option value="warning">Warm Orange</option>
                        <option value="dark">Dark Slate</option>
                    </select>
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="is_parent_tier" value="1" id="newParentSwitch">
                    <label class="form-check-label fw-semibold small text-dark" for="newParentSwitch">
                        Acts as Parent Umbrella
                    </label>
                    <div class="text-muted small mt-1">If enabled, brands with this title can have other sub-brands linked to them.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Description</label>
                    <textarea name="description" rows="2" class="form-control" placeholder="Short description of this brand tier..."></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" placeholder="0" value="0">
                </div>

                <button type="submit" class="btn btn-garudo-primary w-100 fw-bold py-2">
                    <i class="bi bi-check-circle-fill me-1"></i> Save Brand Title
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
