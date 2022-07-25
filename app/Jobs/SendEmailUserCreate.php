<?php

namespace App\Jobs;

use App\Mail\UserCreateNotify;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class SendEmailUserCreate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $user_role;
    protected $email;
    protected $link;
    protected $expire;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_role, $email, $link, $expire)
    {
       $this->user_role = $user_role;
       $this->email = $email;
       $this->link = $link;
       $this->expire = $expire;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
//        $email = new UserCreateNotify();

       Mail::to($this->email)->send(new UserCreateNotify($this->user_role, $this->email, $this->link, $this->expire));
    }
}
