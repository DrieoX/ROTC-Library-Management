<!-- resources/views/books/destroy.blade.php -->
@extends('layouts.librarian')

@section('content')
<div class="container">
    <h1>Confirm Deletion</h1>

    <div class="alert alert-warning">
        Are you sure you want to delete the book "{{ $book->title }}" and its related data? This action cannot be undone.
    </div>

    <form action="{{ route('books.destroy', $book->id) }}" method="POST">
        @csrf
        @method('DELETE')

        <button type="submit" class="btn btn-danger">Delete</button>
        <a href="{{ route('librarian.welcome') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
