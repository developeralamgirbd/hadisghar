<?php

namespace Database\Seeders;

use App\Models\Dropbox;
use Illuminate\Database\Seeder;

class DropboxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       Dropbox::create([
           'app_key' => 'app key',
           'app_secret' => 'app secret',
           'redirect_url' => 'redirect url',
           'refresh_token' => 'refresh token',
           'access_token' => 'access token',
           ]);
    }
}
