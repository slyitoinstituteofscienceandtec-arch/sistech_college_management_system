@extends('layouts.app')
@section('title', 'Edit Book')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Edit Book</h4>
        <p class="text-muted mb-0" style="font-size:13px;">Update book details</p>
    </div>
    <a href="{{ route('admin.library.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
</div>

<div class="card-sistech" style="max-width: 600px;">
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger" style="border-radius: 10px; font-size: 13px;">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('admin.library.update', $book->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-semibold">Title</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $book->title) }}">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Author</label>
                <input type="text" name="author" class="form-control" value="{{ old('author', $book->author) }}">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Category</label>
                <select name="category" class="form-select">
                    <option value="">Select</option>
                    @foreach(['textbook','reference','fiction','non-fiction','journal','digital','other'] as $cat)
                        <option value="{{ $cat }}" {{ old('category', $book->category) === $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $book->description) }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">PDF File</label>
                @if($book->pdf_file)
                <div class="mb-2">
                    <a href="{{ @fileurl($book->pdf_file) }}" target="_blank" class="text-decoration-none" style="font-size: 13px;">
                        <i class="fas fa-file-pdf text-danger me-1"></i> Current PDF
                    </a>
                </div>
                @endif
                <input type="file" name="pdf_file" class="form-control" accept=".pdf">
                <small class="text-muted">PDF only, max 10MB. Leave empty to keep current.</small>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('admin.library.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-sistech">
                    <i class="fas fa-save me-1"></i> Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
