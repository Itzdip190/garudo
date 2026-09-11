@extends('layouts.frontend')

@section('title', 'Rate Garudo - Platform Feedback & Software Reviews')

@push('styles')
<style>
    .feedback-hero-banner {
        background: linear-gradient(135deg, #0A1324 0%, #0D1B3E 100%);
        color: #FFFFFF;
        position: relative;
        overflow: hidden;
        border-radius: 0 0 20px 20px;
    }
    .star-select-btn {
        font-size: 2rem;
        color: #cbd5e1;
        cursor: pointer;
        transition: transform 0.15s ease, color 0.15s ease;
    }
    .star-select-btn:hover {
        transform: scale(1.15);
    }
    .star-select-btn.active {
        color: #f59e0b;
    }
    .testimonial-card {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        background: #FFFFFF;
        transition: all 0.25s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .testimonial-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.07);
        border-color: #cbd5e1;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
    <!-- Hero Banner -->
    <div class="feedback-hero-banner py-5 px-3 px-md-5 mb-5 text-center">
        <div class="container py-3">
            <span class="badge bg-warning text-dark px-3 py-1.5 rounded-pill fw-bold text-uppercase mb-3 shadow-sm" style="letter-spacing: 0.5px;">
                <i class="bi bi-chat-heart-fill me-1"></i> Garudo Experience & Feedback
            </span>
            <h1 class="fw-black text-white display-5 mb-3" style="font-weight: 900;">
                Rate & Review <span class="text-warning">Garudo Software</span>
            </h1>
            <p class="text-white-50 mx-auto fs-6 mb-4" style="max-width: 650px;">
                Your feedback shapes India's leading B2B construction materials platform. Rate our procurement software, bulk quotation response times, verified suppliers, and dispatch logistics.
            </p>

            <div class="d-flex flex-wrap justify-content-center align-items-center gap-4 text-white">
                <div class="d-flex align-items-center gap-2">
                    <span class="fs-4 fw-black text-warning">{{ number_format($avgScore, 1) }}</span>
                    <div class="text-warning small">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <span class="text-white-50 small">({{ $totalCount }} Verified Reviews)</span>
                </div>
                <span class="text-white-50 d-none d-sm-inline">|</span>
                <span class="small"><i class="bi bi-shield-check text-warning me-1"></i> 100% Verified Business Feedback</span>
                <span class="text-white-50 d-none d-sm-inline">|</span>
                <span class="small"><i class="bi bi-truck text-warning me-1"></i> Pan-India Dispatch Network</span>
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="container mb-5">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 p-3 mb-5" role="alert">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill fs-4 text-success"></i>
                    <div>
                        <h6 class="fw-bold mb-0 text-success">Feedback Submitted Successfully!</h6>
                        <span class="small">{{ session('success') }}</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Left Column: Submit Platform Review Form -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white sticky-top" style="top: 90px;">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="p-2 rounded-3 bg-warning text-dark fs-4">
                            <i class="bi bi-pencil-square"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">Submit Your Feedback</h5>
                            <span class="small text-muted">Share your software & procurement experience</span>
                        </div>
                    </div>

                    <form action="{{ route('feedback.store') }}" method="POST">
                        @csrf

                        <!-- Star Rating Selector -->
                        <div class="text-center p-3 bg-light rounded-4 mb-3 border">
                            <label class="form-label fw-bold text-dark small mb-1">Your Platform Rating <span class="text-danger">*</span></label>
                            <div class="d-flex justify-content-center gap-2 my-1" id="feedbackStarGroup">
                                <i class="bi bi-star-fill star-select-btn active" data-val="1"></i>
                                <i class="bi bi-star-fill star-select-btn active" data-val="2"></i>
                                <i class="bi bi-star-fill star-select-btn active" data-val="3"></i>
                                <i class="bi bi-star-fill star-select-btn active" data-val="4"></i>
                                <i class="bi bi-star-fill star-select-btn active" data-val="5"></i>
                            </div>
                            <input type="hidden" name="rating" id="feedbackRatingInput" value="5" required>
                            <span class="small text-muted fw-semibold" id="feedbackRatingLabel">5.0 - Excellent Marketplace Experience</span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Feedback Area <span class="text-danger">*</span></label>
                            <select name="category" class="form-select form-select-sm" required>
                                @foreach($categories as $key => $label)
                                    <option value="{{ $key }}" {{ $key === 'overall' ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold small">Your Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control form-control-sm" value="{{ auth('customer')->user()?->name ?? auth('vendor')->user()?->name ?? '' }}" placeholder="e.g. Anand Sharma" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold small">Company / Firm</label>
                                <input type="text" name="company_name" class="form-control form-control-sm" value="{{ auth('customer')->user()?->company_name ?? auth('vendor')->user()?->company_name ?? '' }}" placeholder="e.g. Buildcon Developers">
                            </div>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold small">Email Address</label>
                                <input type="email" name="email" class="form-control form-control-sm" value="{{ auth('customer')->user()?->email ?? auth('vendor')->user()?->email ?? '' }}" placeholder="e.g. anand@buildcon.in">
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold small">Phone Number</label>
                                <input type="text" name="phone" class="form-control form-control-sm" value="{{ auth('customer')->user()?->phone ?? auth('vendor')->user()?->phone ?? '' }}" placeholder="e.g. 9876543210">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Review Headline</label>
                            <input type="text" name="title" class="form-control form-control-sm" placeholder="e.g. Seamless bulk steel procurement and instant GST billing">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Your Review & Feedback <span class="text-danger">*</span></label>
                            <textarea name="feedback" class="form-control form-control-sm" rows="3" placeholder="Tell us what worked well and how Garudo helped your business..." required minlength="5"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">What did you like most? (Pros)</label>
                            <input type="text" name="pros" class="form-control form-control-sm" placeholder="e.g. Transparent pricing, verified test reports, 24/7 account manager">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold small">What should we improve? (Suggestions)</label>
                            <input type="text" name="suggested_improvements" class="form-control form-control-sm" placeholder="e.g. Add more regional truck route options">
                        </div>

                        <button type="submit" class="btn btn-warning w-100 fw-bold py-2.5 rounded-3 text-dark shadow-sm">
                            <i class="bi bi-send-fill me-1"></i> Post Platform Review
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Column: Testimonial Feed & Community Feedback -->
            <div class="col-lg-7">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-chat-quote-fill text-warning me-2"></i> What Builders & Suppliers Say
                        </h4>
                        <span class="small text-muted">Real ratings from contractors, site engineers, and material manufacturers</span>
                    </div>
                </div>

                <div class="row g-3">
                    @forelse($feedbacks as $item)
                        <div class="col-12">
                            <div class="testimonial-card p-4">
                                <div>
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-circle bg-navy text-white fw-bold d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: var(--garudo-navy);">
                                                {{ strtoupper(substr($item->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark mb-0">{{ $item->name }}</div>
                                                <div class="text-muted small">
                                                    {{ $item->company_name ?? 'Verified Construction Buyer' }}
                                                    @if($item->user_type === 'vendor')
                                                        <span class="badge bg-success-subtle text-success ms-1">Supplier Partner</span>
                                                    @elseif($item->user_type === 'customer')
                                                        <span class="badge bg-primary-subtle text-primary ms-1">Contractor / Buyer</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-end">
                                            <div class="text-warning fs-6 mb-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bi bi-star-fill {{ $i <= $item->rating ? 'text-warning' : 'text-muted text-opacity-25' }}"></i>
                                                @endfor
                                            </div>
                                            <span class="text-muted small" style="font-size: 11px;">{{ $item->created_at->format('d M Y') }}</span>
                                        </div>
                                    </div>

                                    @if($item->title)
                                        <h6 class="fw-bold text-dark mt-2 mb-1">{{ $item->title }}</h6>
                                    @endif

                                    <p class="text-secondary small mb-3 leading-relaxed">
                                        {{ $item->feedback }}
                                    </p>

                                    @if($item->pros)
                                        <div class="small p-2 rounded-3 bg-success-subtle text-success mb-2">
                                            <i class="bi bi-hand-thumbs-up-fill me-1"></i> <strong>Top Highlight:</strong> {{ $item->pros }}
                                        </div>
                                    @endif

                                    @if($item->suggested_improvements)
                                        <div class="small p-2 rounded-3 bg-light text-muted">
                                            <i class="bi bi-lightbulb-fill text-warning me-1"></i> <strong>Suggestion:</strong> {{ $item->suggested_improvements }}
                                        </div>
                                    @endif
                                </div>

                                <div class="pt-2 border-top mt-3 text-muted small d-flex justify-content-between align-items-center" style="font-size: 11px;">
                                    <span>Category: <strong>{{ $categories[$item->category] ?? 'General Experience' }}</strong></span>
                                    <span class="text-success"><i class="bi bi-patch-check-fill me-1"></i> Verified Submission</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
                                <i class="bi bi-stars text-warning display-4 mb-2"></i>
                                <h5 class="fw-bold text-dark">Be the first to rate Garudo Software</h5>
                                <p class="text-muted small mb-0">Submit your review using the form on the left to get your feedback featured.</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                @if($feedbacks->hasPages())
                    <div class="mt-4">
                        {{ $feedbacks->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const starBtns = document.querySelectorAll('#feedbackStarGroup .star-select-btn');
        const ratingInput = document.getElementById('feedbackRatingInput');
        const label = document.getElementById('feedbackRatingLabel');

        const labels = {
            1: '1.0 - Needs Major Improvement',
            2: '2.0 - Below Expectations',
            3: '3.0 - Standard / Satisfactory',
            4: '4.0 - Very Good Platform Experience',
            5: '5.0 - Excellent Marketplace Experience'
        };

        starBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const val = parseInt(this.getAttribute('data-val'));
                ratingInput.value = val;
                label.textContent = labels[val] || (val + '.0');

                starBtns.forEach(s => {
                    const sVal = parseInt(s.getAttribute('data-val'));
                    if (sVal <= val) {
                        s.classList.add('active');
                    } else {
                        s.classList.remove('active');
                    }
                });
            });
        });
    });
</script>
@endpush
