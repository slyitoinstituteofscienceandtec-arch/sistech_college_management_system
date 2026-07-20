@extends('layouts.app')

@section('title', 'Gallery')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 style="margin: 0; font-weight: 700;"><i class="fas fa-images me-2" style="color: var(--primary);"></i>Gallery Management</h4>
        <small class="text-muted">{{ $galleryItems->total() }} image(s) total</small>
    </div>
    <a href="{{ route('admin.gallery.create') }}" class="btn btn-sistech">
        <i class="fas fa-plus me-1"></i> Upload Image
    </a>
</div>

<div class="card-sistech mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.gallery.index') }}" class="d-flex gap-2 flex-wrap">
            <input type="text" name="search" class="form-control" placeholder="Search title or category..." value="{{ request('search') }}" style="max-width: 300px;">
            <select name="category" class="form-control" style="max-width: 200px;">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-sistech"><i class="fas fa-search me-1"></i> Filter</button>
            @if(request('search') || request('category'))
                <a href="{{ route('admin.gallery.index') }}" class="btn btn-outline-secondary">Clear</a>
            @endif
        </form>
    </div>
</div>

<div class="row g-3">
    @forelse($galleryItems as $item)
    <div class="col-md-3 col-sm-6">
        <div class="card-sistech h-100" style="overflow: hidden;">
            <div style="aspect-ratio: 1; overflow: hidden; position: relative;">
                <img src="{{ str_starts_with($item->image ?? '', 'http') ? $item->image : asset('storage/' . $item->image) }}" alt="{{ $item->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                @if(!$item->is_active)
                <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center;">
                    <span class="badge bg-danger">Inactive</span>
                </div>
                @endif
            </div>
            <div class="card-body p-3">
                <h6 style="margin: 0 0 4px; font-weight: 600;">{{ $item->title }}</h6>
                <small class="text-muted">
                    <span class="badge bg-light text-dark" style="font-weight: 500;">{{ ucfirst($item->category) }}</span>
                    @if($item->sort_order > 0)
                        <span class="ms-1">Order: {{ $item->sort_order }}</span>
                    @endif
                </small>
                @if($item->description)
                <p style="margin: 6px 0 0; font-size: 12px; color: var(--text-muted);">{{ Str::limit($item->description, 60) }}</p>
                @endif
            </div>
            <div class="card-footer d-flex gap-2 justify-content-end" style="background: var(--bg); border-top: 1px solid var(--border);">
                <a href="{{ route('admin.gallery.edit', $item) }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-edit"></i>
                </a>
                <form action="{{ route('admin.gallery.destroy', $item) }}" method="POST" onsubmit="return confirm('Delete this image?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card-sistech">
            <div class="card-body text-center py-5 text-muted">
                <i class="fas fa-images fa-3x mb-3" style="opacity: 0.2;"></i>
                <p style="margin: 0;">No images in the gallery yet.</p>
                <a href="{{ route('admin.gallery.create') }}" class="btn btn-sistech btn-sm mt-3">
                    <i class="fas fa-plus me-1"></i> Upload your first image
                </a>
            </div>
        </div>
    </div>
    @endforelse
</div>

@if(method_exists($galleryItems, 'links') && $galleryItems->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $galleryItems->withQueryString()->links() }}
</div>
@endif
@endsection
