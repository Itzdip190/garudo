@extends('layouts.customer')

@section('title', 'My Inquiries')

@section('content')
<div class="inquiries-page-container">
    
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row md-align-items-center justify-content-between mb-4 gap-3">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">My Inquiries & Messages</h1>
            <p class="text-muted mb-0" style="font-size: 0.875rem;">Direct communications, custom specifications, and trade conversations with sellers.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('products.index') }}" class="btn-primary-action">
                <i class="bi bi-chat-left-plus"></i> New Product Inquiry
            </a>
        </div>
    </div>

    <!-- Inquiries List -->
    <div class="card-custom">
        @if($inquiries->count() > 0)
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr style="border-bottom: 1px solid var(--garudo-border); font-size: 0.76rem; color: #64748B;">
                        <th style="padding: 0.85rem 0.65rem;">Inquiry # & Date</th>
                        <th style="padding: 0.85rem 0.65rem;">Product / Material</th>
                        <th style="padding: 0.85rem 0.65rem;">Supplier</th>
                        <th style="padding: 0.85rem 0.65rem;">Quantity</th>
                        <th style="padding: 0.85rem 0.65rem;">Latest Message</th>
                        <th style="padding: 0.85rem 0.65rem;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inquiries as $inq)
                    @php
                        $statusClass = match(strtolower($inq->status)) {
                            'replied', 'converted' => 'badge-delivered',
                            'closed' => 'badge-cancelled',
                            default => 'badge-transit'
                        };
                    @endphp
                    <tr style="border-bottom: 1px solid #F1F5F9;">
                        <td style="padding: 1rem 0.65rem;">
                            <div class="fw-bold text-dark">{{ $inq->enquiry_number }}</div>
                            <div class="text-muted" style="font-size: 0.72rem;">{{ $inq->created_at?->format('d M, Y') }}</div>
                        </td>
                        <td style="padding: 1rem 0.65rem;">
                            <div class="fw-semibold text-dark">{{ $inq->product?->name ?? 'Direct Supplier RFQ' }}</div>
                        </td>
                        <td style="padding: 1rem 0.65rem;">
                            <div class="fw-medium text-dark">{{ $inq->vendor?->shop_name ?? $inq->vendor?->company_name ?? 'Verified Seller' }}</div>
                        </td>
                        <td style="padding: 1rem 0.65rem;">
                            <div class="fw-medium text-dark">{{ $inq->quantity ? $inq->quantity . ' ' . ($inq->unit ?? 'Units') : 'N/A' }}</div>
                        </td>
                        <td style="padding: 1rem 0.65rem;">
                            <div class="text-muted text-truncate" style="max-width: 280px; font-size: 0.78rem;">
                                {{ $inq->message }}
                            </div>
                        </td>
                        <td style="padding: 1rem 0.65rem;">
                            <span class="{{ $statusClass }}">
                                {{ ucfirst($inq->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $inquiries->links() }}
        </div>
        @else
        <div class="text-center py-5 px-3">
            <div class="p-3 bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; color: var(--garudo-primary);">
                <i class="bi bi-chat-left-dots fs-3"></i>
            </div>
            <h5 class="fw-bold text-dark mb-1">No Active Inquiries</h5>
            <p class="text-muted mb-3" style="font-size: 0.85rem;">Have custom material requirements or wholesale queries? Message suppliers directly.</p>
            <a href="{{ route('products.index') }}" class="btn-primary-action">
                <i class="bi bi-search me-1"></i> Browse Products & Enquire
            </a>
        </div>
        @endif
    </div>

</div>
@endsection
