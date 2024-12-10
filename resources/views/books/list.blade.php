@extends('layouts.app')

@section('content')
<div class="container">
    <h1 style="background-color: rgba(255, 255, 255, 0.8);">Available Books</h1>

    {{-- Search form --}}
    <form method="GET" action="{{ route('books.list') }}">
        <input type="text" name="search" placeholder="Search books..." value="{{ request()->input('search') }}">
        <button type="submit">Search</button>
    </form>

    {{-- Books listing --}}
    <div class="book-list">
        @foreach ($books as $book)
            <div class="book-item">
                {{-- Display Book Image --}}
                <div class="book-image">
                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" style="width: 150px; height: auto; border-radius: 8px;">
                </div>

                <h3>{{ $book->title }}</h3>
                <p>Author: {{ $book->author }}</p>
                <p>Description: {{ $book->description ?? 'No description available.' }}</p>

                @php
                    $availableCopiesCount = $book->copies->where('available', true)->count();
                    $borrowedCopiesCount = $book->copies->where('available', false)->count();
                    $userRequested = $book->copies->contains(function ($copy) {
                        return $copy->requests()->where('student_id', auth()->id())->exists();
                    });
                    $requestReturned = $book->copies->contains(function ($copy) {
                        return $copy->requests()->where('student_id', auth()->id())->where('status', 'returned')->exists();
                    });

                    // Get the most recent request based on timestamp
                    $recentRequest = $book->copies->flatMap(function ($copy) {
                        return $copy->requests;
                    })->where('student_id', auth()->id())
                    ->sortByDesc('created_at')->first();
                @endphp

                {{-- Available and Borrowed Copies --}}
                <p>Available Copies: {{ $availableCopiesCount }} </p>
                @if ($borrowedCopiesCount > 0)
                    <p>Borrowed Copies: {{ $borrowedCopiesCount }}</p>
                @endif

                {{-- Check if the user has already requested the book --}}
                @if (auth()->check())
                    @if ($recentRequest)
                        {{-- Show messages based on request status --}}
                        @if ($recentRequest->status === 'pending')
                            <p>You have already requested this book and the request is still pending.</p>
                        @elseif ($recentRequest->status === 'returned')
                            <p>Your previous request was returned. You can request the book again.</p>
                            {{-- Show button to request again --}}
                            @if ($availableCopiesCount > 0)
                                <form action="{{ route('requests.store', $book->id) }}" method="POST">
                                    @csrf
                                    <button type="submit">Request to Borrow</button>
                                </form>
                            @endif
                        @else
                            {{-- If the request status is neither pending nor returned --}}
                            @if ($availableCopiesCount > 0)
                                <form action="{{ route('requests.store', $book->id) }}" method="POST">
                                    @csrf
                                    <button type="submit">Request to Borrow</button>
                                </form>
                            @else
                                <p class="text-danger">No copies available for borrowing.</p>
                            @endif
                        @endif
                    @else
                        {{-- If no previous request exists --}}
                        @if ($availableCopiesCount > 0)
                            <form action="{{ route('requests.store', $book->id) }}" method="POST">
                                @csrf
                                <button type="submit">Request to Borrow</button>
                            </form>
                        @else
                            <p class="text-danger">No copies available for borrowing.</p>
                        @endif
                    @endif
                @else
                    {{-- Prompt guest to log in --}}
                    <p class="text-warning">Please <a href="{{ route('login') }}">log in</a> to request this book.</p>
                @endif

                {{-- If no copies are available, show that it can't be borrowed --}}
                @if ($availableCopiesCount == 0 && $borrowedCopiesCount == 0)
                    <p class="text-danger">This book cannot be borrowed as there are no available copies.</p>
                @endif
            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="pagination">
        {{ $books->links() }}
    </div>
</div>
@endsection
