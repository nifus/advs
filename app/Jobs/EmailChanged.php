<?php

namespace App\Jobs;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\EmailChanged as EmailChangedMail;


class EmailChanged implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    public function __construct( User $user)
    {

        $this->user = $user;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        \Mail::to($this->user->email)->send(new EmailChangedMail($this->user));
    }
}
