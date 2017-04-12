<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Place;
use App\MessageLog;
use App\Jobs\AdvMessage as AdvMessageJob;


class AdvReport extends Model
{

    protected $fillable = [
        'adv_id', 'user_id', 'reason', 'message', 'created_at','updated_at',
    ];

    public $table='advs_reports';

    public function Advert(){
        return $this->hasOne('App\Adv','id','adv_id');
    }

    static function removeFromAdvert($advert_id){
        self::where('adv_id',$advert_id)->delete();
        return true;
    }

    static function createReport(Adv $advert, User $user=null, $data){
        if ( !is_null($user)){
            $data['user_id'] = $user->id;
        }
        $data['adv_id'] = $advert->id;

        $validator = [
            'adv_id' => 'required',
            'reason' => 'required',
        ];
        $validator = \Validator::make($data, $validator);
        if ($validator->fails()) {
            $messages = $validator->messages();
            throw new \Exception($messages->first());
        }
        return self::create($data);
    }


}
