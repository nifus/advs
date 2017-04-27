<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTariffSlot extends Model
{
    public $table='users_tariff_slots';



    protected $fillable = [
        'user_id','tariff_id','created_at','updated_at','is_paid' ,'activate_time','buy_time','adv_id','is_extra_slot'
    ];

    public function Tariff()
    {
        return $this->hasOne('App\UserTariff');
    }
    public function User()
    {
        return $this->hasOne('App\User');
    }

    static function getSlots($user_id, $tariff_id){
        return self::where('user_id', $user_id)->where('tariff_id', $tariff_id )->get();
    }

    static function createSlot($user_id, $user_tariff_id, $extra='0'){
        $date = new \DateTime();
        return self::create([
            'user_id'=>$user_id,
            'tariff_id'=>$user_tariff_id,
            'is_extra_slot'=>$extra,
            'buy_time' => $date->format('Y-m-d H:i:s'),
            //'is_paid'=>'1'
        ]);
    }
}
