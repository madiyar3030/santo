<?php

namespace App\Console\Commands;

use App\FirebasePush;
use App\Models\Blog;
use App\Models\Notification;
use App\Models\SchedulerLog;
use App\Models\User;
use App\Models\Webview;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Relations\Relation;

class NotifyUserBlogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:blog';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends notification to users about blog notes online time';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $log = new SchedulerLog();
        $log->tag = 'Started to check blog notes';
        $log->text = 'notifications';
        $log->save();


        $now = Carbon::now()->format('Y-m-d');
        $blogs = Blog::where('online_until', '>=', $now)->get();
        $users = User::whereNotNull('email_verified_at')->whereType('user')->where('blocked', 0)->get();
        $blog_type = array_search(Blog::class, Relation::$morphMap);
        foreach ($blogs as $blog) {
            $title = trans('notifications.blog_online.title', ['title' => $blog->title, 'time' => Carbon::make($blog->online_from)->format('H:i')]);
            $body = trans('notifications.blog_online.body', ['title'=> $blog->title]);
            foreach ($users as $user) {
                Notification::create([
                    'notificatable_id' => $blog->id,
                    'notificatable_type' => $blog_type,
                    'title' =>  $title,
                    'body' => $body,
                    'user_id' => $user->id,
                ]);
            }
            $message = [
                'title' => $title,
                'body' => $body,
            ];
            FirebasePush::send('/topics/blogs', $message);
        }

        $log = new SchedulerLog();
        $log->tag = 'Finished to check blog notes';
        $log->text = 'notifications';
        $log->save();
    }
}
