{{-- resources/views/welcome.blade.php --}}

@extends('layouts.app') {{-- Change this if your layout file is named differently --}}

@section('title', 'Welcome')

@section('content')
    <div class="welcome-content">
        <h1>Welcome to the ROTC Library Management System</h1>
        <p>Your gateway to knowledge and resources.</p>
    </div>
@endsection

<style>
    .welcome-content h1, .welcome-content p {
        background-color: rgba(0, 0, 0, 0.6); /* Semi-transparent black background */
        color: white; /* White text for contrast */
        border-radius: 8px; /* Rounded corners for a smoother look */
        text-align: center;
    }
</style>
