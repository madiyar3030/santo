<?php

namespace App\Events;

use App\Models\Forum;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ForumCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $forum;
    public $user;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Forum $forum, User $user)
    {
        $this->forum = $forum;
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
