<?php

namespace App\Mail;

use App\Adv;
use App\MessageLog;
use App\MailTemplate;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdvMessage extends Mailable
{
    use Queueable, SerializesModels;

    private $adv;
    private $log;

    public function __construct( Adv $adv, MessageLog $log )
    {
        $this->adv = $adv;
        $this->log = $log;
        $this->replyTo($log->data->email, $log->data->name);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $log = $this->log;
        //$user_from = $log->User;
        $advert = $this->adv;

        $user_to = $advert->Owner;

        $tmpl = MailTemplate::getById(10);

        return $this->subject($tmpl->header)->view('emails.'.$tmpl->path,
            [
                'varContactForename' => $user_to->name,
                'varContactSurename' => $user_to->surname,
                'varAdvertUrl' => route('adv.preview',['id'=>$advert->id]),
                'varAdvertTitle' => $advert->title,
                'varMessageFullName' => $log->FullName,
                'varMessageEmail' => $log->data->email,
                'varMessagePhone' => isset($log->data->phone) ? $log->data->phone : null,
                'varMessage' => $log->MessageWithBr,
            ]
        );

       // return $this->view('emails.advMessage',['name'=>$adv->Owner->name,'log'=>$log,'adv'=>$adv]);
    }
}
