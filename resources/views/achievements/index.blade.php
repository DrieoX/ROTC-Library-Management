@extends('layouts.app')

@section('title', 'Achievements')

@section('content')
<div class="container">
    <h3>Your Achievements</h3>
    <p>View your achievements and accomplishments below.</p>

    <div class="row">
        @foreach($allAchievements as $achievement)
            <div class="col-md-4">
                <div class="achievement-item card 
                    {{ $achievements->contains('id', $achievement->id) ? 'border-success achieved' : 'border-secondary not-achieved' }}">

                    <div class="card-body">
                        <h5 class="card-title">{{ $achievement->title }}</h5>
                        <p class="card-text">{{ $achievement->description }}</p>

                        @if($achievements->contains('id', $achievement->id))
                            <small class="text-success">Achieved</small>
                        @else
                            <small class="text-danger">Not yet achieved</small>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

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
