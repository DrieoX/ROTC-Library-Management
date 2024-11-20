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
        
        @foreach ($requests->groupBy('bookCopy.book_id') as $bookId => $bookRequests)
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
                                @if ($request->status === 'pending')
                                    <form action="{{ route('requests.approve', $request->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button class="btn btn-success" type="submit">Approve</button>
                                    </form>
                                    <form action="{{ route('requests.deny', $request->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger" type="submit">Deny</button>
                                    </form>
                                @else
                                    <span class="text-muted">No actions available</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    </div>
</div>

<script>
    // Optionally add dynamic refresh functionality using AJAX
    setInterval(() => {
        fetch('{{ route("requests.stats") }}')
            .then(response => response.json())
            .then(data => {
                document.getElementById('borrowed-count').textContent = data.borrowed;
                document.getElementById('requested-count').textContent = data.requested;
            })
            .catch(error => console.error('Error fetching stats:', error));
    }, 5000); // Refresh every 5 seconds
</script>
@endsection
