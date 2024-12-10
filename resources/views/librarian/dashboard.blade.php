@extends('layouts.librarian')

@section('title', 'Dashboard')

@section('content')
<style>
    
    .dashboard-container {
        width: 100%;
        max-width: 800px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }
    .dashboard-container h1 {
        font-size: 24px;
        font-weight: bold;
        text-align: center;
        color: #333;
    }
    .dashboard-container p {
        margin-top: 10px;
        text-align: center;
        color: #555;
    }
    .form-section {
        margin-top: 30px;
    }
    .form-section h4 {
        font-size: 18px;
        font-weight: bold;
        color: #333;
        margin-bottom: 15px;
    }
    form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    label {
        font-size: 14px;
        font-weight: 500;
        color: #555;
        margin-bottom: 5px;
    }
    input {
        width: 100%;
        padding: 10px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
    input:focus {
        outline: none;
        border-color: #4f46e5; /* Indigo border on focus */
        box-shadow: 0 0 3px #4f46e5;
    }
    button {
        padding: 10px;
        font-size: 16px;
        font-weight: bold;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    .update-button {
        background-color: #800000;
    }
    .update-button:hover {
        background-color: green;
    }
    .password-button {
        background-color: #800000;
    }
    .password-button:hover {
        background-color: green;
    }
</style>

<div class="dashboard-container">
    <h1>Dashboard</h1>
    <p>Welcome to your ROTC Library Management System dashboard.</p>

    <!-- Profile Settings Section -->
    <div class="form-section">
    <h4>Hello, {{ auth()->user()->name }}!</h4>
    <h4>Profile Settings</h4>
    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PATCH')
        <div>
            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" value="{{ auth()->user()->first_name }}" required>
        </div>
        <div>
            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" value="{{ auth()->user()->last_name }}" required>
        </div>
        <div>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ auth()->user()->email }}" required>
        </div>
        <div>
            <button type="submit" class="update-button">Update Profile</button>
        </div>
    </form>
</div>



    <!-- Change Password Section -->
    <div class="form-section">
        <h4>Change Password</h4>
        <form method="POST" action="{{ route('password.update') }}">
    @csrf
    @method('PUT') <!-- or PATCH if your route expects PATCH -->
    <div>
        <label for="current_password">Current Password</label>
        <input type="password" id="current_password" name="current_password" required>
    </div>
    <div>
        <label for="password">New Password</label>
        <input type="password" id="password" name="password" required>
    </div>
    <div>
        <label for="password_confirmation">Confirm New Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required>
    </div>
    <div>
        <button type="submit" class="password-button">Change Password</button>
    </div>
</form>

    </div>
</div>
@endsection
