@extends('layouts.vendor')

@section('title', 'Business Details & Tax')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--garudo-text-main);">Business & Tax Information</h4>
        <p class="text-muted mb-0 small">GSTIN, PAN, and banking details for automated payout settlements.</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card-custom">
            <h6 class="fw-bold mb-3 border-bottom pb-2">Statutory & Tax Identifiers</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small fw-bold">GSTIN Number</label>
                    <input type="text" class="form-control" placeholder="e.g. 27AAAAA0000A1Z5" value="{{ $vendor->gstin ?? '' }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">PAN Number</label>
                    <input type="text" class="form-control" placeholder="e.g. ABCDE1234F" value="{{ $vendor->pan_number ?? '' }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Business Entity Type</label>
                    <input type="text" class="form-control" value="{{ ucfirst($vendor->business_type ?? 'Trader') }}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">KYC Verification Status</label>
                    <div>
                        <span class="badge bg-{{ $vendor->kyc_status === 'verified' ? 'success' : 'warning' }} bg-opacity-10 text-{{ $vendor->kyc_status === 'verified' ? 'success' : 'warning' }} fs-6">
                            {{ ucfirst($vendor->kyc_status ?? 'Pending Review') }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="mt-4 pt-3 border-top text-end">
                <button class="btn btn-success" style="background-color: var(--garudo-primary); border: none;">Update Business Details</button>
            </div>
        </div>
    </div>
</div>
@endsection
