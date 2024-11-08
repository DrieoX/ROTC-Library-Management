@extends('layouts.librarian')

@section('title', 'Welcome')

@section('content')
    <div style="margin-bottom: 30px;">
        <h2>Available Books</h2>
        <table style="width: 100%; border-collapse: separate; border-spacing: 15px 20px;">
            <thead>
                <tr>
                    <th style="text-align: left;">Cover</th>
                    <th style="text-align: left;">Title</th>
                    <th style="text-align: left;">Author</th>
                    <th style="text-align: left;">ISBN</th>
                    <th style="text-align: left;">Quantity</th>
                    <th style="text-align: left;">Borrowed</th>
                    <th style="text-align: left;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($books as $book)
                    <tr>
                        <td>
                            <img src="{{ asset('storage/covers/' . $book->cover_image) }}" alt="Cover Image" style="width: 60px; height: 90px; object-fit: cover;">
                        </td>
                        <td>{{ $book->title }}</td>
                        <td>{{ $book->author }}</td>
                        <td>{{ $book->isbn }}</td>
                        <td>{{ $book->quantity }}</td>
                        <td>{{ $book->borrowed_count }}</td>
                        <td>
                            <a href="{{ route('books.edit', $book->id) }}" class="button">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="text-align: right; margin-top: 20px;">
        <a href="{{ route('books.create') }}" class="button" style="padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px;">Add New Book</a>
    </div>

    <div style="margin-top: 40px;">
        <h3>Ongoing Borrowed Books: {{ $ongoingBorrowedCount }}</h3>
        <h3>Requested Books: {{ $requestedBooksCount }}</h3>
    </div>
@endsection
