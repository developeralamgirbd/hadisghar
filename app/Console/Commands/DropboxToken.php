<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DropboxToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dropbox-token:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dropbox refresh access token generate';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $refresh_token = \App\Models\Dropbox::select('app_key', 'app_secret','refresh_token')->first();

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.dropbox.com/oauth2/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=refresh_token&refresh_token=".$refresh_token->refresh_token );
        curl_setopt($ch, CURLOPT_USERPWD, $refresh_token->app_key . ':' . $refresh_token->app_secret);

        $headers = array();
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = json_decode(curl_exec($ch)); ;
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        $dropbox = \App\Models\Dropbox::first();
        $dropbox->access_token = $result->access_token;
        $dropbox->update();
        $this->info('Dropbox token generating...');
        $this->info('Dropbox token generated successfully');

    }
}
