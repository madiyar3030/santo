<?php

namespace App\Console\Commands;

use App\Events\NeedDevelopment;
use App\Events\NeedVaccine;
use App\Models\Development;
use App\Models\Notification;
use App\Models\SchedulerLog;
use App\Models\User;
use App\Models\Vaccine;
use App\Models\Webview;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonInterval;
use Illuminate\Console\Command;

class NotifyChildrenVaccine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:vaccine';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification for all users whose children due to any vaccine';

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
        $log->tag = 'Started to check notifications';
        $log->text = 'notifications';
        $log->save();
        $users = User::where('blocked', 0)->with('children')->get();
        $vaccines = Vaccine::all();
        foreach ($users as $user) {
            foreach ($user->children as $child) {
                $birthDate = Carbon::make($child->birth_date);
                foreach ($vaccines as $vaccine) {
                    if ($vaccine->isAppliedTo($birthDate)) {
                        event(new NeedVaccine($vaccine, $user, $child));
                    }
                }
            }
        }
        $log = new SchedulerLog();
        $log->tag = 'Notifications checked successfully';
        $log->text = 'notifications';
        $log->save();
    }
}
