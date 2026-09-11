@extends('layouts.vendor')

@section('title', 'Product Bulk Upload & Backup Restore')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h4 class="fw-bold mb-0" style="color: var(--garudo-text-main);">Bulk Operations & Catalog Data</h4>
            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1 small fw-semibold">Excel & Backup Center</span>
        </div>
        <p class="text-muted mb-0 small">Import hundreds of products via CSV/Excel, download pre-formatted templates, and take full JSON catalog backups.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('vendor.products.index') }}" class="btn btn-outline-secondary btn-sm px-3" style="border-radius: 8px;">
            <i class="bi bi-arrow-left me-1"></i> Back to Products
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show p-3 small mb-4" role="alert" style="border-radius: 12px;">
        <i class="bi bi-check-circle-fill me-2 fs-5"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show p-3 small mb-4" role="alert" style="border-radius: 12px;">
        <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Navigation Tabs -->
<ul class="nav nav-pills mb-4 gap-2" id="bulkTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active fw-semibold px-4" id="excel-tab" data-bs-toggle="pill" data-bs-target="#excelTabPane" type="button" role="tab" style="border-radius: 20px;">
            <i class="bi bi-file-earmark-spreadsheet me-2"></i> Excel / CSV Bulk Upload & Export
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-semibold px-4" id="backup-tab" data-bs-toggle="pill" data-bs-target="#backupTabPane" type="button" role="tab" style="border-radius: 20px;">
            <i class="bi bi-database-gear me-2"></i> Full Catalog Backup & Restore
        </button>
    </li>
</ul>

<div class="tab-content" id="bulkTabsContent">
    <!-- Tab 1: Excel / CSV Bulk Upload & Export -->
    <div class="tab-pane fade show active" id="excelTabPane" role="tabpanel">
        <div class="row g-4">
            <!-- Left: Upload Form -->
            <div class="col-lg-8">
                <div class="card-custom p-4 shadow-sm h-100">
                    <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-cloud-arrow-up text-success me-2"></i>Upload Products Spreadsheet (CSV)</h6>
                        <span class="badge bg-success bg-opacity-10 text-success small">Instant Processing</span>
                    </div>

                    <form action="{{ route('vendor.products.import') }}" method="POST" enctype="multipart/form-data" id="importCsvForm">
                        @csrf
                        <div class="p-5 border-2 border-dashed rounded text-center mb-3 position-relative" style="border: 2px dashed #CBD5E1; background: #F8FAFC;">
                            <input type="file" name="csv_file" id="csvFileInput" class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer" accept=".csv,text/csv" required onchange="displaySelectedFilename(this)">
                            
                            <div id="uploadPrompt">
                                <i class="bi bi-file-earmark-spreadsheet text-success fs-1 mb-2 d-block"></i>
                                <h6 class="fw-bold text-dark mb-1">Click to browse or drop your CSV file here</h6>
                                <p class="text-muted small mb-0">Supports formatted .csv files up to 10MB</p>
                            </div>

                            <div id="fileSelectedDisplay" class="d-none py-2">
                                <i class="bi bi-check-circle-fill text-success fs-2 mb-1 d-block"></i>
                                <div class="fw-bold text-dark" id="selectedFileName">filename.csv</div>
                                <div class="text-muted small" id="selectedFileSize">0 KB</div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-2 fw-bold shadow-sm" style="background-color: var(--garudo-primary); border: none; border-radius: 8px;">
                            <i class="bi bi-upload me-1"></i> Start Bulk Processing & Import
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right: Export & Instructions -->
            <div class="col-lg-4">
                <div class="card-custom p-4 shadow-sm mb-4">
                    <h6 class="fw-bold mb-3 text-dark border-bottom pb-2"><i class="bi bi-download text-success me-2"></i>Download & Templates</h6>
                    
                    <a href="{{ route('vendor.products.template') }}" class="btn btn-outline-success btn-sm w-100 mb-2 py-2 fw-semibold text-start">
                        <i class="bi bi-file-earmark-arrow-down me-2 fs-6"></i> Download Sample CSV Template
                    </a>
                    
                    <a href="{{ route('vendor.products.export') }}" class="btn btn-outline-dark btn-sm w-100 mb-3 py-2 fw-semibold text-start">
                        <i class="bi bi-cloud-download me-2 fs-6"></i> Export My Entire Product Catalog
                    </a>

                    <div class="p-3 bg-light rounded small border">
                        <div class="fw-bold text-dark mb-2"><i class="bi bi-info-circle text-primary me-1"></i>Required Columns:</div>
                        <ul class="ps-3 mb-0 text-muted" style="line-height: 1.6;">
                            <li><strong>Product Name:</strong> Title with grade & size</li>
                            <li><strong>Category:</strong> Auto-created if missing</li>
                            <li><strong>Selling Price & MRP:</strong> Standard B2B base price</li>
                            <li><strong>Unit & MOQ:</strong> Bag, Ton, Piece, etc.</li>
                            <li><strong>Specifications:</strong> JSON key-value format</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab 2: Full Catalog Backup & Restore -->
    <div class="tab-pane fade" id="backupTabPane" role="tabpanel">
        <div class="row g-4">
            <!-- Left: Download Full Backup -->
            <div class="col-lg-6">
                <div class="card-custom p-4 shadow-sm h-100">
                    <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-cloud-download text-success me-2"></i>Export Catalog Backup</h6>
                        <span class="badge bg-light text-muted border small">JSON Snapshot</span>
                    </div>

                    <p class="text-muted small mb-4">
                        Download a complete, single-file snapshot of your entire product catalog, including all technical specifications, variants, pricing matrices, inventory logs, and image URLs.
                    </p>

                    <div class="p-4 bg-light rounded-3 text-center mb-4 border">
                        <i class="bi bi-filetype-json text-warning fs-1 mb-2 d-block"></i>
                        <h6 class="fw-bold text-dark mb-1">Garudo Catalog JSON Backup</h6>
                        <p class="text-muted small mb-0">Contains all products & active specifications</p>
                    </div>

                    <a href="{{ route('vendor.products.backup') }}" class="btn btn-success w-100 py-2 fw-bold" style="background-color: var(--garudo-primary); border: none; border-radius: 8px;">
                        <i class="bi bi-download me-2"></i> Download Complete Backup File
                    </a>
                </div>
            </div>

            <!-- Right: Restore from Backup -->
            <div class="col-lg-6">
                <div class="card-custom p-4 shadow-sm h-100">
                    <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-arrow-counterclockwise text-danger me-2"></i>Restore Catalog from Backup</h6>
                        <span class="badge bg-warning bg-opacity-10 text-dark small">Database Sync</span>
                    </div>

                    <p class="text-muted small mb-3">
                        Upload a previously exported Garudo JSON backup file to restore your entire catalog.
                    </p>

                    <form action="{{ route('vendor.products.restore') }}" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Are you sure you want to restore this catalog? This will modify your current product listings according to your selected mode.');">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-dark">Select JSON Backup File</label>
                            <input type="file" name="backup_file" class="form-control form-control-sm" accept=".json,application/json" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-dark">Restore Strategy / Mode</label>
                            <select name="restore_mode" class="form-select form-select-sm" required>
                                <option value="overwrite">Overwrite existing matching products (Match by SKU)</option>
                                <option value="skip">Skip duplicates (Only add new missing products)</option>
                                <option value="clean">Clean Restore (Wipe existing and replace with backup)</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-outline-danger w-100 py-2 fw-bold" style="border-radius: 8px;">
                            <i class="bi bi-database-check me-2"></i> Execute Catalog Restore
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function displaySelectedFilename(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            document.getElementById('uploadPrompt').classList.add('d-none');
            document.getElementById('fileSelectedDisplay').classList.remove('d-none');
            document.getElementById('selectedFileName').innerText = file.name;
            document.getElementById('selectedFileSize').innerText = (file.size / 1024).toFixed(1) + ' KB';
        }
    }
</script>
@endpush
@endsection
