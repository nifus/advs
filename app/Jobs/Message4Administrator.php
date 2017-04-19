<?php

namespace App\Jobs;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\Message4Administrator as Message4AdministratorMail;


class Message4Administrator implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    private $message;
    public function __construct( User $user, $message)
    {

        $this->user = $user;
        $this->message = $message;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        \Mail::to(\Config::get('variables.varHelpEmailAddress'))->send(new Message4AdministratorMail($this->user, $this->message));
    }
}
