<div>
    <h3>Your Achievements</h3>
    <p>View your achievements and accomplishments.</p>

    @if(isset($achievements) && $achievements->isEmpty())
        <p>No achievements yet!</p>
    @elseif(isset($achievements))
        @foreach($achievements as $achievement)
            <div class="achievement-item {{ in_array($achievement->id, $earnedAchievements) ? 'achieved' : 'not-achieved' }}">
                <h4>{{ $achievement->title }}</h4>
                <p>{{ $achievement->description }}</p>
                <small>Available since: {{ $achievement->created_at->format('F d, Y') }}</small>
            </div>
        @endforeach
    @else
        <p>Achievements data is not available.</p>
    @endif
</div>

<style>
    /* Add these styles in your main CSS file or inside a <style> block in the app.blade.php file */

.achievement-item {
    padding: 10px;
    margin: 10px 0;
    border: 2px solid #ddd; /* Default border */
    border-radius: 5px;
    transition: border-color 0.3s ease;
}

.achievement-item.achieved {
    border-color: #4CAF50; /* Green border for achieved */
    background-color: #e8f5e9; /* Light green background */
}

.achievement-item.not-achieved {
    border-color: #9e9e9e; /* Grey border for not achieved */
    background-color: #f5f5f5; /* Light grey background */
}

.achievement-item h4 {
    font-size: 18px;
    font-weight: bold;
}

.achievement-item p {
    font-size: 14px;
}

.achievement-item small {
    font-size: 12px;
    color: #777;
}

</style>
