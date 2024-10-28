@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Pending Book Requests</h1>

        <ul>
            @foreach($requests as $request)
                <li>
                    <strong>{{ $request->book->title }}</strong> requested by {{ $request->student->name }}

                    <form action="{{ route('requests.approve', $request->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-success">Approve</button>
                    </form>

                    <form action="{{ route('requests.deny', $request->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger">Deny</button>
                    </form>
                </li>
            @endforeach
        </ul>
    </div>
@endsection
