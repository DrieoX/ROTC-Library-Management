@extends('layouts.app')

@section('title', 'Login')

@section('content')
<style>
    .form-container {
        width: 100%;
        max-width: 400px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin: 50px auto;
    }
    .form-container h2 {
        font-size: 24px;
        font-weight: bold;
        text-align: center;
        color: #333;
        margin-bottom: 20px;
    }
    .form-group {
        margin-bottom: 15px;
    }
    label {
        font-size: 14px;
        font-weight: 500;
        color: #555;
        margin-bottom: 5px;
        display: block;
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
        border-color: #4f46e5;
        box-shadow: 0 0 3px #4f46e5;
    }
    button {
        width: 100%;
        padding: 10px;
        font-size: 16px;
        font-weight: bold;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        background-color: #800000;
    }
    button:hover {
        background-color: green;
    }
</style>

<div class="form-container">
    <h2>Login</h2>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required autofocus>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Login</button>
    </form>
</div>
@endsection
