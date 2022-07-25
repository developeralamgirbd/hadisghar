<?php

namespace App\Providers;

use App\Models\Dropbox;
use App\Models\MailSetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (Schema::hasTable('dropboxes')){
            $dropbox = Dropbox::first();
            if ($dropbox){
                $data = [
                    'driver' => 'dropbox',
                    'key' => $dropbox->app_key,
                    'secret' => $dropbox->app_secret,
                    'authorization_token'   => $dropbox->access_token
                ];
                Config::set('filesystems.disks.dropbox', $data);
                Config::set('backup.notifications.mail.to', $dropbox->email);
            }
        }

        if (Schema::hasTable('mail_settings')){
            $mail_settings = MailSetting::first();
            if ($mail_settings){
                $mailData = [
                    'driver'     => $mail_settings->mail_transport,
                    'host'          => $mail_settings->mail_host,
                    'port'          => $mail_settings->mail_port,
                    'username'      => $mail_settings->mail_username,
                    'password'      => $mail_settings->mail_password,
                    'encryption'    => $mail_settings->mail_encryption,
                    'from'          => [
                        'address' => $mail_settings->mail_from,
                        'name' => config('app.name'),
                    ]
                ];
                Config::set('mail', $mailData);
            }
        }
    }
}
