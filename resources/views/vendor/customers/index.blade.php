@extends('layouts.vendor')

@section('title', 'Customers Directory')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--garudo-text-main);">Customers & Buyers</h4>
        <p class="text-muted mb-0 small">Verified buyers, contractors, and builders who have purchased from your store.</p>
    </div>
</div>

<div class="card-custom p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-3 text-muted small">CUSTOMER NAME</th>
                    <th class="text-muted small">EMAIL</th>
                    <th class="text-muted small">PHONE</th>
                    <th class="pe-3 text-end text-muted small">JOINED</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $c)
                    <tr>
                        <td class="ps-3 fw-bold">{{ $c->name }}</td>
                        <td>{{ $c->email }}</td>
                        <td>{{ $c->phone ?? 'N/A' }}</td>
                        <td class="pe-3 text-end small text-muted">{{ $c->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-1 d-block mb-2 text-muted"></i>
                            <h6 class="fw-bold text-dark">No Direct Buyers Yet</h6>
                            <p class="small text-muted mb-0">Customer contact profiles will be listed here after initial purchases.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
