@extends('layouts.customer')

@section('title', 'Saved Addresses')

@section('content')
<div class="addresses-page-container">
    
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row md-align-items-center justify-content-between mb-4 gap-3">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Saved Addresses</h1>
            <p class="text-muted mb-0" style="font-size: 0.875rem;">Manage delivery locations for construction sites, warehouses, and offices.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn-primary-action" data-bs-toggle="modal" data-bs-target="#newAddressModal">
                <i class="bi bi-plus-lg"></i> Add New Address
            </button>
        </div>
    </div>

    <!-- Address Cards Grid -->
    <div class="row g-4">
        @forelse($addresses as $address)
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card-custom h-100 d-flex flex-column justify-content-between position-relative">
                <div>
                    <!-- Header -->
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center">
                            <h3 class="fw-bold text-dark mb-0" style="font-size: 1rem;">
                                {{ $address->label ?? 'Delivery Address' }}
                            </h3>
                            @if($address->is_default)
                            <span class="badge-default-tag">Default</span>
                            @endif
                        </div>
                        <span class="badge bg-light text-secondary border" style="font-size: 0.7rem;">
                            {{ ucfirst($address->address_type ?? 'Shipping') }}
                        </span>
                    </div>

                    <!-- Details -->
                    <p class="fw-semibold text-dark mb-1" style="font-size: 0.88rem;">{{ $address->contact_name }}</p>
                    <p class="text-muted mb-1" style="font-size: 0.82rem; line-height: 1.4;">
                        {{ $address->address_line1 }}<br>
                        @if(!empty($address->address_line2)) {{ $address->address_line2 }}<br> @endif
                        @if(!empty($address->landmark)) <em>Near {{ $address->landmark }}</em><br> @endif
                        {{ $address->city }}, {{ $address->state }} - <strong>{{ $address->pincode }}</strong>
                    </p>
                    <p class="text-dark fw-medium mt-2 mb-0" style="font-size: 0.82rem;">
                        <i class="bi bi-telephone me-1 text-muted"></i> {{ $address->contact_phone }}
                    </p>
                </div>

                <!-- Footer Actions -->
                <div class="d-flex align-items-center gap-2 pt-3 mt-3 border-top">
                    <button class="btn-outline-action py-1 px-3" style="font-size: 0.75rem;">
                        <i class="bi bi-geo-alt me-1"></i> Deliver Here
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card-custom text-center py-5">
                <div class="p-3 bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; color: var(--garudo-primary);">
                    <i class="bi bi-geo-alt fs-3"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">No Saved Addresses</h5>
                <p class="text-muted mb-3" style="font-size: 0.85rem;">Add your construction site or warehouse address for fast material delivery.</p>
                <button class="btn-primary-action" data-bs-toggle="modal" data-bs-target="#newAddressModal">
                    <i class="bi bi-plus-lg me-1"></i> Add Your First Address
                </button>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Modal: Add New Address -->
    <div class="modal fade" id="newAddressModal" tabindex="-1" aria-labelledby="newAddressModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow" style="border-radius: 16px;">
                <div class="modal-header border-bottom px-4 py-3">
                    <h5 class="modal-title fw-bold text-dark" id="newAddressModalLabel">Add Delivery Location</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('customer.addresses.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="font-size: 0.82rem;">Address Label (e.g. Site 4, Main Office)</label>
                                <input type="text" name="label" class="form-control" placeholder="Construction Site / Office" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="font-size: 0.82rem;">Address Type</label>
                                <select name="address_type" class="form-select">
                                    <option value="both">Billing & Shipping</option>
                                    <option value="shipping">Shipping / Site Drop Only</option>
                                    <option value="billing">Billing Only</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="font-size: 0.82rem;">Contact Person Name</label>
                                <input type="text" name="contact_name" class="form-control" placeholder="Site Supervisor / Manager" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="font-size: 0.82rem;">Contact Phone</label>
                                <input type="text" name="contact_phone" class="form-control" placeholder="+91 98765 00000" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold" style="font-size: 0.82rem;">Address Line 1</label>
                                <input type="text" name="address_line1" class="form-control" placeholder="Plot No., Building Name, Street / Sector" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold" style="font-size: 0.82rem;">Address Line 2 (Optional)</label>
                                <input type="text" name="address_line2" class="form-control" placeholder="Area, Landmark, Unloading Bay">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold" style="font-size: 0.82rem;">City</label>
                                <input type="text" name="city" class="form-control" placeholder="e.g. New Delhi" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold" style="font-size: 0.82rem;">State</label>
                                <input type="text" name="state" class="form-control" placeholder="e.g. Delhi" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold" style="font-size: 0.82rem;">Pincode</label>
                                <input type="text" name="pincode" class="form-control" placeholder="110016" required>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_default" id="setDefaultCheck" value="1">
                                    <label class="form-check-label" for="setDefaultCheck" style="font-size: 0.82rem;">
                                        Set as default shipping address for quick checkout
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn-primary-action">Save Address</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
