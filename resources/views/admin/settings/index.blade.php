@extends('layouts.admin')

@section('title', 'Platform & Marketplace Settings')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="page-header-title">Marketplace Settings & Controls</h1>
        <p class="page-header-subtitle mb-0">Configure default platform commission, commercial currency, stock thresholds and support contact channels.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="garudo-card p-4 mb-4">
            <h5 class="fw-bold text-dark border-bottom pb-3 mb-4">Core Commercial Parameters</h5>

            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-bold">Marketplace Public Name</label>
                    <input type="text" name="marketplace_name" class="form-control" value="{{ old('marketplace_name', $settings['marketplace_name']) }}" required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Default Platform Commission (%)</label>
                        <div class="input-group">
                            <input type="number" step="0.1" min="0" max="100" name="default_commission" class="form-control" value="{{ old('default_commission', $settings['default_commission']) }}" required>
                            <span class="input-group-text">%</span>
                        </div>
                        <small class="text-muted">Standard commission deducted from vendor gross sales.</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Low Stock Warning Threshold</label>
                        <input type="number" min="1" name="low_stock_threshold" class="form-control" value="{{ old('low_stock_threshold', $settings['low_stock_threshold']) }}" required>
                        <small class="text-muted">Inventory level triggering replenishment alerts.</small>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Support & Inquiries Email</label>
                        <input type="email" name="support_email" class="form-control" value="{{ old('support_email', $settings['support_email']) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Support Helpline Phone</label>
                        <input type="text" name="support_phone" class="form-control" value="{{ old('support_phone', $settings['support_phone']) }}" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-garudo-primary px-4 fw-bold">
                    <i class="bi bi-floppy-fill me-1"></i> Save Platform Changes
                </button>
            </form>
        </div>
    </div>

    <!-- System Diagnostics Card -->
    <div class="col-lg-4">
        <div class="garudo-card p-4">
            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Environment Diagnostics</h6>

            <div class="mb-3">
                <div class="text-muted small">Application Environment</div>
                <div class="fw-bold"><span class="badge bg-success">{{ $settings['app_env'] }}</span></div>
            </div>

            <div class="mb-3">
                <div class="text-muted small">Base Currency</div>
                <div class="fw-bold fs-6">{{ $settings['currency'] }} ({{ $settings['currency_symbol'] }})</div>
            </div>

            <div class="mb-3">
                <div class="text-muted small">Marketplace Version</div>
                <div class="fw-semibold text-dark">Garudo Core 2.4-Enterprise</div>
            </div>
        </div>
    </div>
</div>
@endsection
