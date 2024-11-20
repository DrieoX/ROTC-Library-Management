<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Requests; // Correct model for handling requests
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the books for librarians to manage.
     */
    public function index()
    {
        // Fetch all books with their associated copies
        $books = Book::with('copies')->paginate(10);

        // Calculate the counts for live stats
        $ongoingBorrowedCount = Requests::where('status', 'borrowed')->count();
        $requestedBooksCount = Requests::where('status', 'pending')->count();

        // Fetch all requests (pending and borrowed)
        $requests = Requests::with(['bookCopy.book', 'student']) // Eager load relationships
                            ->whereIn('status', ['pending', 'borrowed']) // Fetch requests that are pending or borrowed
                            ->get();

        // Pass all data to the librarian's welcome view
        return view('librarian.welcome', compact('books', 'ongoingBorrowedCount', 'requestedBooksCount', 'requests'));
    }

    /**
     * Show the form for creating a new book.
     */
    public function create()
    {
        return view('books.create'); // Change this to the correct blade file for adding a book
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
            'isbn' => 'required|string|unique:book_copies|max:13',
            'quantity' => 'required|integer|min:1',
        ]);

        // Create the main book record
        $book = Book::create([
            'title' => $request->input('title'),
            'cover_image' => $request->hasFile('cover_image') ? $request->file('cover_image')->store('covers', 'public') : null,
            'author' => $request->input('author'),
        ]);

        // Add book copies
        for ($i = 0; $i < $request->input('quantity'); $i++) {
            BookCopy::create([
                'book_id' => $book->id,
                'isbn' => $request->input('isbn'),
                'available' => true, // All new copies are available by default
            ]);
        }

        return redirect()->route('librarian.welcome')->with('success', 'Book added successfully!');
    }

    /**
     * Show the form for editing a specific book.
     */
    public function edit($id)
    {
        $book = Book::findOrFail($id);
        return view('books.edit', compact('book')); // Change this to the correct blade file for editing a book
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
            'isbn' => 'required|string|max:13|unique:book_copies,isbn,' . $id . ',book_id',
            'quantity' => 'required|integer|min:1',
        ]);

        $book = Book::findOrFail($id);

        // Update the main book record
        $coverImagePath = $book->cover_image;
        if ($request->hasFile('cover_image')) {
            $coverImagePath = $request->file('cover_image')->store('covers', 'public');
        }

        $book->update([
            'title' => $request->input('title'),
            'cover_image' => $coverImagePath,
            'author' => $request->input('author'),
        ]);

        // Adjust book copies
        $existingCopies = BookCopy::where('book_id', $book->id)->count();
        $newCopiesCount = $request->input('quantity');

        if ($newCopiesCount > $existingCopies) {
            // Add new copies
            for ($i = $existingCopies; $i < $newCopiesCount; $i++) {
                BookCopy::create([
                    'book_id' => $book->id,
                    'isbn' => $request->input('isbn'),
                    'available' => true,
                ]);
            }
        } elseif ($newCopiesCount < $existingCopies) {
            // Remove extra copies
            BookCopy::where('book_id', $book->id)
                ->latest()
                ->limit($existingCopies - $newCopiesCount)
                ->delete();
        }

        return redirect()->route('librarian.welcome')->with('success', 'Book updated successfully!');
    }

    /**
     * Delete a book from storage.
     */
    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete(); // This deletes the book, and the associated book copies are also deleted because of the foreign key constraint
        return redirect()->route('librarian.welcome')->with('success', 'Book deleted successfully!');
    }
}
