@extends('backend.master')

@section('title', 'Stores List')

@section('body')

    @php
        $totalStores = $stores->count();
        $withLayouts = $stores->filter(fn($s) => $s->storeLayouts && $s->storeLayouts->count() > 0)->count();
        $avgRentPerSqft = $stores->where('total_area_sqft', '>', 0)->avg(fn($s) => $s->monthly_rent / $s->total_area_sqft);
        $totalLocations = $stores->whereNotNull('division_id')->pluck('division_id')->unique()->count();
        $activeStores = $stores->where('status', 1)->count();
    @endphp

    <!-- Main Content -->
    <div class="container px-3 px-lg-4 py-3">

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house me-1"></i>Home</a></li>
                <li class="breadcrumb-item active"><i class="bi bi-shop me-1"></i>Store Management</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="d-flex flex-wrap justify-content-between align-items-start mb-3">
            <div>
                <h1 class="page-title mb-1">Store Management</h1>
                <p class="page-subtitle mb-0">Manage store information, layouts, and calculate branding costs across all locations</p>
            </div>
            <div class="page-header-actions d-flex gap-2">
                <button class="btn btn-outline-secondary btn-sm"><i class="bi bi-download me-1"></i>Export Data</button>
                <button class="btn btn-warning btn-sm text-white" id="btn-add-store"><i class="bi bi-plus me-1"></i>Add Store</button>
            </div>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs-custom nav-tabs border-bottom mb-3" id="storeTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="stores-tab" data-bs-toggle="tab" data-bs-target="#storesPane" type="button" role="tab">
                    <i class="bi bi-shop me-1"></i>Stores
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="layouts-tab" data-bs-toggle="tab" data-bs-target="#layoutsPane" type="button" role="tab">
                    <i class="bi bi-grid-1x2 me-1"></i>Layouts
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="storeTabContent">

            <!-- ========== STORES TAB ========== -->
            <div class="tab-pane fade show active" id="storesPane" role="tabpanel">

                <!-- Stat Cards -->
                <div class="row g-3 mb-3">
                    <div class="col-6 col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon" style="background:#eef1f6;color:#2c3e6b;"><i class="bi bi-shop"></i></div>
                            <div>
                                <div class="stat-value">{{ $totalStores }}</div>
                                <div class="stat-label">Total Stores</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon" style="background:#e8f5e9;color:#2e7d32;"><i class="bi bi-check-circle"></i></div>
                            <div>
                                <div class="stat-value">{{ $activeStores }}</div>
                                <div class="stat-label">Active Stores</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon" style="background:#fff3e0;color:#e65100;"><i class="bi bi-graph-up"></i></div>
                            <div>
                                <div class="stat-value">{{ $avgRentPerSqft ? number_format($avgRentPerSqft, 0) . '৳' : '—' }}</div>
                                <div class="stat-label">Avg Rent/Sq Ft</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon" style="background:#ede7f6;color:#5e35b1;"><i class="bi bi-geo-alt"></i></div>
                            <div>
                                <div class="stat-value">{{ $totalLocations }}</div>
                                <div class="stat-label">Locations</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Store Directory Card -->
                <div class="content-card">
                    <div class="card-header-area">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <h5 class="mb-1">Store Directory</h5>
                                <p class="mb-0">Manage store information and calculate branding costs</p>
                            </div>
                            <span class="text-muted store-count-label" style="font-size:0.85rem;">{{ $totalStores }} of {{ $totalStores }} stores</span>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="px-3 pb-3">
                        <div class="row g-2 filter-row">
                            <div class="col-12 col-sm-6 col-md-3">
                                <input type="text" class="form-control form-control-sm" id="filter-search" placeholder="Search stores...">
                            </div>
                            <div class="col-6 col-md-3">
                                <select class="form-select form-select-sm" id="filter-division">
                                    <option value="">Filter by location</option>
                                    @foreach($divisions as $division)
                                        <option value="{{ $division->name }}">{{ $division->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 col-md-3">
                                <select class="form-select form-select-sm" id="filter-size">
                                    <option value="">Filter by size</option>
                                    <option value="small">&lt; 500 sq ft</option>
                                    <option value="medium">500 - 1000 sq ft</option>
                                    <option value="large">&gt; 1000 sq ft</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-3">
                                <select class="form-select form-select-sm" id="filter-rent">
                                    <option value="">Filter by rent</option>
                                    <option value="low">&lt; 50,000৳</option>
                                    <option value="mid">50,000 - 80,000৳</option>
                                    <option value="high">&gt; 80,000৳</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table store-table mb-0" id="stores-table">
                            <thead>
                            <tr>
                                <th>Store Name <i class="bi bi-arrow-down-up" style="font-size:0.65rem;"></i></th>
                                <th>Location <i class="bi bi-arrow-down-up" style="font-size:0.65rem;"></i></th>
                                <th>Size (sq ft) <i class="bi bi-arrow-down-up" style="font-size:0.65rem;"></i></th>
                                <th>Monthly Rent <i class="bi bi-arrow-down-up" style="font-size:0.65rem;"></i></th>
                                <th>Rent/sq ft <i class="bi bi-arrow-down-up" style="font-size:0.65rem;"></i></th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($stores as $store)
                                @php
                                    $rentPerSqft = ($store->total_area_sqft > 0 && $store->monthly_rent > 0)
                                        ? number_format($store->monthly_rent / $store->total_area_sqft, 2)
                                        : '—';
                                @endphp
                                <tr data-size="{{ $store->total_area_sqft }}" data-rent="{{ $store->monthly_rent }}">
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="store-icon"><i class="bi bi-shop"></i></span>
                                            <div>
                                                <div class="store-name">{{ $store->title }}</div>
                                                <div class="store-id">ID: {{ $store->code }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <i class="bi bi-geo-alt text-muted me-1" style="font-size:0.75rem;"></i>
                                        {{ $store->division?->name ?? '—' }}
                                    </td>
                                    <td>{{ $store->total_area_sqft ? number_format($store->total_area_sqft) : '—' }}</td>
                                    <td>{{ $store->monthly_rent ? number_format($store->monthly_rent) . '৳' : '—' }}</td>
                                    <td class="text-warning">{{ $rentPerSqft !== '—' ? $rentPerSqft . '৳' : '—' }}</td>
                                    <td>
                                        @if($store->status == 1)
                                            <span class="badge badge-current rounded-pill"><i class="bi bi-check-circle me-1"></i>Active</span>
                                        @else
                                            <span class="badge badge-pending rounded-pill"><i class="bi bi-clock me-1"></i>Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn-action btn-view" data-id="{{ $store->id }}" title="View"><i class="bi bi-eye"></i></button>
                                        <button class="btn-action btn-edit" data-id="{{ $store->id }}" title="Edit"><i class="bi bi-pencil-square"></i></button>
                                        <button class="btn-action text-danger btn-delete" data-id="{{ $store->id }}" data-name="{{ $store->title }}" title="Delete"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ========== LAYOUTS TAB ========== -->
            <div class="tab-pane fade" id="layoutsPane" role="tabpanel">

                <!-- Search & Filter Bar -->
                <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
                    <input type="text" class="form-control form-control-sm" placeholder="Search stores..." style="max-width:280px;">
                    <select class="form-select form-select-sm" style="max-width:200px;">
                        <option>All Locations</option>
                        @foreach($divisions as $division)
                            <option value="{{ $division->id }}">{{ $division->name }}</option>
                        @endforeach
                    </select>
                    <span class="ms-auto text-muted" style="font-size:0.85rem;">{{ $totalStores }} stores found</span>
                </div>

                <div class="row g-3">
                    <!-- Left Sidebar - Store List -->
                    <div class="col-12 col-md-5 col-lg-4">
                        <div class="content-card layout-sidebar">
                            <div class="p-3 border-bottom">
                                <h6 class="fw-bold mb-1">Store Layouts</h6>
                                <small class="text-muted">Select a store to view and manage its layouts</small>
                            </div>
                            <div class="store-list">
                                @foreach($stores as $store)
                                    <div class="store-list-item {{ $loop->first ? 'active' : '' }}" data-store-id="{{ $store->id }}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="store-list-name">{{ $store->title }}</div>
                                                <div class="store-list-meta">{{ $store->division?->name ?? 'N/A' }} &bull; {{ $store->code }}</div>
                                                @if($store->storeLayouts && $store->storeLayouts->count() > 0)
                                                    <div class="version-badge"><i class="bi bi-check-circle me-1"></i>Has Layout</div>
                                                @else
                                                    <div class="no-layout-badge"><i class="bi bi-clock me-1"></i>No layout uploaded</div>
                                                @endif
                                            </div>
                                            <i class="bi bi-chevron-right text-muted"></i>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Right Content - Layout Detail -->
                    <div class="col-12 col-md-7 col-lg-8">
                        <div class="content-card p-3 p-lg-4">
                            <!-- Header -->
                            <div class="d-flex flex-wrap justify-content-between align-items-start mb-3">
                                <div class="layout-detail-header">
                                    <h5 class="mb-1" id="layout-store-name">{{ $stores->first()?->title ?? 'No Store Selected' }}</h5>
                                    <p class="text-muted mb-0" style="font-size:0.85rem;" id="layout-store-address">{{ $stores->first()?->division?->name ?? '' }} &bull; {{ $stores->first()?->address ?? '' }}</p>
                                </div>
                                <button class="btn btn-warning btn-sm text-white mt-2 mt-md-0">
                                    <i class="bi bi-upload me-1"></i>Upload New Version
                                </button>
                            </div>

                            <!-- Layout Tabs -->
                            <ul class="nav nav-tabs-custom nav-tabs border-bottom mb-3" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#currentLayoutPane" type="button">
                                        <i class="bi bi-eye me-1"></i>Current Layout
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#versionHistoryPane" type="button">
                                        <i class="bi bi-clock-history me-1"></i>Version History
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <!-- Current Layout -->
                                <div class="tab-pane fade show active" id="currentLayoutPane">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="fw-bold mb-0">Current Layout</h6>
                                        <button class="btn btn-outline-secondary btn-sm"><i class="bi bi-download me-1"></i>Download</button>
                                    </div>

                                    <!-- Preview placeholder -->
                                    <div class="layout-preview-placeholder">
                                        <i class="bi bi-image d-block mb-2"></i>
                                        <div class="fw-semibold" style="font-size:0.9rem;">Layout Preview</div>
                                        <small>Select a store to view its layout</small>
                                    </div>
                                </div>

                                <!-- Version History -->
                                <div class="tab-pane fade" id="versionHistoryPane">
                                    <p class="text-muted">Version history will appear here.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- /tab-content -->
    </div><!-- /container -->

@endsection

@section('modal')
    {{-- Create / Edit Modal --}}
    <div class="modal fade" id="storeModal" tabindex="-1" aria-labelledby="storeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="d-flex align-items-center gap-2">
                        <span class="modal-title-icon"><i class="bi bi-shop"></i></span>
                        <div>
                            <h5 class="modal-title mb-0 fw-bold" id="storeModalLabel" style="font-size:1.05rem;">Add Store</h5>
                            <small class="text-muted" id="storeModalSubtitle">Create a new store entry</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="storeForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" id="store_id" value="">

                        <!-- Basic Information -->
                        <div class="form-section-title">Basic Information</div>
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-5">
                                <label class="form-label fw-semibold" style="font-size:0.85rem;">Store Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="title" name="title" placeholder="Enter store name">
                                <div class="invalid-feedback" id="error-title"></div>
                            </div>
                            <div class="col-6 col-md-2">
                                <label class="form-label fw-semibold" style="font-size:0.85rem;">Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm text-uppercase" id="code" name="code" placeholder="Auto" maxlength="3" readonly>
                                <div class="invalid-feedback" id="error-code"></div>
                            </div>
                            <div class="col-6 col-md-2">
                                <label class="form-label fw-semibold" style="font-size:0.85rem;">Store Code</label>
                                <input type="text" class="form-control form-control-sm" id="store_code" name="store_code" placeholder="e.g. S001">
                                <div class="invalid-feedback" id="error-store_code"></div>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label fw-semibold" style="font-size:0.85rem;">Status <span class="text-danger">*</span></label>
                                <select class="form-select form-select-sm" id="status" name="status">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                <div class="invalid-feedback" id="error-status"></div>
                            </div>
                        </div>

                        <!-- Store Dimensions & Rent -->
                        <div class="form-section-title">Store Dimensions & Rent</div>
                        <div class="row g-3 mb-3">
                            <div class="col-6 col-md-3">
                                <label class="form-label fw-semibold" style="font-size:0.85rem;">Store Size (sq ft)</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="total_area_sqft" name="total_area_sqft" placeholder="0.00">
                                <div class="invalid-feedback" id="error-total_area_sqft"></div>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label fw-semibold" style="font-size:0.85rem;">Monthly Rent (৳)</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="monthly_rent" name="monthly_rent" placeholder="0.00">
                                <div class="invalid-feedback" id="error-monthly_rent"></div>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label fw-semibold" style="font-size:0.85rem;">Rent per Sq Ft (৳)</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="per_sqr_feet_rent" name="per_sqr_feet_rent" placeholder="0.00">
                                <div class="invalid-feedback" id="error-per_sqr_feet_rent"></div>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label fw-semibold" style="font-size:0.85rem;">Opened Date</label>
                                <input type="date" class="form-control form-control-sm" id="opened_date" name="opened_date">
                                <div class="invalid-feedback" id="error-opened_date"></div>
                            </div>
                        </div>
                        <div class="calculated-rent mb-3" id="calculated-rent-display" style="display:none;">
                            <i class="bi bi-info-circle me-1"></i> Calculated Rent per Sq Ft: <strong id="calc-rent-value">—</strong>
                        </div>

                        <!-- Location -->
                        <div class="form-section-title">Location Information</div>
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold" style="font-size:0.85rem;">Division</label>
                                <select class="form-select form-select-sm" id="division_id" name="division_id">
                                    <option value="">— Select Division —</option>
                                    @foreach($divisions as $division)
                                        <option value="{{ $division->id }}">{{ $division->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="error-division_id"></div>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold" style="font-size:0.85rem;">District</label>
                                <select class="form-select form-select-sm" id="district_id" name="district_id" disabled>
                                    <option value="">— Select District —</option>
                                </select>
                                <div class="invalid-feedback" id="error-district_id"></div>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold" style="font-size:0.85rem;">Thana</label>
                                <select class="form-select form-select-sm" id="thana_id" name="thana_id" disabled>
                                    <option value="">— Select Thana —</option>
                                </select>
                                <div class="invalid-feedback" id="error-thana_id"></div>
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold" style="font-size:0.85rem;">Area/Locality</label>
                                <input type="text" class="form-control form-control-sm" id="area" name="area" placeholder="Area or locality name">
                                <div class="invalid-feedback" id="error-area"></div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold" style="font-size:0.85rem;">Postal Code</label>
                                <input type="text" class="form-control form-control-sm" id="postal_code" name="postal_code" placeholder="Postal Code">
                                <div class="invalid-feedback" id="error-postal_code"></div>
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold" style="font-size:0.85rem;">Full Address</label>
                                <textarea class="form-control form-control-sm" id="address" name="address" rows="2" placeholder="Full address"></textarea>
                                <div class="invalid-feedback" id="error-address"></div>
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold" style="font-size:0.85rem;">Latitude</label>
                                <input type="text" class="form-control form-control-sm" id="latitude" name="latitude" placeholder="e.g. 23.8103">
                                <div class="invalid-feedback" id="error-latitude"></div>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold" style="font-size:0.85rem;">Longitude</label>
                                <input type="text" class="form-control form-control-sm" id="longitude" name="longitude" placeholder="e.g. 90.4125">
                                <div class="invalid-feedback" id="error-longitude"></div>
                            </div>
                        </div>

                        <!-- Store Layout -->
                        <div class="form-section-title">Store Layout</div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size:0.85rem;">Upload Layout (PDF)</label>
                            <input type="file" class="filepond-pdf" id="store_layout_pdf" name="store_layout_pdf" accept="application/pdf">
                            <div class="invalid-feedback d-block" id="error-store_layout_pdf" style="display:none !important;"></div>
                            <small class="text-muted d-block mt-1">Upload a new layout file. Max 10MB.</small>
                        </div>

                        <!-- Contact Information -->
                        <div class="form-section-title">Contact Information</div>
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold" style="font-size:0.85rem;">Contact Person</label>
                                <input type="text" class="form-control form-control-sm" id="contact_persion" name="contact_persion" placeholder="Contact person name">
                                <div class="invalid-feedback" id="error-contact_persion"></div>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold" style="font-size:0.85rem;">Phone Number</label>
                                <input type="text" class="form-control form-control-sm" id="shop_official_mobile" name="shop_official_mobile" placeholder="Mobile number">
                                <div class="invalid-feedback" id="error-shop_official_mobile"></div>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold" style="font-size:0.85rem;">Email Address</label>
                                <input type="email" class="form-control form-control-sm" id="shop_official_email" name="shop_official_email" placeholder="Email address">
                                <div class="invalid-feedback" id="error-shop_official_email"></div>
                            </div>
                        </div>

                        <!-- Store Manager -->
                        <div class="form-section-title">Management</div>
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold" style="font-size:0.85rem;">Store Manager</label>
                                <select class="form-select form-select-sm" id="store_manager_id" name="store_manager_id">
                                    <option value="">— Select Manager —</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="error-store_manager_id"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning btn-sm text-white" id="btn-save">
                            <span class="btn-text"><i class="bi bi-save me-1"></i>Save Store</span>
                            <span class="spinner-border spinner-border-sm d-none" id="btn-spinner"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- View Modal --}}
    <div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="d-flex align-items-center gap-2">
                        <span class="modal-title-icon"><i class="bi bi-shop"></i></span>
                        <div>
                            <h5 class="modal-title mb-0 fw-bold" style="font-size:1.05rem;">Store Details</h5>
                            <small class="text-muted">View complete store information</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered table-sm">
                                <tr><th width="40%">Title</th><td id="view-title"></td></tr>
                                <tr><th>Code</th><td id="view-code"></td></tr>
                                <tr><th>Store Code</th><td id="view-store-code"></td></tr>
                                <tr><th>Area (sqft)</th><td id="view-area-sqft"></td></tr>
                                <tr><th>Monthly Rent</th><td id="view-rent"></td></tr>
                                <tr><th>Per Sqft Rent</th><td id="view-per-sqft-rent"></td></tr>
                                <tr><th>Opened Date</th><td id="view-opened"></td></tr>
                                <tr><th>Manager</th><td id="view-manager"></td></tr>
                                <tr><th>Status</th><td id="view-status"></td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered table-sm">
                                <tr><th width="40%">Address</th><td id="view-address"></td></tr>
                                <tr><th>Area</th><td id="view-area"></td></tr>
                                <tr><th>Thana</th><td id="view-thana"></td></tr>
                                <tr><th>District</th><td id="view-district"></td></tr>
                                <tr><th>Division</th><td id="view-division"></td></tr>
                                <tr><th>Postal Code</th><td id="view-postal"></td></tr>
                                <tr><th>Coordinates</th><td id="view-coords"></td></tr>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered table-sm">
                                <tr><th width="20%">Contact Person</th><td id="view-contact"></td></tr>
                                <tr><th>Mobile</th><td id="view-mobile"></td></tr>
                                <tr><th>Email</th><td id="view-email"></td></tr>
                            </table>
                        </div>
                    </div>

                    {{-- Layout History --}}
                    <div id="view-layouts-section" class="mt-3" style="display:none;">
                        <h6 class="fw-semibold text-muted mb-2"><i class="bi bi-grid-1x2 me-1"></i> Layout History</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Changed At</th>
                                        <th>PDF</th>
                                        <th>Active</th>
                                    </tr>
                                </thead>
                                <tbody id="view-layouts-body"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content text-center">
                <div class="modal-body p-4">
                    <div class="mb-3"><i class="bi bi-trash text-danger" style="font-size: 3rem;"></i></div>
                    <h6>Delete Store</h6>
                    <p class="text-muted mb-0">Are you sure you want to delete <strong id="delete-store-name"></strong>?</p>
                    <input type="hidden" id="delete-store-id">
                </div>
                <div class="modal-footer justify-content-center border-0 pt-0">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-sm btn-danger" id="btn-confirm-delete">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('backend/build/assets/libs/filepond/filepond.min.css') }}">
<style>
    .filepond--root { margin-bottom: 0; }
    .calculated-rent {
        background: #fff8e1;
        border: 1px solid #ffe082;
        border-radius: 6px;
        padding: 8px 12px;
        font-size: 0.85rem;
        color: #f57c00;
    }
</style>
@endpush

@push('scripts')
    @include('backend.includes.plugins.toastr')
    @include('backend.includes.plugins.datatable')
    <script src="{{ asset('backend/build/assets/libs/filepond/filepond.min.js') }}"></script>
    <script src="{{ asset('backend/build/assets/libs/filepond-plugin-file-validate-type/filepond-plugin-file-validate-type.min.js') }}"></script>
    <script src="{{ asset('backend/build/assets/libs/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js') }}"></script>
    <script>
    $(document).ready(function () {
        const storeModal = new bootstrap.Modal(document.getElementById('storeModal'));
        const viewModalEl = new bootstrap.Modal(document.getElementById('viewModal'));
        const deleteModalEl = new bootstrap.Modal(document.getElementById('deleteModal'));

        // --- Initialize DataTable ---
        const storesTable = $('#stores-table').DataTable({
            dom: '<"d-none"f>rt<"d-flex justify-content-between align-items-center mt-3"<"text-muted"i>p>',
            pageLength: 15,
            lengthMenu: [[15, 25, 50, 100, -1], [15, 25, 50, 100, "All"]],
            order: [[0, 'asc']],
            language: {
                info: "Showing _START_ to _END_ of _TOTAL_ stores",
                infoEmpty: "No stores found",
                infoFiltered: "(filtered from _MAX_ total stores)",
                zeroRecords: "No matching stores found",
                paginate: {
                    previous: "<i class='bi bi-chevron-left'></i>",
                    next: "<i class='bi bi-chevron-right'></i>"
                }
            },
            columnDefs: [
                { orderable: false, targets: [5, 6] },
                { searchable: false, targets: [6] }
            ]
        });

        // Update store count label
        function updateStoreCount() {
            const info = storesTable.page.info();
            $('.store-count-label').text(info.recordsDisplay + ' of ' + info.recordsTotal + ' stores');
        }
        storesTable.on('draw', updateStoreCount);
        updateStoreCount();

        // --- Custom Filters with DataTable ---
        // Search filter
        $('#filter-search').on('input', function () {
            storesTable.search($(this).val()).draw();
        });

        // Custom filter function for division, size, and rent
        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
            if (settings.nTable.id !== 'stores-table') return true;

            const $row = $(storesTable.row(dataIndex).node());
            const division = $('#filter-division').val().toLowerCase();
            const sizeFilter = $('#filter-size').val();
            const rentFilter = $('#filter-rent').val();

            const location = data[1].toLowerCase(); // Location column
            const size = parseFloat($row.data('size')) || 0;
            const rent = parseFloat($row.data('rent')) || 0;

            // Division filter
            if (division && !location.includes(division)) {
                return false;
            }

            // Size filter
            if (sizeFilter) {
                if (sizeFilter === 'small' && size >= 500) return false;
                if (sizeFilter === 'medium' && (size < 500 || size > 1000)) return false;
                if (sizeFilter === 'large' && size <= 1000) return false;
            }

            // Rent filter
            if (rentFilter) {
                if (rentFilter === 'low' && rent >= 50000) return false;
                if (rentFilter === 'mid' && (rent < 50000 || rent > 80000)) return false;
                if (rentFilter === 'high' && rent <= 80000) return false;
            }

            return true;
        });

        // Trigger redraw on filter change
        $('#filter-division, #filter-size, #filter-rent').on('change', function () {
            storesTable.draw();
        });

        // --- FilePond for Layout PDF ---
        FilePond.registerPlugin(
            FilePondPluginFileValidateType,
            FilePondPluginFileValidateSize
        );

        const pdfPond = FilePond.create(document.querySelector('.filepond-pdf'), {
            labelIdle: '<i class="bi bi-file-earmark-pdf" style="font-size:1.2rem;"></i><br>Drag & Drop layout PDF or <span class="filepond--label-action">Browse</span>',
            acceptedFileTypes: ['application/pdf'],
            maxFileSize: '10MB',
            credits: false,
        });

        // --- Auto-generate code from title ---
        $('#title').on('input', function () {
            if (!$('#store_id').val()) {
                const name = $(this).val().trim();
                if (name.length >= 2) {
                    let code = '';
                    const words = name.split(/\s+/);
                    if (words.length >= 3) {
                        code = words.map(w => w[0]).join('').substring(0, 3);
                    } else if (words.length === 2) {
                        code = words[0].substring(0, 2) + words[1][0];
                    } else {
                        const consonants = name.replace(/[aeiou]/gi, '');
                        if (consonants.length >= 2) {
                            code = name[0] + consonants[1] + (consonants[2] || name[name.length - 1]);
                        } else {
                            code = name.substring(0, 3);
                        }
                    }
                    $('#code').val(code.toUpperCase().substring(0, 3));
                }
            }
        });

        // --- Calculate Rent per Sq Ft ---
        $('#total_area_sqft, #monthly_rent').on('input', function () {
            const area = parseFloat($('#total_area_sqft').val()) || 0;
            const rent = parseFloat($('#monthly_rent').val()) || 0;
            if (area > 0 && rent > 0) {
                const perSqft = (rent / area).toFixed(2);
                $('#calc-rent-value').text(perSqft + '৳');
                $('#calculated-rent-display').show();
            } else {
                $('#calculated-rent-display').hide();
            }
        });

        // --- Cascading Dropdowns ---
        $('#division_id').on('change', function () {
            const divisionId = $(this).val();
            $('#district_id').html('<option value="">— Select District —</option>').prop('disabled', true);
            $('#thana_id').html('<option value="">— Select Thana —</option>').prop('disabled', true);

            if (divisionId) {
                $.get(base_url + 'get-districts/' + divisionId, function (districts) {
                    let options = '<option value="">— Select District —</option>';
                    districts.forEach(function (d) {
                        options += `<option value="${d.id}">${d.name}</option>`;
                    });
                    $('#district_id').html(options).prop('disabled', false);
                });
            }
        });

        $('#district_id').on('change', function () {
            const districtId = $(this).val();
            $('#thana_id').html('<option value="">— Select Thana —</option>').prop('disabled', true);

            if (districtId) {
                $.get(base_url + 'get-thanas/' + districtId, function (thanas) {
                    let options = '<option value="">— Select Thana —</option>';
                    thanas.forEach(function (t) {
                        options += `<option value="${t.id}">${t.name}</option>`;
                    });
                    $('#thana_id').html(options).prop('disabled', false);
                });
            }
        });

        // --- Open Add Modal ---
        $('#btn-add-store').on('click', function () {
            resetForm();
            $('#storeModalLabel').text('Add Store');
            $('#storeModalSubtitle').text('Create a new store entry');
            $('#btn-save .btn-text').html('<i class="bi bi-save me-1"></i>Save Store');
            storeModal.show();
        });

        // --- Open Edit Modal ---
        $(document).on('click', '.btn-edit', function () {
            resetForm();
            const id = $(this).data('id');
            $('#storeModalLabel').text('Edit Store');
            $('#storeModalSubtitle').text('Update store information');
            $('#btn-save .btn-text').html('<i class="bi bi-save me-1"></i>Update Store');
            $.get(base_url + 'stores/' + id + '/edit', function (data) {
                $('#store_id').val(data.id);
                $('#title').val(data.title);
                $('#code').val(data.code);
                $('#store_code').val(data.store_code);
                $('#total_area_sqft').val(data.total_area_sqft);
                $('#monthly_rent').val(data.monthly_rent);
                $('#per_sqr_feet_rent').val(data.per_sqr_feet_rent);
                $('#opened_date').val(data.opened_date);
                $('#store_manager_id').val(data.store_manager_id || '');
                $('#address').val(data.address);
                $('#area').val(data.area);
                $('#postal_code').val(data.postal_code);
                $('#latitude').val(data.latitude);
                $('#longitude').val(data.longitude);
                $('#contact_persion').val(data.contact_persion);
                $('#shop_official_mobile').val(data.shop_official_mobile);
                $('#shop_official_email').val(data.shop_official_email);
                $('#status').val(data.status);

                // Trigger rent calculation
                $('#total_area_sqft').trigger('input');

                // Load cascading dropdowns
                if (data.division_id) {
                    $('#division_id').val(data.division_id);
                    $.get(base_url + 'get-districts/' + data.division_id, function (districts) {
                        let options = '<option value="">— Select District —</option>';
                        districts.forEach(function (d) {
                            options += `<option value="${d.id}" ${d.id == data.district_id ? 'selected' : ''}>${d.name}</option>`;
                        });
                        $('#district_id').html(options).prop('disabled', false);

                        if (data.district_id) {
                            $.get(base_url + 'get-thanas/' + data.district_id, function (thanas) {
                                let options = '<option value="">— Select Thana —</option>';
                                thanas.forEach(function (t) {
                                    options += `<option value="${t.id}" ${t.id == data.thana_id ? 'selected' : ''}>${t.name}</option>`;
                                });
                                $('#thana_id').html(options).prop('disabled', false);
                            });
                        }
                    });
                }

                storeModal.show();
            });
        });

        // --- View Store ---
        $(document).on('click', '.btn-view', function () {
            const id = $(this).data('id');
            $.get(base_url + 'stores/' + id, function (data) {
                $('#view-title').text(data.title);
                $('#view-code').text(data.code);
                $('#view-store-code').text(data.store_code || '—');
                $('#view-area-sqft').text(data.total_area_sqft ? data.total_area_sqft + ' sqft' : '—');
                $('#view-rent').text(data.monthly_rent ? '৳' + parseFloat(data.monthly_rent).toLocaleString() : '—');
                $('#view-per-sqft-rent').text(data.per_sqr_feet_rent ? '৳' + parseFloat(data.per_sqr_feet_rent).toLocaleString() : '—');
                $('#view-opened').text(data.opened_date || '—');
                $('#view-manager').text(data.store_manager ? data.store_manager.name : '—');
                $('#view-status').html(data.status == 1
                    ? '<span class="badge bg-success-transparent">Active</span>'
                    : '<span class="badge bg-danger-transparent">Inactive</span>');
                $('#view-address').text(data.address || '—');
                $('#view-area').text(data.area || '—');
                $('#view-thana').text(data.thana ? data.thana.name : '—');
                $('#view-district').text(data.district ? data.district.name : '—');
                $('#view-division').text(data.division ? data.division.name : '—');
                $('#view-postal').text(data.postal_code || '—');
                $('#view-coords').text(data.latitude && data.longitude ? data.latitude + ', ' + data.longitude : '—');
                $('#view-contact').text(data.contact_persion || '—');
                $('#view-mobile').text(data.shop_official_mobile || '—');
                $('#view-email').text(data.shop_official_email || '—');

                // Layout history
                if (data.store_layouts && data.store_layouts.length) {
                    $('#view-layouts-section').show();
                    let html = '';
                    data.store_layouts.forEach(function (layout, i) {
                        html += '<tr>';
                        html += '<td>' + (i + 1) + '</td>';
                        html += '<td>' + (layout.changed_at || '—') + '</td>';
                        html += '<td>' + (layout.layout_pdf ? '<a href="' + base_url + layout.layout_pdf + '" target="_blank" class="btn btn-xs btn-outline-primary">Download</a>' : '—') + '</td>';
                        html += '<td>' + (layout.is_currently_active == 1 ? '<span class="badge bg-success-transparent">Active</span>' : '<span class="text-muted">—</span>') + '</td>';
                        html += '</tr>';
                    });
                    $('#view-layouts-body').html(html);
                } else {
                    $('#view-layouts-section').hide();
                }

                viewModalEl.show();
            });
        });

        // --- Delete ---
        $(document).on('click', '.btn-delete', function () {
            $('#delete-store-id').val($(this).data('id'));
            $('#delete-store-name').text($(this).data('name'));
            deleteModalEl.show();
        });

        $('#btn-confirm-delete').on('click', function () {
            const id = $('#delete-store-id').val();
            const btn = $(this);
            btn.prop('disabled', true).text('Deleting...');
            $.ajax({
                url: base_url + 'stores/' + id,
                type: 'DELETE',
                success: function (res) {
                    deleteModalEl.hide();
                    toastr.success(res.message);
                    setTimeout(() => location.reload(), 800);
                },
                error: function () {
                    toastr.error('Failed to delete store.');
                },
                complete: function () {
                    btn.prop('disabled', false).text('Yes, Delete');
                }
            });
        });

        // --- Submit Form ---
        $('#storeForm').on('submit', function (e) {
            e.preventDefault();
            clearErrors();

            const id = $('#store_id').val();
            const url = id ? base_url + 'stores/' + id : base_url + 'stores';
            const formData = new FormData(this);

            // Append FilePond PDF file
            const pdfFile = pdfPond.getFile();
            if (pdfFile) {
                formData.append('store_layout_pdf', pdfFile.file);
            }

            if (id) formData.append('_method', 'PUT');

            $('#btn-save').prop('disabled', true);
            $('#btn-spinner').removeClass('d-none');

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (res) {
                    storeModal.hide();
                    toastr.success(res.message);
                    setTimeout(() => location.reload(), 800);
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        $.each(errors, function (field, messages) {
                            if (field === 'store_layout_pdf') {
                                $('#error-store_layout_pdf').text(messages[0]).css('display', 'block');
                            } else {
                                $('#' + field).addClass('is-invalid');
                                $('#error-' + field).text(messages[0]);
                            }
                        });
                    } else {
                        toastr.error('Something went wrong.');
                    }
                },
                complete: function () {
                    $('#btn-save').prop('disabled', false);
                    $('#btn-spinner').addClass('d-none');
                }
            });
        });

        // --- Helpers ---
        function resetForm() {
            $('#storeForm')[0].reset();
            $('#store_id').val('');
            pdfPond.removeFiles();
            $('#district_id').html('<option value="">— Select District —</option>').prop('disabled', true);
            $('#thana_id').html('<option value="">— Select Thana —</option>').prop('disabled', true);
            $('#calculated-rent-display').hide();
            clearErrors();
        }

        function clearErrors() {
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('').css('display', '');
        }
    });
    </script>
@endpush
