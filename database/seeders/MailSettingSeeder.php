<?php

namespace Database\Seeders;

use App\Models\MailSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MailSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MailSetting::create([
            'mail_transport'    => 'smtp',
            'mail_host'         => 'smtp.mailtrap.io',
            'mail_port'         => '2525',
            'mail_username'     => 'a78c7634d16190',
            'mail_password'     => 'ee89fd0efa0c6d',
            'mail_encryption'   => 'tls',
            'mail_from'         => 'test@email.com'
        ]);
    }
}
