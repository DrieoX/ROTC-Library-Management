@extends('layouts.librarian')

@section('title', 'Add New Book')

@section('content')
    <h2>Add New Book</h2>

    <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div>
            <label for="title">Title</label>
            <input type="text" id="title" name="title" required>
        </div>

        <div>
            <label for="cover_image">Cover Image (Optional)</label>
            <input type="file" id="cover_image" name="cover_image" accept="image/*">
        </div>

        <div>
            <label for="author">Author</label>
            <input type="text" id="author" name="author" required>
        </div>

        <div>
            <label for="isbn">ISBN</label>
            <input type="text" id="isbn" name="isbn" required>
        </div>

        <div>
            <label for="quantity">Quantity</label>
            <input type="number" id="quantity" name="quantity" value="1" min="1" required>
        </div>

        <div>
            <button type="submit">Add Book</button>
        </div>
    </form>
@endsection
