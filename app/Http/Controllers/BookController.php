<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Requests;
use App\Models\BorrowingTransaction; // Add BorrowingTransaction import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    $book = Book::findOrFail($id);

    // Validate input fields
    $request->validate([
        'title' => 'required|string|max:255',
        'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'author' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000', // Validate the description
        'isbn' => 'required|array|min:1',
        'isbn.*' => 'required|string|max:13|unique:book_copies,isbn,' . $id,
    ]);

    DB::beginTransaction();

    try {
        // Update the book record
        $book->update([
            'title' => $request->input('title'),
            'cover_image' => $request->hasFile('cover_image') 
                ? $request->file('cover_image')->store('covers', 'public') 
                : $book->cover_image,
            'author' => $request->input('author'),
            'description' => $request->input('description'), // Update description
        ]);

        // Delete existing book copies and add the new ones
        BookCopy::where('book_id', $book->id)->delete();

        foreach ($request->input('isbn') as $isbn) {
            BookCopy::create([
                'book_id' => $book->id,
                'isbn' => $isbn,
                'available' => true,
            ]);
        }

        DB::commit();

        // Redirect back with success message
        return redirect()->route('librarian.welcome')->with('success', 'Book updated successfully!');
    } catch (\Exception $e) {
        // Rollback transaction and log the error
        DB::rollBack();
        \Log::error('Book Update Error: ' . $e->getMessage());
        return redirect()->back()->withErrors(['error' => 'An error occurred while updating the book.']);
    }
}
    public function destroy($id)
    {
        // Find and delete the book
        $book = Book::findOrFail($id);
        $book->delete();

        // Redirect back with success message
        return redirect()->route('librarian.welcome')->with('success', 'Book deleted successfully!');
    }

    /**
     * Return a borrowed book.
     */
    public function return($transactionId)
{
    // Find the borrowing transaction
    $transaction = BorrowingTransaction::findOrFail($transactionId);

    DB::beginTransaction();

    try {
        // Ensure the status is 'active' before returning the book
        if ($transaction->status !== 'active') {
            return redirect()->route('librarian.welcome')->with('error', 'This transaction is not active.');
        }

        // Update transaction status to 'returned'
        $transaction->status = 'returned';
        $transaction->save();

        // Update the book copy to be available again
        $transaction->bookCopy->available = true;
        $transaction->bookCopy->save();

        // Optionally, you can mark the request as 'returned' if necessary
        $transaction->request->status = 'returned';
        $transaction->request->save();

        DB::commit();

        return redirect()->route('librarian.welcome')->with('success', 'Book returned successfully! It is now available for borrowing again.');
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Return Book Error: ' . $e->getMessage());
        return redirect()->back()->withErrors(['error' => 'An error occurred while returning the book.']);
    }
}
}
