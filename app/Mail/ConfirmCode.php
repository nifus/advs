<?php

namespace App\Mail;

use App\User;
use App\MailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ConfirmCode extends Mailable
{
    use Queueable, SerializesModels;

    private $user;
    private $code;

    public function __construct( User $user, $code)
    {
        $this->user = $user;
        $this->code = $code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = $this->user;
        $tmpl = MailTemplate::getById(7);

        return $this->subject($tmpl->header)->view('emails.'.$tmpl->path,
            [
                'varContactForename' => $user->name,
                'varContactSurename' => $user->surname,
                'varAccountEmail' => $user->email,
                'code' => $this->code,
            ]
        );
       // return $this->view('emails.confirmCode',['name'=>$user->name,'code'=>$this->code,'email'=>$user->email]);
    }
}
