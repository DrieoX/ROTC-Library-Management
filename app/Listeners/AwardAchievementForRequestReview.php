<?php

namespace App\Listeners;

use App\Events\RequestReviewed;
use App\Models\Achievement;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AwardAchievementForRequestReview
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\RequestReviewed  $event
     * @return void
     */
    public function handle(RequestReviewed $event)
    {
        $request = $event->request;
        $student = $request->student;

        // Check if the student already has the "Book Request Reviewed" achievement
        $reviewedAchievement = Achievement::where('title', 'Book Request Reviewed')->first();

        if ($reviewedAchievement && !$student->achievements->contains($reviewedAchievement->id)) {
            $student->achievements()->attach($reviewedAchievement->id, ['notified' => false]);
        }
    }
}
