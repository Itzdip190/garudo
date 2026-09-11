<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate_{{ $certificate->certificate_number }} - GARUDO Verified Channel Partner</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }

        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body {
            margin: 0;
            padding: 20px;
            background: #f1f5f9;
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .cert-outer {
            width: 297mm;
            height: 210mm;
            background: #ffffff;
            border: 14px solid #0B4D3C;
            padding: 10px;
            position: relative;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        .cert-inner {
            border: 2.5px solid #D4AF37;
            height: 100%;
            background: #FFFDF9;
            padding: 30px 45px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.04;
            width: 420px;
            pointer-events: none;
        }

        .header {
            text-align: center;
        }

        .brand-title {
            font-size: 26px;
            font-weight: 800;
            color: #0B4D3C;
            letter-spacing: 3px;
        }

        .sub-tagline {
            font-size: 11px;
            color: #D4AF37;
            font-weight: 700;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-top: 2px;
        }

        .cert-title {
            font-family: 'Cinzel', serif;
            font-size: 32px;
            font-weight: 700;
            color: #0B4D3C;
            margin: 12px 0 4px 0;
            letter-spacing: 1px;
        }

        .cert-subtitle {
            font-size: 12px;
            color: #64748B;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .vendor-name-box {
            text-align: center;
            margin: 10px 0;
            padding: 12px 0;
            border-top: 1px solid rgba(212, 175, 55, 0.35);
            border-bottom: 1px solid rgba(212, 175, 55, 0.35);
        }

        .company-name {
            font-size: 30px;
            font-weight: 800;
            color: #0B4D3C;
            margin-bottom: 4px;
        }

        .signatory-meta {
            font-size: 13px;
            color: #475569;
            font-weight: 600;
        }

        .body-text {
            text-align: center;
            font-size: 13px;
            line-height: 1.7;
            color: #475569;
            max-width: 820px;
            margin: 0 auto;
        }

        .footer-grid {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            border-top: 1px solid rgba(212, 175, 55, 0.35);
            padding-top: 15px;
        }

        .meta-col {
            font-size: 11px;
            color: #334155;
            line-height: 1.6;
        }

        .meta-col strong {
            color: #0F172A;
        }

        .qr-col {
            text-align: center;
        }

        .qr-box {
            width: 90px;
            height: 90px;
            background: #ffffff;
            border: 1px solid #E2E8F0;
            border-radius: 6px;
            padding: 4px;
            margin: 0 auto 4px auto;
        }

        .sign-col {
            text-align: right;
        }

        .sig-font {
            font-family: 'Brush Script MT', cursive, Georgia;
            font-size: 28px;
            color: #0B4D3C;
            line-height: 1;
        }

        .sig-line {
            width: 170px;
            height: 1.5px;
            background: #0B4D3C;
            margin: 4px 0 4px auto;
        }

        .sig-meta {
            font-size: 10px;
            color: #64748B;
        }

        .no-print-bar {
            position: fixed;
            top: 15px;
            right: 15px;
            z-index: 1000;
            display: flex;
            gap: 10px;
        }

        .btn-print {
            background: #0B4D3C;
            color: #ffffff;
            border: none;
            padding: 8px 18px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        @media print {
            body {
                background: none;
                padding: 0;
            }
            .cert-outer {
                width: 100vw;
                height: 100vh;
                border-width: 10mm;
                box-shadow: none;
            }
            .no-print-bar {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <div class="no-print-bar">
        <button class="btn-print" onclick="window.print()">🖨️ Print / Save as PDF</button>
        <button class="btn-print" style="background:#475569;" onclick="window.close()">✕ Close</button>
    </div>

    <div class="cert-outer">
        <div class="cert-inner">
            <!-- Watermark -->
            <div class="watermark">
                <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 14C14 14 20 18 22 24C16 23 10 20 6 14Z" fill="#0B4D3C"/>
                    <path d="M8 20C16 20 22 24 24 30C18 29 12 26 8 20Z" fill="#0B4D3C"/>
                    <path d="M12 26C18 26 24 29 26 35C20 34 15 31 12 26Z" fill="#0B4D3C"/>
                    <path d="M22 24C26 16 34 12 42 12C36 18 30 22 24 30C23 27 22 25 22 24Z" fill="#0B4D3C"/>
                </svg>
            </div>

            <!-- Header -->
            <div class="header">
                <div class="brand-title">GARUDO</div>
                <div class="sub-tagline">National B2B Trade & Construction Materials Marketplace</div>
                <div class="cert-title">Certificate of Channel Partnership</div>
                <div class="cert-subtitle">This accreditation certifies that</div>
            </div>

            <!-- Recipient -->
            <div class="vendor-name-box">
                <div class="company-name">{{ $vendor->company_name }}</div>
                <div class="signatory-meta">
                    Proprietor: {{ $vendor->name }} &nbsp;|&nbsp; GSTIN: {{ $vendor->gstin ?? 'VERIFIED' }} &nbsp;|&nbsp; Category: {{ ucfirst($vendor->business_type ?? 'Manufacturer') }}
                </div>
            </div>

            <!-- Body -->
            <div class="body-text">
                has fulfilled all rigorous statutory compliance, GST verification, banking criteria, and enterprise authenticity audits established by <strong>GARUDO</strong>, and is officially recognized as an <strong>Authorized Verified Channel Partner</strong>.
            </div>

            <!-- Footer Grid -->
            <div class="footer-grid">
                <!-- Metas -->
                <div class="meta-col">
                    <div><strong>Certificate No:</strong> {{ $certificate->certificate_number }}</div>
                    <div><strong>Vendor ID:</strong> #GAR-V{{ str_pad($vendor->id, 5, '0', STR_PAD_LEFT) }}</div>
                    <div><strong>Date of Issue:</strong> {{ $certificate->issue_date->format('d F, Y') }}</div>
                    <div><strong>Valid Until:</strong> {{ $certificate->expiry_date->format('d F, Y') }} (1 Year)</div>
                </div>

                <!-- QR -->
                <div class="qr-col">
                    <div class="qr-box">
                        {!! $certificate->qr_code_svg !!}
                    </div>
                    <div style="font-size: 9px; color: #0B4D3C; font-weight: 700; text-transform: uppercase;">
                        Scan to Verify Authenticity
                    </div>
                </div>

                <!-- Signatory -->
                <div class="sign-col">
                    <div class="sig-font">Garudo Council</div>
                    <div class="sig-line"></div>
                    <div style="font-size: 11px; font-weight: 700; color: #0F172A;">Authorized Digital Signatory</div>
                    <div class="sig-meta">Compliance & Trust Directorate</div>
                    <div class="sig-meta" style="font-family: monospace; font-size: 8px;">
                        SHA256: {{ substr($certificate->digital_signature_hash, 0, 20) }}...
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
