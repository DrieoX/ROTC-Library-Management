@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="dashboard-container">
    <h1>Edit Profile</h1>
    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PATCH')
        <div>
            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" value="{{ $user->first_name }}" required>
        </div>
        <div>
            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" value="{{ $user->last_name }}" required>
        </div>
        <div>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ $user->email }}" required>
        </div>
        <div>
            <button type="submit" class="update-button">Update Profile</button>
        </div>
    </form>

    <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Are you sure you want to delete your account?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="delete-button">Delete Account</button>
    </form>
</div>
@endsection
