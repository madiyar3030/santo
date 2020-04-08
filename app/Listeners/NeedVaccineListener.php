<?php

namespace App\Listeners;

use App\Events\NeedVaccine;
use App\FirebasePush;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NeedVaccineListener
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
     * @param  NeedVaccine  $event
     * @return void
     */
    public function handle(NeedVaccine $event)
    {
        $notification = Notification::where('user_id', $event->user->id)
            ->where('notificatable_type', 'vaccine')
            ->where('notificatable_id', $event->vaccine->id)
            ->where('unique_id', $event->child->id)
            ->first();
        if (!$notification) {
            $notification = new Notification();
            $notification->user_id = $event->user->id;
            $notification->notificatable_type = 'vaccine';
            $notification->notificatable_id = $event->vaccine->id;
            $notification->unique_id = $event->child->id;
            $notification->title = $event->child->name.' нужно сделать прививку \''.$event->vaccine->title.'\'';
            $notification->body = $event->child->name.' нужно сделать прививку \''.$event->vaccine->title.'\'';
            $notification->save();
            FirebasePush::sendMessage($notification['title'], $notification['body'], $event->user);
        }
        /*$notification = Notification::firstOrNew([
            'user_id' => $event->user->id,
            'notificatable_type' => 'vaccine',
            'notificatable_id' => $event->vaccine->id,
            'unique_id' => $event->child->id,
        ]);*/
    }
}
