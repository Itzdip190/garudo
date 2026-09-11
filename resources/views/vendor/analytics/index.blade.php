@extends('layouts.vendor')

@section('title', 'Analytics & Reports')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--garudo-text-main);">Store Analytics & Performance Reports</h4>
        <p class="text-muted mb-0 small">Comprehensive sales trends, unit movements, and channel performance.</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card-custom text-center p-4">
            <div class="text-muted small mb-1">Total Revenue</div>
            <h3 class="fw-bold text-success mb-0">₹{{ number_format($totalSales, 2) }}</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-custom text-center p-4">
            <div class="text-muted small mb-1">Total Orders Processed</div>
            <h3 class="fw-bold text-dark mb-0">{{ $totalOrders }}</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-custom text-center p-4">
            <div class="text-muted small mb-1">Total Units Dispatched</div>
            <h3 class="fw-bold text-primary mb-0">{{ $totalUnits }}</h3>
        </div>
    </div>
</div>

<div class="card-custom">
    <h6 class="fw-bold mb-3">Performance Overview</h6>
    <p class="text-muted small">Real-time analytical graphs update automatically with daily order volumes and buyer engagements across India.</p>
</div>
@endsection
