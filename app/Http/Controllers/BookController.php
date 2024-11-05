<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Requests; // Assuming Requests is the model for borrowing transactions
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of books with ongoing and requested borrow counts.
     */
    public function index()
    {
        $books = Book::all();

        $ongoingBorrowedCount = Requests::where('status', 'ongoing')->count();
        $requestedBooksCount = Requests::where('status', 'requested')->count();

        return view('librarian.welcome', compact('books', 'ongoingBorrowedCount', 'requestedBooksCount'));
    }

    /**
     * Show the form for creating a new book.
     */
    public function create()
    {
        return view('books.create'); // Create a view file for the book creation form
    }

    /**
     * Store a newly created book in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books|max:13', // Validate ISBN uniqueness
            'quantity' => 'required|integer|min:1',
        ]);

        // Handle the cover image upload if provided
        $coverImagePath = null;
        if ($request->hasFile('cover_image')) {
            $coverImagePath = $request->file('cover_image')->store('covers', 'public');
        }

        Book::create([
            'title' => $request->input('title'),
            'cover_image' => $coverImagePath,
            'author' => $request->input('author'),
            'isbn' => $request->input('isbn'),
            'quantity' => $request->input('quantity'),
        ]);

        return redirect()->route('books.index')->with('success', 'Book added successfully!');
    }

    /**
     * Display a specific book's details.
     */
    public function show($id)
    {
        // Retrieve a specific book by its ID
        $book = Book::findOrFail($id);

        return view('books.show', compact('book'));
    }

    /**
     * Show the form for editing a specific book.
     */
    public function edit($id)
    {
        $book = Book::findOrFail($id);
        return view('books.edit', compact('book')); // Create a view file for the book edit form
    }

    /**
     * Update a specific book in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|max:13|unique:books,isbn,' . $id, // Exclude current book from uniqueness check
            'quantity' => 'required|integer|min:1',
        ]);

        $book = Book::findOrFail($id);

        // Handle the cover image upload if provided
        $coverImagePath = $book->cover_image; // Keep the existing image if none is uploaded
        if ($request->hasFile('cover_image')) {
            // Delete old image if necessary
            if ($coverImagePath) {
                \Storage::disk('public')->delete($coverImagePath);
            }
            $coverImagePath = $request->file('cover_image')->store('covers', 'public');
        }

        $book->update([
            'title' => $request->input('title'),
            'cover_image' => $coverImagePath,
            'author' => $request->input('author'),
            'isbn' => $request->input('isbn'),
            'quantity' => $request->input('quantity'),
        ]);

        return redirect()->route('books.index')->with('success', 'Book updated successfully!');
    }

    /**
     * Search books by title and optional genre.
     */
    public function searchBooks(Request $request)
    {
        $query = $request->input('search');
        $genre = $request->input('genre');

        $books = Book::where('title', 'like', "%$query%")
                     ->when($genre, function ($q) use ($genre) {
                         return $q->where('genre', $genre);
                     })
                     ->get();

        return response()->json($books); // Return results as JSON
    }
}
