@extends('layouts.vendor')

@section('title', 'Returns & Refunds')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--garudo-text-main);">Returns & Replacement Requests</h4>
        <p class="text-muted mb-0 small">Manage customer returns, damages, and refund approvals.</p>
    </div>
</div>

<div class="card-custom p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-3 text-muted small">ORDER NUMBER</th>
                    <th class="text-muted small">PRODUCT</th>
                    <th class="text-muted small">DATE</th>
                    <th class="text-muted small">AMOUNT</th>
                    <th class="pe-3 text-muted small">STATUS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($returns as $ret)
                    <tr>
                        <td class="ps-3 fw-bold text-dark">#{{ $ret->order->order_number ?? $ret->order_id }}</td>
                        <td>{{ $ret->product_name }}</td>
                        <td class="small text-muted">{{ $ret->created_at->format('d M Y') }}</td>
                        <td class="fw-bold">₹{{ number_format($ret->total_amount, 2) }}</td>
                        <td class="pe-3">
                            <span class="badge bg-danger bg-opacity-10 text-danger">{{ ucfirst($ret->item_status) }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-shield-check text-success fs-1 d-block mb-2"></i>
                            <h6 class="fw-bold text-dark">No Return Requests</h6>
                            <p class="small text-muted mb-0">Your store has a 0% return defect rate.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
