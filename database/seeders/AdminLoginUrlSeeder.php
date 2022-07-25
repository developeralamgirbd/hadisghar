<?php

namespace Database\Seeders;

use App\Http\Controllers\Admin\Security\BruteForceController;
use App\Models\AdminLoginUrl;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminLoginUrlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AdminLoginUrl::create([
            'admin_login_url' => 'admin'
        ]);
    }
}
