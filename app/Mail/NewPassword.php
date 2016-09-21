<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewPassword extends Mailable
{
    use Queueable, SerializesModels;

    private $user;
    private $password;

    public function __construct( User $user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = $this->user;
        return $this->view('emails.newPassword',['name'=>$user->name,'password'=>$this->password,'email'=>$user->email]);
    }
}
