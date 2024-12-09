<?php

namespace App\Events;

use App\Models\Requests;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RequestReviewed
{
    use Dispatchable, SerializesModels;

    public $request;
    public $status;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Requests  $request
     * @param  string  $status
     * @return void
     */
    public function __construct(Requests $request, $status)
    {
        $this->request = $request;
        $this->status = $status;
    }
}
