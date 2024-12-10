@extends('layouts.librarian')

@section('content')
<div class="dashboard-container mx-auto px-4 py-6">

    <!-- Welcome Section -->
    <div class="text-center bg-red-600 text-white p-6 rounded-lg mb-6">
        <h1>Welcome, Librarian</h1>
    </div>

    <!-- Live Stats Section -->
    <div class="form-section">
        <h4>Live Stats</h4>
        <p>Ongoing Borrowed Books: <span id="borrowed-count" class="font-bold">{{ $ongoingBorrowedCount }}</span></p>
        <p>Requested Books: <span id="requested-count" class="font-bold">{{ $requestedBooksCount }}</span></p>
    </div>

    <!-- Book Management Section -->
    <div class="form-section">
        <h4>Manage Books</h4>
        <a href="{{ route('books.create') }}" class="btn btn-primary mb-3 inline-block">Add New Book</a>

        <table class="table-auto w-full border-collapse mb-4">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">Title</th>
                    <th class="px-4 py-2 border">Author</th>
                    <th class="px-4 py-2 border">Copies</th>
                    <th class="px-4 py-2 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($books as $book)
                    <tr>
                        <td class="px-4 py-2 border">{{ $book->title }}</td>
                        <td class="px-4 py-2 border">{{ $book->author }}</td>
                        <td class="px-4 py-2 border">{{ $book->copies->count() }}</td>
                        <td class="px-4 py-2 border text-center">
                            <a href="{{ route('books.edit', $book->id) }}" class="btn btn-warning mb-2 inline-block">Edit</a>
                            <a href="{{ route('books.confirmDelete', $book->id) }}" class="btn btn-danger inline-block">Delete</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        {{ $books->links() }}
    </div>

    <!-- Requested Books Section -->
    <div class="form-section">
        <h4>Requested Books</h4>
        
        @foreach ($requests->groupBy('bookCopy.book_id')->sortBy(function($group) {
            return $group->first()->bookCopy->book->title; // Sort by book title
        }) as $bookId => $bookRequests)
            <h5 class="font-semibold mb-2">{{ $bookRequests->first()->bookCopy->book->title }}</h5> <!-- Group by book -->

            <table class="table-auto w-full border-collapse mb-4">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 border">ISBN</th>
                        <th class="px-4 py-2 border">Requested By</th>
                        <th class="px-4 py-2 border">Status</th>
                        <th class="px-4 py-2 border">Request Date</th>
                        <th class="px-4 py-2 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bookRequests as $request)
                        <tr>
                            <td class="px-4 py-2 border">{{ $request->bookCopy->isbn }}</td>
                            <td class="px-4 py-2 border">{{ $request->student->name }}</td>
                            <td class="px-4 py-2 border">{{ ucfirst($request->status) }}</td>
                            <td class="px-4 py-2 border">{{ $request->created_at->format('Y-m-d H:i:s') }}</td>
                            <td class="px-4 py-2 border text-center">
                                @if ($request->status == 'pending')
                                    <a href="{{ route('requests.approve', $request->id) }}" class="btn btn-success mb-2 inline-block">Approve</a>
                                    <a href="{{ route('requests.deny', $request->id) }}" class="btn btn-danger inline-block">Deny</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    </div>

    <!-- Borrowing Transactions Section -->
    <div class="form-section">
        <h4>Borrowed Books</h4>

        <table class="table-auto w-full border-collapse mb-4">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">Book Title</th>
                    <th class="px-4 py-2 border">Student</th>
                    <th class="px-4 py-2 border">Borrow Date</th>
                    <th class="px-4 py-2 border">Due Date</th>
                    <th class="px-4 py-2 border">Overdue</th>
                    <th class="px-4 py-2 border">Fine Amount</th>
                    <th class="px-4 py-2 border">Status</th>
                    <th class="px-4 py-2 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                    @php
                        $currentDate = now();
                        $isOverdue = $transaction->due_date < $currentDate && $transaction->status == 'active';
                        $finePerDay = 10; // Define the fine amount per day
                        $daysOverdue = $isOverdue ? floor($currentDate->diffInDays($transaction->due_date)) : 0;
                        $fineAmount = $isOverdue ? $daysOverdue * $finePerDay : 0;
                    @endphp
                    <tr>
                        <td class="px-4 py-2 border">{{ $transaction->book->title }}</td>
                        <td class="px-4 py-2 border">{{ $transaction->student->name }}</td>
                        <td class="px-4 py-2 border">{{ $transaction->borrow_date->format('Y-m-d') }}</td>
                        <td class="px-4 py-2 border">{{ $transaction->due_date->format('Y-m-d') }}</td>
                        <td class="px-4 py-2 border">
                            @if ($isOverdue)
                                <span class="text-red-500">Yes</span>
                            @else
                                <span class="text-green-500">No</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 border">
                            @if ($isOverdue)
                                ₱{{ number_format($fineAmount, 2) }}
                            @else
                                ₱0.00
                            @endif
                        </td>
                        <td class="px-4 py-2 border">{{ ucfirst($transaction->status) }}</td>
                        <td class="px-4 py-2 border text-center">
                            @if ($transaction->status == 'active')
                                @if ($isOverdue)
                                    <form action="{{ route('transactions.confirm', $transaction->id) }}" method="GET" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-warning">Confirm Payment</button>
                                    </form>
                                @else
                                    <form action="{{ route('requests.return', $transaction->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">Return Book</button>
                                    </form>
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection

<style>
    .dashboard-container {
        width: 100%;
        max-width: 1000px;
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

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 10px;
        border: 1px solid #ccc;
        text-align: left;
    }

    th {
        background-color: #f3f3f3;
    }

    /* Ensure all buttons are the same size */
.btn {
    padding: 10px 20px;
    font-size: 14px;
    font-weight: bold;
    border-radius: 4px;
    text-decoration: none;
    display: inline-block;
    text-align: center;
    width: auto; /* Ensures buttons size according to the content */
}

/* Ensure buttons in the table align properly */
table .btn {
    display: inline-block;
    margin: 5px;
    width: 100%; /* Makes buttons expand within their table cell */
}

/* Update button colors */
.btn-primary {
    background-color: #4CAF50;
    color: white;
}

.btn-primary:hover {
    background-color: #45a049;
}

.btn-warning {
    background-color: #ff9800;
    color: white;
}

.btn-warning:hover {
    background-color: #e68900;
}

.btn-danger {
    background-color: #f44336;
    color: white;
}

.btn-danger:hover {
    background-color: #e53935;
}

.btn-success {
    background-color: #4CAF50;
    color: white;
}

.btn-success:hover {
    background-color: #45a049;
}

/* Ensures button height and alignment are consistent */
.table td, .table th {
    text-align: center;
}

.table td .btn {
    width: auto; /* Make sure buttons have consistent width */
    display: inline-block; /* Align buttons next to each other */
}
</style>
