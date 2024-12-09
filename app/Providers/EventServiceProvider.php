<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\BookBorrowed::class => [
            \App\Listeners\FirstBorrowAchievementListener::class,
        ],

        \App\Events\BookRequested::class => [
            \App\Listeners\AwardAchievementForBookRequest::class,
        ],
        
        \App\Events\RequestReviewed::class => [
            \App\Listeners\AwardAchievementForRequestReview::class,
        ],
        // Add more events and listeners here if needed
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();

        // Additional event binding logic can be placed here
    }
}
