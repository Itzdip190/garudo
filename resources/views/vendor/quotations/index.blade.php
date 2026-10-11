@extends('layouts.vendor')

@section('title', 'Quotations & B2B Proposals')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h4 class="fw-bold mb-0" style="color: var(--garudo-text-main);">Quotations & RFQ Proposals</h4>
            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1 small fw-semibold">B2B Trade Desk</span>
        </div>
        <p class="text-muted mb-0 small">Review incoming buyer RFQs, configure tailored price proposals (F.O.R. freight, GST, payment terms), and convert accepted quotes to orders.</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show p-3 small mb-4" role="alert" style="border-radius: 12px;">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill fs-5 text-success"></i>
            <div><strong>Success:</strong> {{ session('success') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Top KPI Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card-custom p-3 h-100 shadow-sm border-0">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold">Total RFQs Received</span>
                <div class="bg-primary bg-opacity-10 p-2 rounded-3 text-primary"><i class="bi bi-file-earmark-text-fill fs-5"></i></div>
            </div>
            <h3 class="fw-bold mb-1 text-dark">{{ number_format($totalQuotations) }}</h3>
            <span class="text-muted" style="font-size: 0.75rem;">Lifetime requests</span>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card-custom p-3 h-100 shadow-sm border-0">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold">Pending Response</span>
                <div class="bg-warning bg-opacity-10 p-2 rounded-3 text-warning"><i class="bi bi-hourglass-split fs-5"></i></div>
            </div>
            <h3 class="fw-bold mb-1 text-warning">{{ number_format($pendingCount) }}</h3>
            <span class="text-muted" style="font-size: 0.75rem;">Awaiting your price quote</span>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card-custom p-3 h-100 shadow-sm border-0">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold">Proposals Sent / Quoted</span>
                <div class="bg-info bg-opacity-10 p-2 rounded-3 text-info"><i class="bi bi-send-check-fill fs-5"></i></div>
            </div>
            <h3 class="fw-bold mb-1 text-dark">{{ number_format($quotedCount) }}</h3>
            <span class="text-muted" style="font-size: 0.75rem;">Active buyer proposals</span>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card-custom p-3 h-100 shadow-sm border-0">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold">Total Quoted Pipeline</span>
                <div class="bg-success bg-opacity-10 p-2 rounded-3 text-success"><i class="bi bi-currency-rupee fs-5"></i></div>
            </div>
            <h3 class="fw-bold mb-1 text-success">₹{{ number_format($totalQuotedValue, 2) }}</h3>
            <span class="text-muted" style="font-size: 0.75rem;">{{ $convertedCount }} Converted to orders</span>
        </div>
    </div>
</div>

<!-- Filters & Search Card -->
<div class="card-custom p-3 mb-4 shadow-sm">
    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
        <!-- Status Filter Pills -->
        <ul class="nav nav-pills gap-1">
            <li class="nav-item">
                <a class="nav-link btn-sm py-1.5 px-3 {{ $status === 'all' ? 'active bg-success' : 'text-dark bg-light' }}" href="{{ route('vendor.quotations.index', ['status' => 'all', 'product_id' => $productId, 'q' => $search]) }}" style="border-radius: 8px;">
                    All ({{ $counts['all'] }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link btn-sm py-1.5 px-3 {{ $status === 'pending' ? 'active bg-warning text-dark' : 'text-dark bg-light' }}" href="{{ route('vendor.quotations.index', ['status' => 'pending', 'product_id' => $productId, 'q' => $search]) }}" style="border-radius: 8px;">
                    Pending RFQs ({{ $counts['pending'] }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link btn-sm py-1.5 px-3 {{ $status === 'quoted' ? 'active bg-primary' : 'text-dark bg-light' }}" href="{{ route('vendor.quotations.index', ['status' => 'quoted', 'product_id' => $productId, 'q' => $search]) }}" style="border-radius: 8px;">
                    Proposals Sent ({{ $counts['quoted'] }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link btn-sm py-1.5 px-3 {{ $status === 'accepted' ? 'active bg-success' : 'text-dark bg-light' }}" href="{{ route('vendor.quotations.index', ['status' => 'accepted', 'product_id' => $productId, 'q' => $search]) }}" style="border-radius: 8px;">
                    Accepted ({{ $counts['accepted'] }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link btn-sm py-1.5 px-3 {{ $status === 'converted' ? 'active bg-dark text-white' : 'text-dark bg-light' }}" href="{{ route('vendor.quotations.index', ['status' => 'converted', 'product_id' => $productId, 'q' => $search]) }}" style="border-radius: 8px;">
                    Converted ({{ $counts['converted'] }})
                </a>
            </li>
        </ul>

        <!-- Search and Product Filter Form -->
        <form action="{{ route('vendor.quotations.index') }}" method="GET" class="d-flex flex-wrap gap-2">
            <input type="hidden" name="status" value="{{ $status }}">
            
            <div class="input-group input-group-sm" style="min-width: 240px;">
                <input type="text" name="q" value="{{ $search }}" class="form-control" placeholder="Quote #, buyer, company...">
                <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
            </div>

            @if($products->isNotEmpty())
                <select name="product_id" class="form-select form-select-sm" style="min-width: 170px;" onchange="this.form.submit()">
                    <option value="">All Products</option>
                    @foreach($products as $prod)
                        <option value="{{ $prod->id }}" {{ $productId == $prod->id ? 'selected' : '' }}>{{ \Illuminate\Support\Str::limit($prod->name, 22) }}</option>
                    @endforeach
                </select>
            @endif

            @if($search || $productId)
                <a href="{{ route('vendor.quotations.index', ['status' => $status]) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x"></i> Clear</a>
            @endif
        </form>
    </div>
</div>

<!-- Quotations Master Table -->
<div class="card-custom p-0 overflow-hidden shadow-sm">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-3 text-muted small">QUOTE / RFQ #</th>
                    <th class="text-muted small">BUYER & FIRM</th>
                    <th class="text-muted small">REQUESTED MATERIAL</th>
                    <th class="text-muted small">REQ QTY</th>
                    <th class="text-muted small">DISPATCH URGENCY</th>
                    <th class="text-muted small">YOUR QUOTE (₹)</th>
                    <th class="text-muted small">STATUS</th>
                    <th class="pe-3 text-end text-muted small">ACTION</th>
                </tr>
            </thead>
            <tbody>
                @forelse($quotations as $q)
                    <tr>
                        <td class="ps-3">
                            <a href="{{ route('vendor.quotations.show', $q->id) }}" class="fw-bold text-primary text-decoration-none d-block">
                                #{{ $q->quotation_number }}
                            </a>
                            <span class="text-muted" style="font-size: 0.72rem;">{{ $q->created_at->format('d M Y, h:i A') }}</span>
                        </td>
                        <td>
                            <strong class="d-block text-dark">{{ $q->buyer_name }}</strong>
                            <div class="small text-muted" style="font-size: 0.75rem;">
                                @if($q->company_name)
                                    <span class="text-dark fw-semibold">{{ $q->company_name }}</span><br>
                                @endif
                                <i class="bi bi-telephone me-1"></i>{{ $q->buyer_phone }}
                            </div>
                        </td>
                        <td>
                            @if($q->product)
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $q->product->primaryImage ? asset('storage/'.$q->product->primaryImage->file_path) : 'https://placehold.co/50x50?text=Item' }}" class="rounded border" style="width: 40px; height: 40px; object-fit: cover;" alt="">
                                    <div>
                                        <a href="{{ route('products.show', $q->product->slug) }}" target="_blank" class="fw-semibold text-dark text-decoration-none d-block small">
                                            {{ \Illuminate\Support\Str::limit($q->product->name, 30) }}
                                        </a>
                                        @if($q->selected_variant)
                                            <span class="badge bg-light text-dark border" style="font-size: 0.68rem;">{{ $q->selected_variant }}</span>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <span class="text-muted small">Custom Material Specification</span>
                            @endif
                        </td>
                        <td>
                            <span class="fw-bold text-dark">{{ number_format($q->quantity) }}</span>
                            <span class="text-muted small">{{ $q->unit }}</span>
                        </td>
                        <td>
                            {!! $q->getUrgencyBadge() !!}
                            @if($q->attachment_path)
                                <span class="badge bg-secondary-subtle text-dark border ms-1" title="BOQ / Drawing Attached">
                                    <i class="bi bi-paperclip"></i>
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($q->total_amount > 0)
                                <strong class="text-success d-block">₹{{ number_format($q->total_amount, 2) }}</strong>
                                <span class="text-muted" style="font-size: 0.72rem;">@ ₹{{ number_format($q->unit_price, 2) }}/unit</span>
                            @else
                                <span class="badge bg-light text-muted border">Not Quoted Yet</span>
                            @endif
                        </td>
                        <td>
                            {!! $q->getStatusBadge() !!}
                        </td>
                        <td class="pe-3 text-end">
                            <a href="{{ route('vendor.quotations.show', $q->id) }}" class="btn btn-sm btn-outline-primary px-3 fw-semibold" style="border-radius: 8px;">
                                @if($q->isPending())
                                    <i class="bi bi-pencil-square me-1"></i> Send Quote
                                @else
                                    <i class="bi bi-eye me-1"></i> View Details
                                @endif
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-file-earmark-text fs-1 d-block mb-2 text-muted"></i>
                            <h6 class="fw-bold text-dark">No Quotation Requests Found</h6>
                            <p class="small text-muted mb-0">When buyers request custom project pricing or bulk RFQs on your products, they will appear here in real time.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($quotations->hasPages())
        <div class="p-3 border-top bg-light">
            {{ $quotations->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection
