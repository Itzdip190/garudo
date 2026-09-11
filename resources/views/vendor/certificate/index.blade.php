@extends('layouts.vendor')

@section('title', 'Verified Channel Partner Certificate')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2">
            <h4 class="fw-bold mb-0" style="color: var(--garudo-text-main);">Verified Channel Partner Certificate</h4>
            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill">
                <i class="bi bi-patch-check-fill me-1"></i> Active & Authenticated
            </span>
        </div>
        <p class="text-muted mb-0 small mt-1">Official accreditation credential issued by GARUDO B2B Trust & Verification Council.</p>
    </div>

    <!-- Quick Actions Toolbar -->
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('vendor.certificate.download') }}" target="_blank" class="btn btn-garudo-primary btn-sm px-3 rounded-pill fw-bold shadow-sm">
            <i class="bi bi-printer-fill me-1"></i> Print / Download PDF
        </a>
        <button type="button" class="btn btn-outline-secondary btn-sm px-3 rounded-pill" onclick="copyVerificationLink()">
            <i class="bi bi-link-45deg me-1"></i> Copy Verification URL
        </button>
        <button type="button" class="btn btn-outline-info btn-sm px-3 rounded-pill" data-bs-toggle="modal" data-bs-target="#embedBadgeModal">
            <i class="bi bi-code-slash me-1"></i> Website Trust Badge
        </button>
    </div>
</div>

<!-- Certificate Preview Frame -->
<div class="row justify-content-center mb-5">
    <div class="col-xl-11">
        <div class="certificate-container shadow-lg rounded-4 overflow-hidden position-relative bg-white" style="border: 12px solid #0B4D3C; box-shadow: 0 20px 40px rgba(11, 77, 60, 0.15);">
            
            <!-- Ornate Inner Border -->
            <div class="certificate-inner-border p-4 p-md-5" style="border: 2px solid #D4AF37; margin: 8px; position: relative; background: #FFFDF9;">
                
                <!-- Watermark Background Logo -->
                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); opacity: 0.035; pointer-events: none; width: 450px;">
                    <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 100%; height: auto;">
                        <path d="M6 14C14 14 20 18 22 24C16 23 10 20 6 14Z" fill="#0B4D3C"/>
                        <path d="M8 20C16 20 22 24 24 30C18 29 12 26 8 20Z" fill="#0B4D3C"/>
                        <path d="M12 26C18 26 24 29 26 35C20 34 15 31 12 26Z" fill="#0B4D3C"/>
                        <path d="M22 24C26 16 34 12 42 12C36 18 30 22 24 30C23 27 22 25 22 24Z" fill="#0B4D3C"/>
                    </svg>
                </div>

                <!-- Certificate Header -->
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center gap-2 mb-2">
                        <svg viewBox="0 0 48 48" fill="none" style="width: 38px; height: 38px;" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 14C14 14 20 18 22 24C16 23 10 20 6 14Z" fill="#0B4D3C"/>
                            <path d="M8 20C16 20 22 24 24 30C18 29 12 26 8 20Z" fill="#0B4D3C"/>
                            <path d="M12 26C18 26 24 29 26 35C20 34 15 31 12 26Z" fill="#0B4D3C"/>
                            <path d="M22 24C26 16 34 12 42 12C36 18 30 22 24 30C23 27 22 25 22 24Z" fill="#0B4D3C"/>
                        </svg>
                        <span class="fs-3 fw-bold" style="color: #0B4D3C; letter-spacing: 2px;">GARUDO</span>
                    </div>
                    <div class="text-uppercase fw-semibold small" style="color: #D4AF37; letter-spacing: 4px;">National B2B Trade & Construction Materials Marketplace</div>
                    <h1 class="display-6 fw-bold mt-3 mb-2" style="font-family: 'Cinzel', Georgia, serif; color: #0B4D3C; letter-spacing: 1px;">
                        Certificate of Channel Partnership
                    </h1>
                    <div class="text-muted small text-uppercase" style="letter-spacing: 2px;">This accreditation certifies that</div>
                </div>

                <!-- Recipient Business Name -->
                <div class="text-center my-4 py-2 border-top border-bottom" style="border-color: rgba(212, 175, 55, 0.3) !important;">
                    <h2 class="fw-bold mb-1" style="color: #0B4D3C; font-size: 2.1rem; font-family: 'Inter', sans-serif;">
                        {{ $vendor->company_name }}
                    </h2>
                    <div class="text-muted fw-semibold">
                        Proprietor / Signatory: <span class="text-dark">{{ $vendor->name }}</span> 
                        &nbsp;|&nbsp; GSTIN: <span class="font-monospace text-dark">{{ $vendor->gstin ?? 'VERIFIED' }}</span>
                    </div>
                </div>

                <!-- Certificate Body Text -->
                <div class="text-center text-secondary mx-auto mb-5" style="max-width: 780px; font-size: 0.95rem; line-height: 1.8;">
                    has fulfilled all rigorous statutory compliance, GST verification, banking criteria, and enterprise authenticity audits established by <strong>GARUDO</strong>, and is officially recognized as an <strong>Authorized Verified Channel Partner</strong>.
                </div>

                <!-- Bottom Credential Details Grid -->
                <div class="row align-items-center g-4 pt-4 border-top" style="border-color: rgba(212, 175, 55, 0.3) !important;">
                    <!-- Left: Certificate Metas -->
                    <div class="col-md-4">
                        <div class="small text-muted text-uppercase fw-semibold" style="letter-spacing: 1px;">Certificate Details</div>
                        <div class="mt-2">
                            <div class="small"><strong class="text-dark">Certificate ID:</strong> <code class="text-success fw-bold">{{ $certificate->certificate_number }}</code></div>
                            <div class="small"><strong class="text-dark">Vendor ID:</strong> <span class="text-secondary">#GAR-V{{ str_pad($vendor->id, 5, '0', STR_PAD_LEFT) }}</span></div>
                            <div class="small"><strong class="text-dark">Date of Issue:</strong> <span class="text-secondary">{{ $certificate->issue_date->format('d F, Y') }}</span></div>
                            <div class="small"><strong class="text-dark">Valid Until:</strong> <span class="text-secondary">{{ $certificate->expiry_date->format('d F, Y') }} (1 Year)</span></div>
                        </div>
                    </div>

                    <!-- Center: Dynamic Verification QR Code -->
                    <div class="col-md-4 text-center">
                        <div class="bg-white p-2 border rounded-3 shadow-sm d-inline-block" style="width: 120px; height: 120px;">
                            {!! $certificate->qr_code_svg !!}
                        </div>
                        <div class="mt-2">
                            <span class="badge bg-light text-dark border small">
                                <i class="bi bi-qr-code-scan me-1 text-success"></i> Scan to Verify Authenticity
                            </span>
                        </div>
                    </div>

                    <!-- Right: Digital Seal & Signature -->
                    <div class="col-md-4 text-md-end text-center">
                        <div class="d-inline-block text-center">
                            <div class="mb-1" style="font-family: 'Brush Script MT', cursive, Georgia; font-size: 1.8rem; color: #0B4D3C;">
                                Garudo Council
                            </div>
                            <div style="width: 180px; height: 1.5px; background: #0B4D3C; margin: 0 auto;"></div>
                            <div class="small fw-bold text-dark mt-1">Authorized Digital Signatory</div>
                            <div class="text-muted" style="font-size: 0.7rem;">Compliance & Trust Directorate</div>
                            <div class="text-muted font-monospace mt-1" style="font-size: 0.6rem;">
                                SHA256: {{ substr($certificate->digital_signature_hash, 0, 16) }}...
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Embed Badge Modal -->
<div class="modal fade" id="embedBadgeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h6 class="modal-title fw-bold"><i class="bi bi-code-slash text-warning me-2"></i>Embed Garudo Trust Badge on Your Website</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small">Showcase your verified channel partnership on your own corporate website or email signature. Copy and paste the HTML code below:</p>
                <div class="p-3 bg-light rounded-3 font-monospace small mb-3 border text-break">
                    &lt;a href="{{ $certificate->verification_url }}" target="_blank" rel="noopener"&gt;&lt;img src="{{ asset('images/badges/verified-partner-badge.png') }}" alt="Garudo Verified Channel Partner" width="180"&gt;&lt;/a&gt;
                </div>
                <div class="text-center p-3 border rounded-3 bg-white">
                    <span class="badge bg-success py-2 px-3 fs-6 rounded-pill">
                        <i class="bi bi-patch-check-fill me-1"></i> GARUDO Verified Channel Partner
                    </span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success fw-bold" onclick="navigator.clipboard.writeText('{{ $certificate->verification_url }}'); alert('Verification URL copied to clipboard!');">
                    Copy URL
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function copyVerificationLink() {
    const url = "{{ $certificate->verification_url }}";
    navigator.clipboard.writeText(url).then(() => {
        alert("Official verification link copied to clipboard:\n" + url);
    });
}
</script>
@endsection
