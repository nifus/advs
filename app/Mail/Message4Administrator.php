<?php

namespace App\Mail;

use App\MailTemplate;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Message4Administrator extends Mailable
{
    use Queueable, SerializesModels;

    private $user;
    private $message;

    public function __construct( User $user, $message)
    {
        $this->user = $user;
        $this->message = $message;
        $this->replyTo($user->email, $user->name);

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = $this->user;

        $tmpl = MailTemplate::getById(11);

        return $this->subject($tmpl->header)->view('emails.'.$tmpl->path,
            [
                'varContactForename' => $user->name,
                'varContactSurname' => $user->surname,
                'varContactId' => $user->id,
                'varContactTitle' => $user->sex=='male' ? 'Mister' : 'Miss',
                'varMessageForAdministrator' => $this->message,
            ]
        );

    }
}
