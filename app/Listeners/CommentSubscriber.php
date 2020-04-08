<?php


namespace App\Listeners;


use App\FirebasePush;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Webview;
use Illuminate\Database\Eloquent\Relations\Relation;

class CommentSubscriber
{

    public function onReplied($event) {
        $notification = new Notification();
        $notification->title = $event->from_user->name.' ответил на ваш комментарий';
        $notification->notificatable_type = $event->comment->commentable_type;
        $notification->notificatable_id = $event->comment->commentable_id;
        $notification->body = $event->comment->comment;
        $notification->user_id = $event->user->id;
        $notification->thumb = $event->from_user->thumb;
        $notification->unique_id = $event->from_user->id;
        $notification->save();
        FirebasePush::sendMessage($notification['title'], $notification['body'], $event->user);
    }

    public function onLiked($event) {
        //if ($event->user->id != $event->from_user->id) {
            $notification = new Notification();
            $notification->title = $event->from_user->name . ' понравился ваш комментарий';
            $notification->body = $event->comment->comment;
            $notification->notificatable_type = $event->comment->commentable_type;
            $notification->notificatable_id = $event->comment->commentable_id;
            $notification->user_id = $event->user->id;
            $notification->thumb = $event->from_user->thumb;
            $notification->unique_id = $event->from_user->id;
            $notification->save();
            FirebasePush::sendMessage($notification['title'], $notification['body'], $event->user);
        //}
    }

    public function subscribe($events) {
        $events->listen(
            'App\Events\CommentReplied',
            'App\Listeners\CommentSubscriber@onReplied'
        );
        $events->listen(
            'App\Events\CommentLiked',
            'App\Listeners\CommentSubscriber@onLiked'
        );
    }
}