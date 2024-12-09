<?php

namespace App\Events;

use App\Models\Requests;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookRequested
{
    use Dispatchable, SerializesModels;

    public $request;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Requests $request
     * @return void
     */
    public function __construct(Requests $request)
    {
        $this->request = $request;
    }
}
