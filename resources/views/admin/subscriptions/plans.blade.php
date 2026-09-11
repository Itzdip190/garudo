@extends('layouts.admin')

@section('title', 'Subscription Plans & Tiers')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Section -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--garudo-text-main);">
                <i class="bi bi-patch-check-fill text-success me-2"></i> Subscription Plans & Pricing Tiers
            </h4>
            <p class="text-muted small mb-0">Control tier pricing, 24/7 priority support, sponsored product quotas, commission rates, and feature limits.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-people-fill me-1"></i> View Subscribers & Approvals
            </a>
            <button type="button" class="btn btn-garudo-primary" data-bs-toggle="modal" data-bs-target="#createPlanModal">
                <i class="bi bi-plus-circle-fill me-1"></i> Create New Plan
            </button>
        </div>
    </div>

    <!-- Filter Pills -->
    <div class="d-flex gap-2 mb-4">
        <a href="{{ route('admin.subscriptions.plans', ['role' => 'all']) }}" 
           class="btn btn-sm {{ $roleFilter === 'all' ? 'btn-dark' : 'btn-outline-secondary' }} rounded-pill px-3">
            All Plans ({{ \App\Models\SubscriptionPlan::count() }})
        </a>
        <a href="{{ route('admin.subscriptions.plans', ['role' => 'vendor']) }}" 
           class="btn btn-sm {{ $roleFilter === 'vendor' ? 'btn-success' : 'btn-outline-secondary' }} rounded-pill px-3">
            <i class="bi bi-shop me-1"></i> Vendor Plans ({{ \App\Models\SubscriptionPlan::where('target_role', 'vendor')->count() }})
        </a>
        <a href="{{ route('admin.subscriptions.plans', ['role' => 'customer']) }}" 
           class="btn btn-sm {{ $roleFilter === 'customer' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-3">
            <i class="bi bi-person-badge me-1"></i> Buyer Pro Plans ({{ \App\Models\SubscriptionPlan::where('target_role', 'customer')->count() }})
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
            <ul class="mb-0 small">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Plans Grid -->
    <div class="row g-4">
        @forelse($plans as $plan)
            <div class="col-xl-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 position-relative overflow-hidden" 
                     style="border: {{ $plan->is_featured ? '2px solid #10B981' : '1px solid var(--garudo-border)' }} !important;">
                    
                    @if($plan->is_featured)
                        <div class="position-absolute top-0 end-0 bg-success text-white px-3 py-1 fw-bold small rounded-bottom-start-3 shadow-sm">
                            <i class="bi bi-star-fill me-1"></i> POPULAR / RECOMMENDED
                        </div>
                    @endif

                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <!-- Header Info -->
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge {{ $plan->target_role === 'vendor' ? 'bg-success-subtle text-success' : 'bg-primary-subtle text-primary' }} rounded-pill px-2.5 py-1 text-uppercase fw-bold" style="font-size: 0.7rem;">
                                    <i class="bi {{ $plan->target_role === 'vendor' ? 'bi-shop' : 'bi-person' }} me-1"></i>
                                    {{ $plan->target_role }} Tier
                                </span>
                                @if($plan->is_active)
                                    <span class="badge bg-success text-white rounded-pill px-2 py-0.5" style="font-size: 0.68rem;">Active</span>
                                @else
                                    <span class="badge bg-secondary text-white rounded-pill px-2 py-0.5" style="font-size: 0.68rem;">Inactive</span>
                                @endif
                            </div>

                            <h5 class="fw-bold mb-1 text-dark">{{ $plan->name }}</h5>
                            <p class="text-muted small mb-3">{{ $plan->description ?? 'Comprehensive marketplace subscription package.' }}</p>

                            <!-- Pricing Block -->
                            <div class="p-3 bg-light rounded-3 mb-3 border">
                                <div class="d-flex align-items-baseline gap-1">
                                    <span class="fs-4 fw-black text-dark">₹{{ number_format($plan->monthly_price, 0) }}</span>
                                    <span class="text-muted small">/ month</span>
                                </div>
                                <div class="text-muted small mt-1">
                                    <i class="bi bi-calendar3 me-1"></i> Yearly: <strong class="text-dark">₹{{ number_format($plan->yearly_price, 0) }}</strong> / year
                                    @if($plan->monthly_price > 0 && ($plan->monthly_price * 12) > $plan->yearly_price)
                                        <span class="badge bg-warning-subtle text-dark ms-1">Save ₹{{ number_format(($plan->monthly_price * 12) - $plan->yearly_price, 0) }}</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Core Highlights & Admin Features -->
                            <div class="mb-3">
                                <div class="fw-bold text-uppercase small text-muted mb-2" style="font-size: 0.72rem; letter-spacing: 0.5px;">Key Perks & Quotas</div>
                                
                                <div class="d-flex flex-column gap-2 small">
                                    <!-- 24/7 Dedicated Support -->
                                    <div class="d-flex align-items-center justify-content-between p-2 rounded {{ $plan->has_24_7_support ? 'bg-success-subtle text-success' : 'bg-light text-muted' }}">
                                        <span><i class="bi {{ $plan->has_24_7_support ? 'bi-headset text-success' : 'bi-slash-circle' }} me-2"></i> <strong>24/7 Priority Support</strong></span>
                                        <span class="fw-bold">{{ $plan->has_24_7_support ? 'ENABLED' : 'No' }}</span>
                                    </div>

                                    <!-- Sponsored Boosts Quota -->
                                    <div class="d-flex align-items-center justify-content-between p-2 rounded {{ $plan->sponsored_products_quota > 0 ? 'bg-warning-subtle text-warning-emphasis' : 'bg-light text-muted' }}">
                                        <span><i class="bi bi-rocket-takeoff-fill me-2 {{ $plan->sponsored_products_quota > 0 ? 'text-warning' : 'text-muted' }}"></i> <strong>Sponsored Products Quota</strong></span>
                                        <span class="fw-bold">{{ $plan->sponsored_products_quota > 0 ? $plan->sponsored_products_quota . ' / month' : '0' }}</span>
                                    </div>

                                    <!-- Commission Rate -->
                                    <div class="d-flex align-items-center justify-content-between p-2 rounded bg-light">
                                        <span><i class="bi bi-percent me-2 text-primary"></i> <strong>Platform Commission</strong></span>
                                        <span class="fw-bold text-dark">{{ $plan->commission_rate }}%</span>
                                    </div>

                                    <!-- Product Limit -->
                                    <div class="d-flex align-items-center justify-content-between p-2 rounded bg-light">
                                        <span><i class="bi bi-box-seam me-2 text-secondary"></i> <strong>Catalog Limit</strong></span>
                                        <span class="fw-bold text-dark">{{ $plan->product_limit < 0 ? 'Unlimited' : $plan->product_limit . ' Items' }}</span>
                                    </div>

                                    <!-- Verified Badge -->
                                    <div class="d-flex align-items-center justify-content-between p-2 rounded bg-light">
                                        <span><i class="bi bi-shield-check me-2 text-success"></i> <strong>Verified Partner Badge</strong></span>
                                        <span class="fw-bold">{{ $plan->verified_badge_included ? 'Included' : 'Standard' }}</span>
                                    </div>

                                    <!-- Custom F.O.R. Logistics -->
                                    <div class="d-flex align-items-center justify-content-between p-2 rounded bg-light">
                                        <span><i class="bi bi-truck me-2 text-info"></i> <strong>Custom F.O.R. Logistics</strong></span>
                                        <span class="fw-bold">{{ $plan->custom_logistics_enabled ? 'Yes' : 'No' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Custom Dynamic Feature Bullet Points -->
                            @if(!empty($plan->features) && count($plan->features) > 0)
                                <div class="mb-3">
                                    <div class="fw-bold text-uppercase small text-muted mb-2" style="font-size: 0.72rem; letter-spacing: 0.5px;">All Included Features ({{ count($plan->features) }})</div>
                                    <ul class="list-unstyled small mb-0 d-flex flex-column gap-1.5">
                                        @foreach($plan->features as $feature)
                                            <li class="d-flex align-items-start gap-2 text-secondary">
                                                <i class="bi bi-check2-circle text-success mt-0.5"></i>
                                                <span>{{ $feature }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <!-- Card Footer Controls -->
                        <div class="border-top pt-3 mt-3">
                            <div class="d-flex align-items-center justify-content-between gap-2">
                                <button type="button" class="btn btn-sm btn-outline-primary flex-fill" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editPlanModal{{ $plan->id }}">
                                    <i class="bi bi-pencil-square me-1"></i> Edit Plan
                                </button>
                                
                                <form action="{{ route('admin.subscriptions.plans.toggle', $plan->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $plan->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}" title="Toggle Status">
                                        <i class="bi {{ $plan->is_active ? 'bi-pause-fill' : 'bi-play-fill' }}"></i>
                                    </button>
                                </form>

                                @if($plan->subscriptions_count == 0)
                                    <form action="{{ route('admin.subscriptions.plans.delete', $plan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this plan?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Plan">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Plan Modal -->
            <div class="modal fade" id="editPlanModal{{ $plan->id }}" tabindex="-1" aria-labelledby="editPlanModalLabel{{ $plan->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content border-0 shadow-lg rounded-4">
                        <form action="{{ route('admin.subscriptions.plans.update', $plan->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-header bg-dark text-white rounded-top-4 px-4 py-3">
                                <h5 class="modal-title fw-bold" id="editPlanModalLabel{{ $plan->id }}">
                                    <i class="bi bi-sliders me-2 text-warning"></i> Edit Plan: {{ $plan->name }}
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="row g-3">
                                    <div class="col-md-8">
                                        <label class="form-label fw-semibold small">Plan Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name', $plan->name) }}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold small">Badge Tag (e.g. Popular, Best Value)</label>
                                        <input type="text" name="badge_title" class="form-control" value="{{ old('badge_title', $plan->badge_title) }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold small">Description</label>
                                        <textarea name="description" class="form-control" rows="2">{{ old('description', $plan->description) }}</textarea>
                                    </div>

                                    <!-- Pricing Row -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small">Monthly Price (₹) <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">₹</span>
                                            <input type="number" step="0.01" name="monthly_price" class="form-control" value="{{ old('monthly_price', $plan->monthly_price) }}" required>
                                        </div>
                                        <div class="form-text small">Enter 0 for free plan.</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small">Yearly Price (₹) <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">₹</span>
                                            <input type="number" step="0.01" name="yearly_price" class="form-control" value="{{ old('yearly_price', $plan->yearly_price) }}" required>
                                        </div>
                                    </div>

                                    <!-- Key Admin Controlled Perks -->
                                    <div class="col-12">
                                        <div class="p-3 bg-light rounded-3 border">
                                            <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-shield-fill-check text-success me-1"></i> Admin Controlled Tier Quotas</h6>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold small">Commission Rate (%) <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.01" min="0" max="100" name="commission_rate" class="form-control" value="{{ old('commission_rate', $plan->commission_rate) }}" required>
                                                        <span class="input-group-text bg-light">%</span>
                                                    </div>
                                                    <div class="form-text small">0% = Zero commission</div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold small">Sponsored Boost Products / Month <span class="text-danger">*</span></label>
                                                    <input type="number" min="0" name="sponsored_products_quota" class="form-control" value="{{ old('sponsored_products_quota', $plan->sponsored_products_quota) }}">
                                                    <div class="form-text small">Free promotional tokens credited monthly</div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold small">Catalog Product Limit <span class="text-danger">*</span></label>
                                                    <input type="number" name="product_limit" class="form-control" value="{{ old('product_limit', $plan->product_limit) }}" required>
                                                    <div class="form-text small">-1 for Unlimited Products</div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold small">Buyer RFQ & Quotation Access</label>
                                                    <select name="rfq_access_level" class="form-select">
                                                        <option value="standard" {{ $plan->rfq_access_level === 'standard' ? 'selected' : '' }}>Standard Access</option>
                                                        <option value="priority" {{ $plan->rfq_access_level === 'priority' ? 'selected' : '' }}>Priority Dispatch</option>
                                                        <option value="unlimited_instant" {{ $plan->rfq_access_level === 'unlimited_instant' ? 'selected' : '' }}>Unlimited & Instant RFQ Access</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <hr class="my-3">

                                            <div class="row g-2">
                                                <div class="col-md-4">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" name="has_24_7_support" value="1" id="support247Edit{{ $plan->id }}" {{ $plan->has_24_7_support ? 'checked' : '' }}>
                                                        <label class="form-check-label fw-semibold small" for="support247Edit{{ $plan->id }}">24/7 Priority Support</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" name="verified_badge_included" value="1" id="badgeEdit{{ $plan->id }}" {{ $plan->verified_badge_included ? 'checked' : '' }}>
                                                        <label class="form-check-label fw-semibold small" for="badgeEdit{{ $plan->id }}">Verified Partner Badge</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" name="custom_logistics_enabled" value="1" id="logisticsEdit{{ $plan->id }}" {{ $plan->custom_logistics_enabled ? 'checked' : '' }}>
                                                        <label class="form-check-label fw-semibold small" for="logisticsEdit{{ $plan->id }}">Custom F.O.R. Logistics</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Dynamic Features List -->
                                    <div class="col-12">
                                        <label class="form-label fw-semibold small">Dynamic Feature Bullet Points (One per line)</label>
                                        <textarea name="features_text" class="form-control font-monospace small" rows="5" placeholder="Dedicated Account Manager&#10;24/7 Phone & WhatsApp Support Desk&#10;Free Monthly Sponsored Boosts&#10;0% Zero Commission on Transactions">{{ implode("\n", $plan->features ?? []) }}</textarea>
                                        <div class="form-text small">Enter each feature on a new line. You can update these anytime.</div>
                                    </div>

                                    <!-- Extra options -->
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="featuredEdit{{ $plan->id }}" {{ $plan->is_featured ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold small" for="featuredEdit{{ $plan->id }}">Mark as Popular / Recommended</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="activeEdit{{ $plan->id }}" {{ $plan->is_active ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold small" for="activeEdit{{ $plan->id }}">Plan is Active & Visible</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer bg-light px-4 py-3">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success fw-bold px-4">
                                    <i class="bi bi-save me-1"></i> Save Plan Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                    <i class="bi bi-patch-exclamation text-muted display-4 mb-3"></i>
                    <h5 class="fw-bold text-dark">No Subscription Plans Found</h5>
                    <p class="text-muted small mb-4">You have not created any subscription plans for this category yet.</p>
                    <div>
                        <button type="button" class="btn btn-garudo-primary" data-bs-toggle="modal" data-bs-target="#createPlanModal">
                            <i class="bi bi-plus-circle me-1"></i> Create Your First Plan
                        </button>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- Create Plan Modal -->
<div class="modal fade" id="createPlanModal" tabindex="-1" aria-labelledby="createPlanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form action="{{ route('admin.subscriptions.plans.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-dark text-white rounded-top-4 px-4 py-3">
                    <h5 class="modal-title fw-bold" id="createPlanModalLabel">
                        <i class="bi bi-plus-circle-fill me-2 text-success"></i> Create New Subscription Plan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Target Role <span class="text-danger">*</span></label>
                            <select name="target_role" class="form-select" required>
                                <option value="vendor" selected>Vendor / Seller</option>
                                <option value="customer">Buyer / Customer Pro</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Badge Tag (e.g. Popular, Titanium, Pro)</label>
                            <input type="text" name="badge_title" class="form-control" placeholder="e.g. Most Popular">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Plan Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Enterprise Titanium Tier" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Short Description</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Brief overview of who this plan is tailored for..."></textarea>
                        </div>

                        <!-- Pricing Row -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Monthly Price (₹) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">₹</span>
                                <input type="number" step="0.01" name="monthly_price" class="form-control" value="0" required>
                            </div>
                            <div class="form-text small">Enter 0 for free plan.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Yearly Price (₹) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">₹</span>
                                <input type="number" step="0.01" name="yearly_price" class="form-control" value="0" required>
                            </div>
                        </div>

                        <!-- Key Admin Controlled Perks -->
                        <div class="col-12">
                            <div class="p-3 bg-light rounded-3 border">
                                <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-shield-fill-check text-success me-1"></i> Admin Controlled Tier Quotas</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small">Commission Rate (%) <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" step="0.01" min="0" max="100" name="commission_rate" class="form-control" value="0" required>
                                            <span class="input-group-text bg-light">%</span>
                                        </div>
                                        <div class="form-text small">0% = Zero commission</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small">Sponsored Boost Products / Month <span class="text-danger">*</span></label>
                                        <input type="number" min="0" name="sponsored_products_quota" class="form-control" value="0">
                                        <div class="form-text small">Free promotional tokens credited monthly</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small">Catalog Product Limit <span class="text-danger">*</span></label>
                                        <input type="number" name="product_limit" class="form-control" value="50" required>
                                        <div class="form-text small">-1 for Unlimited Products</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small">Buyer RFQ & Quotation Access</label>
                                        <select name="rfq_access_level" class="form-select">
                                            <option value="standard" selected>Standard Access</option>
                                            <option value="priority">Priority Dispatch</option>
                                            <option value="unlimited_instant">Unlimited & Instant RFQ Access</option>
                                        </select>
                                    </div>
                                </div>

                                <hr class="my-3">

                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="has_24_7_support" value="1" id="support247Create" checked>
                                            <label class="form-check-label fw-semibold small" for="support247Create">24/7 Priority Support</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="verified_badge_included" value="1" id="badgeCreate">
                                            <label class="form-check-label fw-semibold small" for="badgeCreate">Verified Partner Badge</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="custom_logistics_enabled" value="1" id="logisticsCreate">
                                            <label class="form-check-label fw-semibold small" for="logisticsCreate">Custom F.O.R. Logistics</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dynamic Features List -->
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Dynamic Feature Bullet Points (One per line)</label>
                            <textarea name="features_text" class="form-control font-monospace small" rows="5" placeholder="Dedicated Account Manager&#10;24/7 Phone & WhatsApp Support Desk&#10;Free Monthly Sponsored Boosts&#10;0% Zero Commission on Transactions"></textarea>
                            <div class="form-text small">Enter each feature on a new line. You can edit these anytime.</div>
                        </div>

                        <!-- Extra options -->
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="featuredCreate">
                                <label class="form-check-label fw-semibold small" for="featuredCreate">Mark as Popular / Recommended</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="activeCreate" checked>
                                <label class="form-check-label fw-semibold small" for="activeCreate">Plan is Active & Visible</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-garudo-primary fw-bold px-4">
                        <i class="bi bi-check-lg me-1"></i> Create Plan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
