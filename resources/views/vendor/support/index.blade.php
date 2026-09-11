@extends('layouts.vendor')

@section('title', 'Support Tickets & Dedicated Help Desk')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--garudo-text-main);">Support & Dedicated Help Desk</h4>
        <p class="text-muted mb-0 small">Get prompt 24x7 merchant assistance with cataloging, bulk freight logistics, payouts, and buyer communications.</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-success btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#newTicketModal" style="background-color: var(--garudo-primary); border: none;">
            <i class="bi bi-plus-circle me-1"></i> Open Support Ticket
        </button>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Tickets & Inquiries Table -->
    <div class="col-lg-8">
        <div class="card-custom p-0 overflow-hidden">
            <div class="p-3 border-bottom bg-light d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-chat-left-text text-success me-2"></i>Buyer Inquiries & Support Tickets</h6>
                    <small class="text-muted">Track all questions, complaints, and ticket threads</small>
                </div>
                <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#newTicketModal">
                    <i class="bi bi-pencil-square me-1"></i> New Ticket
                </button>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3 text-muted small">TICKET / INQ #</th>
                            <th class="text-muted small">SENDER / TOPIC</th>
                            <th class="text-muted small">MESSAGE PREVIEW</th>
                            <th class="text-muted small">DATE</th>
                            <th class="pe-3 text-end text-muted small">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($enquiries as $enq)
                            <tr>
                                <td class="ps-3 fw-bold text-dark">
                                    <span class="badge bg-light text-dark border">#{{ $enq->enquiry_number }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark small">{{ $enq->name }}</div>
                                    <div class="text-muted" style="font-size: 0.72rem;">{{ $enq->phone ?? 'Contact' }}</div>
                                </td>
                                <td class="small text-truncate" style="max-width: 260px;">
                                    {{ Str::limit($enq->message, 80) }}
                                </td>
                                <td class="small text-muted">{{ $enq->created_at->format('d M Y, h:i A') }}</td>
                                <td class="pe-3 text-end">
                                    @if($enq->status === 'new')
                                        <span class="badge bg-danger bg-opacity-10 text-danger fw-semibold">New</span>
                                    @elseif($enq->status === 'read')
                                        <span class="badge bg-info bg-opacity-10 text-info fw-semibold">Read</span>
                                    @elseif($enq->status === 'replied')
                                        <span class="badge bg-success bg-opacity-10 text-success fw-semibold">Replied</span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary fw-semibold">{{ ucfirst($enq->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-headset fs-1 d-block mb-2 text-success opacity-50"></i>
                                    <h6 class="fw-bold text-dark">No Active Support Tickets</h6>
                                    <p class="small text-muted mb-3">Direct customer questions and ticket threads will appear here.</p>
                                    <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#newTicketModal">
                                        <i class="bi bi-plus-circle me-1"></i> Submit a Ticket to Garudo Help Desk
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column: Dedicated Help Desk Card (Image 2) -->
    <div class="col-lg-4">
        <div class="card-custom mb-3">
            <h6 class="fw-bold mb-3 border-bottom pb-2 text-dark">Dedicated Help Desk</h6>
            
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="p-3 rounded-circle bg-success bg-opacity-10 text-success fs-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                    <i class="bi bi-headset"></i>
                </div>
                <div>
                    <div class="fw-bold text-dark fs-6">Garudo Seller Help</div>
                    <div class="text-success fw-semibold small">support@garudo.in</div>
                    <div class="text-muted" style="font-size: 0.72rem;">Merchant Relationship Team</div>
                </div>
            </div>

            <p class="text-muted small mb-3">Reach out directly to our merchant relationship team for assistance with cataloging or bulk freight logistics.</p>

            <div class="d-flex flex-column gap-2">
                <a href="mailto:support@garudo.in?subject=Garudo%20Seller%20Support%20Request%20-%20Vendor%20ID%20GRD-V{{ str_pad($vendor->id, 4, '0', STR_PAD_LEFT) }}&body=Hello%20Garudo%20Seller%20Support%20Team,%0D%0A%0D%0AVendor%20Name:%20{{ urlencode($vendor->company_name ?? $vendor->name) }}%0D%0APhone:%20{{ $vendor->phone }}%0D%0A%0D%0AMy%20Inquiry%20Details:%0D%0A" class="btn btn-outline-success btn-sm w-100 fw-semibold d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-envelope"></i> Send Support Email
                </a>

                <button type="button" class="btn btn-success btn-sm w-100 fw-semibold d-flex align-items-center justify-content-center gap-2" style="background-color: var(--garudo-primary); border: none;" data-bs-toggle="modal" data-bs-target="#newTicketModal">
                    <i class="bi bi-ticket-perforated"></i> Open Support Ticket
                </button>

                <a href="https://wa.me/919876543210?text=Hello%20Garudo%20Merchant%20Support,%20I%20am%20vendor%20{{ urlencode($vendor->company_name ?? $vendor->name) }}" target="_blank" class="btn btn-outline-dark btn-sm w-100 fw-semibold d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-whatsapp text-success"></i> WhatsApp Quick Connect
                </a>
            </div>
        </div>

        <!-- Help Desk Quick Contacts Card -->
        <div class="card-custom bg-light border-0">
            <h6 class="fw-bold small mb-2 text-dark"><i class="bi bi-telephone-outbound text-primary me-1"></i> Direct Hotline Support</h6>
            <div class="small text-muted mb-2">Operating Hours: Mon - Sat (9:00 AM - 7:00 PM IST)</div>
            <div class="fw-bold text-dark fs-6 mb-1">
                <i class="bi bi-telephone me-1 text-success"></i> +91 98765 43210
            </div>
            <div class="text-muted small">Toll-Free Merchant Desk: 1800-GARUDO-IN</div>
        </div>
    </div>
</div>

<!-- Modal: Create New Support Ticket -->
<div class="modal fade" id="newTicketModal" tabindex="-1" aria-labelledby="newTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('vendor.support.ticket') }}" method="POST">
                @csrf
                <div class="modal-header border-bottom bg-light">
                    <h6 class="modal-title fw-bold text-dark" id="newTicketModalLabel">
                        <i class="bi bi-headset text-success me-2"></i>Submit Support Request to Garudo Help Desk
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">Inquiry Category <span class="text-danger">*</span></label>
                            <select name="category" class="form-select form-select-sm" required>
                                <option value="orders">B2B Orders & Delivery</option>
                                <option value="shipping">Freight & Shipping Logistics</option>
                                <option value="payouts">Payouts & Financial Invoices</option>
                                <option value="catalog">Product Catalog & Approvals</option>
                                <option value="kyc">KYC & Certificate Compliance</option>
                                <option value="boost">Promotional Boost Campaigns</option>
                                <option value="technical">Technical Portal Assistance</option>
                                <option value="other" selected>General Merchant Support</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">Priority / Urgency</label>
                            <select name="urgency" class="form-select form-select-sm">
                                <option value="low">Low - General Question</option>
                                <option value="medium" selected>Medium - Normal Issue</option>
                                <option value="high">High - Urgent Order Delay</option>
                                <option value="critical">Critical - Account / Payment Block</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-dark">Subject / Summary <span class="text-danger">*</span></label>
                            <input type="text" name="subject" class="form-control form-control-sm" placeholder="e.g. Need assistance with bulk freight quotation for cement order" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-dark">Detailed Description <span class="text-danger">*</span></label>
                            <textarea name="message" class="form-control" rows="4" placeholder="Please describe your issue or question in detail. Mention order ID or product SKU if relevant..." required></textarea>
                            <div class="form-text text-muted" style="font-size: 0.75rem;">Support responses will also be delivered to your registered email and portal notifications.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-success fw-semibold px-3" style="background-color: var(--garudo-primary); border: none;">
                        <i class="bi bi-send me-1"></i> Submit Ticket
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
