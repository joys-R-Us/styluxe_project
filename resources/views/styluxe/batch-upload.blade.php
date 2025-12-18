@extends('styluxe.layouts.dashboard')

@section('title', 'Batch Upload - Styluxe')

@section('content')
<div class="admin-batch-upload">
    <div class="page-header">
        <div>
            <h1 class="page-title">📤 Batch Upload Items</h1>
            <p class="text-muted">Import multiple items from a CSV file</p>
        </div>
        <a href="{{ route('styluxe.items.index-public') }}" class="btn btn-outline-secondary">
            ← Back to Items
        </a>
    </div>

    {{-- alerts displayed in layout --}}

    <div class="row">
        <div class="col-lg-8">
            <!-- Upload Form -->
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('styluxe.batch-upload.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="csv_file" class="form-label">Select CSV File</label>
                            <input 
                                type="file" 
                                id="csv_file" 
                                name="csv_file" 
                                accept=".csv,.txt" 
                                class="form-control @error('csv_file') is-invalid @enderror"
                                required>
                            @error('csv_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-2">
                                Supported formats: CSV (.csv) or TXT (.txt) | Max 2MB
                            </small>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                📤 Upload & Import
                            </button>
                            <a href="{{ asset('samples/batch-upload-sample.csv') }}" class="btn btn-outline-secondary btn-lg" download>
                                ⬇️ Download Sample CSV
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- CSV Format Guide -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">📋 CSV Format Guide</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <strong>💡 Tip:</strong> Download our <a href="{{ asset('samples/batch-upload-sample.csv') }}" download class="alert-link">sample CSV file</a> to see the correct format and fill it with your items!
                    </div>
                    <p class="text-muted">Your CSV file must have the following columns (in order):</p>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Column Name</th>
                                    <th>Type</th>
                                    <th>Example</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><code>item_name</code></td>
                                    <td>String</td>
                                    <td>Vintage Blue Denim Jacket</td>
                                </tr>
                                <tr>
                                    <td><code>category</code></td>
                                    <td>String</td>
                                    <td>Outerwear</td>
                                </tr>
                                <tr>
                                    <td><code>size</code></td>
                                    <td>String</td>
                                    <td>M</td>
                                </tr>
                                <tr>
                                    <td><code>color</code></td>
                                    <td>String</td>
                                    <td>Blue</td>
                                </tr>
                                <tr>
                                    <td><code>condition</code></td>
                                    <td>New|Pre-Loved|Vintage|Branded</td>
                                    <td>Pre-Loved</td>
                                </tr>
                                <tr>
                                    <td><code>description</code></td>
                                    <td>String</td>
                                    <td>Great condition, minimal wear</td>
                                </tr>
                                <tr>
                                    <td><code>quantity</code></td>
                                    <td>Integer</td>
                                    <td>5</td>
                                </tr>
                                <tr>
                                    <td><code>price</code></td>
                                    <td>Decimal</td>
                                    <td>299.99</td>
                                </tr>
                                <tr>
                                    <td><code>status</code></td>
                                    <td>Available|Out-Of-Stock|Sold Out</td>
                                    <td>Available</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="alert alert-info mt-3">
                        <strong>Sample CSV row:</strong><br>
                        <code>Vintage Blue Denim Jacket,Outerwear,M,Blue,Pre-Loved,Great condition,5,299.99,Available</code>
                    </div>
                </div>
            </div>

            <!-- Quick Start Guide -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">🚀 Quick Start Guide</h5>
                </div>
                <div class="card-body">
                    <ol>
                        <li class="mb-3">
                            <strong>Download the sample:</strong> Click the "⬇️ Download Sample CSV" button above to get a pre-formatted template.
                        </li>
                        <li class="mb-3">
                            <strong>Fill in your data:</strong> Open the CSV file in Excel or Google Sheets and add your items. Keep the column headers exactly as shown.
                        </li>
                        <li class="mb-3">
                            <strong>Save as CSV:</strong> Make sure to save the file as CSV format (.csv), not Excel format.
                        </li>
                        <li class="mb-3">
                            <strong>Upload:</strong> Select your CSV file and click "📤 Upload & Import".
                        </li>
                        <li class="mb-3">
                            <strong>Review results:</strong> Check the success message for import count. Any errors will be reported.
                        </li>
                        <li>
                            <strong>Verify:</strong> Go to Items Management to verify your items were imported correctly.
                        </li>
                    </ol>
                    <div class="alert alert-warning mt-3 mb-0">
                        <strong>⚠️ Important:</strong> Each item gets an auto-generated barcode. Do NOT include barcodes in your CSV file.
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">ℹ️ Tips</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <strong>File Size:</strong>
                            <p class="text-muted small mb-0">Maximum 2 MB per file</p>
                        </li>
                        <li class="mb-3">
                            <strong>Barcodes:</strong>
                            <p class="text-muted small mb-0">Auto-generated for each item</p>
                        </li>
                        <li class="mb-3">
                            <strong>Low Stock:</strong>
                            <p class="text-muted small mb-0">Default threshold: 20 units</p>
                        </li>
                        <li class="mb-3">
                            <strong>Error Handling:</strong>
                            <p class="text-muted small mb-0">Failed rows are reported, but valid rows import</p>
                        </li>
                        <li>
                            <strong>Stock Logging:</strong>
                            <p class="text-muted small mb-0">Each import is logged in stock history</p>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">⚠️ Validation Rules</h5>
                </div>
                <div class="card-body text-muted small">
                    <ul class="mb-0">
                        <li>Item name required (max 255 chars)</li>
                        <li>Category required</li>
                        <li>Size required</li>
                        <li>Condition must match allowed values</li>
                        <li>Quantity must be numeric</li>
                        <li>Price must be numeric</li>
                        <li>Status must match allowed values</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.admin-batch-upload {
    padding: 2rem 0;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
}

code {
    background: #f5f5f5;
    padding: 0.2rem 0.4rem;
    border-radius: 3px;
    font-size: 0.9rem;
}

.table-sm td {
    padding: 0.5rem;
    vertical-align: middle;
}
</style>
@endsection
