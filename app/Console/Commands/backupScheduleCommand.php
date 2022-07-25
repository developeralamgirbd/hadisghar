<?php

namespace App\Console\Commands;

use Cron\CronExpression;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Carbon;

class backupScheduleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List when scheduled commands are executed.';

    /**
     * @var Schedule
     */
    protected $schedule;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Schedule $schedule)
    {
        parent::__construct();
        $this->schedule = $schedule;
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $events = array_map(function ($event) {
            return [
                'cron'    => $event->expression,
                'command' => static::fixupCommand($event->command),
                'next' => $event->nextRunDate()->toDateTimeString(),
                'previous' => static::previousRunDate($event->expression)->toDateTimeString(),
            ];
        }, $this->schedule->events());

        $getBackupSchedule = array_filter($events, function ($var) {
            if(config('app.debug') == true){
                return ( strstr($var['command'], '"artisan" backup:run'));
            }else{
                return ( strstr($var['command'], "'artisan' backup:run"));
            }

        });
        $next = '';
        foreach ($getBackupSchedule as $schedule){
            $next .= $schedule['next'];
        }
        $scheduleModel = \App\Models\Schedule::first();
        $scheduleModel->backup_next = $next;
        $scheduleModel->update();
    }

    /**
     * If it's an artisan command, strip off the PHP
     *
     * @param  $command
     * @return string
     */
    protected static function fixupCommand($command)
    {
        $parts = explode(' ', $command);
        if (count($parts) > 2 && $parts[1] === "'artisan'") {
            array_shift($parts);
        }

        return implode(' ', $parts);
    }

    /**
     * Determine the previous run date for an event.
     *
     * @param  string $expression
     * @return \Carbon\Carbon
     */
    protected static function previousRunDate($expression)
    {
        return Carbon::instance(
            CronExpression::factory($expression)->getPreviousRunDate()
        );
    }
}
