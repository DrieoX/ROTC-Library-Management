<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
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
        // Here, you can fetch librarian-specific data as needed

        return view('librarian.welcome', compact('librarian'));
    }
}
