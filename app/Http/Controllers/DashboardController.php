<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Requests;
use App\Models\Book;
use App\Models\Librarian;

class DashboardController extends Controller
{
    /**
     * Show the dashboard for students.
     */
    public function index()
    {
        // Get the authenticated user’s student record and load achievements
        $student = auth()->user()->student;
        $achievements = $student ? $student->achievements : [];

        return view('dashboard', compact('achievements'));
    }

    /**
     * Show the dashboard for librarians.
     */
    public function librarianIndex()
    {
        $librarian = auth()->user()->librarian;
        $books = Book::all();
        // Here, you can fetch librarian-specific data as needed

        $ongoingBorrowedCount = Requests::where('status', 'ongoing')->count();
        $requestedBooksCount = Requests::where('status', 'requested')->count();

        return view('librarian.dashboard', compact('books', 'librarian','ongoingBorrowedCount', 'requestedBooksCount'));
    }
}
