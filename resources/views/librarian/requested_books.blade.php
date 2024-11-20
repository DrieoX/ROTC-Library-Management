@extends('layouts.librarian')

@section('content')
<div class="container">
    <h1>Requested Books</h1>

    @foreach ($requests->groupBy('bookCopy.book_id') as $bookId => $bookRequests)
        <h2>{{ $bookRequests->first()->bookCopy->book->title }}</h2>

        <table class="table">
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
@endsection
