<?php

namespace App\Listeners;

use App\Events\BookRequested;
use App\Models\Achievement;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AwardAchievementForBookRequest
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\BookRequested  $event
     * @return void
     */
    public function handle(BookRequested $event)
    {
        $request = $event->request;
        $student = $request->student;

        // Check if the student already has the "Request a Book" achievement
        $requestAchievement = Achievement::where('title', 'Request a Book')->first();

        if ($requestAchievement && !$student->achievements->contains($requestAchievement->id)) {
            $student->achievements()->attach($requestAchievement->id, ['notified' => false]);
        }
    }
}
