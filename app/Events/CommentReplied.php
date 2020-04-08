<?php

namespace App\Events;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentReplied
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;

    public $user;

    public $from_user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Comment $comment, $userId, $from_userId)
    {
        $this->comment = $comment;
        $this->user = User::find($userId);
        $this->from_user = User::find($from_userId);
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
