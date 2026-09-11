@extends('layouts.admin')

@section('title', 'Buyer Profile - ' . ($customer->company_name ?? $customer->name))

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.customers.index') }}" class="btn btn-light btn-sm rounded-pill px-3 mb-3">
        <i class="bi bi-arrow-left me-1"></i> Back to All Buyers
    </a>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-info bg-opacity-10 text-info rounded-4 d-flex align-items-center justify-content-center fw-bold fs-3" style="width: 64px; height: 64px;">
                {{ strtoupper(substr($customer->company_name ?? $customer->name, 0, 2)) }}
            </div>
            <div>
                <h1 class="page-header-title mb-1">{{ $customer->company_name ?? $customer->name }}</h1>
                <div class="d-flex align-items-center gap-2">
                    <span class="pill-status pill-{{ strtolower($customer->status) }}">{{ ucfirst($customer->status) }}</span>
                    <span class="text-muted small">• Member since {{ $customer->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-info btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#resetCustomerPassModal">
                <i class="bi bi-key me-1"></i> Reset Password
            </button>

            <form action="{{ route('admin.customers.toggle_status', $customer->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-warning btn-sm rounded-pill px-3">
                    <i class="bi bi-slash-circle me-1"></i> {{ $customer->status === 'active' ? 'Block Account' : 'Activate Account' }}
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
<div class="modal fade" id="resetCustomerPassModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.customers.reset_password', $customer->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold"><i class="bi bi-key-fill text-warning me-2"></i>Reset Password for {{ $customer->company_name ?? $customer->name }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">Enter a new secure password for this buyer to access their customer portal.</p>
                    <div class="mb-3">
                        <label class="form-label fw-bold">New Password</label>
                        <input type="text" name="password" class="form-control" placeholder="Enter new password" value="Buyer@{{ rand(1000, 9999) }}#" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-garudo-primary fw-bold">Update & Hash Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Buyer Profile -->
    <div class="col-lg-4">
        <div class="garudo-card p-4 mb-4">
            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Buyer Company & Tax Info</h6>
            
            <div class="mb-3">
                <div class="text-muted small">Company Name</div>
                <div class="fw-bold fs-6">{{ $customer->company_name ?? 'Individual Buyer' }}</div>
            </div>

            <div class="mb-3">
                <div class="text-muted small">GSTIN Number</div>
                <div class="fw-bold"><code>{{ $customer->gstin ?? 'Not Provided' }}</code></div>
            </div>

            <div class="mb-3">
                <div class="text-muted small">Total Lifetime Spend</div>
                <div class="fw-bold fs-5 text-success">₹{{ number_format($totalSpent, 2) }}</div>
            </div>
        </div>

        <div class="garudo-card p-4">
            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Contact Details</h6>
            
            <div class="mb-3">
                <div class="text-muted small">Contact Person</div>
                <div class="fw-bold">{{ $customer->name }}</div>
            </div>

            <div class="mb-3">
                <div class="text-muted small">Email Address</div>
                <div class="fw-bold">{{ $customer->email }}</div>
            </div>

            <div class="mb-3">
                <div class="text-muted small">Phone Number</div>
                <div class="fw-bold">{{ $customer->phone ?? 'Not Provided' }}</div>
            </div>
        </div>
    </div>

    <!-- Right Column: Order History -->
    <div class="col-lg-8">
        <div class="garudo-card p-4">
            <h6 class="fw-bold text-dark mb-3">Purchase Order History ({{ $customer->orders->count() }})</h6>

            @if($customer->orders->count() > 0)
                <div class="table-responsive">
                    <table class="table admin-table">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Grand Total</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customer->orders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="fw-bold text-primary text-decoration-none">
                                            {{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td class="text-muted">{{ $order->created_at->format('d M Y') }}</td>
                                    <td class="fw-bold text-dark">₹{{ number_format($order->grand_total, 2) }}</td>
                                    <td>
                                        <span class="pill-status pill-{{ strtolower($order->payment_status) }}">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="pill-status pill-{{ strtolower($order->status) }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-light btn-sm rounded-pill px-3">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox fs-2 mb-2 d-block"></i>
                    <div>This buyer has not placed any orders yet.</div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
