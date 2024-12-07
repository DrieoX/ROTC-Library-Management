@foreach ($fines as $fine)
    <div>
        <p>Fine Amount: ${{ $fine->fine_amount }}</p>
        <form action="{{ route('fines.pay', $fine->id) }}" method="POST">
            @csrf
            <button type="submit">Pay Fine</button>
        </form>
    </div>
@endforeach
