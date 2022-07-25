<?php

namespace Database\Seeders;

use App\Models\LimitLoginAttempt;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LimitLoginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LimitLoginAttempt::create([
           'attempt' => 5,
           'second' => 300,
        ]);
    }
}
