<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Librarian;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration form for students.
     */
    public function createStudent(): View
    {
        return view('auth.register-student');
    }

    /**
     * Handle the student registration request.
     */
    public function storeStudent(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            // Create user
            $user = User::create([
                'name' => "{$request->first_name} {$request->last_name}",
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Store in students table
            Student::create([
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'contact_number' => $request->contact_number,
            ]);

            event(new Registered($user));

            Auth::login($user);

            // Redirect to the student dashboard
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            \Log::error('Registration error: ' . $e->getMessage());
            return back()->withErrors(['registration' => 'Registration failed, please try again.']);
        }
    }

    /**
     * Show the registration form for librarians.
     */
    public function createLibrarian(): View
    {
        return view('auth.register-librarian');
    }

    /**
     * Handle the librarian registration request.
     */
    public function storeLibrarian(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            // Create user
            $user = User::create([
                'name' => "{$request->first_name} {$request->last_name}",
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Store in librarians table
            Librarian::create([
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'position' => $request->position,
                'contact_number' => $request->contact_number,
            ]);

            event(new Registered($user));

            Auth::login($user);

            // Redirect to the librarian dashboard
            return redirect()->route('librarian.dashboard');
        } catch (\Exception $e) {
            \Log::error('Registration error: ' . $e->getMessage());
            return back()->withErrors(['registration' => 'Registration failed, please try again.']);
        }
    }
}
