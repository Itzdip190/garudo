@extends('layouts.vendor')

@section('title', 'Warehouses & Dispatch Depots')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--garudo-text-main);">Multi-Warehouse & Dispatch Facilities</h4>
        <p class="text-muted mb-0 small">Manage multiple stockyards, depots, and factories. Select your Primary Dispatch Warehouse & State for logistics calculation.</p>
    </div>
    <button class="btn btn-success px-3 shadow-sm d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#addWarehouseModal" style="background-color: var(--garudo-primary); border: none; font-weight: 600;">
        <i class="bi bi-plus-lg"></i> Add New Warehouse
    </button>
</div>

<!-- Primary Dispatch Origin Banner -->
<div class="card-custom mb-4 p-3 bg-white" style="border-left: 5px solid var(--garudo-primary);">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: #E8F7F0; color: #0B7B58; font-size: 1.5rem;">
                <i class="bi bi-building-check"></i>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-success bg-opacity-10 text-success border border-success fw-semibold" style="font-size: 0.75rem;">
                        Primary Dispatch State
                    </span>
                    <strong class="fs-5 text-dark">{{ $primaryState }}</strong>
                </div>
                <div class="text-muted small mt-1">
                    Facility: <strong class="text-dark">{{ $primaryWarehouse ? $primaryWarehouse->name : 'No Primary Facility Assigned' }}</strong>
                    @if($primaryWarehouse)
                        — {{ $primaryWarehouse->full_address }}
                    @endif
                </div>
            </div>
        </div>
        <div class="text-md-end">
            <span class="badge bg-light text-muted border px-3 py-2">
                <i class="bi bi-info-circle me-1 text-primary"></i> Orders & Logistics default to this State
            </span>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card-custom p-3 text-center mb-0">
            <div class="text-muted small fw-semibold text-uppercase">Total Depots</div>
            <div class="fs-4 fw-bold text-dark mt-1">{{ $stats['total'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card-custom p-3 text-center mb-0">
            <div class="text-muted small fw-semibold text-uppercase">Active Facilities</div>
            <div class="fs-4 fw-bold text-success mt-1">{{ $stats['active'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card-custom p-3 text-center mb-0">
            <div class="text-muted small fw-semibold text-uppercase">Primary State</div>
            <div class="fs-5 fw-bold text-primary mt-1 text-truncate">{{ $stats['primary_state'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card-custom p-3 text-center mb-0">
            <div class="text-muted small fw-semibold text-uppercase">Primary Facility</div>
            <div class="fs-6 fw-bold text-dark mt-1 text-truncate" title="{{ $stats['primary_name'] }}">{{ $stats['primary_name'] }}</div>
        </div>
    </div>
</div>

<!-- Warehouses List -->
<div class="card-custom p-0 overflow-hidden shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
            <thead class="bg-light text-muted border-bottom">
                <tr>
                    <th class="ps-3 py-3">Warehouse / Depot</th>
                    <th>State & Location</th>
                    <th>Supervisor / Contact</th>
                    <th>Status</th>
                    <th>Role</th>
                    <th class="text-end pe-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($warehouses as $wh)
                    <tr class="{{ $wh->is_primary ? 'table-light' : '' }}">
                        <td class="ps-3 py-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded p-2 bg-light border text-muted">
                                    <i class="bi bi-building fs-5 {{ $wh->is_primary ? 'text-success' : '' }}"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $wh->name }}</div>
                                    <code class="small text-muted">{{ $wh->code }}</code>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div><strong class="text-dark">{{ $wh->state }}</strong> ({{ $wh->city }})</div>
                            <div class="text-muted small text-truncate" style="max-width: 250px;" title="{{ $wh->full_address }}">
                                {{ $wh->address_line1 }}{{ $wh->pincode ? ', PIN: ' . $wh->pincode : '' }}
                            </div>
                        </td>
                        <td>
                            <div class="text-dark fw-semibold">{{ $wh->contact_person ?: 'N/A' }}</div>
                            <div class="text-muted small">
                                @if($wh->phone)
                                    <i class="bi bi-telephone me-1"></i>{{ $wh->phone }}
                                @endif
                                @if($wh->email)
                                    <span class="ms-1">({{ $wh->email }})</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="badge {{ $wh->status === 'active' ? 'bg-success bg-opacity-10 text-success border border-success' : 'bg-secondary' }}">
                                {{ ucfirst($wh->status) }}
                            </span>
                        </td>
                        <td>
                            @if($wh->is_primary)
                                <span class="badge bg-success py-1.5 px-2.5 rounded-pill fw-bold">
                                    <i class="bi bi-star-fill me-1"></i> Primary Dispatch Origin
                                </span>
                            @else
                                <span class="badge bg-light text-muted border">
                                    Branch Facility
                                </span>
                            @endif
                        </td>
                        <td class="text-end pe-3">
                            <div class="d-inline-flex gap-1">
                                @if(!$wh->is_primary)
                                    <form action="{{ route('vendor.warehouses.set-primary', $wh->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success py-1 px-2" title="Set as Primary Dispatch Origin">
                                            <i class="bi bi-star me-1"></i> Set Primary
                                        </button>
                                    </form>
                                @endif

                                <button class="btn btn-sm btn-light border py-1 px-2" onclick="openEditModal({{ json_encode($wh) }})" title="Edit Details">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                @if(!$wh->is_primary)
                                    <form action="{{ route('vendor.warehouses.destroy', $wh->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to remove {{ $wh->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-building-exclamation fs-1 d-block mb-2 text-secondary"></i>
                            <h6>No Warehouses Added Yet</h6>
                            <p class="small mb-3">Add your first factory, stockyard, or godown to designate your Primary Dispatch State.</p>
                            <button class="btn btn-success btn-sm px-3" data-bs-toggle="modal" data-bs-target="#addWarehouseModal" style="background-color: var(--garudo-primary); border: none;">
                                <i class="bi bi-plus-lg me-1"></i> Add Primary Warehouse
                            </button>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal: Add Warehouse -->
<div class="modal fade" id="addWarehouseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-building-add fs-5" style="color: var(--garudo-primary);"></i>
                    <h6 class="modal-title fw-bold">Add Warehouse / Depot</h6>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('vendor.warehouses.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-semibold">Warehouse / Depot Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Bhiwandi Central Godown" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Warehouse Code</label>
                            <input type="text" name="code" class="form-control" placeholder="e.g. WH-BHI-01">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Dispatch State <span class="text-danger">*</span></label>
                            <select name="state" class="form-select" required>
                                <option value="">-- Select Indian State --</option>
                                @foreach($indianStates as $st)
                                    <option value="{{ $st }}" {{ old('state', $primaryState) === $st ? 'selected' : '' }}>{{ $st }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">City / District <span class="text-danger">*</span></label>
                            <input type="text" name="city" class="form-control" placeholder="e.g. Thane" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">PIN Code <span class="text-danger">*</span></label>
                            <input type="text" name="pincode" class="form-control" placeholder="e.g. 421302" required maxlength="10">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-semibold">Street Address / Industrial Area <span class="text-danger">*</span></label>
                            <input type="text" name="address_line1" class="form-control" placeholder="Plot No, Warehouse Park, Road name" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Address Line 2 (Optional)</label>
                            <input type="text" name="address_line2" class="form-control" placeholder="Landmark, Near Highway Gate, etc.">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Supervisor / Contact Person</label>
                            <input type="text" name="contact_person" class="form-control" placeholder="Manager Name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Contact Phone Number</label>
                            <input type="text" name="phone" class="form-control" placeholder="+91 98765 43210">
                        </div>

                        <div class="col-12">
                            <div class="form-check p-3 bg-light rounded border">
                                <input class="form-check-input" type="checkbox" name="is_primary" id="addPrimaryCheck" value="1" {{ $warehouses->isEmpty() ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold text-dark small" for="addPrimaryCheck">
                                    Set as Primary Dispatch Warehouse & State
                                </label>
                                <div class="text-muted" style="font-size: 0.74rem;">Default origin address and state for new products and automated courier bidding quotes.</div>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-semibold">Transporter & Dispatch Notes (Optional)</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="e.g. Heavy 16-wheelers allowed; Weighbridge available inside premises."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success px-4" style="background-color: var(--garudo-primary); border: none; font-weight: 600;">
                        Save Warehouse
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Edit Warehouse -->
<div class="modal fade" id="editWarehouseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-pencil-square fs-5" style="color: var(--garudo-primary);"></i>
                    <h6 class="modal-title fw-bold">Edit Warehouse Details</h6>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editWarehouseForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-semibold">Warehouse / Depot Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Warehouse Code</label>
                            <input type="text" name="code" id="edit_code" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Dispatch State <span class="text-danger">*</span></label>
                            <select name="state" id="edit_state" class="form-select" required>
                                @foreach($indianStates as $st)
                                    <option value="{{ $st }}">{{ $st }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">City / District <span class="text-danger">*</span></label>
                            <input type="text" name="city" id="edit_city" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">PIN Code <span class="text-danger">*</span></label>
                            <input type="text" name="pincode" id="edit_pincode" class="form-control" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-semibold">Street Address <span class="text-danger">*</span></label>
                            <input type="text" name="address_line1" id="edit_address_line1" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Address Line 2</label>
                            <input type="text" name="address_line2" id="edit_address_line2" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Supervisor / Contact Person</label>
                            <input type="text" name="contact_person" id="edit_contact_person" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Contact Phone</label>
                            <input type="text" name="phone" id="edit_phone" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Status</label>
                            <select name="status" id="edit_status" class="form-select">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" name="is_primary" id="edit_is_primary" value="1">
                                <label class="form-check-label fw-bold text-dark small" for="edit_is_primary">
                                    Set as Primary Dispatch Warehouse & State
                                </label>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-semibold">Notes</label>
                            <textarea name="notes" id="edit_notes" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success px-4" style="background-color: var(--garudo-primary); border: none; font-weight: 600;">
                        Update Warehouse
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openEditModal(wh) {
    document.getElementById('editWarehouseForm').action = `/vendor/warehouses/${wh.id}`;
    document.getElementById('edit_name').value = wh.name || '';
    document.getElementById('edit_code').value = wh.code || '';
    document.getElementById('edit_state').value = wh.state || '';
    document.getElementById('edit_city').value = wh.city || '';
    document.getElementById('edit_pincode').value = wh.pincode || '';
    document.getElementById('edit_address_line1').value = wh.address_line1 || '';
    document.getElementById('edit_address_line2').value = wh.address_line2 || '';
    document.getElementById('edit_contact_person').value = wh.contact_person || '';
    document.getElementById('edit_phone').value = wh.phone || '';
    document.getElementById('edit_status').value = wh.status || 'active';
    document.getElementById('edit_is_primary').checked = (wh.is_primary == 1);
    document.getElementById('edit_notes').value = wh.notes || '';

    new bootstrap.Modal(document.getElementById('editWarehouseModal')).show();
}
</script>
@endpush
@endsection
