@extends('backend.master')

@section('title', 'Brands')

@section('body')
    <div class="container m-t-50">
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="card-title">Brands Management</div>
                        <button type="button" class="btn btn-sm btn-primary btn-wave" id="btn-add-brand">
                            <i class="ri-add-line me-1"></i> Add Brand
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="brands-table" class="table table-bordered text-nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Logo</th>
                                        <th>Code</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($brands as $brand)
                                    <tr id="brand-row-{{ $brand->id }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $brand->name }}</td>
                                        <td>
                                            @if($brand->logo)
                                                <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}" style="height: 40px; border-radius: 5px;">
                                            @else
                                                <span class="badge bg-light text-muted">No Logo</span>
                                            @endif
                                        </td>
                                        <td><span class="badge bg-primary-transparent">{{ $brand->code }}</span></td>
                                        <td class="text-wrap">{{ \Str::limit($brand->description, 50) }}</td>
                                        <td>
                                            @if($brand->status == 1)
                                                <span class="badge bg-outline-success">Published</span>
                                            @else
                                                <span class="badge bg-outline-danger">Unpublished</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-list">
                                                <button class="btn btn-icon btn-sm btn-info-light btn-wave btn-view" data-id="{{ $brand->id }}" title="View"><i class="ri-eye-line"></i></button>
                                                <button class="btn btn-icon btn-sm btn-primary-light btn-wave btn-edit" data-id="{{ $brand->id }}" title="Edit"><i class="ri-edit-box-line"></i></button>
                                                <button class="btn btn-icon btn-sm btn-danger-light btn-wave btn-delete " data-id="{{ $brand->id }}" data-name="{{ $brand->name }}" title="Delete"><i class="ri-delete-bin-line"></i></button>
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
    <div class="modal fade" id="brandModal" tabindex="-1" aria-labelledby="brandModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="brandModalLabel">Add Brand</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="brandForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" id="brand_id" value="">
                        <div class="mb-3">
                            <label for="name" class="form-label">Brand Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter brand name">
                            <div class="invalid-feedback" id="error-name"></div>
                        </div>
                        <div class="mb-3">
                            <label for="code" class="form-label">Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control text-uppercase" id="code" name="code" placeholder="2-3 letter abbreviation" maxlength="3">
                            <small class="form-text text-muted">A 2-3 letter abbreviation that sounds close to the brand name.</small>
                            <div class="invalid-feedback" id="error-code"></div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter description"></textarea>
                            <div class="invalid-feedback" id="error-description"></div>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="status" name="status">
                                <option value="1">Published</option>
                                <option value="0">Unpublished</option>
                            </select>
                            <div class="invalid-feedback" id="error-status"></div>
                        </div>
                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo</label>
                            <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                            <div class="invalid-feedback" id="error-logo"></div>
                            <div id="logo-preview" class="mt-2 d-none">
                                <img src="" alt="Logo Preview" style="height: 60px; border-radius: 5px;">
                            </div>
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
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Brand Details</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3" id="view-logo-container">
                        <img id="view-logo" src="" alt="" style="max-height: 80px; border-radius: 8px;">
                    </div>
                    <table class="table table-bordered">
                        <tr><th width="30%">Name</th><td id="view-name"></td></tr>
                        <tr><th>Code</th><td id="view-code"></td></tr>
                        <tr><th>Description</th><td id="view-description"></td></tr>
                        <tr><th>Status</th><td id="view-status"></td></tr>
                        <tr><th>Created</th><td id="view-created"></td></tr>
                    </table>
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
                    <h6>Delete Brand</h6>
                    <p class="text-muted mb-0">Are you sure you want to delete <strong id="delete-brand-name"></strong>?</p>
                    <input type="hidden" id="delete-brand-id">
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
<style>
    .btn-list { display: flex; gap: 4px; }
</style>
@endpush

@push('scripts')
    @include('backend.includes.plugins.datatable')
    <script>
    $(document).ready(function () {
        const brandModal = new bootstrap.Modal(document.getElementById('brandModal'));
        const viewModal = new bootstrap.Modal(document.getElementById('viewModal'));
        const deleteModalEl = new bootstrap.Modal(document.getElementById('deleteModal'));

        // Auto-generate code from name
        $('#name').on('input', function () {
            if (!$('#brand_id').val()) { // Only auto-generate for new brands
                const name = $(this).val().trim();
                if (name.length >= 2) {
                    let code = '';
                    const words = name.split(/\s+/);
                    if (words.length >= 3) {
                        code = words.map(w => w[0]).join('').substring(0, 3);
                    } else if (words.length === 2) {
                        code = words[0].substring(0, 2) + words[1][0];
                    } else {
                        // Single word: take first consonant cluster + vowel pattern
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

        // Open Add Modal
        $('#btn-add-brand').on('click', function () {
            resetForm();
            $('#brandModalLabel').text('Add Brand');
            $('#btn-save .btn-text').text('Save');
            brandModal.show();
        });

        // Open Edit Modal
        $(document).on('click', '.btn-edit', function () {
            resetForm();
            const id = $(this).data('id');
            $('#brandModalLabel').text('Edit Brand');
            $('#btn-save .btn-text').text('Update');
            $.get(base_url + 'brands/' + id + '/edit', function (data) {
                $('#brand_id').val(data.id);
                $('#name').val(data.name);
                $('#code').val(data.code);
                $('#description').val(data.description);
                $('#status').val(data.status);
                if (data.logo) {
                    $('#logo-preview').removeClass('d-none').find('img').attr('src', base_url + 'storage/' + data.logo);
                }
                brandModal.show();
            });
        });

        // View Brand
        $(document).on('click', '.btn-view', function () {
            const id = $(this).data('id');
            $.get(base_url + 'brands/' + id, function (data) {
                $('#view-name').text(data.name);
                $('#view-code').text(data.code);
                $('#view-description').text(data.description || '—');
                $('#view-status').html(data.status == 1
                    ? '<span class="badge bg-success-transparent">Published</span>'
                    : '<span class="badge bg-danger-transparent">Unpublished</span>');
                $('#view-created').text(new Date(data.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }));
                if (data.logo) {
                    $('#view-logo').attr('src', base_url + 'storage/' + data.logo);
                    $('#view-logo-container').show();
                } else {
                    $('#view-logo-container').hide();
                }
                viewModal.show();
            });
        });

        // Open Delete Modal
        $(document).on('click', '.btn-delete', function () {
            $('#delete-brand-id').val($(this).data('id'));
            $('#delete-brand-name').text($(this).data('name'));
            deleteModalEl.show();
        });

        // Confirm Delete
        $('#btn-confirm-delete').on('click', function () {
            const id = $('#delete-brand-id').val();
            const btn = $(this);
            btn.prop('disabled', true).text('Deleting...');
            $.ajax({
                url: base_url + 'brands/' + id,
                type: 'DELETE',
                success: function (res) {
                    deleteModalEl.hide();
                    showToast(res.message, 'success');
                    setTimeout(() => location.reload(), 800);
                },
                error: function () {
                    showToast('Failed to delete brand.', 'danger');
                },
                complete: function () {
                    btn.prop('disabled', false).text('Yes, Delete');
                }
            });
        });

        // Submit Form (Create / Update)
        $('#brandForm').on('submit', function (e) {
            e.preventDefault();
            clearErrors();

            const id = $('#brand_id').val();
            const url = id ? base_url + 'brands/' + id : base_url + 'brands';
            const formData = new FormData(this);
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
                    brandModal.hide();
                    showToast(res.message, 'success');
                    setTimeout(() => location.reload(), 800);
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        $.each(errors, function (field, messages) {
                            $('#' + field).addClass('is-invalid');
                            $('#error-' + field).text(messages[0]);
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

        // Logo preview on file select
        $('#logo').on('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    $('#logo-preview').removeClass('d-none').find('img').attr('src', e.target.result);
                };
                reader.readAsDataURL(file);
            } else {
                $('#logo-preview').addClass('d-none');
            }
        });

        function resetForm() {
            $('#brandForm')[0].reset();
            $('#brand_id').val('');
            $('#logo-preview').addClass('d-none');
            clearErrors();
        }

        function clearErrors() {
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');
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
