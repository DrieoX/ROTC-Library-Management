@extends('layouts.librarian')

@section('title', 'Add New Book')

@section('content')
    <h2>Add New Book</h2>

    {{-- Display validation errors --}}
    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Flash message for errors or success --}}
    @if (session('error'))
        <div style="color: red;">{{ session('error') }}</div>
    @endif

    @if (session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div>
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" required>
        </div>

        <div>
            <label for="cover_image">Cover Image (Optional)</label>
            <input type="file" id="cover_image" name="cover_image" accept="image/*">
        </div>

        <div>
            <label for="author">Author</label>
            <input type="text" id="author" name="author" value="{{ old('author') }}" required>
        </div>

        <div>
            <label for="description">Description</label>
            <textarea id="description" name="description">{{ old('description') }}</textarea>
        </div>

        <div id="isbn-fields">
            <div>
                <label for="isbn_0">ISBN</label>
                <input type="text" id="isbn_0" name="isbn[]" value="{{ old('isbn.0') }}" required>
            </div>
        </div>

        <div>
            <button type="button" id="add-isbn">Add Another ISBN</button>
        </div>

        <div>
            <button type="submit">Add Book</button>
        </div>
    </form>

    <script>
        let counter = 1;
        document.getElementById('add-isbn').addEventListener('click', function () {
            const isbnFields = document.getElementById('isbn-fields');
            const uniqueId = `isbn_${counter}`;
            const newIsbnField = document.createElement('div');

            newIsbnField.innerHTML = `
                <label for="${uniqueId}">ISBN</label>
                <input type="text" id="${uniqueId}" name="isbn[]" required>
            `;

            isbnFields.appendChild(newIsbnField);
            counter++;
        });
    </script>
@endsection
