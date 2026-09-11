@extends('layouts.frontend')

@section('title', 'Shopping Cart - Garudo B2B Marketplace')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--garudo-navy);">
                <i class="bi bi-cart3 me-2 text-warning"></i> B2B Procurement Cart ({{ $itemCount }} {{ Str::plural('item', $itemCount) }})
            </h4>
            <p class="text-muted small mb-0">Review order quantities, supplier dispatch terms, and GST input credit breakdown.</p>
        </div>
        @if(count($cartItems) > 0)
            <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to clear your cart?');">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                    <i class="bi bi-trash me-1"></i> Clear Cart
                </button>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(count($cartItems) > 0)
        <div class="row g-4">
            <!-- Left: Cart Items Table -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 bg-white">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Product & Supplier</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th class="pe-4 text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $key => $item)
                                    @php
                                        $itemTotal = $item['price'] * $item['quantity'];
                                        $img = !empty($item['image']) ? asset('storage/' . $item['image']) : 'https://images.unsplash.com/photo-1581094794329-c8112a89af12?auto=format&fit=crop&w=300&q=80';
                                    @endphp
                                    <tr>
                                        <!-- Product info -->
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="rounded-3 border bg-light d-flex align-items-center justify-content-center overflow-hidden" style="width: 60px; height: 60px; min-width: 60px;">
                                                    <img src="{{ $img }}" alt="{{ $item['name'] }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                                </div>
                                                <div>
                                                    <h6 class="fw-bold mb-0 text-dark text-truncate" style="max-width: 240px;" title="{{ $item['name'] }}">
                                                        <a href="{{ route('products.show', $item['slug']) }}" class="text-dark text-decoration-none">
                                                            {{ $item['name'] }}
                                                        </a>
                                                    </h6>
                                                    <div class="text-muted small" style="font-size: 11px;">
                                                        SKU: <span class="font-monospace">{{ $item['sku'] }}</span>
                                                    </div>
                                                    <div class="text-muted small" style="font-size: 11px;">
                                                        Seller: <span class="text-success fw-semibold">{{ $item['vendor_name'] }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Unit Price -->
                                        <td>
                                            <span class="fw-bold text-dark">₹{{ number_format($item['price'], 2) }}</span>
                                            <div class="text-muted small">/ {{ $item['unit'] }}</div>
                                        </td>

                                        <!-- Quantity update form -->
                                        <td>
                                            <form action="{{ route('cart.update') }}" method="POST" class="d-flex align-items-center gap-1">
                                                @csrf
                                                <input type="hidden" name="key" value="{{ $key }}">
                                                <input type="number" name="quantity" min="1" value="{{ $item['quantity'] }}" class="form-control form-control-sm text-center font-monospace" style="width: 70px;" onchange="this.form.submit()">
                                            </form>
                                        </td>

                                        <!-- Item Total -->
                                        <td>
                                            <strong class="text-navy fs-6">₹{{ number_format($itemTotal, 2) }}</strong>
                                        </td>

                                        <!-- Delete action -->
                                        <td class="pe-4 text-end">
                                            <form action="{{ route('cart.remove', $key) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Remove from Cart">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-dark rounded-pill px-4 fw-semibold">
                        <i class="bi bi-arrow-left me-1"></i> Continue Shopping
                    </a>
                </div>
            </div>

            <!-- Right: Order Summary Sidebar -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                    <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">Procurement Summary</h5>

                    <div class="d-flex justify-content-between text-muted small mb-2">
                        <span>Items Subtotal:</span>
                        <span class="text-dark fw-bold">₹{{ number_format($subtotal, 2) }}</span>
                    </div>

                    <div class="d-flex justify-content-between text-muted small mb-2">
                        <span>Estimated GST (18% ITC Eligible):</span>
                        <span class="text-dark fw-bold">₹{{ number_format($gstTax, 2) }}</span>
                    </div>

                    <div class="d-flex justify-content-between text-muted small mb-3">
                        <span>F.O.R. Site Freight & Logistics:</span>
                        <span class="text-success fw-bold">Calculated at Checkout</span>
                    </div>

                    <hr class="my-3">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="fw-bold text-dark fs-6">Estimated Total:</span>
                        <span class="fs-4 fw-black text-navy" style="color: var(--garudo-navy);">
                            ₹{{ number_format($grandTotal, 2) }}
                        </span>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('customer.orders') }}" class="btn btn-yellow fw-bold py-2.5 rounded-3 text-dark shadow-sm">
                            <i class="bi bi-shield-check me-1"></i> Proceed to Secure Checkout
                        </a>
                        <button type="button" class="btn btn-outline-primary fw-bold py-2.5 rounded-3" data-bs-toggle="modal" data-bs-target="#bulkQuoteModal">
                            <i class="bi bi-lightning-charge-fill me-1"></i> Request Bulk Order Discount
                        </button>
                    </div>

                    <!-- Trust Perks -->
                    <div class="mt-4 pt-3 border-top d-flex flex-column gap-2 small text-muted">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-receipt-cutoff text-success fs-6"></i>
                            <span>GST Tax Invoices with Full Input Tax Credit (ITC)</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-truck text-primary fs-6"></i>
                            <span>F.O.R. Unloading & Multi-City Bulk Freight</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-shield-lock-fill text-warning fs-6"></i>
                            <span>100% Genuine ISI / ISO Factory Certified Materials</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Empty Cart State -->
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white my-4">
            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 90px; height: 90px;">
                <i class="bi bi-cart-x text-muted display-4"></i>
            </div>
            <h4 class="fw-bold text-dark mb-1">Your Procurement Cart is Empty</h4>
            <p class="text-muted small mx-auto mb-4" style="max-width: 480px;">
                Explore India's largest B2B building materials catalog including TMT Steel, Cement, Tiles, Sanitaryware, and Electricals at direct wholesale prices.
            </p>
            <div>
                <a href="{{ route('products.index') }}" class="btn btn-yellow px-4 py-2.5 fw-bold rounded-pill shadow-sm">
                    <i class="bi bi-box-seam me-1"></i> Browse Building Materials Catalog
                </a>
            </div>
        </div>
    @endif
</div>

<!-- Bulk Quote Modal -->
<div class="modal fade" id="bulkQuoteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-dark text-white px-4 py-3 rounded-top-4">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-lightning-charge-fill me-2 text-warning"></i> Request Special Bulk Project Pricing
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted small mb-3">
                    Purchasing for large infrastructure or residential projects? Submit your requirement for special contractor pricing.
                </p>
                <form action="#" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Project / Site Name</label>
                        <input type="text" class="form-control" placeholder="e.g. Prestige Heights Tower B">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Delivery Location (City & State)</label>
                        <input type="text" class="form-control" placeholder="e.g. Noida Sector 62, Uttar Pradesh">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Expected Dispatch Date</label>
                        <input type="date" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-yellow w-100 fw-bold py-2 rounded-3 text-dark">
                        Submit Bulk Discount Request
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
