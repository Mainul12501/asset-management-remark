@extends('backend.master')

@section('title', 'Stores')

@section('body')
    <div class="container m-t-50">
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="card-title">Store Management</div>
                        <button type="button" class="btn btn-sm btn-primary btn-wave" id="btn-add-store">
                            <i class="ri-add-line me-1"></i> Add Store
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="data-table" class="table table-bordered text-nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Code</th>
                                        <th>Location</th>
                                        <th>Contact</th>
                                        <th>Manager</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($stores as $store)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $store->title }}</td>
                                        <td><span class="badge bg-primary-transparent">{{ $store->code }}</span></td>
                                        <td>
                                            @if($store->area || $store->district)
                                                {{ $store->area }}{{ $store->area && $store->district ? ', ' : '' }}{{ $store->district }}
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>{{ $store->shop_official_mobile ?: '—' }}</td>
                                        <td>{{ $store->storeManager?->name ?: '—' }}</td>
                                        <td>
                                            @if($store->status == 1)
                                                <span class="badge bg-outline-success">Active</span>
                                            @else
                                                <span class="badge bg-outline-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-list">
                                                <button class="btn btn-icon btn-sm btn-info-light btn-wave btn-view" data-id="{{ $store->id }}" title="View"><i class="ri-eye-line"></i></button>
                                                <button class="btn btn-icon btn-sm btn-primary-light btn-wave btn-edit" data-id="{{ $store->id }}" title="Edit"><i class="ri-edit-box-line"></i></button>
                                                <button class="btn btn-icon btn-sm btn-danger-light btn-wave btn-delete" data-id="{{ $store->id }}" data-name="{{ $store->title }}" title="Delete"><i class="ri-delete-bin-line"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    {{-- Create / Edit Modal --}}
    <div class="modal fade" id="storeModal" tabindex="-1" aria-labelledby="storeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="storeModalLabel">Add Store</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="storeForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" id="store_id" value="">

                        {{-- Basic Info --}}
                        <h6 class="fw-semibold text-muted mb-3"><i class="ri-store-2-line me-1"></i> Basic Information</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="title" class="form-label">Store Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="Enter store title">
                                <div class="invalid-feedback" id="error-title"></div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="code" class="form-label">Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" readonly id="code" name="code" placeholder="Auto-generated" maxlength="3">
                                <small class="form-text text-muted">Auto-generated from title.</small>
                                <div class="invalid-feedback" id="error-code"></div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="total_area_sqft" class="form-label">Area (sqft)</label>
                                <input type="number" step="0.01" class="form-control" id="total_area_sqft" name="total_area_sqft" placeholder="0.00">
                                <div class="invalid-feedback" id="error-total_area_sqft"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="monthly_rent" class="form-label">Monthly Rent</label>
                                <input type="number" step="0.01" class="form-control" id="monthly_rent" name="monthly_rent" placeholder="0.00">
                                <div class="invalid-feedback" id="error-monthly_rent"></div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="opened_date" class="form-label">Opened Date</label>
                                <input type="date" class="form-control" id="opened_date" name="opened_date">
                                <div class="invalid-feedback" id="error-opened_date"></div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="store_manager_id" class="form-label">Store Manager</label>
                                <select class="form-select" id="store_manager_id" name="store_manager_id">
                                    <option value="">— Select Manager —</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="error-store_manager_id"></div>
                            </div>
                        </div>

                        <hr class="my-3">

                        {{-- Map & Location --}}
                        <h6 class="fw-semibold text-muted mb-3"><i class="ri-map-pin-line me-1"></i> Location <small class="text-primary fw-normal">(Search, click on map, or enter coordinates)</small></h6>
                        <div class="mb-2 position-relative">
                            <input type="text" class="form-control" id="map-search" placeholder="Search location... (e.g. Dhanmondi, Dhaka)" autocomplete="off">
                            <div id="map-search-results" class="list-group position-absolute w-100 shadow-sm" style="z-index:1000; max-height:200px; overflow-y:auto; display:none;"></div>
                        </div>
                        <div id="map" style="height: 250px; border-radius: 8px; border: 1px solid #e9ecef;" class="mb-3"></div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="latitude" class="form-label">Latitude</label>
                                <input type="text" class="form-control" id="latitude" name="latitude" placeholder="e.g. 23.8103">
                                <div class="invalid-feedback" id="error-latitude"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="longitude" class="form-label">Longitude</label>
                                <input type="text" class="form-control" id="longitude" name="longitude" placeholder="e.g. 90.4125">
                                <div class="invalid-feedback" id="error-longitude"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="2" placeholder="Full address"></textarea>
                            <div class="invalid-feedback" id="error-address"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="area" class="form-label">Area</label>
                                <input type="text" class="form-control" id="area" name="area" placeholder="Area" readonly>
                                <div class="invalid-feedback" id="error-area"></div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="thana" class="form-label">Thana</label>
                                <input type="text" class="form-control" id="thana" name="thana" placeholder="Thana" readonly>
                                <div class="invalid-feedback" id="error-thana"></div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="district" class="form-label">District</label>
                                <input type="text" class="form-control" id="district" name="district" placeholder="District" readonly>
                                <div class="invalid-feedback" id="error-district"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="division" class="form-label">Division</label>
                                <input type="text" class="form-control" id="division" name="division" placeholder="Division" readonly>
                                <div class="invalid-feedback" id="error-division"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="postal_code" class="form-label">Postal Code</label>
                                <input type="text" class="form-control" id="postal_code" name="postal_code" placeholder="Postal Code" readonly>
                                <div class="invalid-feedback" id="error-postal_code"></div>
                            </div>
                        </div>

                        <hr class="my-3">

                        {{-- Contact Info --}}
                        <h6 class="fw-semibold text-muted mb-3"><i class="ri-phone-line me-1"></i> Contact Information</h6>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="contact_persion" class="form-label">Contact Person</label>
                                <input type="text" class="form-control" id="contact_persion" name="contact_persion" placeholder="Contact person name">
                                <div class="invalid-feedback" id="error-contact_persion"></div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="shop_official_mobile" class="form-label">Official Mobile</label>
                                <input type="text" class="form-control" id="shop_official_mobile" name="shop_official_mobile" placeholder="Mobile number">
                                <div class="invalid-feedback" id="error-shop_official_mobile"></div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="shop_official_email" class="form-label">Official Email</label>
                                <input type="email" class="form-control" id="shop_official_email" name="shop_official_email" placeholder="Email address">
                                <div class="invalid-feedback" id="error-shop_official_email"></div>
                            </div>
                        </div>

                        <hr class="my-3">

                        {{-- Layout Files --}}
                        <h6 class="fw-semibold text-muted mb-3"><i class="ri-layout-3-line me-1"></i> Store Layout</h6>
                        <div class="mb-3">
                            <label for="store_layout_pdf" class="form-label">Layout PDF</label>
                            <input type="file" class="filepond-pdf" id="store_layout_pdf" name="store_layout_pdf" accept="application/pdf">
                            <div class="invalid-feedback d-block" id="error-store_layout_pdf" style="display:none !important;"></div>
                        </div>

                        <hr class="my-3">

                        {{-- Status --}}
                        <div class="mb-3">
                            <label class="form-label d-block">Status</label>
                            <div class="toggle-switch">
                                <label class="switch">
                                    <input type="checkbox" id="status-switch" checked>
                                    <span class="slider round"></span>
                                </label>
                                <span class="ms-2" id="status-label">Active</span>
                            </div>
                            <input type="hidden" id="status" name="status" value="1">
                            <div class="invalid-feedback" id="error-status"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="btn-save">
                            <span class="btn-text">Save</span>
                            <span class="spinner-border spinner-border-sm d-none" id="btn-spinner"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- View Modal --}}
    <div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Store Details</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered table-sm">
                                <tr><th width="40%">Title</th><td id="view-title"></td></tr>
                                <tr><th>Code</th><td id="view-code"></td></tr>
                                <tr><th>Area (sqft)</th><td id="view-area-sqft"></td></tr>
                                <tr><th>Monthly Rent</th><td id="view-rent"></td></tr>
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
                        <div class="col-md-6">
                            <table class="table table-bordered table-sm">
                                <tr><th width="40%">Contact Person</th><td id="view-contact"></td></tr>
                                <tr><th>Mobile</th><td id="view-mobile"></td></tr>
                                <tr><th>Email</th><td id="view-email"></td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div id="view-map" style="height: 180px; border-radius: 8px; border: 1px solid #e9ecef;"></div>
                        </div>
                    </div>

                    {{-- Layout History --}}
                    <div id="view-layouts-section" class="mt-3" style="display:none;">
                        <h6 class="fw-semibold text-muted mb-2"><i class="ri-layout-3-line me-1"></i> Layout History</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Changed At</th>
                                        <th>Image</th>
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
        <div class="modal-dialog modal-sm">
            <div class="modal-content text-center">
                <div class="modal-body p-4">
                    <div class="mb-3"><i class="ri-delete-bin-line text-danger" style="font-size: 3rem;"></i></div>
                    <h6>Delete Store</h6>
                    <p class="text-muted mb-0">Are you sure you want to delete <strong id="delete-store-name"></strong>?</p>
                    <input type="hidden" id="delete-store-id">
                </div>
                <div class="modal-footer justify-content-center border-0 pt-0">
                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-sm btn-danger" id="btn-confirm-delete">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('backend/build/assets/libs/leaflet/leaflet.css') }}">
<link rel="stylesheet" href="{{ asset('backend/build/assets/libs/filepond/filepond.min.css') }}">
<style>
    .btn-list { display: flex; gap: 4px; }
    .toggle-switch { display: flex; align-items: center; }
    .toggle-switch .switch { position: relative; display: inline-block; width: 44px; height: 24px; margin-bottom: 0; }
    .toggle-switch .switch input { opacity: 0; width: 0; height: 0; }
    .toggle-switch .slider { position: absolute; cursor: pointer; inset: 0; background-color: #ccc; transition: .3s; }
    .toggle-switch .slider.round { border-radius: 24px; }
    .toggle-switch .slider.round:before { border-radius: 50%; }
    .toggle-switch .slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: #fff; transition: .3s; border-radius: 50%; }
    .toggle-switch .switch input:checked + .slider { background-color: #5b6edf; }
    .toggle-switch .switch input:checked + .slider:before { transform: translateX(20px); }
    .filepond--root { margin-bottom: 0; }
</style>
@endpush

@push('scripts')
    @include('backend.includes.plugins.datatable')
    <script src="{{ asset('backend/build/assets/libs/leaflet/leaflet.js') }}"></script>
    <script src="{{ asset('backend/build/assets/libs/filepond/filepond.min.js') }}"></script>
    <script src="{{ asset('backend/build/assets/libs/filepond-plugin-file-validate-type/filepond-plugin-file-validate-type.min.js') }}"></script>
    <script src="{{ asset('backend/build/assets/libs/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js') }}"></script>
    <script src="{{ asset('backend/build/assets/libs/filepond-plugin-file-encode/filepond-plugin-file-encode.min.js') }}"></script>
    <script>
    $(document).ready(function () {
        const storeModal = new bootstrap.Modal(document.getElementById('storeModal'));
        const viewModalEl = new bootstrap.Modal(document.getElementById('viewModal'));
        const deleteModalEl = new bootstrap.Modal(document.getElementById('deleteModal'));

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

        // --- Leaflet Map (Create/Edit) ---
        let map, marker;
        const defaultLat = 23.8103, defaultLng = 90.4125; // Dhaka

        function initMap() {
            if (map) { map.remove(); }
            map = L.map('map').setView([defaultLat, defaultLng], 7);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            map.on('click', function (e) {
                setMarker(e.latlng.lat, e.latlng.lng);
                $('#latitude').val(e.latlng.lat.toFixed(6));
                $('#longitude').val(e.latlng.lng.toFixed(6));
                reverseGeocode(e.latlng.lat, e.latlng.lng);
            });
        }

        function setMarker(lat, lng) {
            if (marker) { marker.setLatLng([lat, lng]); }
            else { marker = L.marker([lat, lng], { draggable: true }).addTo(map); }
            map.setView([lat, lng], 14);

            marker.off('dragend').on('dragend', function (e) {
                const pos = e.target.getLatLng();
                $('#latitude').val(pos.lat.toFixed(6));
                $('#longitude').val(pos.lng.toFixed(6));
                reverseGeocode(pos.lat, pos.lng);
            });
        }

        function removeMarker() {
            if (marker) { map.removeLayer(marker); marker = null; }
        }

        // --- Map Search using Nominatim ---
        let searchTimer;
        $('#map-search').on('input', function () {
            const query = $(this).val().trim();
            clearTimeout(searchTimer);
            if (query.length < 3) {
                $('#map-search-results').hide().empty();
                return;
            }
            searchTimer = setTimeout(function () {
                $.get('https://nominatim.openstreetmap.org/search', {
                    q: query, format: 'json', addressdetails: 1, limit: 5, 'accept-language': 'en'
                }, function (results) {
                    const $list = $('#map-search-results').empty();
                    if (results.length === 0) {
                        $list.append('<div class="list-group-item text-muted small">No results found</div>');
                    }
                    results.forEach(function (r) {
                        $list.append(
                            $('<a href="#" class="list-group-item list-group-item-action small py-2"></a>')
                                .text(r.display_name)
                                .data('lat', r.lat)
                                .data('lon', r.lon)
                                .data('address', r.address)
                                .data('display', r.display_name)
                        );
                    });
                    $list.show();
                });
            }, 400);
        });

        $(document).on('click', '#map-search-results a', function (e) {
            e.preventDefault();
            const lat = parseFloat($(this).data('lat'));
            const lng = parseFloat($(this).data('lon'));
            const a = $(this).data('address');

            setMarker(lat, lng);
            $('#latitude').val(lat.toFixed(6));
            $('#longitude').val(lng.toFixed(6));
            $('#address').val($(this).data('display'));
            $('#area').val(a.suburb || a.neighbourhood || a.village || a.hamlet || '');
            $('#thana').val(a.county || a.city_district || a.town || '');
            $('#district').val(a.state_district || a.city || '');
            $('#division').val(a.state || '');
            $('#postal_code').val(a.postcode || '');

            $('#map-search').val('');
            $('#map-search-results').hide().empty();
        });

        // Hide search results when clicking outside
        $(document).on('click', function (e) {
            if (!$(e.target).closest('#map-search, #map-search-results').length) {
                $('#map-search-results').hide();
            }
        });

        // --- Reverse Geocode using Nominatim ---
        let geocodeTimer;
        function reverseGeocode(lat, lng) {
            clearTimeout(geocodeTimer);
            geocodeTimer = setTimeout(function () {
                $.get('https://nominatim.openstreetmap.org/reverse', {
                    lat: lat, lon: lng, format: 'json', addressdetails: 1, 'accept-language': 'en'
                }, function (data) {
                    if (data && data.address) {
                        const a = data.address;
                        $('#address').val(data.display_name || '');
                        $('#area').val(a.suburb || a.neighbourhood || a.village || a.hamlet || '');
                        $('#thana').val(a.county || a.city_district || a.town || '');
                        $('#district').val(a.state_district || a.city || '');
                        $('#division').val(a.state || '');
                        $('#postal_code').val(a.postcode || '');
                    }
                }).fail(function () {
                    console.warn('Reverse geocoding failed');
                });
            }, 400);
        }

        // Manual lat/lng input triggers reverse geocode
        let manualGeoTimer;
        $('#latitude, #longitude').on('input', function () {
            clearTimeout(manualGeoTimer);
            manualGeoTimer = setTimeout(function () {
                const lat = parseFloat($('#latitude').val());
                const lng = parseFloat($('#longitude').val());
                if (!isNaN(lat) && !isNaN(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
                    setMarker(lat, lng);
                    reverseGeocode(lat, lng);
                }
            }, 600);
        });

        // --- FilePond for Layout PDF ---
        FilePond.registerPlugin(
            FilePondPluginFileValidateType,
            FilePondPluginFileValidateSize
        );

        const pdfPond = FilePond.create(document.querySelector('.filepond-pdf'), {
            labelIdle: '<i class="ri-file-pdf-2-line" style="font-size:1.2rem;"></i><br>Drag & Drop layout PDF or <span class="filepond--label-action">Browse</span>',
            acceptedFileTypes: ['application/pdf'],
            maxFileSize: '10MB',
            credits: false,
        });

        // --- Status Switch ---
        $('#status-switch').on('change', function () {
            const isChecked = $(this).is(':checked');
            $('#status').val(isChecked ? '1' : '0');
            $('#status-label').text(isChecked ? 'Active' : 'Inactive');
        });

        // --- Open Add Modal ---
        $('#btn-add-store').on('click', function () {
            resetForm();
            $('#storeModalLabel').text('Add Store');
            $('#btn-save .btn-text').text('Save');
            storeModal.show();
            setTimeout(function () { initMap(); }, 300);
        });

        // --- Open Edit Modal ---
        $(document).on('click', '.btn-edit', function () {
            resetForm();
            const id = $(this).data('id');
            $('#storeModalLabel').text('Edit Store');
            $('#btn-save .btn-text').text('Update');
            $.get(base_url + 'stores/' + id + '/edit', function (data) {
                $('#store_id').val(data.id);
                $('#title').val(data.title);
                $('#code').val(data.code);
                $('#total_area_sqft').val(data.total_area_sqft);
                $('#monthly_rent').val(data.monthly_rent);
                $('#opened_date').val(data.opened_date);
                $('#store_manager_id').val(data.store_manager_id || '');
                $('#address').val(data.address);
                $('#area').val(data.area);
                $('#thana').val(data.thana);
                $('#district').val(data.district);
                $('#division').val(data.division);
                $('#postal_code').val(data.postal_code);
                $('#latitude').val(data.latitude);
                $('#longitude').val(data.longitude);
                $('#contact_persion').val(data.contact_persion);
                $('#shop_official_mobile').val(data.shop_official_mobile);
                $('#shop_official_email').val(data.shop_official_email);
                $('#status').val(data.status);
                $('#status-switch').prop('checked', data.status == 1).trigger('change');

                storeModal.show();
                setTimeout(function () {
                    initMap();
                    if (data.latitude && data.longitude) {
                        setMarker(parseFloat(data.latitude), parseFloat(data.longitude));
                    }
                }, 300);
            });
        });

        // --- View Store ---
        let viewMap, viewMarker;
        $(document).on('click', '.btn-view', function () {
            const id = $(this).data('id');
            $.get(base_url + 'stores/' + id, function (data) {
                $('#view-title').text(data.title);
                $('#view-code').text(data.code);
                $('#view-area-sqft').text(data.total_area_sqft ? data.total_area_sqft + ' sqft' : '—');
                $('#view-rent').text(data.monthly_rent ? '৳' + parseFloat(data.monthly_rent).toLocaleString() : '—');
                $('#view-opened').text(data.opened_date || '—');
                $('#view-manager').text(data.store_manager ? data.store_manager.name : '—');
                $('#view-status').html(data.status == 1
                    ? '<span class="badge bg-success-transparent">Active</span>'
                    : '<span class="badge bg-danger-transparent">Inactive</span>');
                $('#view-address').text(data.address || '—');
                $('#view-area').text(data.area || '—');
                $('#view-thana').text(data.thana || '—');
                $('#view-district').text(data.district || '—');
                $('#view-division').text(data.division || '—');
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
                        html += '<td>' + (layout.layout_img ? '<a href="' + base_url + layout.layout_img + '" target="_blank" class="btn btn-xs btn-primary-light">View</a>' : '—') + '</td>';
                        html += '<td>' + (layout.layout_pdf ? '<a href="' + base_url + layout.layout_pdf + '" target="_blank" class="btn btn-xs btn-info-light">Download</a>' : '—') + '</td>';
                        html += '<td>' + (layout.is_currently_active == 1 ? '<span class="badge bg-success-transparent">Active</span>' : '<span class="text-muted">—</span>') + '</td>';
                        html += '</tr>';
                    });
                    $('#view-layouts-body').html(html);
                } else {
                    $('#view-layouts-section').hide();
                }

                viewModalEl.show();

                // View map
                setTimeout(function () {
                    if (viewMap) { viewMap.remove(); }
                    viewMap = L.map('view-map').setView([defaultLat, defaultLng], 7);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19, attribution: '&copy; OpenStreetMap'
                    }).addTo(viewMap);

                    if (data.latitude && data.longitude) {
                        const lat = parseFloat(data.latitude), lng = parseFloat(data.longitude);
                        viewMarker = L.marker([lat, lng]).addTo(viewMap);
                        viewMap.setView([lat, lng], 14);
                    }
                }, 300);
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
                    showToast(res.message, 'success');
                    setTimeout(() => location.reload(), 800);
                },
                error: function () {
                    showToast('Failed to delete store.', 'danger');
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
                    showToast(res.message, 'success');
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
                        showToast('Something went wrong.', 'danger');
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
            removeMarker();
            $('#map-search').val('');
            $('#map-search-results').hide().empty();
            $('#status-switch').prop('checked', true).trigger('change');
            $('#area, #thana, #district, #division, #postal_code').val('');
            clearErrors();
        }

        function clearErrors() {
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('').css('display', '');
        }

        function showToast(message, type) {
            const toast = $(`
                <div class="toast align-items-center text-bg-${type} border-0 show position-fixed top-0 end-0 m-3" style="z-index:9999" role="alert">
                    <div class="d-flex">
                        <div class="toast-body">${message}</div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            `).appendTo('body');
            setTimeout(() => toast.remove(), 3000);
        }
    });
    </script>
@endpush
