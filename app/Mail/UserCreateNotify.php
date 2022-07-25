<?php

namespace App\Mail;

use App\Models\MailSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Notifications\Notifiable;

class UserCreateNotify extends Mailable
{
    use Queueable, SerializesModels;
    use Notifiable;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user_role;
    public $email;
    public $token;
    public $link;
    public $expire;

    public function __construct($user_role, $email, $action_link, $expire)
    {
        $this->user_role = $user_role;
        $this->email = $email;
        $this->link = $action_link;
        $this->expire = $expire;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $url = '';
        if ($this->user_role === 'user'){
            $url .= 'password.reset';
        }else{
            $url .= 'admin.password.reset';
        }
        $mail_settings = MailSetting::first();
        return $this->from($mail_settings->mail_from)
            ->subject('Password Reset Link From '. config('app.name'))
        ->markdown('emails.usernotify', [
            'url' => $this->link,
            'email' => $this->email,
            'role'  => $this->user_role,
            'expire'  => $this->expire,
        ]);
    }
}
