@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1>Dashboard</h1>
    <p>Welcome to your ROTC Library Management System dashboard.</p>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium">{{ __("You're logged in!") }}</h3>

                    <!-- Profile Settings Section -->
                    <div class="mt-6">
                        <h4 class="font-semibold text-lg">Profile Settings</h4>
                        <form method="POST" action="{{ route('profile.update') }}" class="mt-4">
                            @csrf
                            @method('PATCH')
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Name</label>
                                <input type="text" id="name" name="name" value="{{ auth()->user()->name }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            </div>
                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Email</label>
                                <input type="email" id="email" name="email" value="{{ auth()->user()->email }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            </div>
                            <div class="mb-4">
                                <button type="submit" class="button bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">Update Profile</button>
                            </div>
                        </form>
                    </div>

                    <!-- Change Password Section -->
                    <div class="mt-8">
                        <h4 class="font-semibold text-lg">Change Password</h4>
                        <form method="POST" action="{{ route('password.update') }}" class="mt-4">
                            @csrf
                            @method('PATCH')
                            <div class="mb-4">
                                <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Current Password</label>
                                <input type="password" id="current_password" name="current_password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-200">New Password</label>
                                <input type="password" id="password" name="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            </div>
                            <div class="mb-4">
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Confirm New Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            </div>
                            <div class="mb-4">
                                <button type="submit" class="button bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">Change Password</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
