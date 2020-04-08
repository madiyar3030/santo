<?php

namespace App\Listeners;

use App\Events\NeedDevelopment;
use App\FirebasePush;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NeedDevelopmentListener
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
     * @param  NeedDevelopment  $event
     * @return void
     */
    public function handle(NeedDevelopment $event)
    {
        $notification = Notification::firstOrNew([
            'user_id' => $event->user->id,
            'notificatable_type' => 'development',
            'notificatable_id' => $event->development->id,
            'unique_id' => $event->child->id,
        ]);
        $notification->title = $event->child->name.' нужно пройти осмотр \''.$event->development->title.'\'';
        $notification->body = $event->child->name.' нужно пройти осмотр \''.$event->development->title.'\'';
        $notification->save();
        FirebasePush::sendMessage($notification['title'], $notification['body'], $event->user);
    }
}
