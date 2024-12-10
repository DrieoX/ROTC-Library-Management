<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Requests;
use App\Models\BorrowingTransaction;
use App\Models\Achievement;
use App\Events\BookRequested;
use App\Events\RequestReviewed;
use App\Events\BookBorrowed;
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

    // Check if the user has a pending request or an active transaction
    $existingRequest = Requests::where('student_id', Auth::id())
        ->where('book_copy_id', $bookCopy->id)
        ->whereIn('status', ['pending', 'approved'])
        ->orderBy('created_at', 'desc') // Get the most recent request
        ->first();

    if ($existingRequest) {
        // Check if the latest request is still pending
        if ($existingRequest->status === 'pending') {
            return redirect()->back()->with('error', 'You already have a pending request for this book.');
        }
    }

    // Check if the user has a borrowing transaction for this book
    $existingTransaction = BorrowingTransaction::where('student_id', Auth::id())
        ->where('book_id', $bookId)
        ->whereIn('status', ['active', 'pending'])
        ->first();

    if ($existingTransaction) {
        return redirect()->back()->with('error', 'You cannot request this book as it is currently borrowed or pending.');
    }

    // Check if there are any previous requests for this book and determine if they have been returned
    $previousRequest = Requests::where('student_id', Auth::id())
        ->where('book_copy_id', $bookCopy->id)
        ->orderBy('created_at', 'desc') // Get the most recent request
        ->first();

    if ($previousRequest) {
        // If the most recent request has been returned, allow a new request
        if ($previousRequest->status === 'returned') {
            // Create the new request
            $newRequest = Requests::create([
                'student_id' => Auth::user()->student ? Auth::user()->student->id : null,
                'book_copy_id' => $bookCopy->id,
                'status' => 'pending',
            ]);

            // Dispatch event for book requested
            event(new BookRequested($newRequest));

            // Assign "Request a Book" achievement
            $student = $newRequest->student;
            $student->load('achievements'); // Ensure achievements are loaded

            $requestAchievement = Achievement::where('title', 'Request a Book')->first();
            if ($requestAchievement && !$student->achievements->contains($requestAchievement->id)) {
                $student->achievements()->attach($requestAchievement->id, ['notified' => false, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
            }

            return redirect()->route('books.list')->with('success', 'Your request has been submitted.');
        } else {
            // If the previous request is still pending, do not allow a new request
            return redirect()->back()->with('error', 'You cannot request this book at the moment as the previous request is still pending.');
        }
    } else {
        // If no previous request exists, allow the user to send a new request
        $newRequest = Requests::create([
            'student_id' => Auth::user()->student ? Auth::user()->student->id : null,
            'book_copy_id' => $bookCopy->id,
            'status' => 'pending',
        ]);

        // Dispatch event for book requested
        event(new BookRequested($newRequest));

        // Assign "Request a Book" achievement
        $student = $newRequest->student;
        $student->load('achievements'); // Ensure achievements are loaded

        $requestAchievement = Achievement::where('title', 'Request a Book')->first();
        if ($requestAchievement && !$student->achievements->contains($requestAchievement->id)) {
            $student->achievements()->attach($requestAchievement->id, ['notified' => false, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
        }

        return redirect()->route('books.list')->with('success', 'Your request has been submitted.');
    }
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

        // Get the librarian's ID
        $librarianId = Auth::user()->librarian->id;

        // Create borrowing transaction
        $borrowDate = Carbon::now();
        $dueDate = Carbon::now()->addDays(7);

        BorrowingTransaction::create([
            'book_id' => $bookCopy->book_id,
            'student_id' => $request->student_id,
            'librarian_id' => $librarianId,
            'borrow_date' => $borrowDate,
            'due_date' => $dueDate,
            'status' => 'active',
        ]);

        // Check and assign "First Borrow" achievement
        $student = $request->student;
        $student->load('achievements'); // Load achievements

        $hasPreviousBorrows = BorrowingTransaction::where('student_id', $student->id)->exists();

        if (!$hasPreviousBorrows) {
            $firstBorrowAchievement = Achievement::where('title', 'First Borrow')->first();
            if ($firstBorrowAchievement && !$student->achievements->contains($firstBorrowAchievement->id)) {
                $student->achievements()->attach($firstBorrowAchievement->id, ['notified' => false, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
                event(new BookBorrowed($student)); // Trigger event for first borrow
            }
        }

        // Dispatch event for approved request
        event(new RequestReviewed($request, 'approved'));

        // Assign "Book Request Reviewed" achievement
        $reviewedAchievement = Achievement::where('title', 'Book Request Reviewed')->first();
        if ($reviewedAchievement && !$student->achievements->contains($reviewedAchievement->id)) {
            $student->achievements()->attach($reviewedAchievement->id, ['notified' => false, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
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

        // Dispatch event for denied request
        event(new RequestReviewed($request, 'denied'));

        // Assign "Book Request Reviewed" achievement when request is denied
        $student = $request->student;
        $student->load('achievements'); // Load achievements

        $reviewedAchievement = Achievement::where('title', 'Book Request Reviewed')->first();
        if ($reviewedAchievement && !$student->achievements->contains($reviewedAchievement->id)) {
            $student->achievements()->attach($reviewedAchievement->id, ['notified' => false, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
        }

        return redirect()->route('librarian.welcome')->with('success', 'Request denied successfully.');
    }

    public function return($transactionId)
{
    // Find the borrowing transaction
    $transaction = BorrowingTransaction::findOrFail($transactionId);

    // Ensure the transaction is currently active and has been borrowed
    if ($transaction->status !== 'active') {
        return redirect()->back()->with('error', 'This transaction is not active or already returned.');
    }

    // Check if the transaction is overdue
    if ($transaction->due_date < Carbon::now()) {
        // Mark as overdue if past due date
        $transaction->status = 'overdue';
    }

    // Update the borrowing transaction to reflect the return
    $transaction->status = 'returned';
    $transaction->return_date = Carbon::now(); // Set the current date as the return date
    $transaction->save();

    // Find the associated request for this transaction
    $request = $transaction->request;  // Access the associated request

    if ($request) {
        // Update the first request's status to 'returned'
        $request->status = 'returned';
        $request->returned_at = Carbon::now(); // Optional: track the return time
        $request->save();

        // Mark the associated book copy as available again
        $bookCopy = BookCopy::findOrFail($transaction->book_id);
        $bookCopy->available = true;
        $bookCopy->save();
    }

    // If there are other requests associated with the same book copy, update them as well
    $otherRequests = Requests::where('book_copy_id', $transaction->book_copy_id)
        ->where('status', 'pending')  // Only consider pending requests
        ->get();

    // Loop through each pending request and update its status
    foreach ($otherRequests as $otherRequest) {
        $otherRequest->status = 'returned'; // Update status to 'returned'
        $otherRequest->returned_at = Carbon::now(); // Track return time
        $otherRequest->save();
    }

    // Return success response
    return redirect()->route('librarian.welcome')->with('success', 'Book successfully returned.');
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
            ->groupBy('bookCopy.book_id'); // Group requests by book ID

        return view('librarian.welcome', compact('requests'));
    }
}
