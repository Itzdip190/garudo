@extends('layouts.vendor')

@section('title', 'Earnings & Payouts')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--garudo-text-main);">Earnings & Wallet Balance</h4>
        <p class="text-muted mb-0 small">Track net revenues, settled funds, and bank account payouts.</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card-custom text-center p-4">
            <div class="text-muted small mb-1">Total Net Earnings</div>
            <h3 class="fw-bold text-success mb-0">₹{{ number_format($totalEarnings, 2) }}</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-custom text-center p-4">
            <div class="text-muted small mb-1">Pending Clearance</div>
            <h3 class="fw-bold text-warning mb-0">₹{{ number_format($pendingPayouts, 2) }}</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-custom text-center p-4">
            <div class="text-muted small mb-1">Settled to Bank</div>
            <h3 class="fw-bold text-dark mb-0">₹{{ number_format($completedPayouts, 2) }}</h3>
        </div>
    </div>
</div>

<div class="card-custom">
    <h6 class="fw-bold mb-3 border-bottom pb-2">Payout Settlements</h6>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-3 text-muted small">ORDER ITEM</th>
                    <th class="text-muted small">DATE</th>
                    <th class="text-muted small">GROSS AMOUNT</th>
                    <th class="text-muted small">COMMISSION</th>
                    <th class="text-muted small">NET PAYOUT</th>
                    <th class="pe-3 text-muted small">STATUS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payoutHistory as $p)
                    <tr>
                        <td class="ps-3 fw-semibold">{{ $p->product_name }}</td>
                        <td class="small text-muted">{{ $p->created_at->format('d M Y') }}</td>
                        <td>₹{{ number_format($p->total_amount, 2) }}</td>
                        <td class="text-danger">-₹{{ number_format($p->commission_amount, 2) }}</td>
                        <td class="fw-bold text-success">₹{{ number_format($p->vendor_payout, 2) }}</td>
                        <td class="pe-3">
                            <span class="badge bg-success bg-opacity-10 text-success">{{ ucfirst($p->payout_status ?? 'Settled') }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            No payout settlements recorded yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
