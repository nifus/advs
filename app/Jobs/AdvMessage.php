<?php

namespace App\Jobs;

use App\Adv;
use App\MessageLog;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\AdvMessage as AdvMessageMail;


class AdvMessage implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    private $adv;
    private $log;
    public function __construct( Adv $adv, MessageLog $log)
    {
        $this->adv = $adv;
        $this->log = $log;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $log = $this->log;
        $owner = $this->adv->Owner;

        \Mail::to($owner)
            ->send(new AdvMessageMail($this->adv, $this->log));
        $this->log->update(['is_sent'=>'1']);
    }
}
