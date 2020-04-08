<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\FirebasePush;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotificationSubscriber
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

    public function onUserRegistered($event) {
        /*$notification = new Notification();
        $notification->user_id = $event->user->id;
        $notification->notificatable_type = 'discount';
        $notification->notificatable_id = $event->user->id;
        $notification->title = 'Вы получили промокод '.$event->user->promocode;
        $notification->body = 'Используйте промокод для получения скидки на все товары Santo';
        $notification->thumb = $event->user->thumb;
        $notification->save();
        FirebasePush::sendMessage($notification['title'], $notification['body'], $event->user);*/
    }

    public function subscribe($events) {
        $events->listen(
            'App\Events\UserRegistered',
            'App\Listeners\NotificationSubscriber@onUserRegistered'
        );
    }

    /**
     * Handle the event.
     *
     * @param  UserRegistered  $event
     * @return void
     */
    public function handle(UserRegistered $event)
    {
        //
    }
}
