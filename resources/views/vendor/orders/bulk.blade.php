@extends('layouts.vendor')

@section('title', 'Bulk Orders & Inquiries')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--garudo-text-main);">Bulk Material Inquiries (RFQ)</h4>
        <p class="text-muted mb-0 small">High-volume wholesale RFQs received directly from contractors and builders.</p>
    </div>
</div>

<div class="card-custom p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-3 text-muted small">RFQ ID</th>
                    <th class="text-muted small">BUYER / COMPANY</th>
                    <th class="text-muted small">MATERIAL / PRODUCT</th>
                    <th class="text-muted small">QTY REQUIRED</th>
                    <th class="text-muted small">DATE</th>
                    <th class="pe-3 text-end text-muted small">STATUS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bulkInquiries as $inq)
                    <tr>
                        <td class="ps-3 fw-bold text-dark">#{{ $inq->enquiry_number }}</td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $inq->name }}</div>
                            <div class="small text-muted">{{ $inq->company_name ?? 'Individual Contractor' }}</div>
                        </td>
                        <td>{{ $inq->product->name ?? 'Custom Inquiry' }}</td>
                        <td class="fw-bold">{{ $inq->quantity ?? 'Bulk' }} {{ $inq->unit ?? 'Units' }}</td>
                        <td class="small text-muted">{{ $inq->created_at->format('d M Y') }}</td>
                        <td class="pe-3 text-end">
                            <span class="badge bg-primary bg-opacity-10 text-primary">{{ ucfirst($inq->status) }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-boxes fs-1 d-block mb-2 text-muted"></i>
                            <h6 class="fw-bold text-dark">No Bulk RFQs Yet</h6>
                            <p class="small text-muted mb-0">When contractors request wholesale bulk quotes, they will appear here.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
