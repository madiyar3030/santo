<?php

namespace App\Console;

use App\Console\Commands\NotifyChildrenVaccine;
use App\Console\Commands\NotifyUserBlogs;
use App\Console\Commands\RefreshDiscounts;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        NotifyChildrenVaccine::class,
        RefreshDiscounts::class,
        NotifyUserBlogs::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->command('notify:vaccine')->dailyAt('13:00');
        $schedule->command('notify:blog')->dailyAt('19:00');
        $schedule->command('discount:refresh')->monthly();
        $schedule->command('db:backup')->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
