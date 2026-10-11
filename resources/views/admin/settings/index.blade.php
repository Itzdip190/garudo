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

        <!-- Official Pre-Launch & Header Tagline Settings -->
        <div class="garudo-card p-4 mb-4" style="border-top: 3px solid #f59e0b;">
            <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-4">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-rocket-takeoff-fill text-warning fs-4"></i>
                    <div>
                        <h5 class="fw-bold text-dark mb-0">Official Launch & Header Tagline Controls</h5>
                        <small class="text-muted">Control the pre-launch preview strip, launch date, purchasing notices, and website header tagline.</small>
                    </div>
                </div>
                <span class="badge {{ ($launchSettings['launch_mode_active'] ?? '1') == '1' ? 'bg-warning text-dark' : 'bg-success text-white' }} px-2.5 py-1 fw-bold rounded-pill">
                    {{ ($launchSettings['launch_mode_active'] ?? '1') == '1' ? 'PRE-LAUNCH MODE ACTIVE' : 'STOREFRONT LIVE' }}
                </span>
            </div>

            <form action="{{ route('admin.launch-mode.update') }}" method="POST">
                @csrf
                <div class="p-3 rounded-3 mb-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <label class="form-label fw-bold text-dark mb-0">Pre-Launch Mode Active (Show Strip & Pause Direct Purchasing)</label>
                            <div class="text-muted small">Toggle OFF to hide the announcement strip and enable full direct purchasing immediately.</div>
                        </div>
                        <div class="form-check form-switch m-0">
                            <input class="form-check-input" type="checkbox" name="launch_mode_active" value="1" style="width: 2.8em; height: 1.4em; cursor: pointer;" {{ ($launchSettings['launch_mode_active'] ?? '1') == '1' ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-dark">Official Launch Date</label>
                        <input type="text" name="launch_date" class="form-control" value="{{ old('launch_date', $launchSettings['launch_date'] ?? '20th October') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold text-dark">Header Tagline (Desktop)</label>
                        <input type="text" name="header_tagline" class="form-control" value="{{ old('header_tagline', $launchSettings['header_tagline'] ?? "India's Leading B2B Commerce & Wholesale Network") }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold text-dark">Header Tagline (Mobile)</label>
                    <input type="text" name="header_tagline_mobile" class="form-control" value="{{ old('header_tagline_mobile', $launchSettings['header_tagline_mobile'] ?? "India's #1 B2B Commerce & Wholesale Network") }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold text-dark">Top Strip Announcement Message</label>
                    <input type="text" name="launch_strip_text" class="form-control" value="{{ old('launch_strip_text', $launchSettings['launch_strip_text'] ?? 'Official Launching on 20th October | Pre-Launch Preview Active') }}" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-dark">Pre-Launch Notice Message (Shown when customer attempts to purchase)</label>
                    <textarea name="launch_notice_message" class="form-control" rows="2" required>{{ old('launch_notice_message', $launchSettings['launch_notice_message'] ?? 'Official Launching on 20th October. Direct order purchasing will start on our official launch day!') }}</textarea>
                </div>

                <button type="submit" class="btn btn-warning text-dark px-4 fw-bold shadow-sm">
                    <i class="bi bi-floppy-fill me-1"></i> Save Launch & Tagline Settings
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
