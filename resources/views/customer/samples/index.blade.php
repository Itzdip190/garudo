@extends('layouts.customer')

@section('title', 'My Material Samples')

@section('content')
<div class="samples-page-container">
    
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row md-align-items-center justify-content-between mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h1 class="h3 fw-bold text-dark mb-0">My Sample Requests & Trial Kits</h1>
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 small fw-semibold">
                    <i class="bi bi-box2-heart-fill me-1"></i>Materials Testing
                </span>
            </div>
            <p class="text-muted mb-0" style="font-size: 0.875rem;">Track status, courier AWB numbers, and lab approvals for material samples ordered from verified suppliers.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('products.index') }}" class="btn-primary-action">
                <i class="bi bi-search"></i> Browse Catalog & Order Samples
            </a>
        </div>
    </div>

    <!-- Summary KPI Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4">
            <div class="card-custom p-3">
                <div class="text-muted small fw-bold text-uppercase" style="font-size: 0.72rem;">Total Samples</div>
                <div class="fs-4 fw-bold text-dark mt-1">{{ $counts['total'] }}</div>
                <span class="text-muted" style="font-size: 0.72rem;">Requested across suppliers</span>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="card-custom p-3" style="background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);">
                <div class="text-primary small fw-bold text-uppercase" style="font-size: 0.72rem;">In Transit / Processing</div>
                <div class="fs-4 fw-bold text-primary mt-1">{{ $counts['in_progress'] }}</div>
                <span class="text-muted" style="font-size: 0.72rem;">Being prepared or shipped</span>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card-custom p-3" style="background: linear-gradient(135deg, #F0FDF4 0%, #DCFCE7 100%);">
                <div class="text-success small fw-bold text-uppercase" style="font-size: 0.72rem;">Delivered Samples</div>
                <div class="fs-4 fw-bold text-success mt-1">{{ $counts['delivered'] }}</div>
                <span class="text-muted" style="font-size: 0.72rem;">Ready for project testing</span>
            </div>
        </div>
    </div>

    <!-- Samples List Card -->
    <div class="card-custom">
        @if($samples->count() > 0)
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr style="border-bottom: 1px solid var(--garudo-border); font-size: 0.76rem; color: #64748B;">
                        <th style="padding: 0.85rem 0.75rem;">Sample # & Date</th>
                        <th style="padding: 0.85rem 0.75rem;">Product / Material</th>
                        <th style="padding: 0.85rem 0.75rem;">Supplier</th>
                        <th style="padding: 0.85rem 0.75rem;">Pricing Type</th>
                        <th style="padding: 0.85rem 0.75rem;">Courier & Tracking</th>
                        <th style="padding: 0.85rem 0.75rem;">Status</th>
                        <th class="text-end" style="padding: 0.85rem 0.75rem;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($samples as $sample)
                    <tr style="border-bottom: 1px solid #F1F5F9;">
                        <td style="padding: 1rem 0.75rem;">
                            <a href="{{ route('customer.samples.show', $sample->id) }}" class="fw-bold text-dark text-decoration-none d-block">
                                {{ $sample->request_number }}
                            </a>
                            <div class="text-muted" style="font-size: 0.72rem;">{{ $sample->created_at?->format('d M, Y') }}</div>
                        </td>

                        <td style="padding: 1rem 0.75rem;">
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $sample->product->primaryImage ? asset('storage/'.$sample->product->primaryImage->file_path) : 'https://placehold.co/80x80?text=Material' }}" 
                                     class="rounded border" style="width: 40px; height: 40px; object-fit: cover;" alt="{{ $sample->product->name ?? 'Material' }}">
                                <div>
                                    <div class="fw-semibold text-dark small" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $sample->product?->name ?? 'Sample Material' }}
                                    </div>
                                    <span class="text-muted small" style="font-size: 0.72rem;">Qty: <strong>{{ $sample->quantity }}</strong> | {{ $sample->product?->sample_unit_description ?? 'Standard Specimen' }}</span>
                                </div>
                            </div>
                        </td>

                        <td style="padding: 1rem 0.75rem;">
                            <div class="fw-medium text-dark small">{{ $sample->vendor?->company_name ?? 'Verified Supplier' }}</div>
                            <span class="text-muted small" style="font-size: 0.72rem;"><i class="bi bi-telephone me-1"></i>{{ $sample->vendor?->phone ?? 'N/A' }}</span>
                        </td>

                        <td style="padding: 1rem 0.75rem;">
                            {!! $sample->getSampleTypeBadge() !!}
                            <div class="small fw-semibold mt-1">
                                {{ $sample->total_amount > 0 ? '₹' . number_format($sample->total_amount, 2) : '₹0.00 (Free)' }}
                            </div>
                        </td>

                        <td style="padding: 1rem 0.75rem;">
                            @if($sample->tracking_number)
                                <div class="small fw-semibold text-dark"><i class="bi bi-truck text-primary me-1"></i>{{ $sample->courier_partner }}</div>
                                <code class="small text-muted" style="font-size: 0.74rem;">{{ $sample->tracking_number }}</code>
                            @else
                                <span class="badge bg-light text-muted border small">Awaiting Dispatch</span>
                            @endif
                        </td>

                        <td style="padding: 1rem 0.75rem;">
                            {!! $sample->getStatusBadge() !!}
                        </td>

                        <td class="text-end" style="padding: 1rem 0.75rem;">
                            <a href="{{ route('customer.samples.show', $sample->id) }}" class="btn btn-outline-dark btn-sm px-3 fw-semibold">
                                View Tracker <i class="bi bi-chevron-right ms-1"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($samples->hasPages())
            <div class="p-3 border-top">
                {{ $samples->links() }}
            </div>
        @endif
        @else
        <div class="text-center py-5">
            <i class="bi bi-box2 text-muted" style="font-size: 3rem;"></i>
            <h5 class="fw-bold text-dark mt-3">No Sample Requests Yet</h5>
            <p class="text-muted small mb-3">You haven't requested any material samples yet. Request physical swatches & test pieces before placing bulk construction orders.</p>
            <a href="{{ route('products.index') }}" class="btn-primary-action">
                <i class="bi bi-box-seam me-1"></i> Explore Products & Order Samples
            </a>
        </div>
        @endif
    </div>

</div>
@endsection
