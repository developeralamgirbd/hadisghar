<?php

namespace App\Console\Commands;

use App\Models\ScheduleList;
use Cron\CronExpression;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class ScheduleCalenderCommand extends Command
{/**
 * The name and signature of the console command.
 *
 * @var string
 */
    protected $signature = 'schedule:list';

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
    public $find = array('"', "'", " ");
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
                'command_name' => Str::after(Str::replace($this->find, '', $event->command), 'artisan') ,
                'next' => $event->nextRunDate()->toDateTimeString(),
                'previous' => static::previousRunDate($event->expression)->toDateTimeString(),
            ];
        }, $this->schedule->events());
        $count = ScheduleList::all()->count();
        foreach ($events as $even){
           if ($count ===  0){
               ScheduleList::create([
                   'cron' => $even['cron'],
                   'command_name' => $even['command_name'],
                   'last_run' => $even['previous'],
                   'next_run' => $even['next'],
               ]);
           } else {
              $schedule_list = ScheduleList::where('command_name', $even['command_name'])->first();
              $schedule_list->cron = $even['cron'];
              $schedule_list->command_name = $even['command_name'];
              $schedule_list->last_run = $even['previous'];
              $schedule_list->next_run = $even['next'];
              $schedule_list->update();
           }
        }

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
    protected static function descriptiion($desc){
//        Artisan::call('')
    }
}
