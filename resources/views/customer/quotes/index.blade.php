@extends('layouts.customer')

@section('title', 'My Quotes')

@section('content')
<div class="quotes-page-container">
    
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row md-align-items-center justify-content-between mb-4 gap-3">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">My Quotes & RFQs</h1>
            <p class="text-muted mb-0" style="font-size: 0.875rem;">Manage bulk material price estimates and supplier quotations.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('products.index') }}" class="btn-primary-action">
                <i class="bi bi-file-earmark-plus"></i> Request New Quote
            </a>
        </div>
    </div>

    <!-- Quotes List Card -->
    <div class="card-custom">
        @if($quotes->count() > 0)
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr style="border-bottom: 1px solid var(--garudo-border); font-size: 0.76rem; color: #64748B;">
                        <th style="padding: 0.85rem 0.65rem;">Quote # & Date</th>
                        <th style="padding: 0.85rem 0.65rem;">Material Requested</th>
                        <th style="padding: 0.85rem 0.65rem;">Quantity</th>
                        <th style="padding: 0.85rem 0.65rem;">Supplier / Vendor</th>
                        <th style="padding: 0.85rem 0.65rem;">Quoted Price</th>
                        <th style="padding: 0.85rem 0.65rem;">Valid Until</th>
                        <th style="padding: 0.85rem 0.65rem;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quotes as $quote)
                    @php
                        $statusClass = match(strtolower($quote->status)) {
                            'accepted' => 'badge-delivered',
                            'expired', 'rejected' => 'badge-cancelled',
                            default => 'badge-transit'
                        };
                    @endphp
                    <tr style="border-bottom: 1px solid #F1F5F9;">
                        <td style="padding: 1rem 0.65rem;">
                            <div class="fw-bold text-dark">{{ $quote->quotation_number }}</div>
                            <div class="text-muted" style="font-size: 0.72rem;">{{ $quote->created_at?->format('d M, Y') }}</div>
                        </td>
                        <td style="padding: 1rem 0.65rem;">
                            <div class="fw-semibold text-dark">{{ $quote->product?->name ?? 'Material Quotation' }}</div>
                        </td>
                        <td style="padding: 1rem 0.65rem;">
                            <div class="fw-medium text-dark">{{ $quote->quantity }} Units</div>
                        </td>
                        <td style="padding: 1rem 0.65rem;">
                            <div class="fw-medium text-dark">{{ $quote->vendor?->shop_name ?? $quote->vendor?->company_name ?? 'Verified Seller' }}</div>
                        </td>
                        <td style="padding: 1rem 0.65rem;">
                            <div class="fw-bold text-dark">₹{{ number_format($quote->total_amount, 2) }}</div>
                        </td>
                        <td style="padding: 1rem 0.65rem;">
                            <div class="text-muted" style="font-size: 0.78rem;">{{ $quote->valid_until?->format('d M, Y') ?? 'N/A' }}</div>
                        </td>
                        <td style="padding: 1rem 0.65rem;">
                            <span class="{{ $statusClass }}">
                                {{ ucfirst($quote->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $quotes->links() }}
        </div>
        @else
        <div class="text-center py-5 px-3">
            <div class="p-3 bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; color: var(--garudo-primary);">
                <i class="bi bi-file-earmark-text fs-3"></i>
            </div>
            <h5 class="fw-bold text-dark mb-1">No Quotations Found</h5>
            <p class="text-muted mb-3" style="font-size: 0.85rem;">Request wholesale price estimates directly from building materials suppliers.</p>
            <a href="{{ route('products.index') }}" class="btn-primary-action">
                <i class="bi bi-search me-1"></i> Request Quote on Products
            </a>
        </div>
        @endif
    </div>

</div>
@endsection
