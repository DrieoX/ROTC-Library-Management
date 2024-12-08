<?php

namespace App\Listeners;

use App\Models\Achievement;
use App\Models\AchievementStudent;
use App\Events\BookBorrowed; // This event should be triggered when a book is borrowed.

class FirstBorrowAchievementListener
{
    public function handle($event)
    {
        $student = $event->student;

        // Check if the student has already borrowed a book
        $hasBorrowedBefore = $student->borrowingTransactions()->exists();

        if (!$hasBorrowedBefore) {
            // Assign the "First Borrow" achievement
            $achievement = Achievement::where('title', 'First Borrow')->first();

            if ($achievement && !$student->achievements->contains($achievement->id)) {
                $student->achievements()->attach($achievement->id, ['notified' => false]);
            }
        }
    }
}
