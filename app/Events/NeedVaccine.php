<?php

namespace App\Events;

use App\Models\Children;
use App\Models\User;
use App\Models\Vaccine;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NeedVaccine
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $child;
    public $vaccine;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Vaccine $vaccine, User $user, Children $child)
    {
        $this->vaccine = $vaccine;
        $this->child = $child;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
