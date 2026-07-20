@extends('layouts.public')

@section('content')
<div class="page-hero">
    <div class="container">
        <h1>Gallery</h1>
        <p>Explore life at SISTECH campus</p>
        <div class="breadcrumb">
            <a href="{{ route('public.home') }}">Home</a>
            <span>/</span>
            <span>Gallery</span>
        </div>
    </div>
</div>

@if($categories->count() > 0)
<section class="section" style="padding-bottom: 0;">
    <div class="container">
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; justify-content: center;">
            <button class="btn gallery-filter active" data-category="all" onclick="filterGallery('all', this)" style="border-radius: 20px; padding: 0.5rem 1.25rem; font-size: 0.85rem; font-weight: 600; border: 2px solid #0066CC; background: #0066CC; color: #fff; cursor: pointer; transition: all 0.2s;">
                All
            </button>
            @foreach($categories as $cat)
            <button class="btn gallery-filter" data-category="{{ $cat }}" onclick="filterGallery('{{ $cat }}', this)" style="border-radius: 20px; padding: 0.5rem 1.25rem; font-size: 0.85rem; font-weight: 600; border: 2px solid #e2e8f0; background: #fff; color: #666; cursor: pointer; transition: all 0.2s;">
                {{ ucfirst($cat) }}
            </button>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="section">
    <div class="container">
        @if($galleryItems->count() > 0)
        <div class="grid-4" style="gap: 1.25rem;" id="galleryGrid">
            @foreach($galleryItems as $item)
            @php $imgUrl = str_starts_with($item->image ?? '', 'http') ? $item->image : asset('storage/' . $item->image); @endphp
            <div class="gallery-item-wrapper" data-category="{{ $item->category }}" style="border-radius: 16px; overflow: hidden; position: relative; aspect-ratio: 1; cursor: pointer; background: #f1f5f9;" onclick="openLightbox('{{ $imgUrl }}', '{{ addslashes($item->title) }}')">
                <img src="{{ $imgUrl }}" alt="{{ $item->title }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div style="display: none; position: absolute; inset: 0; flex-direction: column; align-items: center; justify-content: center; background: #f1f5f9; color: #94a3b8;">
                    <i class="fas fa-image" style="font-size: 2rem; margin-bottom: 0.5rem;"></i>
                    <span style="font-size: 0.8rem;">{{ $item->title }}</span>
                </div>
                <div style="position: absolute; inset: 0; background: linear-gradient(transparent 50%, rgba(0,0,0,0.7)); display: flex; flex-direction: column; justify-content: flex-end; padding: 1rem; opacity: 0; transition: opacity 0.3s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'">
                    <span style="color: #fff; font-weight: 600; font-size: 0.95rem;">{{ $item->title }}</span>
                    <span style="color: rgba(255,255,255,0.7); font-size: 0.8rem;">{{ ucfirst($item->category) }}</span>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div style="text-align: center; padding: 4rem 2rem; color: #94a3b8;">
            <div style="width: 80px; height: 80px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 2rem;">
                <i class="fas fa-images"></i>
            </div>
            <h3 style="color: #475569; margin-bottom: 0.5rem;">Gallery Coming Soon</h3>
            <p style="margin: 0;">Photos will appear here once uploaded by the administration.</p>
        </div>
        @endif
    </div>
</section>

<!-- Lightbox -->
<div id="lightbox" style="display: none; position: fixed; inset: 0; z-index: 9999; background: rgba(0,0,0,0.9); align-items: center; justify-content: center; flex-direction: column;" onclick="closeLightbox()">
    <button onclick="closeLightbox()" style="position: absolute; top: 1rem; right: 1rem; background: none; border: none; color: #fff; font-size: 2rem; cursor: pointer; z-index: 10000;">&times;</button>
    <img id="lightbox-img" src="" alt="" style="max-width: 90%; max-height: 80vh; border-radius: 8px;">
    <p id="lightbox-caption" style="color: #fff; margin-top: 1rem; font-size: 1.1rem; font-weight: 500;"></p>
</div>

@endsection

@section('styles')
<style>
    .gallery-filter.active { background: #0066CC !important; color: #fff !important; border-color: #0066CC !important; }
    .gallery-item-wrapper { transition: transform 0.2s, box-shadow 0.2s; }
    .gallery-item-wrapper:hover { box-shadow: 0 8px 30px rgba(0,0,0,0.2); }
</style>
@endsection

@section('scripts')
<script>
function filterGallery(category, btn) {
    document.querySelectorAll('.gallery-filter').forEach(b => {
        b.style.background = '#fff';
        b.style.color = '#666';
        b.style.borderColor = '#e2e8f0';
        b.classList.remove('active');
    });
    btn.style.background = '#0066CC';
    btn.style.color = '#fff';
    btn.style.borderColor = '#0066CC';
    btn.classList.add('active');

    document.querySelectorAll('.gallery-item-wrapper').forEach(item => {
        if (category === 'all' || item.dataset.category === category) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
}

function openLightbox(src, caption) {
    document.getElementById('lightbox-img').src = src;
    document.getElementById('lightbox-caption').textContent = caption;
    document.getElementById('lightbox').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    document.getElementById('lightbox').style.display = 'none';
    document.body.style.overflow = '';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeLightbox();
});
</script>
@endsection
