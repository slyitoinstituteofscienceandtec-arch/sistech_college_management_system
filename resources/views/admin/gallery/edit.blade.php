@extends('layouts.app')

@section('title', 'Edit Gallery Image')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 style="margin: 0; font-weight: 700;"><i class="fas fa-edit me-2" style="color: var(--primary);"></i>Edit Gallery Image</h4>
    </div>
    <a href="{{ route('admin.gallery.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
</div>

@if($errors->any())
<div class="alert alert-danger" style="border-radius: 10px;">
    <ul style="margin: 0; padding-left: 1.25rem;">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div style="display: grid; grid-template-columns: 1fr 300px; gap: 2rem; align-items: start;">
    <div class="card-sistech">
        <div class="card-body" style="padding: 2rem;">
            <form action="{{ route('admin.gallery.update', $galleryItem) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Replace Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*" id="imageInput" onchange="previewImage(this, 'preview')">
                    <small class="text-muted">Leave empty to keep current image. JPEG, PNG, JPG, GIF, or WebP. Max 5MB.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $galleryItem->title) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                    <input type="text" name="category" class="form-control" value="{{ old('category', $galleryItem->category) }}" required list="categoryList">
                    <datalist id="categoryList">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">
                        @endforeach
                    </datalist>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $galleryItem->description) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $galleryItem->sort_order) }}" min="0" style="max-width: 150px;">
                </div>

                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input type="checkbox" name="is_active" class="form-check-input" id="isActive" {{ old('is_active', $galleryItem->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="isActive">Active (visible on public site)</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-sistech btn-lg">
                    <i class="fas fa-save me-1"></i> Save Changes
                </button>
            </form>
        </div>
    </div>

    <div>
        <div class="card-sistech">
            <div class="card-header fw-semibold"><i class="fas fa-image me-2"></i>Current Image</div>
            <div class="card-body text-center">
                <div id="preview">
                    <img src="{{ file_url($galleryItem->image) }}" alt="{{ $galleryItem->title }}" style="width: 100%; border-radius: 8px; border: 1px solid var(--border);">
                </div>
                <small class="text-muted d-block mt-2">Uploaded: {{ $galleryItem->created_at->format('M d, Y') }}</small>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function previewImage(input, previewId) {
    var preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview" style="width: 100%; border-radius: 8px; border: 1px solid var(--border);">';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
