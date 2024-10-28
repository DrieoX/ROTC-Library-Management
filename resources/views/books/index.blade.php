@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Available Books</h1>

        <ul>
            @foreach($books as $book)
                <li>
                    <strong>{{ $book->title }}</strong> by {{ $book->author }} 
                    - {{ $book->available ? 'Available' : 'Unavailable' }}
                    
                    @if($book->available)
                        <form action="{{ route('requests.store', $book->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary">Request to Borrow</button>
                        </form>
                    @else
                        <span>Currently borrowed</span>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
@endsection
