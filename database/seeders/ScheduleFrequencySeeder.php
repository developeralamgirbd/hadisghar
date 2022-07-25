<?php

namespace Database\Seeders;

use App\Models\taskScheduleFrequencie;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScheduleFrequencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->frequencies() as $frequency)
        taskScheduleFrequencie::updateOrCreate([
            'name' => $frequency
        ]);
    }

    public function frequencies(){
        return [
            'everyMinute',
            'everyTwoMinutes',
            'everyThreeMinutes',
            'everyFourMinutes',
            'everyFiveMinutes',
            'everyTenMinutes',
            'everyFifteenMinutes',
            'everyThirtyMinutes',
            'hourly',
            'hourlyAt',
            'everyTwoHours',
            'everyThreeHours',
            'everyFourHours',
            'everySixHours',
            'daily',
            "dailyAt",
            'twiceDaily',
            "weekly",
            "weeklyOn",
            "monthly",
            "monthlyOn",
            "twiceMonthly",
            "lastDayOfMonth",
            "quarterly",
            "yearly",
            "yearlyOn",
            "weekdays",
            "weekends",
            "sundays",
            "mondays",
            "tuesdays",
            "wednesdays",
            "thursdays",
            "fridays",
            "saturdays",
            "days",
            "between",
            "unlessBetween",
        ];
    }
}
