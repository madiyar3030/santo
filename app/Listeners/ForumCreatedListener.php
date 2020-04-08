<?php

namespace App\Listeners;

use App\Events\ForumCreated;
use App\FirebasePush;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ForumCreatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ForumCreated  $event
     * @return void
     */
    public function handle(ForumCreated $event)
    {
        $notification = new Notification();
        $notification->user_id = $event->user->id;
        $notification->notificatable_type = 'forum';
        $notification->notificatable_id = $event->forum->id;
        $notification->title = 'Ваша тема одобрена и добавлена';
        $notification->body = $event->forum->title;
        $notification->save();
        FirebasePush::sendMessage($notification['title'], $notification['body'], $event->user);
    }
}
