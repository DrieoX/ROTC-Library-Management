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
                <h3>{{ $book->title }}</h3>
                <p>Author: {{ $book->author }}</p>
                <p>Description: {{ $book->description ?? 'No description available.' }}</p> {{-- Display the book's description or a fallback message --}}

                @php
                    $availableCopiesCount = $book->copies->where('available', true)->count();
                    $borrowedCopiesCount = $book->copies->where('available', false)->count();
                    $userRequested = $book->copies->contains(function ($copy) {
                        return $copy->requests()->where('student_id', auth()->id())->exists();
                    });
                @endphp

                {{-- Available and Borrowed Copies --}}
                <p>Available Copies: {{ $availableCopiesCount }} </p>
                @if ($borrowedCopiesCount > 0)
                    <p>Borrowed Copies: {{ $borrowedCopiesCount }}</p>
                @endif

                {{-- Check if the user has already requested the book --}}
                @if ($userRequested)
                    <p>You have already requested this book.</p>
                @else
                    {{-- Show message if no copies are available --}}
                    @if ($availableCopiesCount == 0)
                        <p class="text-danger">No copies available for borrowing.</p>
                    @else
                        {{-- Button to request book --}}
                        <form action="{{ route('requests.store', $book->id) }}" method="POST">
                            @csrf
                            <button type="submit">Request to Borrow</button>
                        </form>
                    @endif
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
