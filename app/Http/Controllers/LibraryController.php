<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookBorrowing;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LibraryController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query();

        if ($request->search) {
            $query->where('title', 'like', "%{$request->search}%")
                  ->orWhere('author', 'like', "%{$request->search}%");
        }

        $books = $query->latest()->paginate(20);
        return view('library.index', compact('books'));
    }

    public function create()
    {
        return view('library.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:50',
            'publisher' => 'nullable|string|max:255',
            'category' => 'required|string',
            'quantity' => 'nullable|integer|min:1',
            'shelf_location' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'pdf_file' => 'nullable|file|max:10240',
        ]);

        $validated['quantity'] = $validated['quantity'] ?? 1;
        $validated['available'] = $validated['quantity'];

        $pdfUrl = null;
        if ($request->hasFile('pdf_file')) {
            $file = $request->file('pdf_file');
            $cloudinary = app(CloudinaryService::class);
            $cloudUrl = $cloudinary->upload($file, 'sistech/books');
            $pdfUrl = $cloudUrl ?: $file->store('books/pdfs', 'public');
        }

        unset($validated['pdf_file']);
        $validated['pdf_file'] = $pdfUrl;

        Book::create($validated);

        return redirect()->route('admin.library.index')->with('success', 'Book added successfully.');
    }

    public function show(Book $book)
    {
        return view('library.show', compact('book'));
    }

    public function edit(Book $book)
    {
        return view('library.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $data = $request->only(['title', 'author', 'category', 'description']);

        if ($request->hasFile('pdf_file')) {
            $this->deleteFile($book->pdf_file);
            $file = $request->file('pdf_file');
            $cloudinary = app(CloudinaryService::class);
            $cloudUrl = $cloudinary->upload($file, 'sistech/books');
            $data['pdf_file'] = $cloudUrl ?: $file->store('books/pdfs', 'public');
        }

        $book->update($data);
        return redirect()->route('admin.library.show', $book)->with('success', 'Book updated successfully.');
    }

    public function destroy(Book $book)
    {
        $this->deleteFile($book->pdf_file);
        $book->delete();
        return redirect()->route('admin.library.index')->with('success', 'Book deleted successfully.');
    }

    public function borrowings(Request $request)
    {
        $query = BookBorrowing::with(['book', 'student.user']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->date_from) {
            $query->whereDate('borrow_date', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('borrow_date', '<=', $request->date_to);
        }

        $borrowings = $query->latest()->paginate(20);
        return view('library.borrowings', compact('borrowings'));
    }

    public function returnBook(BookBorrowing $borrowing)
    {
        $borrowing->update([
            'status' => 'returned',
            'return_date' => now(),
        ]);

        $book = $borrowing->book;
        $book->available = $book->available + 1;
        $book->save();

        return back()->with('success', 'Book returned successfully.');
    }

    protected function deleteFile(?string $path): void
    {
        if (!$path) {
            return;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            app(CloudinaryService::class)->destroy($path);
        } elseif (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
