<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Console\Scheduling\ScheduleListCommand;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
         $schedule->command('dropbox-token:generate')->everyThreeHours()->withoutOverlapping()->timezone('Asia/Dhaka');
        $schedule->command('backup:run')->dailyAt('01:00')->withoutOverlapping()->timezone('Asia/Dhaka');
         $schedule->command('sitemap:generate')->everyFourHours()->withoutOverlapping()->timezone('Asia/Dhaka');
        $schedule->command('backup:clean')->days([Schedule::FRIDAY, Schedule::MONDAY])->withoutOverlapping()->timezone('Asia/Dhaka')->at('03:00');
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
