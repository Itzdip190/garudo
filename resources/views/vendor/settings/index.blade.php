@extends('layouts.vendor')

@section('title', 'Account Settings')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--garudo-text-main);">General & Notification Settings</h4>
        <p class="text-muted mb-0 small">Configure email alerts, SMS notifications, and account preferences.</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card-custom mb-3">
            <h6 class="fw-bold mb-3 border-bottom pb-2">Order & Inbound Alert Notifications</h6>
            <div class="d-flex flex-column gap-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="fw-bold small">New Order Alert (Email & SMS)</div>
                        <div class="text-muted small">Receive instant notification whenever a contractor places an order.</div>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" checked>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="fw-bold small">Bulk RFQ & Quotation Inquiries</div>
                        <div class="text-muted small">Get alerted when wholesale inquiries arrive for your building materials.</div>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" checked>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="fw-bold small">Payout & Settlement Updates</div>
                        <div class="text-muted small">Receive bank transfer confirmation when funds are deposited.</div>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" checked>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-custom">
            <h6 class="fw-bold mb-3 border-bottom pb-2">Account Management</h6>
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="fw-bold small text-danger">Vacation / Pause Store Mode</div>
                    <div class="text-muted small">Temporarily hide your products from the public marketplace during warehouse holidays.</div>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
