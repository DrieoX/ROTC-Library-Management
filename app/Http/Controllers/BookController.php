<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Requests;
use App\Models\BorrowingTransaction; // Add BorrowingTransaction import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookController extends Controller
{
    public function index()
{
    $books = Book::with('copies')->paginate(10);

    // Count ongoing borrowed and requested books
    $ongoingBorrowedCount = BorrowingTransaction::where('status', 'active')->count(); // Changed to count active borrowing transactions
    $requestedBooksCount = Requests::where('status', 'pending')->count();

    // Fetch requests with their associated book copies and student
    $requests = Requests::with(['bookCopy.book', 'student'])
                        ->whereIn('status', ['pending', 'borrowed'])
                        ->get();

    // Fetch borrowing transactions
    $transactions = BorrowingTransaction::with('book', 'student', 'bookCopy')
                                        ->where('status', 'active') // Only active transactions
                                        ->get();

    // Pass data to the view
    return view('librarian.welcome', compact('books', 'ongoingBorrowedCount', 'requestedBooksCount', 'requests', 'transactions'));
}

public function welcome()
{
    // Fetch books (modify the query as needed for your requirements)
    $books = Book::with('copies')->paginate(10); // Includes book copies if needed

    return view('welcome', compact('books'));
}

    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
{
    // Validate input fields
    $request->validate([
        'title' => 'required|string|max:255',
        'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'author' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000', // Validate the description
        'isbn' => 'required|array|min:1',
        'isbn.*' => 'required|string|max:19|unique:book_copies,isbn',
    ]);

    DB::beginTransaction();

    try {
        // Create the book record
        $book = Book::create([
            'title' => $request->input('title'),
            'cover_image' => $request->hasFile('cover_image') 
                ? $request->file('cover_image')->store('covers', 'public') 
                : null,
            'author' => $request->input('author'),
            'description' => $request->input('description'), // Store description
        ]);

        // Add book copies with ISBNs
        foreach ($request->input('isbn') as $isbn) {
            BookCopy::create([
                'book_id' => $book->id,
                'isbn' => $isbn,
                'available' => true,
            ]);
        }

        DB::commit();

        // Redirect back with success message
        return redirect()->route('librarian.welcome')->with('success', 'Book added successfully!');
    } catch (\Exception $e) {
        // Rollback transaction and log the error
        DB::rollBack();
        \Log::error('Book Store Error: ' . $e->getMessage());
        return redirect()->back()->withErrors(['error' => 'An error occurred while saving the book.']);
    }
}


    public function edit($id)
    {
        // Fetch book and its copies for editing
        $book = Book::with('copies')->findOrFail($id);
        return view('books.edit', compact('book'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'author' => 'required|string|max:255',
        'description' => 'required|string',
        'isbn' => 'required|array',
        'isbn.*' => 'required|string|distinct',
        'cover_image' => 'nullable|image|max:2048',
    ]);

    // Find the book
    $book = Book::findOrFail($id);

    // Update book details
    $book->title = $request->title;
    $book->author = $request->author;
    $book->description = $request->description;

    // Handle cover image upload (optional)
    if ($request->hasFile('cover_image')) {
        $imagePath = $request->file('cover_image')->store('covers', 'public');
        $book->cover_image = basename($imagePath);
    }

    $book->save();

    // Update book copies: match the old copies with the new ISBNs
    foreach ($request->isbn as $index => $isbn) {
        // Check if there's an existing book copy to update
        $copy = $book->copies()->skip($index)->first(); // Get the copy at the current index
        if ($copy) {
            $copy->isbn = $isbn;  // Update the ISBN
            $copy->save();
        } else {
            // If there is no existing copy (in case of new entries), create a new one
            $book->copies()->create(['isbn' => $isbn, 'available' => true]);
        }
    }

    return redirect()->route('librarian.welcome')->with('success', 'Book updated successfully!');
}
public function destroy($id)
{
    // Find the book
    $book = Book::findOrFail($id);

    // Proceed to delete the book
    $book->delete();

    // Redirect back with success message
    return redirect()->route('librarian.welcome')->with('success', 'Book deleted successfully!');
}

public function confirmDelete($id)
{
    // Find the book by ID
    $book = Book::findOrFail($id);

    // Check if the book has related requests
    if ($book->copies()->whereHas('requests')->exists()) {
        return redirect()->route('librarian.welcome')
                         ->with('error', 'Cannot delete this book because it has related requests.');
    }

    // Return the confirmation view
    return view('books.destroy', compact('book'));
}

}