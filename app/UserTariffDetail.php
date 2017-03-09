<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTariffDetails extends Model
{
    public $table='tariffs_details';



    protected $fillable = [
        'user_id','tariff_id','created_at','updated_at','is_paid' ,'activate_time','buy_time','adv_id','is_extra_slot'
    ];

    public function Tariff()
    {
        return $this->hasOne('App\Tariff');
    }
    public function User()
    {
        return $this->hasOne('App\User');
    }

    static function createSlot($user_id, $tariff_id){
        $date = new \DateTime();
        return self::create([
            'user_id'=>$user_id,
            'tariff_id'=>$tariff_id,
            'is_extra_slot'=>'0',
            'buy_time' => $date->format('Y-m-d H:i:s'),
            'is_paid'=>'1'
        ]);
    }
}
