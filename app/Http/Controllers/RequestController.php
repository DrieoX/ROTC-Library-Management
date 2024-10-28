<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Request;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    /**
     * Store a book borrowing request.
     */
    public function store(HttpRequest $request, $bookId)
    {
        $book = Book::findOrFail($bookId);

        // Ensure the book is available
        if (!$book->available) {
            return redirect()->back()->with('error', 'This book is not available for borrowing.');
        }

        // Create the request
        Request::create([
            'student_id' => Auth::id(), // Authenticated user making the request
            'book_id' => $bookId,
            'status' => 'pending', // Initial status is pending
        ]);

        return redirect()->route('books.index')->with('success', 'Your request has been submitted.');
    }

    /**
     * Approve a book borrowing request (Librarian only).
     */
    public function approve($requestId)
    {
        $request = Request::findOrFail($requestId);
        $request->status = 'approved';
        $request->save();

        // Update the book's availability
        $book = Book::findOrFail($request->book_id);
        $book->available = false;
        $book->save();

        return redirect()->route('requests.index')->with('success', 'Request has been approved.');
    }

    /**
     * Deny a book borrowing request (Librarian only).
     */
    public function deny($requestId)
    {
        $request = Request::findOrFail($requestId);
        $request->status = 'denied';
        $request->save();

        return redirect()->route('requests.index')->with('error', 'Request has been denied.');
    }

    /**
     * Display a listing of requests for librarians.
     */
    public function index()
    {
        // Get all pending requests for librarians to approve/deny
        $requests = Request::where('status', 'pending')->get();

        return view('requests.index', compact('requests'));
    }
}
