@extends('layouts.vendor')

@section('title', 'Product Sample Requests')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h4 class="fw-bold mb-0" style="color: var(--garudo-text-main);">Product Samples & Trial Dispatch</h4>
            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 small fw-semibold">
                <i class="bi bi-box2-heart-fill me-1"></i>Sample Desk
            </span>
        </div>
        <p class="text-muted mb-0 small">Manage test swatches, trial specimens, dispatch tracking, and convert sample leads into high-volume wholesale commercial purchase orders.</p>
    </div>
    <div class="d-flex flex-wrap align-items-center gap-2">
        <a href="{{ route('vendor.products.create') }}" class="btn btn-outline-success btn-sm px-3 fw-semibold" style="border-radius: 8px;">
            <i class="bi bi-plus-circle me-1"></i> Add Product with Samples
        </a>
        <a href="{{ route('vendor.messages.index') }}" class="btn btn-light border btn-sm px-3 fw-semibold" style="border-radius: 8px;">
            <i class="bi bi-chat-dots me-1"></i> Buyer Inquiries
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4 rounded-3 border-0 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show mb-4 rounded-3 border-0 shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- STATS / KPI CARDS -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card border-0 shadow-sm p-3 rounded-3 h-100" style="background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);">
            <div class="text-muted small fw-bold text-uppercase" style="font-size: 0.7rem;">Total Requests</div>
            <div class="fs-4 fw-bold text-dark mt-1">{{ $totalRequests }}</div>
            <span class="text-muted" style="font-size: 0.7rem;">Lifetime Sample Orders</span>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card border-0 shadow-sm p-3 rounded-3 h-100" style="background: linear-gradient(135deg, #FFFBEB 0%, #FEF3C7 100%);">
            <div class="text-warning small fw-bold text-uppercase" style="font-size: 0.7rem;">Pending Review</div>
            <div class="fs-4 fw-bold text-warning mt-1">{{ $pendingCount }}</div>
            <span class="text-muted" style="font-size: 0.7rem;">Requires Dispatch Action</span>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card border-0 shadow-sm p-3 rounded-3 h-100" style="background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);">
            <div class="text-primary small fw-bold text-uppercase" style="font-size: 0.7rem;">In Transit</div>
            <div class="fs-4 fw-bold text-primary mt-1">{{ $dispatchedCount }}</div>
            <span class="text-muted" style="font-size: 0.7rem;">Dispatched via Courier</span>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card border-0 shadow-sm p-3 rounded-3 h-100" style="background: linear-gradient(135deg, #F0FDF4 0%, #DCFCE7 100%);">
            <div class="text-success small fw-bold text-uppercase" style="font-size: 0.7rem;">Delivered</div>
            <div class="fs-4 fw-bold text-success mt-1">{{ $deliveredCount }}</div>
            <span class="text-muted" style="font-size: 0.7rem;">Received by Buyer</span>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card border-0 shadow-sm p-3 rounded-3 h-100" style="background: linear-gradient(135deg, #FAF5FF 0%, #F3E8FF 100%);">
            <div class="text-purple small fw-bold text-uppercase" style="color: #7C3AED; font-size: 0.7rem;">Bulk Conversions</div>
            <div class="fs-4 fw-bold mt-1" style="color: #6D28D9;">{{ $convertedCount }}</div>
            <span class="text-muted" style="font-size: 0.7rem;">Bought Bulk Products</span>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card border-0 shadow-sm p-3 rounded-3 h-100" style="background: linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%);">
            <div class="text-success small fw-bold text-uppercase" style="font-size: 0.7rem;">Conversion Rate</div>
            <div class="fs-4 fw-bold text-success mt-1">{{ $conversionRate }}%</div>
            <span class="text-muted" style="font-size: 0.7rem;">Sample to Bulk PO</span>
        </div>
    </div>
</div>

<!-- FILTERS & TABS -->
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-header bg-white border-bottom p-3">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <!-- Navigation Tabs -->
            <ul class="nav nav-pills flex-nowrap overflow-auto pb-2 pb-md-0" style="gap: 6px;">
                <li class="nav-item">
                    <a class="nav-link btn-sm py-1.5 px-3 {{ $status === 'all' ? 'active bg-success' : 'text-dark bg-light' }}" href="{{ route('vendor.samples.index', ['status' => 'all', 'q' => $search]) }}" style="border-radius: 8px;">
                        All <span class="badge {{ $status === 'all' ? 'bg-white text-success' : 'bg-secondary' }} ms-1">{{ $counts['all'] }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn-sm py-1.5 px-3 {{ $status === 'pending' ? 'active bg-warning text-dark' : 'text-dark bg-light' }}" href="{{ route('vendor.samples.index', ['status' => 'pending', 'q' => $search]) }}" style="border-radius: 8px;">
                        Pending <span class="badge {{ $status === 'pending' ? 'bg-dark text-white' : 'bg-warning text-dark' }} ms-1">{{ $counts['pending'] }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn-sm py-1.5 px-3 {{ $status === 'approved' ? 'active bg-info text-white' : 'text-dark bg-light' }}" href="{{ route('vendor.samples.index', ['status' => 'approved', 'q' => $search]) }}" style="border-radius: 8px;">
                        Approved <span class="badge {{ $status === 'approved' ? 'bg-white text-info' : 'bg-info text-white' }} ms-1">{{ $counts['approved'] }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn-sm py-1.5 px-3 {{ $status === 'dispatched' ? 'active bg-primary' : 'text-dark bg-light' }}" href="{{ route('vendor.samples.index', ['status' => 'dispatched', 'q' => $search]) }}" style="border-radius: 8px;">
                        Dispatched <span class="badge {{ $status === 'dispatched' ? 'bg-white text-primary' : 'bg-primary' }} ms-1">{{ $counts['dispatched'] }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn-sm py-1.5 px-3 {{ $status === 'delivered' ? 'active bg-success' : 'text-dark bg-light' }}" href="{{ route('vendor.samples.index', ['status' => 'delivered', 'q' => $search]) }}" style="border-radius: 8px;">
                        Delivered <span class="badge {{ $status === 'delivered' ? 'bg-white text-success' : 'bg-success' }} ms-1">{{ $counts['delivered'] }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn-sm py-1.5 px-3 {{ $status === 'converted' ? 'active bg-dark text-white' : 'text-dark bg-light' }}" href="{{ route('vendor.samples.index', ['status' => 'converted', 'q' => $search]) }}" style="border-radius: 8px;">
                        Converted <span class="badge {{ $status === 'converted' ? 'bg-warning text-dark' : 'bg-secondary' }} ms-1">{{ $counts['converted'] }}</span>
                    </a>
                </li>
            </ul>

            <!-- Search Form -->
            <form action="{{ route('vendor.samples.index') }}" method="GET" class="d-flex gap-2">
                <input type="hidden" name="status" value="{{ $status }}">
                <div class="input-group input-group-sm" style="min-width: 250px;">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="q" class="form-control border-start-0" placeholder="Search Request #, Buyer, Product..." value="{{ $search }}">
                    @if($search)
                        <a href="{{ route('vendor.samples.index', ['status' => $status]) }}" class="btn btn-outline-secondary"><i class="bi bi-x"></i></a>
                    @endif
                </div>
                <button type="submit" class="btn btn-dark btn-sm">Filter</button>
            </form>
        </div>
    </div>

    <!-- SAMPLES DATA TABLE -->
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light text-muted small text-uppercase">
                <tr>
                    <th class="ps-3 py-3" style="width: 140px;">Request #</th>
                    <th style="min-width: 200px;">Product & Specimen</th>
                    <th style="min-width: 190px;">Buyer / Site Contact</th>
                    <th>Type & Pricing</th>
                    <th>Status</th>
                    <th>Logistics & Tracking</th>
                    <th class="text-end pe-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($samples as $sample)
                    <tr>
                        <td class="ps-3">
                            <a href="{{ route('vendor.samples.show', $sample->id) }}" class="fw-bold text-decoration-none text-dark d-block">
                                {{ $sample->request_number }}
                            </a>
                            <small class="text-muted" style="font-size: 0.72rem;">{{ $sample->created_at->format('d M Y, h:i A') }}</small>
                        </td>

                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $sample->product->primaryImage ? asset('storage/'.$sample->product->primaryImage->file_path) : 'https://placehold.co/80x80?text=Sample' }}" 
                                     class="rounded border" style="width: 45px; height: 45px; object-fit: cover;" alt="{{ $sample->product->name ?? 'Sample' }}">
                                <div>
                                    <a href="{{ route('products.show', $sample->product->slug ?? '#') }}" target="_blank" class="fw-semibold text-dark text-decoration-none small d-block" style="max-width: 220px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $sample->product->name ?? 'Deleted Product' }}
                                    </a>
                                    <span class="badge bg-light text-muted border small" style="font-size: 0.7rem;">
                                        Qty: <strong>{{ $sample->quantity }}</strong> | {{ $sample->product->sample_unit_description ?? 'Standard Specimen' }}
                                    </span>
                                </div>
                            </div>
                        </td>

                        <td>
                            <div class="fw-semibold text-dark small">{{ $sample->buyer_name }}</div>
                            @if($sample->company_name)
                                <div class="text-muted small" style="font-size: 0.73rem;"><i class="bi bi-building me-1"></i>{{ $sample->company_name }}</div>
                            @endif
                            <div class="text-muted small" style="font-size: 0.73rem;">
                                <i class="bi bi-telephone me-1 text-success"></i>{{ $sample->buyer_phone }} | <i class="bi bi-geo-alt me-1"></i>{{ $sample->city }}, {{ $sample->state }}
                            </div>
                            @if($sample->project_type)
                                <span class="badge bg-secondary-subtle text-dark border small mt-1" style="font-size: 0.68rem;">
                                    <i class="bi bi-tools me-1"></i>{{ $sample->project_type }}
                                </span>
                            @endif
                        </td>

                        <td>
                            {!! $sample->getSampleTypeBadge() !!}
                            <div class="mt-1 small">
                                @if($sample->sample_type === 'free')
                                    <span class="text-success fw-bold">₹0.00 (Free)</span>
                                @else
                                    <span class="fw-bold text-dark">Total: ₹{{ number_format($sample->total_amount, 2) }}</span>
                                    {!! $sample->getPaymentStatusBadge() !!}
                                @endif
                            </div>
                        </td>

                        <td>
                            {!! $sample->getStatusBadge() !!}
                            @if($sample->status === 'converted_to_order' && $sample->convertedOrder)
                                <a href="{{ route('vendor.orders.show', $sample->convertedOrder->id) }}" class="small text-success d-block fw-semibold mt-1">
                                    <i class="bi bi-link-45deg"></i> PO #{{ $sample->convertedOrder->order_number }}
                                </a>
                            @endif
                        </td>

                        <td>
                            @if($sample->tracking_number)
                                <div class="small fw-semibold text-dark">
                                    <i class="bi bi-truck text-primary me-1"></i>{{ $sample->courier_partner }}
                                </div>
                                <code class="small text-muted d-block" style="font-size: 0.75rem;">{{ $sample->tracking_number }}</code>
                                @if($sample->dispatched_at)
                                    <span class="text-muted" style="font-size: 0.7rem;">Sent: {{ $sample->dispatched_at->format('d M') }}</span>
                                @endif
                            @else
                                <span class="badge bg-light text-muted border small">Not Dispatched</span>
                            @endif
                        </td>

                        <td class="text-end pe-3">
                            <div class="dropdown">
                                <button class="btn btn-light border btn-sm dropdown-toggle px-2 py-1" type="button" data-bs-toggle="dropdown">
                                    Actions
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm small">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('vendor.samples.show', $sample->id) }}">
                                            <i class="bi bi-eye text-primary me-2"></i> View Full Details
                                        </a>
                                    </li>

                                    @if($sample->status === 'pending')
                                        <li>
                                            <form action="{{ route('vendor.samples.approve', $sample->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="dropdown-item text-success">
                                                    <i class="bi bi-check-circle me-2"></i> Approve Sample
                                                </button>
                                            </form>
                                        </li>
                                    @endif

                                    @if(in_array($sample->status, ['pending', 'approved']))
                                        <li>
                                            <button type="button" class="dropdown-item text-primary" data-bs-toggle="modal" data-bs-target="#dispatchModal{{ $sample->id }}">
                                                <i class="bi bi-truck me-2"></i> Dispatch with Courier
                                            </button>
                                        </li>
                                    @endif

                                    @if($sample->status === 'dispatched')
                                        <li>
                                            <form action="{{ route('vendor.samples.deliver', $sample->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="dropdown-item text-success">
                                                    <i class="bi bi-box2-heart me-2"></i> Mark as Delivered
                                                </button>
                                            </form>
                                        </li>
                                    @endif

                                    <li><hr class="dropdown-divider"></li>

                                    <li>
                                        <form action="{{ route('vendor.samples.convert-quote', $sample->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-warning fw-semibold">
                                                <i class="bi bi-file-earmark-text me-2"></i> Convert to Bulk Quotation
                                            </button>
                                        </form>
                                    </li>

                                    @if($sample->status === 'pending')
                                        <li>
                                            <button type="button" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $sample->id }}">
                                                <i class="bi bi-x-circle me-2"></i> Reject Request
                                            </button>
                                        </li>
                                    @endif
                                </ul>
                            </div>

                            <!-- Modal: Quick Dispatch -->
                            <div class="modal fade" id="dispatchModal{{ $sample->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-4 border-0 shadow">
                                        <div class="modal-header border-bottom">
                                            <h6 class="modal-title fw-bold text-dark">
                                                <i class="bi bi-truck text-primary me-2"></i>Dispatch Sample #{{ $sample->request_number }}
                                            </h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('vendor.samples.dispatch', $sample->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body text-start p-4">
                                                <div class="p-3 bg-light rounded-3 mb-3 border">
                                                    <div class="small text-muted">Recipient:</div>
                                                    <div class="fw-bold text-dark">{{ $sample->buyer_name }} ({{ $sample->buyer_phone }})</div>
                                                    <div class="small text-muted">{{ $sample->delivery_address }}, {{ $sample->city }}, {{ $sample->state }} - {{ $sample->pincode }}</div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold small text-dark">Courier / Logistics Partner <span class="text-danger">*</span></label>
                                                    <select name="courier_partner" class="form-select" required>
                                                        <option value="Delhivery Express">Delhivery Express</option>
                                                        <option value="BlueDart Air">BlueDart Air</option>
                                                        <option value="DTDC Courier">DTDC Courier</option>
                                                        <option value="Trackon Courier">Trackon Courier</option>
                                                        <option value="India Post Speed Post">India Post Speed Post</option>
                                                        <option value="Self / Local Courier">Self / Local Delivery Agent</option>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold small text-dark">Tracking / AWB Number <span class="text-danger">*</span></label>
                                                    <input type="text" name="tracking_number" class="form-control" placeholder="e.g. BLUEDART-8492019" required>
                                                </div>

                                                <div class="mb-2">
                                                    <label class="form-label fw-semibold small text-dark">Dispatch / Packing Notes</label>
                                                    <textarea name="vendor_notes" rows="2" class="form-control" placeholder="e.g. Packaged in bubble test pouch. Lab test certificate included."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top bg-light">
                                                <button type="button" class="btn btn-light border btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary btn-sm px-4 fw-bold">
                                                    <i class="bi bi-send-check me-1"></i> Confirm Dispatch
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal: Reject Request -->
                            @if($sample->status === 'pending')
                                <div class="modal fade" id="rejectModal{{ $sample->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 border-0 shadow">
                                            <div class="modal-header border-bottom">
                                                <h6 class="modal-title fw-bold text-danger">
                                                    <i class="bi bi-x-circle me-2"></i>Reject Sample Request #{{ $sample->request_number }}
                                                </h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('vendor.samples.reject', $sample->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body text-start p-4">
                                                    <p class="small text-muted mb-3">Please specify a clear reason for rejecting this sample request so the buyer is informed.</p>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold small text-dark">Rejection Reason <span class="text-danger">*</span></label>
                                                        <select name="rejection_reason" class="form-select mb-2" required>
                                                            <option value="Sample stock currently exhausted. Contact for bulk direct delivery.">Sample stock currently exhausted</option>
                                                            <option value="Delivery address out of courier coverage area.">Address out of courier coverage</option>
                                                            <option value="Duplicate or unverified contact details.">Unverified contact / phone number</option>
                                                            <option value="Material requires site inspection before trial dispatch.">Site inspection required first</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-top bg-light">
                                                    <button type="button" class="btn btn-light border btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger btn-sm px-4 fw-bold">Confirm Rejection</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-box2 text-muted" style="font-size: 3rem;"></i>
                            <h6 class="fw-bold mt-2 text-dark">No Sample Requests Found</h6>
                            <p class="small mb-3">When buyers request test swatches or specimen kits on your products, they will appear here.</p>
                            <a href="{{ route('vendor.products.index') }}" class="btn btn-success btn-sm px-3 fw-bold" style="background-color: var(--garudo-primary); border: none;">
                                <i class="bi bi-box-seam me-1"></i> Enable Samples on Products
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($samples->hasPages())
        <div class="card-footer bg-white border-top p-3">
            {{ $samples->links() }}
        </div>
    @endif
</div>
@endsection
