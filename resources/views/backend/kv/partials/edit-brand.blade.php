<form id="editBrandForm" method="post" action="{{ route('brands.update', $brand->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="modal-body">
{{--        <input type="hidden" id="brand_id" value="{{ $brand->id }}">--}}
        <div class="mb-3">
            <label for="name" class="form-label">Brand Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="editName" value="{{ $brand->name }}" name="name" placeholder="Enter brand name">
            <div class="invalid-feedback" id="error-name"></div>
        </div>
        <div class="mb-3">
            <label for="code" class="form-label">Code <span class="text-danger">*</span></label>
            <input type="text" class="form-control text-uppercase" value="{{ $brand->code }}" readonly id="editCode" name="code" placeholder="2-3 letter abbreviation" maxlength="3">
            <small class="form-text text-muted">A 2-3 letter abbreviation that sounds close to the brand name.</small>
            <div class="invalid-feedback" id="error-code"></div>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="editDescription" name="description" rows="3" placeholder="Enter description">{{ $brand->description }}</textarea>
            <div class="invalid-feedback" id="error-description"></div>
        </div>
        <div class="mb-3">
            <label for="logo" class="form-label">Logo</label>
            <input type="file" class="filepond logo" id="editLogo" name="logo" accept="image/jpeg, image/png, image/jpg, image/gif, image/svg+xml, image/webp">
            <div class="invalid-feedback d-block" id="error-logo" style="display:none !important;"></div>
            <div id="logo-preview" class="mt-2 d-none">
                <img src="{{ asset($brand->logo) }}" alt="Logo Preview" style="height: 60px; border-radius: 5px;">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label d-block">Status</label>
            <div class="toggle-switch">
                <label class="switch">
                    <input type="checkbox" id="status-switch" {{ $brand->status == 1 ? 'checked' : '' }}>
                    <span class="slider round"></span>
                </label>
                <span class="ms-2" id="status-label">Published</span>
            </div>
            <input type="hidden" id="editStatus" name="status" value="{{ $brand->status }}">
            <div class="invalid-feedback" id="error-status"></div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary btn-update" id="btn-save">
            <span class="btn-text">Save</span>
            <span class="spinner-border spinner-border-sm d-none btn-spinner" id="btn-spinner"></span>
        </button>
    </div>
</form>
