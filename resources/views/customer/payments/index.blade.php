@extends('layouts.customer')

@section('title', 'Payment Methods & Billing')

@section('content')
<div class="payments-page-container">
    
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row md-align-items-center justify-content-between mb-4 gap-3">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Payment Methods & Billing</h1>
            <p class="text-muted mb-0" style="font-size: 0.875rem;">Manage cards, UPI VPAs, and B2B GST tax invoice profiles.</p>
        </div>
    </div>

    <!-- GST Business Invoicing Profile -->
    <div class="card-custom mb-4" style="border-left: 4px solid var(--garudo-primary);">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    @if($customer->gstin)
                    <span class="badge bg-success" style="font-size: 0.7rem;">Verified B2B Profile</span>
                    @else
                    <span class="badge bg-secondary" style="font-size: 0.7rem;">Standard Account</span>
                    @endif
                    <h3 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">{{ $customer->company_name ?? $customer->name }}</h3>
                </div>
                <p class="text-muted mb-0" style="font-size: 0.82rem;">
                    GSTIN: <strong class="text-dark">{{ $customer->gstin ?? 'Not registered (Add GSTIN in Settings)' }}</strong>
                </p>
            </div>
            <a href="{{ route('customer.settings') }}" class="btn-outline-action">
                <i class="bi bi-pencil me-1"></i> Update Tax Info
            </a>
        </div>
    </div>

    <div class="card-custom text-center py-5">
        <div class="p-3 bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; color: var(--garudo-primary);">
            <i class="bi bi-credit-card-2-front fs-3"></i>
        </div>
        <h5 class="fw-bold text-dark mb-1">Secure Checkout</h5>
        <p class="text-muted mb-0 mx-auto" style="font-size: 0.85rem; max-width: 480px;">
            Payment methods (UPI, Cards, Net Banking, and Bank Transfers) are securely processed via verified payment gateways during checkout.
        </p>
    </div>

</div>
@endsection
