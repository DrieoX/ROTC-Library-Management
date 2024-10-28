<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of books.
     */
    public function index()
    {
        // Retrieve all books and their availability
        $books = Book::all();

        return view('books.index', compact('books'));
    }

    /**
     * Display a specific book details.
     */
    public function show($id)
    {
        // Retrieve a specific book by its ID
        $book = Book::findOrFail($id);

        return view('books.show', compact('book'));
    }

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
