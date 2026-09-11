@extends('layouts.admin')

@section('title', 'Customers & Buyers Management')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="page-header-title">B2B Buyers & Customers</h1>
        <p class="page-header-subtitle mb-0">Monitor verified contractors, corporate buyers, orders, and purchasing history.</p>
    </div>
</div>

<!-- ---------------- STATS PILLS ---------------- -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="garudo-card p-3">
            <div class="text-muted small fw-bold text-uppercase">Total Buyers</div>
            <div class="fs-4 fw-bold text-dark mt-1">{{ $stats['total'] }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="garudo-card p-3">
            <div class="text-success small fw-bold text-uppercase">Active Accounts</div>
            <div class="fs-4 fw-bold text-success mt-1">{{ $stats['active'] }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="garudo-card p-3">
            <div class="text-danger small fw-bold text-uppercase">Inactive / Blocked</div>
            <div class="fs-4 fw-bold text-danger mt-1">{{ $stats['inactive'] }}</div>
        </div>
    </div>
</div>

<!-- ---------------- SEARCH & FILTERS ---------------- -->
<div class="garudo-card p-3 mb-4">
    <form action="{{ route('admin.customers.index') }}" method="GET" class="row g-2 align-items-center">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Search by Buyer Name, Company, Email, GSTIN..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-garudo-primary w-100"><i class="bi bi-funnel me-1"></i> Filter Buyers</button>
        </div>
    </form>
</div>

<!-- ---------------- CUSTOMERS TABLE ---------------- -->
<div class="garudo-card p-0 overflow-hidden">
    @if($customers->count() > 0)
        <div class="table-responsive">
            <table class="table admin-table">
                <thead>
                    <tr>
                        <th>Buyer / Enterprise</th>
                        <th>Contact Email & Phone</th>
                        <th>GSTIN</th>
                        <th>Orders Placed</th>
                        <th>Status</th>
                        <th>Joined On</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $customer)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-info bg-opacity-10 text-info rounded-3 d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
                                        {{ strtoupper(substr($customer->company_name ?? $customer->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $customer->company_name ?? $customer->name }}</div>
                                        <div class="text-muted small">Contact: {{ $customer->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $customer->email }}</div>
                                <div class="text-muted small">{{ $customer->phone ?? 'No Phone' }}</div>
                            </td>
                            <td>
                                @if($customer->gstin)
                                    <code class="bg-light px-2 py-1 rounded text-dark">{{ $customer->gstin }}</code>
                                @else
                                    <span class="text-muted small">Individual Buyer</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1">{{ $customer->orders_count }} Orders</span>
                            </td>
                            <td>
                                <span class="pill-status pill-{{ strtolower($customer->status) }}">
                                    {{ ucfirst($customer->status) }}
                                </span>
                            </td>
                            <td class="text-muted small">{{ $customer->created_at->format('d M Y') }}</td>
                            <td class="text-end">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-light btn-sm rounded-pill px-3">
                                        <i class="bi bi-eye text-primary me-1"></i> Profile
                                    </a>
                                    <button type="button" class="btn btn-outline-info btn-sm rounded-pill px-2" data-bs-toggle="modal" data-bs-target="#resetCustomerPassModal{{ $customer->id }}" title="Reset Buyer Password">
                                        <i class="bi bi-key"></i>
                                    </button>
                                    <form action="{{ route('admin.customers.toggle_status', $customer->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-secondary btn-sm rounded-pill px-3" title="Toggle Active/Inactive">
                                            {{ $customer->status === 'active' ? 'Block' : 'Activate' }}
                                        </button>
                                    </form>
                                </div>

                                <!-- Customer Password Reset Modal -->
                                <div class="modal fade text-start" id="resetCustomerPassModal{{ $customer->id }}" tabindex="-1">
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
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top">
            {{ $customers->links() }}
        </div>
    @else
        <div class="text-center py-5 text-muted">
            <i class="bi bi-people fs-1 mb-2 d-block"></i>
            <h5>No Customers Found</h5>
            <p class="small">Try searching with a different keyword.</p>
        </div>
    @endif
</div>
@endsection
