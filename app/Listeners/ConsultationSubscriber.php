<?php


namespace App\Listeners;


use App\Events\ConsultationAnswered;
use App\Events\ConsultationCreated;
use App\FirebasePush;
use App\Models\Notification;

class ConsultationSubscriber
{

    public function onCreated(ConsultationCreated $event) {
        $notification = new Notification();
        $notification->user_id = $event->user->id;
        $notification->notificatable_type = 'consultation';
        $notification->notificatable_id = $event->consultation->id;
        $notification->title = 'Ваш вопрос одобрен и добавлен';
        $notification->body = $event->consultation->title;
        $notification->save();
        FirebasePush::sendMessage($notification['title'], $notification['body'], $event->user);
    }

    public function onAnswered(ConsultationAnswered $event) {
        $notification = new Notification();
        $notification->user_id = $event->user->id;
        $notification->notificatable_type = 'consultation';
        $notification->notificatable_id = $event->consultation->id;
        $notification->title = 'Новый ответ на вопрос: '.$event->consultation->title;
        $notification->body = $event->consultation->description;
        $notification->save();
        FirebasePush::sendMessage($notification['title'], $notification['body'], $event->user);
    }

    public function subscribe($events) {
        $events->listen(
            'App\Events\ConsultationCreated',
            'App\Listeners\ConsultationSubscriber@onCreated'
        );
        $events->listen(
            'App\Events\ConsultationAnswered',
            'App\Listeners\ConsultationSubscriber@onAnswered'
        );
    }
}