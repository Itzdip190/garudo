@extends('layouts.admin')

@section('title', 'Vendor Details - ' . ($vendor->company_name ?? $vendor->name))

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.vendors.index') }}" class="btn btn-light btn-sm rounded-pill px-3 mb-3">
        <i class="bi bi-arrow-left me-1"></i> Back to All Vendors
    </a>
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary text-white rounded-4 d-flex align-items-center justify-content-center fw-bold fs-3" style="width: 64px; height: 64px;">
                {{ strtoupper(substr($vendor->company_name ?? $vendor->name, 0, 2)) }}
            </div>
            <div>
                <h1 class="page-header-title mb-1">{{ $vendor->company_name ?? $vendor->name }}</h1>
                <div class="d-flex align-items-center gap-2">
                    <span class="pill-status pill-{{ strtolower($vendor->approval_status) }}">{{ ucfirst($vendor->approval_status) }}</span>
                    <span class="pill-status pill-{{ strtolower($vendor->status) }}">{{ ucfirst($vendor->status) }}</span>
                    <span class="text-muted small">• Registered {{ $vendor->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.vendors.kyc.review', $vendor->id) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold">
                <i class="bi bi-shield-check me-1"></i> KYC & Compliance Workbench
            </a>

            @if($vendor->activeCertificate)
                <a href="{{ $vendor->activeCertificate->verification_url }}" target="_blank" class="btn btn-outline-success btn-sm rounded-pill px-3 fw-bold">
                    <i class="bi bi-award-fill me-1"></i> Digital Certificate
                </a>
            @endif

            @if($vendor->approval_status !== 'approved')
                <form action="{{ route('admin.vendors.approve', $vendor->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm rounded-pill px-4 fw-bold">
                        <i class="bi bi-check-circle-fill me-1"></i> Approve Vendor
                    </button>
                </form>
                <button type="button" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#rejectModal">
                    <i class="bi bi-x-circle-fill me-1"></i> Reject
                </button>
            @endif

            <button type="button" class="btn btn-outline-info btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#resetPassModal">
                <i class="bi bi-key me-1"></i> Reset Password
            </button>

            <form action="{{ route('admin.vendors.toggle_status', $vendor->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-warning btn-sm rounded-pill px-3">
                    <i class="bi bi-slash-circle me-1"></i> {{ $vendor->status === 'active' ? 'Suspend Store' : 'Reactivate' }}
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPassModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.vendors.reset_password', $vendor->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold"><i class="bi bi-key-fill text-warning me-2"></i>Reset Password for {{ $vendor->company_name ?? $vendor->name }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">Enter a new secure password for this seller. They will use this password to sign into the Vendor Portal.</p>
                    <div class="mb-3">
                        <label class="form-label fw-bold">New Password</label>
                        <input type="text" name="password" class="form-control" placeholder="Enter new password" value="Vendor@{{ rand(1000, 9999) }}#" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-garudo-primary fw-bold">Update & Hash Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($vendor->rejection_reason)
    <div class="alert alert-danger mb-4 rounded-3 border-0 shadow-sm">
        <h6 class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i>Rejection Reason Recorded:</h6>
        <div>{{ $vendor->rejection_reason }}</div>
    </div>
@endif

<div class="row g-4">
    <!-- Left Column: Business & Tax Information -->
    <div class="col-lg-4">
        <div class="garudo-card p-4 mb-4">
            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Enterprise & Tax Profile</h6>
            
            <div class="mb-3">
                <div class="text-muted small">GSTIN Number</div>
                <div class="fw-bold fs-6"><code>{{ $vendor->gstin ?? 'Not Provided' }}</code></div>
            </div>

            <div class="mb-3">
                <div class="text-muted small">PAN Number</div>
                <div class="fw-bold">{{ $vendor->pan_number ?? 'Not Provided' }}</div>
            </div>

            <div class="mb-3">
                <div class="text-muted small">Business Structure</div>
                <div class="fw-semibold text-dark">{{ ucfirst($vendor->business_type ?? 'Manufacturer') }}</div>
            </div>

            <div class="mb-3">
                <div class="text-muted small">Year Established</div>
                <div class="fw-semibold text-dark">{{ $vendor->year_established ?? 'N/A' }}</div>
            </div>

            @if($vendor->company_website)
                <div class="mb-3">
                    <div class="text-muted small">Corporate Website</div>
                    <a href="{{ $vendor->company_website }}" target="_blank" class="fw-semibold text-primary text-decoration-none">
                        {{ $vendor->company_website }} <i class="bi bi-box-arrow-up-right small"></i>
                    </a>
                </div>
            @endif
        </div>

        <div class="garudo-card p-4">
            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Primary Contact</h6>
            
            <div class="mb-3">
                <div class="text-muted small">Contact Person</div>
                <div class="fw-bold">{{ $vendor->name }}</div>
            </div>

            <div class="mb-3">
                <div class="text-muted small">Email Address</div>
                <div class="fw-bold">{{ $vendor->email }}</div>
            </div>

            <div class="mb-3">
                <div class="text-muted small">Phone / Mobile</div>
                <div class="fw-bold">{{ $vendor->phone ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Right Column: Products & Activity -->
    <div class="col-lg-8">
        <div class="garudo-card p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold text-dark mb-0">Products Catalog by this Seller ({{ $vendor->products->count() }})</h6>
                <a href="{{ route('admin.products.index', ['vendor_id' => $vendor->id]) }}" class="btn btn-light btn-sm text-primary">View Full Catalog</a>
            </div>

            @if($vendor->products->count() > 0)
                <div class="table-responsive">
                    <table class="table admin-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>SKU</th>
                                <th>Selling Price</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vendor->products as $product)
                                <tr>
                                    <td class="fw-bold text-dark">{{ \Illuminate\Support\Str::limit($product->name, 35) }}</td>
                                    <td><code>{{ $product->sku ?? 'N/A' }}</code></td>
                                    <td class="fw-bold text-dark">₹{{ number_format($product->selling_price, 2) }}</td>
                                    <td>
                                        <span class="pill-status pill-{{ strtolower($product->approval_status) }}">
                                            {{ ucfirst($product->approval_status) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-light btn-sm rounded-pill px-3">Inspect</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-box-seam fs-2 mb-2 d-block"></i>
                    <div>This seller hasn't listed any products yet.</div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.vendors.reject', $vendor->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold">Reject Vendor Application</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Reason for Rejection / Missing Documents:</label>
                        <textarea name="rejection_reason" class="form-control" rows="4" placeholder="Explain why the vendor application is rejected (e.g. Invalid GSTIN certificate, blurred KYC document)..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger fw-bold">Submit Rejection</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
