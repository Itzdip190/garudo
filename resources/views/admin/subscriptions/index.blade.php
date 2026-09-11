@extends('layouts.admin')

@section('title', 'Subscribers & Plan Approvals')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Section -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--garudo-text-main);">
                <i class="bi bi-person-lines-fill text-primary me-2"></i> Subscribers & Payment Approvals
            </h4>
            <p class="text-muted small mb-0">Review membership upgrades, verify bank/UPI transaction UTRs, and manage subscriber access.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.subscriptions.plans') }}" class="btn btn-outline-secondary">
                <i class="bi bi-sliders me-1"></i> Manage Pricing Tiers
            </a>
            <button type="button" class="btn btn-garudo-primary" data-bs-toggle="modal" data-bs-target="#assignPlanModal">
                <i class="bi bi-gift-fill me-1"></i> Manually Assign Plan
            </button>
        </div>
    </div>

    <!-- Stats Cards Row -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Active Subscribers</div>
                        <h3 class="fw-bold mb-0 text-dark mt-1">{{ number_format($stats['total_active']) }}</h3>
                    </div>
                    <div class="rounded-3 p-3 bg-success-subtle text-success fs-4">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Pending UTR Reviews</div>
                        <h3 class="fw-bold mb-0 text-warning mt-1">{{ number_format($stats['pending_review']) }}</h3>
                    </div>
                    <div class="rounded-3 p-3 bg-warning-subtle text-warning fs-4">
                        <i class="bi bi-clock-history"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Active Vendors Subscribed</div>
                        <h3 class="fw-bold mb-0 text-primary mt-1">{{ number_format($stats['vendor_subs']) }}</h3>
                    </div>
                    <div class="rounded-3 p-3 bg-primary-subtle text-primary fs-4">
                        <i class="bi bi-shop"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Active Monthly MRR</div>
                        <h3 class="fw-bold mb-0 text-success mt-1">₹{{ number_format($stats['revenue_mrr'], 0) }}</h3>
                    </div>
                    <div class="rounded-3 p-3 bg-success-subtle text-success fs-4">
                        <i class="bi bi-currency-rupee"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Filter Bar -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <!-- Status Tabs -->
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.subscriptions.index', ['status' => 'all', 'role' => $roleFilter]) }}" 
                       class="btn btn-sm {{ $statusFilter === 'all' ? 'btn-dark' : 'btn-outline-secondary' }} rounded-pill px-3">
                        All
                    </a>
                    <a href="{{ route('admin.subscriptions.index', ['status' => 'pending_approval', 'role' => $roleFilter]) }}" 
                       class="btn btn-sm {{ $statusFilter === 'pending_approval' ? 'btn-warning' : 'btn-outline-secondary' }} rounded-pill px-3">
                        Pending UTR ({{ $stats['pending_review'] }})
                    </a>
                    <a href="{{ route('admin.subscriptions.index', ['status' => 'active', 'role' => $roleFilter]) }}" 
                       class="btn btn-sm {{ $statusFilter === 'active' ? 'btn-success' : 'btn-outline-secondary' }} rounded-pill px-3">
                        Active ({{ $stats['total_active'] }})
                    </a>
                    <a href="{{ route('admin.subscriptions.index', ['status' => 'expired', 'role' => $roleFilter]) }}" 
                       class="btn btn-sm {{ $statusFilter === 'expired' ? 'btn-secondary' : 'btn-outline-secondary' }} rounded-pill px-3">
                        Expired
                    </a>
                </div>

                <!-- Role Tabs -->
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.subscriptions.index', ['status' => $statusFilter, 'role' => 'all']) }}" 
                       class="btn btn-sm {{ $roleFilter === 'all' ? 'btn-primary' : 'btn-light border' }} rounded-pill px-3">
                        All Entities
                    </a>
                    <a href="{{ route('admin.subscriptions.index', ['status' => $statusFilter, 'role' => 'vendor']) }}" 
                       class="btn btn-sm {{ $roleFilter === 'vendor' ? 'btn-primary' : 'btn-light border' }} rounded-pill px-3">
                        <i class="bi bi-shop me-1"></i> Vendors
                    </a>
                    <a href="{{ route('admin.subscriptions.index', ['status' => $statusFilter, 'role' => 'customer']) }}" 
                       class="btn btn-sm {{ $roleFilter === 'customer' ? 'btn-primary' : 'btn-light border' }} rounded-pill px-3">
                        <i class="bi bi-person me-1"></i> Buyers
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Subscribers Table -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table admin-table mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Subscriber / Business</th>
                        <th>Role</th>
                        <th>Plan & Tier</th>
                        <th>Billing Cycle</th>
                        <th>Amount</th>
                        <th>Payment UTR / Ref</th>
                        <th>Duration / Validity</th>
                        <th>Status</th>
                        <th class="pe-4 text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscriptions as $sub)
                        <tr>
                            <!-- Subscriber info -->
                            <td class="ps-4">
                                @if($sub->subscribable)
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center fw-bold text-dark" style="width: 38px; height: 38px; min-width: 38px;">
                                            <i class="bi {{ $sub->subscribable instanceof \App\Models\Vendor ? 'bi-shop' : 'bi-person' }}"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">
                                                {{ $sub->subscribable->company_name ?? $sub->subscribable->name ?? 'N/A' }}
                                            </div>
                                            <div class="text-muted small">
                                                {{ $sub->subscribable->email ?? $sub->subscribable->phone ?? '' }}
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">Unknown Entity #{{ $sub->subscribable_id }}</span>
                                @endif
                            </td>

                            <!-- Role -->
                            <td>
                                @if($sub->subscribable_type === \App\Models\Vendor::class)
                                    <span class="badge bg-success-subtle text-success rounded-pill px-2.5 py-1">
                                        <i class="bi bi-shop me-1"></i> Vendor
                                    </span>
                                @else
                                    <span class="badge bg-primary-subtle text-primary rounded-pill px-2.5 py-1">
                                        <i class="bi bi-person me-1"></i> Buyer
                                    </span>
                                @endif
                            </td>

                            <!-- Plan -->
                            <td>
                                <div class="fw-bold text-dark">{{ $sub->plan->name ?? $sub->plan_name }}</div>
                                @if($sub->plan && $sub->plan->has_24_7_support)
                                    <span class="badge bg-info-subtle text-info small" style="font-size: 0.65rem;">
                                        <i class="bi bi-headset me-0.5"></i> 24/7 Support
                                    </span>
                                @endif
                                @if($sub->remaining_boost_tokens > 0)
                                    <span class="badge bg-warning-subtle text-warning-emphasis small" style="font-size: 0.65rem;">
                                        <i class="bi bi-rocket-takeoff-fill me-0.5"></i> {{ $sub->remaining_boost_tokens }} Boosts
                                    </span>
                                @endif
                            </td>

                            <!-- Billing Cycle -->
                            <td>
                                <span class="badge bg-light text-dark border text-capitalize">
                                    {{ $sub->billing_cycle }}
                                </span>
                            </td>

                            <!-- Amount -->
                            <td>
                                <strong class="text-dark">₹{{ number_format($sub->amount_paid, 2) }}</strong>
                            </td>

                            <!-- UTR / Payment Info -->
                            <td>
                                @if($sub->payment_reference)
                                    <div class="fw-bold font-monospace small text-primary">
                                        {{ $sub->payment_reference }}
                                    </div>
                                    <span class="text-muted small text-capitalize">{{ str_replace('_', ' ', $sub->payment_method) }}</span>
                                @elseif($sub->payment_method === 'free')
                                    <span class="badge bg-light text-secondary border">Free Plan</span>
                                @elseif($sub->payment_method === 'admin_assigned')
                                    <span class="badge bg-info-subtle text-info border">Admin Granted</span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>

                            <!-- Dates -->
                            <td>
                                @if($sub->starts_at)
                                    <div class="small text-dark">{{ $sub->starts_at->format('d M Y') }}</div>
                                    <div class="text-muted small">
                                        to {{ $sub->expires_at ? $sub->expires_at->format('d M Y') : 'Lifetime' }}
                                    </div>
                                @else
                                    <span class="badge bg-warning-subtle text-warning small">Awaiting Activation</span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td>
                                @if($sub->status === 'active')
                                    <span class="pill-status pill-active">
                                        <i class="bi bi-check-circle-fill"></i> Active
                                    </span>
                                @elseif($sub->status === 'pending_approval')
                                    <span class="pill-status pill-pending">
                                        <i class="bi bi-clock-fill"></i> Pending UTR
                                    </span>
                                @elseif($sub->status === 'expired')
                                    <span class="pill-status pill-suspended">
                                        <i class="bi bi-exclamation-circle-fill"></i> Expired
                                    </span>
                                @else
                                    <span class="pill-status pill-rejected">
                                        {{ ucfirst($sub->status) }}
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="pe-4 text-end">
                                @if($sub->status === 'pending_approval')
                                    <button type="button" class="btn btn-sm btn-success fw-bold px-3 shadow-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#approveSubModal{{ $sub->id }}">
                                        <i class="bi bi-check-circle me-1"></i> Verify & Approve
                                    </button>

                                    <!-- Approve Modal -->
                                    <div class="modal fade" id="approveSubModal{{ $sub->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow-lg rounded-4 text-start">
                                                <form action="{{ route('admin.subscriptions.approve', $sub->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header bg-success text-white rounded-top-4 px-4 py-3">
                                                        <h5 class="modal-title fw-bold">
                                                            <i class="bi bi-check2-all me-2"></i> Approve Subscription Payment
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body p-4">
                                                        <p class="mb-3 text-dark">
                                                            Confirm payment verification for <strong>{{ $sub->subscribable->company_name ?? $sub->subscribable->name ?? 'Subscriber' }}</strong>:
                                                        </p>
                                                        <div class="p-3 bg-light rounded-3 mb-3 border">
                                                            <div class="d-flex justify-content-between mb-1">
                                                                <span class="text-muted small">Plan:</span>
                                                                <span class="fw-bold text-dark">{{ $sub->plan->name ?? $sub->plan_name }}</span>
                                                            </div>
                                                            <div class="d-flex justify-content-between mb-1">
                                                                <span class="text-muted small">Cycle:</span>
                                                                <span class="fw-bold text-dark text-capitalize">{{ $sub->billing_cycle }}</span>
                                                            </div>
                                                            <div class="d-flex justify-content-between mb-1">
                                                                <span class="text-muted small">Amount Paid:</span>
                                                                <span class="fw-bold text-success fs-6">₹{{ number_format($sub->amount_paid, 2) }}</span>
                                                            </div>
                                                            <div class="d-flex justify-content-between">
                                                                <span class="text-muted small">UTR / Ref No:</span>
                                                                <span class="fw-bold font-monospace text-primary">{{ $sub->payment_reference }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold small">Admin Verification Note (Optional)</label>
                                                            <input type="text" name="admin_notes" class="form-control" value="Bank payment verified by Admin" placeholder="e.g. Verified via HDFC Bank statement">
                                                        </div>

                                                        <div class="alert alert-info py-2 small mb-0">
                                                            <i class="bi bi-info-circle-fill me-1"></i> Activating this will immediately enable 24/7 Priority Support, allocate {{ $sub->plan->sponsored_products_quota ?? 0 }} Boost Tokens, and notify the vendor.
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-light px-4 py-3">
                                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-success fw-bold px-4">
                                                            <i class="bi bi-check-lg me-1"></i> Confirm & Activate
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted small">Active</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox display-6 d-block mb-2 text-muted"></i>
                                No subscriptions found matching this filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($subscriptions->hasPages())
            <div class="card-footer bg-white border-top p-3">
                {{ $subscriptions->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Assign Plan Modal -->
<div class="modal fade" id="assignPlanModal" tabindex="-1" aria-labelledby="assignPlanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form action="{{ route('admin.subscriptions.assign') }}" method="POST">
                @csrf
                <div class="modal-header bg-dark text-white rounded-top-4 px-4 py-3">
                    <h5 class="modal-title fw-bold" id="assignPlanModalLabel">
                        <i class="bi bi-gift-fill me-2 text-warning"></i> Assign Subscription Plan Manually
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <input type="hidden" name="target_type" value="vendor">
                        
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Select Vendor <span class="text-danger">*</span></label>
                            <select name="target_id" class="form-select" required>
                                <option value="" disabled selected>-- Choose Vendor --</option>
                                @foreach($vendors as $vnd)
                                    <option value="{{ $vnd->id }}">
                                        {{ $vnd->company_name }} ({{ $vnd->email ?? $vnd->phone }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small">Select Plan Tier <span class="text-danger">*</span></label>
                            <select name="subscription_plan_id" class="form-select" required>
                                <option value="" disabled selected>-- Choose Plan Tier --</option>
                                @foreach($plans as $p)
                                    <option value="{{ $p->id }}">
                                        {{ $p->name }} (₹{{ number_format($p->monthly_price, 0) }}/mo | 24/7 Support: {{ $p->has_24_7_support ? 'Yes' : 'No' }} | Boosts: {{ $p->sponsored_products_quota }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small">Duration (Months) <span class="text-danger">*</span></label>
                            <select name="duration_months" class="form-select" required>
                                <option value="1">1 Month</option>
                                <option value="3">3 Months</option>
                                <option value="6">6 Months</option>
                                <option value="12" selected>12 Months (1 Year)</option>
                                <option value="24">24 Months (2 Years)</option>
                                <option value="36">36 Months (3 Years)</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small">Internal Note / Reason</label>
                            <input type="text" name="admin_notes" class="form-control" placeholder="e.g. VIP onboarding partnership package">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-garudo-primary fw-bold px-4">
                        <i class="bi bi-check-lg me-1"></i> Assign Plan Now
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
