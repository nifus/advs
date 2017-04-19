<?php

namespace App\Mail;

use App\MailTemplate;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ConfirmEmailBusinessAccount extends Mailable
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
        $link = route('register.confirm',['user_id'=>$user->id,'key'=>$user->activate_key]);
        $tmpl = MailTemplate::getById(2);

        return $this->subject($tmpl->header)->view('emails.'.$tmpl->path,
            [
                'varContactForename' => $user->name,
                'varContactSurename' => $user->surname,
                'varContactTitle' => $user->sex=='male' ? 'Mister' : 'Miss',
                'varAccountConfirmLink' => $link
            ]);

    }
}
