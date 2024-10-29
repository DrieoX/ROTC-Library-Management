<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Requests; // Assuming Borrow is the model for borrowing transactions
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
     * Display a specific book's details.
     */
    public function show($id)
    {
        // Retrieve a specific book by its ID
        $book = Book::findOrFail($id);

        return view('books.show', compact('book'));
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
