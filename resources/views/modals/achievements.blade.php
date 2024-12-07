<div>
    <h3>Your Achievements</h3>
    <p>View your achievements and accomplishments.</p>

    @if(isset($achievements) && $achievements->isEmpty())
        <p>No achievements yet!</p>
    @elseif(isset($achievements))
        @foreach($achievements as $achievement)
            <div class="achievement-item">
                <h4>{{ $achievement->title }}</h4>
                <p>{{ $achievement->description }}</p>
                <small>Achieved on: {{ $achievement->pivot->created_at->format('F d, Y') }}</small>
            </div>
        @endforeach
    @else
        <p>Achievements data is not available.</p>
    @endif
</div>
