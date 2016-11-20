<?php

namespace App\Mail;

use App\Adv;
use App\MessageLog;

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
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $log = $this->log;
        $adv = $this->adv;
        return $this->view('emails.advMessage',['name'=>$adv->Owner->name,'log'=>$log,'adv'=>$adv]);
    }
}
