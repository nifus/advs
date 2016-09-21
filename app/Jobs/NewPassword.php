<?php

namespace App\Jobs;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\NewPassword as NewPasswordMail;


class NewPassword implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    private $password;
    public function __construct( User $user, $new_password)
    {

        $this->user = $user;
        $this->password = $new_password;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        \Mail::to($this->user->email)->send(new NewPasswordMail($this->user,$this->password));
    }
}
