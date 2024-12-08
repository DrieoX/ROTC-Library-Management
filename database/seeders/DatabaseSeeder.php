<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run the default user factory if needed
        // Uncomment the line below if you still need test users
        // \App\Models\User::factory(10)->create();

        // Optionally create a default user (optional, not related to achievements)

        // Call additional seeders
        $this->call([
            AchievementSeeder::class, // Seeds the Achievements table
        ]);

        // Add other seeders here as needed
        // Example: $this->call(StudentSeeder::class);
    }
}
