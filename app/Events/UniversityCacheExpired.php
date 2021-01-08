<?php

namespace App\Events;

use App\Models\University;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UniversityCacheExpired
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $university;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(University $university)
    {
        $this->university = $university;
    }
}
