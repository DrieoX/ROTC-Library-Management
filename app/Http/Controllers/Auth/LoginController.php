<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Display the login view.
     */
    public function create()
    {
        return view('auth.login'); // Adjust the view path if necessary
    }

    /**
     * Handle an incoming login request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Attempt to log the user in
        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate(); // Regenerate session to prevent session fixation

            // Redirect based on user role
            if (Auth::user()->role === 'librarian') {
                return redirect()->intended('/librarian');
            }

        }

        // If authentication fails, redirect back with errors
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Log the user out of the application.
     */
    public function destroy(Request $request)
    {
        Auth::logout(); // Log out the user

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate CSRF token
        $request->session()->regenerateToken();

        return redirect('/login'); // Redirect to the login page
    }
}
