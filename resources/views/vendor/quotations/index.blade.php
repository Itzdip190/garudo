@extends('layouts.vendor')

@section('title', 'Quotations & B2B Proposals')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--garudo-text-main);">Quotations & Price Proposals</h4>
        <p class="text-muted mb-0 small">Create and track custom B2B price estimates sent to prospective buyers.</p>
    </div>
</div>

<div class="card-custom p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-3 text-muted small">QUOTE #</th>
                    <th class="text-muted small">CUSTOMER</th>
                    <th class="text-muted small">MATERIAL</th>
                    <th class="text-muted small">QTY</th>
                    <th class="text-muted small">TOTAL (₹)</th>
                    <th class="text-muted small">VALID UNTIL</th>
                    <th class="pe-3 text-end text-muted small">STATUS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($quotations as $q)
                    <tr>
                        <td class="ps-3 fw-bold text-dark">#{{ $q->quotation_number }}</td>
                        <td>{{ $q->customer->name ?? 'Direct Prospect' }}</td>
                        <td>{{ $q->product->name ?? 'Custom Specification' }}</td>
                        <td>{{ $q->quantity }}</td>
                        <td class="fw-bold">₹{{ number_format($q->total_amount, 2) }}</td>
                        <td class="small text-muted">{{ $q->valid_until ? \Carbon\Carbon::parse($q->valid_until)->format('d M Y') : '30 Days' }}</td>
                        <td class="pe-3 text-end">
                            <span class="badge bg-success bg-opacity-10 text-success">{{ ucfirst($q->status) }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-file-earmark-text fs-1 d-block mb-2 text-muted"></i>
                            <h6 class="fw-bold text-dark">No Quotations Issued Yet</h6>
                            <p class="small text-muted mb-0">When buyers request custom project pricing, quotations will appear here.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
