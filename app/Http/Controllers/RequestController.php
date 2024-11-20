<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Requests;
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

        // Find an available book copy (only one available copy)
        $bookCopy = BookCopy::where('book_id', $bookId)
                            ->where('available', true)
                            ->first();

        if (!$bookCopy) {
            return redirect()->back()->with('error', 'No available copies of this book.');
        }

        // Check if the user has already requested this book
        $existingRequest = Requests::where('student_id', Auth::id())
                                   ->where('book_copy_id', $bookCopy->id)
                                   ->first();

        if ($existingRequest) {
            return redirect()->back()->with('error', 'You have already requested this book.');
        }

        // Create the request
        Requests::create([
            'student_id' => Auth::id(), // Authenticated user making the request
            'book_copy_id' => $bookCopy->id, // Store the book copy ID in the request
            'status' => 'pending', // Initial status is pending
        ]);

        return redirect()->route('books.list')->with('success', 'Your request has been submitted.');
    }

    /**
     * Approve a book borrowing request (Librarian only).
     */
    public function approve($requestId)
{
    $request = Requests::findOrFail($requestId);

    // Ensure the request is still pending
    if ($request->status !== 'pending') {
        return redirect()->back()->with('error', 'Request is no longer pending.');
    }

    $request->status = 'approved';
    $request->save();

    // Mark the book copy as unavailable
    $bookCopy = BookCopy::findOrFail($request->book_copy_id);
    $bookCopy->available = false;
    $bookCopy->save();

    return redirect()->route('requests.list')->with('success', 'Request approved successfully.');
}

public function deny($requestId)
{
    $request = Requests::findOrFail($requestId);

    // Ensure the request is still pending
    if ($request->status !== 'pending') {
        return redirect()->back()->with('error', 'Request is no longer pending.');
    }

    $request->status = 'denied';
    $request->save();

    return redirect()->route('requests.list')->with('success', 'Request denied successfully.');
}


    /**
     * Display a listing of requests for librarians.
     */
    public function index()
    {
        // Get all pending requests for librarians to approve/deny
        $requests = Requests::where('status', 'pending')->get();

        return view('requests.index', compact('requests'));
    }

    public function listRequests()
    {
        $requests = Requests::with(['bookCopy.book', 'student'])
            ->where('status', 'pending')
            ->get()
            ->groupBy('bookCopy.book_id'); // Group requests by book ID for sorting

        return view('librarian.requested_books', compact('requests'));
    }


    public function stats()
    {
        $totalRequested = Requests::where('status', 'pending')->count();
        $totalBorrowed = Requests::where('status', 'approved')->count();

        return response()->json([
            'requested' => $totalRequested,
            'borrowed' => $totalBorrowed,
        ]);
    }

    


}
