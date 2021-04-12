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

class UniversityCacheUpdated implements ShouldBroadcast
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

    public function broadcastAs()
    {
        return 'UniversityCacheUpdated';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new channel('university.domains.' . $this->university->id);
    }
}
