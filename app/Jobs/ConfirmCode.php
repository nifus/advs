<?php

namespace App\Jobs;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\ConfirmCode as ConfirmCodeMail;


class ConfirmCode implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    private $code;
    private $new_email;
    public function __construct( User $user, $code, $new_email)
    {

        $this->user = $user;
        $this->code = $code;
        $this->new_email = $new_email;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        \Mail::to($this->new_email)->send(new ConfirmCodeMail($this->user,$this->code));
    }
}
