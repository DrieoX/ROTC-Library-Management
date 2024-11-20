<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCopy;
use Illuminate\Http\Request;

class ListController extends Controller
{
    /**
     * Display a listing of available books for students to borrow.
     */
    public function index(Request $request)
    {
        // Search functionality for books
        $query = $request->input('search');
        
        // Retrieve books that have available copies and eager load 'copies' relationship
        $books = Book::with('copies') // Eager load the 'copies' relationship
            ->when($query, function ($q) use ($query) {
                return $q->where('title', 'like', "%$query%")
                        ->orWhere('author', 'like', "%$query%");
            })
            ->whereHas('copies', function ($q) {
                $q->where('available', true); // Only show books with available copies
            })
            ->paginate(10); // Paginate results for better UI

        // Passing data to the view (the available books)
        return view('books.list', compact('books'));
    }

    /**
     * Search books by title or author (for students).
     */
    public function search(Request $request)
    {
        $query = $request->input('search');
        
        // Fetch books based on search query with available copies and eager load 'copies'
        $books = Book::with('copies') // Eager load the 'copies' relationship
            ->where('title', 'like', "%$query%")
            ->orWhere('author', 'like', "%$query%")
            ->whereHas('copies', function ($q) {
                $q->where('available', true);
            })
            ->get();

        return response()->json($books);
    }
}
