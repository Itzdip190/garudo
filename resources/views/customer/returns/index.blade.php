@extends('layouts.customer')

@section('title', 'Returns & Refunds')

@section('content')
<div class="returns-page-container">
    
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row md-align-items-center justify-content-between mb-4 gap-3">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Returns & Refunds</h1>
            <p class="text-muted mb-0" style="font-size: 0.875rem;">Track material return requests, damaged supply claims, and payment refunds.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('customer.orders') }}" class="btn-primary-action">
                <i class="bi bi-arrow-return-left"></i> View Orders for Return
            </a>
        </div>
    </div>

    <!-- Returns Table -->
    <div class="card-custom">
        @if($returns->count() > 0)
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr style="border-bottom: 1px solid var(--garudo-border); font-size: 0.76rem; color: #64748B;">
                        <th style="padding: 0.85rem 0.65rem;">Order # & Date</th>
                        <th style="padding: 0.85rem 0.65rem;">Items</th>
                        <th style="padding: 0.85rem 0.65rem;">Supplier</th>
                        <th style="padding: 0.85rem 0.65rem;">Amount</th>
                        <th style="padding: 0.85rem 0.65rem;">Status</th>
                        <th style="padding: 0.85rem 0.65rem;">Reason / Note</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($returns as $ret)
                    @php
                        $firstItem = $ret->items->first();
                    @endphp
                    <tr style="border-bottom: 1px solid #F1F5F9;">
                        <td style="padding: 1rem 0.65rem;">
                            <div class="fw-bold text-dark">{{ $ret->order_number }}</div>
                            <div class="text-muted" style="font-size: 0.72rem;">{{ $ret->created_at?->format('d M, Y') }}</div>
                        </td>
                        <td style="padding: 1rem 0.65rem;">
                            <div class="fw-semibold text-dark">{{ $firstItem?->product_name ?? 'Materials' }}</div>
                        </td>
                        <td style="padding: 1rem 0.65rem;">
                            <div class="fw-medium text-dark">{{ $firstItem?->vendor?->shop_name ?? 'Supplier' }}</div>
                        </td>
                        <td style="padding: 1rem 0.65rem;">
                            <div class="fw-bold text-dark">₹{{ number_format($ret->grand_total, 2) }}</div>
                        </td>
                        <td style="padding: 1rem 0.65rem;">
                            <span class="badge-cancelled">
                                {{ ucfirst($ret->status) }}
                            </span>
                        </td>
                        <td style="padding: 1rem 0.65rem;">
                            <div class="text-muted" style="font-size: 0.78rem;">{{ $ret->cancellation_reason ?? 'Return / cancellation processed' }}</div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $returns->links() }}
        </div>
        @else
        <div class="text-center py-5 px-3">
            <div class="p-3 bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; color: var(--garudo-primary);">
                <i class="bi bi-arrow-return-left fs-3"></i>
            </div>
            <h5 class="fw-bold text-dark mb-1">No Returns or Refund Claims</h5>
            <p class="text-muted mb-3" style="font-size: 0.85rem;">All your placed building material orders are in good standing without any return claims.</p>
            <a href="{{ route('customer.orders') }}" class="btn-primary-action">
                <i class="bi bi-box-seam me-1"></i> View Orders
            </a>
        </div>
        @endif
    </div>

</div>
@endsection
