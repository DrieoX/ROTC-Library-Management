<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Requests;
use App\Models\BorrowingTransaction;
use App\Models\Achievement;
use App\Events\BookRequested;
use App\Events\RequestReviewed;
use App\Events\BookBorrowed; // Add this import for the event
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

        // Create the request (only student_id, book_copy_id, and status)
        $request = Requests::create([
            'student_id' => Auth::user()->student ? Auth::user()->student->id : null, // Ensure student_id is correct
            'book_copy_id' => $bookCopy->id, // Store the book copy ID in the request
            'status' => 'pending', // Initial status is pending
        ]);

        // Dispatch the event for requesting a book
        event(new BookRequested($request));

        // Assign "Request a Book" achievement
        $student = $request->student;
        $requestAchievement = Achievement::where('title', 'Request a Book')->first();

        if ($requestAchievement && !$student->achievements->contains($requestAchievement->id)) {
            $student->achievements()->attach($requestAchievement->id, ['notified' => false]);
        }

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

        $bookCopy = BookCopy::findOrFail($request->book_copy_id);

        // Check if the book copy is still available
        if (!$bookCopy->available) {
            return redirect()->back()->with('error', 'This book is no longer available.');
        }

        // Update request status to approved
        $request->status = 'approved';
        $request->save();

        // Mark the book copy as unavailable
        $bookCopy->available = false;
        $bookCopy->save();

        // Get the librarian's ID from the authenticated user
        $librarianId = Auth::user()->librarian->id; // Reference the librarian's ID

        // Create a borrowing transaction when the request is approved
        $borrowDate = Carbon::now(); // current date as borrow date
        $dueDate = Carbon::now()->addDays(7); // set due date 7 days from now

        BorrowingTransaction::create([
            'book_id' => $bookCopy->book_id,
            'student_id' => $request->student_id, // Use student_id from the request
            'librarian_id' => $librarianId, // Correctly reference the librarian ID
            'borrow_date' => $borrowDate,
            'due_date' => $dueDate,
            'status' => 'active',
        ]);

        // Check and assign the "First Borrow" achievement
        $student = $request->student; // Get the student who made the request
        $hasPreviousBorrows = BorrowingTransaction::where('student_id', $student->id)->exists();

        if (!$hasPreviousBorrows) {
            $firstBorrowAchievement = Achievement::where('title', 'First Borrow')->first();

            if ($firstBorrowAchievement && !$student->achievements->contains($firstBorrowAchievement->id)) {
                $student->achievements()->attach($firstBorrowAchievement->id, ['notified' => false]);

                // Dispatch the BookBorrowed event to trigger any further actions like achievement notification
                event(new BookBorrowed($student)); // Dispatch the event for first borrow
            }
        }

        // Dispatch the event for the book request being approved
        event(new RequestReviewed($request, 'approved'));

        // Assign "Book Request Reviewed" achievement when request is approved
        $reviewedAchievement = Achievement::where('title', 'Book Request Reviewed')->first();

        if ($reviewedAchievement && !$student->achievements->contains($reviewedAchievement->id)) {
            $student->achievements()->attach($reviewedAchievement->id, ['notified' => false]);
        }

        return redirect()->route('librarian.welcome')->with('success', 'Request approved successfully.');
    }

    /**
     * Deny a book borrowing request.
     */
    public function deny($requestId)
    {
        $request = Requests::findOrFail($requestId);

        // Ensure the request is still pending
        if ($request->status !== 'pending') {
            return redirect()->back()->with('error', 'Request is no longer pending.');
        }

        $request->status = 'rejected';
        $request->save();

        // Dispatch the event for the book request being denied
        event(new RequestReviewed($request, 'denied'));

        // Assign "Book Request Reviewed" achievement when request is denied
        $student = $request->student;
        $reviewedAchievement = Achievement::where('title', 'Book Request Reviewed')->first();

        if ($reviewedAchievement && !$student->achievements->contains($reviewedAchievement->id)) {
            $student->achievements()->attach($reviewedAchievement->id, ['notified' => false]);
        }

        return redirect()->route('librarian.welcome')->with('success', 'Request denied successfully.');
    }

    /**
     * Display a listing of requests for librarians to approve or deny.
     */
    public function index()
    {
        // Get all pending requests for librarians to approve/deny
        $requests = Requests::where('status', 'pending')->get();

        return view('librarian.welcome', compact('requests'));
    }

    /**
     * List requested books for librarians (grouped by book).
     */
    public function listRequests()
    {
        // Get all pending requests and group by book ID, sorted by book title
        $requests = Requests::with(['bookCopy.book', 'student'])
            ->where('status', 'pending')
            ->get()
            ->groupBy('bookCopy.book_id'); // Group requests by book ID for sorting

        return view('librarian.welcome', compact('requests'));
    }
}
