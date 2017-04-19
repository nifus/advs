<?php

namespace App\Mail;

use App\User;
use App\MailTemplate;
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
        $tmpl = MailTemplate::getById(9);

        return $this->subject($tmpl->header)->view('emails.'.$tmpl->path,
            [
                'varContactForename' => $user->name,
                'varContactSurname' => $user->surname,
                'varContactTitle' => $user->sex=='male' ? 'Mister' : 'Miss',
                'varNewPassword' => $this->password,
                'varAccountEmail' => $user->email,
            ]
        );
    }
}
