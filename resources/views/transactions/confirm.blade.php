@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <h1>Confirm Return and Fine Payment</h1>
    <p><strong>Book Title:</strong> {{ $transaction->book->title }}</p>
    <p><strong>Borrowed By:</strong> {{ $transaction->student->name }}</p>
    <p><strong>Due Date:</strong> {{ $transaction->due_date->format('Y-m-d') }}</p>
    <p><strong>Days Overdue:</strong> {{ $daysOverdue }} days</p>
    <p><strong>Fine Amount:</strong> ₱{{ number_format($fineAmount, 2) }}</p>

    <form action="{{ route('fines.pay', $transaction->id) }}" method="POST">
        @csrf
        <input type="hidden" name="fine_amount" value="{{ $fineAmount }}">
        <button type="submit" class="btn btn-success">Pay Fine and Return Book</button>
    </form>

    <a href="{{ route('librarian.welcome') }}" class="btn btn-secondary">Cancel</a>
</div>
@endsection

<style>
    .dashboard-container {
        width: 100%;
        max-width: 800px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin: 0 auto;
    }

    .dashboard-container h1 {
        font-size: 24px;
        font-weight: bold;
        text-align: center;
        color: #333;
    }

    .dashboard-container p {
        margin-top: 10px;
        text-align: left;
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

    .btn {
        padding: 10px 20px;
        border-radius: 4px;
        text-decoration: none;
        display: inline-block;
        width: 100%;
        text-align: center;
        font-weight: bold;
        cursor: pointer;
        margin: 10px 0;
        transition: background-color 0.3s;
    }

    .btn-success {
        background-color: #4CAF50;
        color: white;
    }

    .btn-success:hover {
        background-color: #45a049;
    }

    .btn-secondary {
        background-color: #800000;
        color: white;
    }

    .btn-secondary:hover {
        background-color: green;
    }

    .btn:hover {
        cursor: pointer;
    }
</style>
