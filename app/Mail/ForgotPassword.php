<?php

namespace App\Mail;

use App\User;
use App\MailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ForgotPassword extends Mailable
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
        $tmpl = MailTemplate::getById(8);

        $link = route('user.reset',['user_id'=>$user->id,'key'=>$user->activate_key]);

        return $this->subject($tmpl->header)->view('emails.'.$tmpl->path,
            [
                'varContactForename' => $user->name,
                'varContactSurname' => $user->surname,
                'varContactTitle' => $user->sex=='male' ? 'Mister' : 'Miss',
                'varForgotLink' => $link,
            ]
        );
    }
}
