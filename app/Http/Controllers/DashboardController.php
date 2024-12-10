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

    public function update(Request $request)
{
    $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255',
    ]);

    $user = auth()->user();

    // Combine first_name and last_name for the `name` column in the `users` table
    $fullName = $request->input('first_name') . ' ' . $request->input('last_name');

    // Update the users table
    $user->update([
        'name' => $fullName,
        'email' => $request->input('email'),
    ]);

    // Update the students table if applicable
    if ($user->student) {
        $user->student->update([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
        ]);
    }

    return redirect()->route('dashboard')->with('success', 'Profile updated successfully!');
}

public function edit()
    {
        $user = auth()->user(); // Get the authenticated user
        return view('profile.edit', compact('user'));
    }

    // Delete the authenticated user's profile
    public function destroy()
    {
        $user = auth()->user();

        // Delete the user from the database
        $user->delete();

        // Logout the user after deletion
        auth()->logout();

        return redirect('/')->with('success', 'Your account has been deleted successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        // Verify the current password
        if (!Hash::check($request->input('current_password'), $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        // Update the password
        $user->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

}
