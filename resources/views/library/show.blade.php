@extends('layouts.app')
@section('title', $book->title)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ $book->title }}</h4>
        <p class="text-muted mb-0" style="font-size:13px;">{{ $book->author }}</p>
    </div>
    <div class="d-flex gap-2">
        @if($book->pdf_file)
        <a href="{{ str_starts_with($book->pdf_file ?? '', 'http') ? $book->pdf_file : asset('storage/' . $book->pdf_file) }}" target="_blank" class="btn btn-danger">
            <i class="fas fa-file-pdf me-1"></i> Open PDF
        </a>
        @endif
        <a href="{{ route('admin.library.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
        <a href="{{ route('admin.library.edit', $book->id) }}" class="btn btn-sistech">
            <i class="fas fa-edit me-1"></i> Edit
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card-sistech">
            <div class="card-header">
                <i class="fas fa-book me-2" style="color: var(--primary);"></i>Details
            </div>
            <div class="card-body" style="font-size: 13px;">
                <div class="d-flex justify-content-between py-2" style="border-bottom: 1px solid var(--border);">
                    <span class="text-muted">Title</span>
                    <strong>{{ $book->title ?? '—' }}</strong>
                </div>
                <div class="d-flex justify-content-between py-2" style="border-bottom: 1px solid var(--border);">
                    <span class="text-muted">Author</span>
                    <strong>{{ $book->author ?? '—' }}</strong>
                </div>
                <div class="d-flex justify-content-between py-2" style="border-bottom: 1px solid var(--border);">
                    <span class="text-muted">Category</span>
                    <strong>{{ ucfirst($book->category ?? '—') }}</strong>
                </div>
                <div class="d-flex justify-content-between py-2">
                    <span class="text-muted">PDF</span>
                    @if($book->pdf_file)
                        <a href="{{ str_starts_with($book->pdf_file ?? '', 'http') ? $book->pdf_file : asset('storage/' . $book->pdf_file) }}" target="_blank" class="text-decoration-none">
                            <i class="fas fa-file-pdf text-danger me-1"></i> Available
                        </a>
                    @else
                        <span class="text-muted">Not uploaded</span>
                    @endif
                </div>
                @if($book->description)
                <div class="mt-3">
                    <small class="text-muted fw-semibold">Description</small>
                    <p class="mt-1 mb-0">{{ $book->description }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        @if($book->pdf_file)
        <div class="card-sistech">
            <div class="card-header">
                <i class="fas fa-file-pdf me-2" style="color: #DC2626;"></i>PDF Preview
            </div>
            <div class="card-body p-0">
                <iframe src="{{ str_starts_with($book->pdf_file ?? '', 'http') ? $book->pdf_file : asset('storage/' . $book->pdf_file) }}" style="width: 100%; height: 600px; border: none; border-radius: 0 0 12px 12px;"></iframe>
            </div>
        </div>
        @else
        <div class="card-sistech">
            <div class="card-body text-center py-5 text-muted">
                <i class="fas fa-file-pdf" style="font-size: 3rem; opacity: 0.3;"></i>
                <p class="mt-2 mb-0">No PDF uploaded for this book.</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
