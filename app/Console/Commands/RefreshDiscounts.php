<?php

namespace App\Console\Commands;

use App\Models\SchedulerLog;
use App\Models\User;
use App\Models\Webview;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RefreshDiscounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'discount:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command resets to zero count of discount promocodes used in month';

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
        $log->tag = 'Started to refresh discounts';
        $log->text = 'notifications';
        $log->save();

        User::query()->update(['promocode_used_count' => 0]);

        $log = new SchedulerLog();
        $log->tag = 'Refresh discounts successfully done';
        $log->text = 'notifications';
        $log->save();
    }
}
