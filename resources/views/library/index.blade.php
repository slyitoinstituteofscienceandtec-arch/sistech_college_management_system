@extends('layouts.app')
@section('title', auth()->user()->role === 'student' ? 'Student Library' : 'Library')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ auth()->user()->role === 'student' ? 'Student Library' : 'Library' }}</h4>
        <p class="text-muted mb-0" style="font-size:13px;">{{ auth()->user()->role === 'student' ? 'Browse and read available books' : 'Manage books and PDF resources' }}</p>
    </div>
    @if(auth()->user()->role !== 'student')
    <a href="{{ route('admin.library.create') }}" class="btn btn-sistech">
        <i class="fas fa-plus me-1"></i> Add Book
    </a>
    @endif
</div>

<div class="card-sistech mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.library.index') }}" class="row g-3 align-items-end">
            <div class="col-md-8">
                <input type="text" name="search" class="form-control" placeholder="Search by title or author..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-sistech flex-grow-1">
                    <i class="fas fa-search me-1"></i> Search
                </button>
                <a href="{{ route('admin.library.index') }}" class="btn btn-outline-secondary">Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="card-sistech">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-book me-2" style="color: var(--primary);"></i>Books</span>
        <span class="badge" style="background: var(--primary-light); color: var(--primary);">{{ $books->total() }}</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sistech mb-0">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th class="text-center">PDF</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $book)
                    <tr>
                        <td class="fw-semibold" style="font-size: 13px;">{{ $book->title }}</td>
                        <td>{{ $book->author ?? '—' }}</td>
                        <td>{{ ucfirst($book->category ?? '—') }}</td>
                        <td class="text-center">
                            @if($book->pdf_file)
                                <a href="{{ @fileurl($book->pdf_file) }}" target="_blank" class="text-decoration-none" title="View PDF">
                                    <i class="fas fa-file-pdf" style="color: #DC2626; font-size: 16px;"></i>
                                </a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.library.show', $book->id) }}" class="btn btn-outline-primary btn-sm" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if(auth()->user()->role !== 'student')
                            <a href="{{ route('admin.library.edit', $book->id) }}" class="btn btn-outline-warning btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.library.destroy', $book->id) }}" class="d-inline" onsubmit="return confirm('Delete?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="fas fa-book" style="font-size: 3rem; opacity: 0.3;"></i>
                            <p class="mt-2 mb-0">No books yet.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($books, 'links'))
    <div class="card-footer d-flex justify-content-center" style="background: var(--bg); border-top: 1px solid var(--border);">
        {{ $books->links() }}
    </div>
    @endif
</div>
@endsection
