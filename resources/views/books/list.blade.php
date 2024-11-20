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
                <p>ISBN: {{ $book->copies->first()->isbn ?? 'N/A' }}</p> {{-- Display first copy's ISBN or 'N/A' if no copies available --}}
                <p>Available Copies: {{ $book->copies->where('available', true)->count() }}</p>

                {{-- Check if the user has already requested the book --}}
                @php
                    $userRequested = $book->copies->contains(function ($copy) {
                        return $copy->requests()->where('student_id', auth()->id())->exists();
                    });
                @endphp

                {{-- Show the request button only if the user hasn't already requested the book --}}
                @if ($userRequested)
                    <p>You have already requested this book.</p>
                @else
                    {{-- Button to request book --}}
                    <form action="{{ route('requests.store', $book->id) }}" method="POST">
                        @csrf
                        <button type="submit">Request to Borrow</button>
                    </form>
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
