<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Adv as Advert;
use App\EventsLog;

class advertClean extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'advert:cleaner';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove blocked  and disable old adverts';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $advs = Advert::getForRemove();
        foreach($advs as $adv){
            EventsLog::removeBlockedAdvert($adv);
            $adv->delete();
        }

        $advs = Advert::getForDisable();
        foreach($advs as $adv){
            EventsLog::disableBlockedAdvert($adv);
            $adv->disable();
        }
    }
}
