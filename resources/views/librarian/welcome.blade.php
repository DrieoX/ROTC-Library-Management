@extends('layouts.librarian')

@section('content')
<div class="container">
    <h1>Welcome, Librarian</h1>

    <!-- Live Stats Section -->
    <div class="stats">
        <h3>Live Stats</h3>
        <p>Ongoing Borrowed Books: <span id="borrowed-count">{{ $ongoingBorrowedCount }}</span></p>
        <p>Requested Books: <span id="requested-count">{{ $requestedBooksCount }}</span></p>
    </div>

    <!-- Book Management Section -->
    <div class="book-management">
        <h3>Manage Books</h3>
        <a href="{{ route('books.create') }}" class="btn btn-primary">Add New Book</a>

        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Copies</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($books as $book)
                    <tr>
                        <td>{{ $book->title }}</td>
                        <td>{{ $book->author }}</td>
                        <td>{{ $book->copies->count() }}</td>
                        <td>
                            <a href="{{ route('books.edit', $book->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('books.destroy', $book->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        {{ $books->links() }}
    </div>

    <!-- Requested Books Section -->
    <div class="requested-books mt-5">
        <h3>Requested Books</h3>
        
        @foreach ($requests->groupBy('bookCopy.book_id')->sortBy(function($group) {
            return $group->first()->bookCopy->book->title; // Sort by book title
        }) as $bookId => $bookRequests)
            <h4>{{ $bookRequests->first()->bookCopy->book->title }}</h4> <!-- Group by book -->

            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>ISBN</th>
                        <th>Requested By</th>
                        <th>Status</th>
                        <th>Request Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bookRequests as $request)
                        <tr>
                            <td>{{ $request->bookCopy->isbn }}</td>
                            <td>{{ $request->student->name }}</td>
                            <td>{{ ucfirst($request->status) }}</td>
                            <td>{{ $request->created_at->format('Y-m-d H:i:s') }}</td>
                            <td>
                                @if ($request->status == 'pending')
                                    <a href="{{ route('requests.approve', $request->id) }}" class="btn btn-success">Approve</a>
                                    <a href="{{ route('requests.deny', $request->id) }}" class="btn btn-danger">Deny</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    </div>

    <!-- Borrowing Transactions Section -->
    <div class="borrowed-books mt-5">
        <h3>Borrowed Books</h3>

        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Book Title</th>
                    <th>Student</th>
                    <th>Borrow Date</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->book->title }}</td>
                        <td>{{ $transaction->student->name }}</td>
                        <td>{{ $transaction->borrow_date->format('Y-m-d') }}</td>
                        <td>{{ $transaction->due_date->format('Y-m-d') }}</td>
                        <td>{{ ucfirst($transaction->status) }}</td>
                        <td>
                            @if ($transaction->status == 'active')
                                <form action="{{ route('transactions.return', $transaction->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Return Book</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection
