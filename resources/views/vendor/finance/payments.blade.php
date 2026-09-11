@extends('layouts.vendor')

@section('title', 'Payment History')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--garudo-text-main);">Payment Transactions</h4>
        <p class="text-muted mb-0 small">Audit log of payments received from customer purchases.</p>
    </div>
</div>

<div class="card-custom p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-3 text-muted small">ORDER ID</th>
                    <th class="text-muted small">DATE</th>
                    <th class="text-muted small">PAYMENT METHOD</th>
                    <th class="text-muted small">GROSS AMOUNT</th>
                    <th class="pe-3 text-end text-muted small">STATUS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $t)
                    <tr>
                        <td class="ps-3 fw-bold">#{{ $t->order->order_number ?? $t->order_id }}</td>
                        <td class="small text-muted">{{ $t->created_at->format('d M Y, h:i A') }}</td>
                        <td class="small">{{ strtoupper($t->order->payment_method ?? 'ONLINE') }}</td>
                        <td class="fw-bold">₹{{ number_format($t->total_amount, 2) }}</td>
                        <td class="pe-3 text-end">
                            <span class="badge bg-success bg-opacity-10 text-success">{{ ucfirst($t->order->payment_status ?? 'Paid') }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-credit-card fs-1 d-block mb-2 text-muted"></i>
                            <h6 class="fw-bold text-dark">No Payment Transactions Recorded</h6>
                            <p class="small text-muted mb-0">Transactions will appear here automatically when orders are processed.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
