<?php

namespace App\Mail;

use App\Models\MailSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserUpdateNotify extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user_role;
    public $user_name;
    public $user_email;
    public $url;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user_role, $user_email, $user_name)
    {
        $this->user_role = $user_role;
        $this->user_email = $user_email;
        $this->user_name = $user_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        if ($this->user_role !== 'user'){
           $this->url = 'admin.login';
        }else{
            $this->url = 'login';
        }
        $mail_settings = MailSetting::first();
        return $this->from($mail_settings->mail_from)
            ->subject('Admin Update Your Information'. config('app.name'))
        ->markdown('emails.userUpdateNotify', [
            'user_role' => $this->user_role,
            'user_email' => $this->user_email,
            'user_name' => $this->user_name,
            'url' => $this->url,
        ]);
    }
}
