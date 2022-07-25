<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            PermissionSeeder::class,
//            CategorySeeder::class,
//            PostSeeder::class,
            LimitLoginSeeder::class,
            AdminLoginUrlSeeder::class,
            DropboxSeeder::class,
            ScheduleSeeder::class,
            ScheduleFrequencySeeder::class,
            MailSettingSeeder::class,
            MaintenanceSeeder::class,
        ]);
    }
}
