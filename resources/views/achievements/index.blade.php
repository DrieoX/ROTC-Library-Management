<div>
    <h3>Your Achievements</h3>
    <p>View your achievements and accomplishments.</p>

    @if($achievements->isEmpty())
        <p>No achievements yet!</p>
    @else
        @foreach($achievements as $achievement)
            <div class="achievement-item">
                <h4>{{ $achievement->title }}</h4>
                <p>{{ $achievement->description }}</p>
                <small>Achieved on: {{ $achievement->pivot->created_at->format('F d, Y') }}</small>
            </div>
        @endforeach
    @endif
</div>

<!-- Modal -->
<div id="achievementsModal" class="custom-modal">
    <div class="custom-modal-header">
        <h5>Achievements</h5>
        <button class="close-btn" onclick="toggleModal('achievementsModal')">&times;</button>
    </div>
    <div class="modal-body">
        @include('modals.achievements') <!-- Including achievements modal content here -->
    </div>
</div>
