<!-- resources/views/auth/register_student.blade.php -->

@extends('layouts.app')

@section('title', 'Register as Student')

@section('content')
<div class="container mx-auto mt-8 p-6 max-w-md bg-white text-black rounded-lg shadow-lg">
    <h1 class="text-3xl font-bold mb-6 text-center text-rotc-yellow">Register as Student</h1>

    @if ($errors->any())
        <div class="mb-4">
            <ul class="list-disc pl-5 text-red-500">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register-student') }}">
        @csrf
        <div class="mb-4">
            <label for="first_name" class="form-label font-semibold">First Name</label>
            <input type="text" name="first_name" id="first_name" class="form-input w-full border-gray-300 rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label for="last_name" class="form-label font-semibold">Last Name</label>
            <input type="text" name="last_name" id="last_name" class="form-input w-full border-gray-300 rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label for="email" class="form-label font-semibold">Email</label>
            <input type="email" name="email" id="email" class="form-input w-full border-gray-300 rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label for="password" class="form-label font-semibold">Password</label>
            <input type="password" name="password" id="password" class="form-input w-full border-gray-300 rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label font-semibold">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-input w-full border-gray-300 rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label for="contact_number" class="form-label font-semibold">Contact Number</label>
            <input type="text" name="contact_number" id="contact_number" class="form-input w-full border-gray-300 rounded px-3 py-2">
        </div>

        <button type="submit" class="w-full py-2 mt-4 bg-rotc-yellow text-rotc-dark-green font-semibold rounded hover:bg-rotc-hover-yellow">Register</button>
    </form>
</div>
@endsection
