@extends('layouts.librarian')

@section('title', 'Edit Book')

@section('content')
<div class="container">
    <h1>Edit Book</h1>

    <form action="{{ route('books.update', $book->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $book->title) }}" required>
            @error('title')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="cover_image">Cover Image</label>
            <input type="file" name="cover_image" id="cover_image" class="form-control" accept="image/*">
            @if ($book->cover_image)
                <div class="mt-2">
                    <img src="{{ asset('storage/covers/' . $book->cover_image) }}" alt="Current Cover Image" style="width: 100px; height: auto;">
                </div>
            @endif
            @error('cover_image')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="author">Author</label>
            <input type="text" name="author" id="author" class="form-control" value="{{ old('author', $book->author) }}" required>
            @error('author')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control" required>{{ old('description', $book->description) }}</textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="isbn">ISBN</label>
            <div id="isbn-container">
                @foreach ($book->copies as $copy)
                    <div class="isbn-entry mb-2">
                        <input type="text" name="isbn[]" class="form-control" value="{{ old('isbn', $copy->isbn) }}" required>
                        <button type="button" class="btn btn-danger remove-isbn" style="margin-top: 5px;">Remove</button>
                    </div>
                @endforeach
                <button type="button" class="btn btn-primary" id="add-isbn">Add ISBN</button>
            </div>
            @error('isbn')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" name="quantity" id="quantity" class="form-control" value="{{ old('quantity', $book->copies->count()) }}" required min="1" readonly>
            @error('quantity')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Update Book</button>
        <a href="{{ route('librarian.welcome') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isbnContainer = document.getElementById('isbn-container');
        const addIsbnButton = document.getElementById('add-isbn');

        addIsbnButton.addEventListener('click', function() {
            const newIsbnEntry = document.createElement('div');
            newIsbnEntry.classList.add('isbn-entry', 'mb-2');
            newIsbnEntry.innerHTML = `
                <input type="text" name="isbn[]" class="form-control" required>
                <button type="button" class="btn btn-danger remove-isbn" style="margin-top: 5px;">Remove</button>
            `;
            isbnContainer.appendChild(newIsbnEntry);
        });

        isbnContainer.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-isbn')) {
                e.target.closest('.isbn-entry').remove();
            }
        });
    });
</script>
@endsection

@endsection
