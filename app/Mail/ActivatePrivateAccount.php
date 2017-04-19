<?php

namespace App\Mail;

use App\MailTemplate;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ActivatePrivateAccount extends Mailable
{
    use Queueable, SerializesModels;

    private $user;

    public function __construct( User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = $this->user;

        $tmpl = MailTemplate::getById(3);

        return $this->subject($tmpl->header)->view('emails.'.$tmpl->path,
            [
                'varContactForename' => $user->name,
                'varContactSurname' => $user->surname,
                'varContactTitle' => $user->sex=='male' ? 'Mister' : 'Miss',
                'varAccountEmail' => $user->email,
            ]
        );

    }
}
