<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Achievement;

class AchievementSeeder extends Seeder
{
    public function run()
    {
        $achievements = [
            [
                'title' => 'First Borrow',
                'description' => 'Borrow your first book.',
                'type' => 'first',
            ],
            [
                'title' => 'Bookworm',
                'description' => 'Borrow 10 books.',
                'type' => 'milestone',
            ],
            [
                'title' => 'Loyal Reader',
                'description' => 'Borrow books 3 months in a row.',
                'type' => 'special',
            ],
            // New Achievements for Book Request and Review Process
            [
                'title' => 'Request a Book',
                'description' => 'Earned by requesting a book.',
                'type' => 'special',
            ],
            [
                'title' => 'Book Request Reviewed',
                'description' => 'Earned when your book request is approved or denied.',
                'type' => 'special',
            ],
        ];

        foreach ($achievements as $achievement) {
            Achievement::create($achievement);
        }
    }
}
