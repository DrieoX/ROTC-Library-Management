<!-- resources/views/auth/login.blade.php -->

@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container mx-auto mt-8 p-6 max-w-md bg-white text-black rounded-lg shadow-lg">
    <h1 class="text-3xl font-bold mb-6 text-center">Login</h1>
    
    <form action="{{ route('login.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="email" class="form-label block font-semibold">Email:</label>
            <input type="email" id="email" name="email" class="form-input w-full border-gray-300 rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label for="password" class="form-label block font-semibold">Password:</label>
            <input type="password" id="password" name="password" class="form-input w-full border-gray-300 rounded px-3 py-2" required>
        </div>

        <button type="submit" class="w-full py-2 mt-4 bg-blue-500 text-white font-semibold rounded hover:bg-blue-600">Login</button>
    </form>
</div>
@endsection
