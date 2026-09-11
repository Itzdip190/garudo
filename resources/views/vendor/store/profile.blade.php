@extends('layouts.vendor')

@section('title', 'Store Profile')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--garudo-text-main);">Store Information</h4>
        <p class="text-muted mb-0 small">Manage your public seller profile, branding, and enterprise overview.</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card-custom">
            <h6 class="fw-bold mb-3 border-bottom pb-2">Store Profile Details</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Company / Store Name</label>
                    <input type="text" class="form-control" value="{{ $vendor->company_name ?? $vendor->name }}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Business Email</label>
                    <input type="email" class="form-control" value="{{ $vendor->email }}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Phone Number</label>
                    <input type="text" class="form-control" value="{{ $vendor->phone ?? 'N/A' }}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Verification Status</label>
                    <div>
                        <span class="badge bg-success bg-opacity-10 text-success fs-6">{{ ucfirst($vendor->approval_status ?? 'Pending') }}</span>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label small fw-bold">Company Description / Overview</label>
                    <textarea class="form-control" rows="4" placeholder="Brief description of your manufacturing or distribution business...">{{ $vendor->company_description ?? '' }}</textarea>
                </div>
            </div>
            <div class="mt-4 pt-3 border-top text-end">
                <button class="btn btn-success" style="background-color: var(--garudo-primary); border: none;">Save Changes</button>
            </div>
        </div>
    </div>
</div>
@endsection
