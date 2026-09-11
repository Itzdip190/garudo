@extends('layouts.admin')

@section('title', 'Vendor Promotional Boosts & Campaigns Moderation')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--garudo-text-main);">Promotional Boost Campaigns & Payments</h4>
        <p class="text-muted mb-0 small">Verify vendor payments (UTR references) and activate high-visibility search boosts across the marketplace.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.marketing.campaigns', ['status' => 'all']) }}" class="btn btn-sm {{ ($status ?? 'all') === 'all' ? 'btn-success' : 'btn-outline-secondary' }}">All ({{ $stats['total_campaigns'] }})</a>
        <a href="{{ route('admin.marketing.campaigns', ['status' => 'pending']) }}" class="btn btn-sm {{ ($status ?? '') === 'pending' ? 'btn-warning text-dark fw-bold' : 'btn-outline-warning' }}">
            <i class="bi bi-hourglass-split me-1"></i> Pending Verification ({{ $stats['pending_count'] }})
        </a>
        <a href="{{ route('admin.marketing.campaigns', ['status' => 'active']) }}" class="btn btn-sm {{ ($status ?? '') === 'active' ? 'btn-success' : 'btn-outline-success' }}">
            <i class="bi bi-check-circle-fill me-1"></i> Active Live ({{ $stats['active_count'] }})
        </a>
    </div>
</div>

<!-- Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card p-3 border-0 shadow-sm rounded-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-semibold">Pending Approvals</div>
                    <div class="fs-4 fw-bold text-warning">{{ $stats['pending_count'] }}</div>
                </div>
                <div class="p-3 bg-warning bg-opacity-10 text-warning rounded-circle">
                    <i class="bi bi-hourglass-split fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card p-3 border-0 shadow-sm rounded-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-semibold">Active Live Boosts</div>
                    <div class="fs-4 fw-bold text-success">{{ $stats['active_count'] }}</div>
                </div>
                <div class="p-3 bg-success bg-opacity-10 text-success rounded-circle">
                    <i class="bi bi-rocket-takeoff-fill fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card p-3 border-0 shadow-sm rounded-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-semibold">Total Boost Revenue</div>
                    <div class="fs-4 fw-bold text-primary">₹{{ number_format($stats['total_revenue'], 2) }}</div>
                </div>
                <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-circle">
                    <i class="bi bi-cash-stack fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card p-3 border-0 shadow-sm rounded-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-semibold">Total Requests</div>
                    <div class="fs-4 fw-bold text-dark">{{ $stats['total_campaigns'] }}</div>
                </div>
                <div class="p-3 bg-secondary bg-opacity-10 text-secondary rounded-circle">
                    <i class="bi bi-collection fs-4"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-3 text-muted small">CAMPAIGN & VENDOR</th>
                    <th class="text-muted small">PLAN TIER</th>
                    <th class="text-muted small">PAYMENT & UTR REF</th>
                    <th class="text-muted small">DURATION</th>
                    <th class="text-muted small">STATUS</th>
                    <th class="pe-3 text-end text-muted small">ADMIN VERIFICATION ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($campaigns as $camp)
                    <tr>
                        <td class="ps-3">
                            <div class="fw-bold text-dark small">#{{ $camp->campaign_code }}</div>
                            <div class="text-primary fw-semibold small">
                                {{ $camp->vendor->company_name ?? 'Vendor Store' }}
                            </div>
                            <small class="text-muted" style="font-size: 0.7rem;">ID: GRD-V{{ str_pad($camp->vendor_id, 4, '0', STR_PAD_LEFT) }}</small>
                        </td>
                        <td>
                            <div class="fw-bold text-dark small">{{ $camp->plan_title }}</div>
                            <div class="text-muted" style="font-size: 0.72rem;">
                                {{ count($camp->product_ids ?? []) }} Boosted Product SKU(s)
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold text-success small">₹{{ number_format($camp->total_payable, 2) }}</div>
                            <div class="badge bg-light text-dark border font-monospace mt-1" style="font-size: 0.75rem;">
                                UTR: {{ $camp->payment_reference_utr ?? 'Not Provided' }}
                            </div>
                            <div class="text-muted" style="font-size: 0.68rem;">Mode: {{ ucfirst(str_replace('_', ' ', $camp->payment_mode)) }}</div>
                        </td>
                        <td>
                            <div class="small fw-semibold text-dark">{{ $camp->duration_days }} Days</div>
                            <small class="text-muted" style="font-size: 0.7rem;">
                                @if($camp->activated_at)
                                    Active: {{ $camp->activated_at->format('d M') }} - {{ $camp->expires_at ? $camp->expires_at->format('d M Y') : '' }}
                                @else
                                    Requested: {{ $camp->created_at->format('d M Y') }}
                                @endif
                            </small>
                        </td>
                        <td>
                            @if($camp->status === 'pending_payment_approval')
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-1">
                                    <i class="bi bi-hourglass-split me-1"></i> Awaiting Verification
                                </span>
                            @elseif($camp->status === 'active')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">
                                    <i class="bi bi-check-circle-fill me-1"></i> Live Boosted
                                </span>
                            @elseif($camp->status === 'rejected')
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1">
                                    <i class="bi bi-x-circle-fill me-1"></i> Rejected
                                </span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1">
                                    {{ ucfirst($camp->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="pe-3 text-end">
                            @if($camp->status === 'pending_payment_approval')
                                <div class="d-flex align-items-center justify-content-end gap-2">
                                    <!-- Confirm Payment & Approve -->
                                    <form action="{{ route('admin.marketing.campaigns.approve', $camp->id) }}" method="POST" onsubmit="return confirm('Confirm payment received for UTR: {{ $camp->payment_reference_utr }} and activate boost for {{ $camp->duration_days }} days?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success fw-semibold px-3" style="background-color: var(--garudo-primary); border: none;">
                                            <i class="bi bi-check-lg me-1"></i> Confirm & Activate Boost
                                        </button>
                                    </form>

                                    <!-- Reject -->
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $camp->id }}">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>

                                <!-- Modal Reject -->
                                <div class="modal fade" id="rejectModal{{ $camp->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content text-start">
                                            <form action="{{ route('admin.marketing.campaigns.reject', $camp->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h6 class="modal-title fw-bold text-danger">Reject Boost Request #{{ $camp->campaign_code }}</h6>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <label class="form-label small fw-bold">Reason for Rejection / Payment Discrepancy <span class="text-danger">*</span></label>
                                                    <textarea name="admin_notes" class="form-control" rows="3" placeholder="e.g. UTR reference not matching bank statement or incorrect amount." required></textarea>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-sm btn-danger">Reject Request</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @elseif($camp->status === 'active')
                                <span class="text-success small fw-semibold">
                                    <i class="bi bi-check-all fs-5"></i> Activated
                                </span>
                            @else
                                <span class="text-muted small">{{ $camp->admin_notes ?? 'No notes' }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-megaphone fs-1 d-block mb-2 opacity-50"></i>
                            <p class="small mb-0">No promotional boost campaigns in this category.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
