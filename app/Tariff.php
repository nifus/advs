<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tariff extends Model
{
    public $table='tariffs';

    protected $fillable = [
        'user_id','created_at','updated_at','tariff_id','is_future' ,'is_paid','begin_time','end_time','slots','extra','price'
    ];

    public function Details()
    {
        return $this->hasMany('App\TariffDetails');
    }
    static function addNewTariff($user_id, $tariff_id){

        $tariffs = \Config::get('app.tariffs');
        foreach( $tariffs as $tariff ){
            if ($tariff['id']==$tariff_id){
                $date = new \DateTime();
                $t = self::create([
                    'user_id'=> $user_id,
                    'begin_time'=> $date->format('Y-m-d H:i:s'),
                    'end_time'=> $date->modify('+1 month')->format('Y-m-d H:i:s'),
                    'is_paid' => '1',
                    'is_future'=>'0',
                    'tariff_id'=>$tariff['id'],
                    'price'=>$tariff['price'],
                    'extra'=>$tariff['additional'],
                    'slots'=>$tariff['slots'],
                ]);
                for($i=0;$i<$tariff['slots'];$i++){
                    TariffDetails::createSlot($user_id, $t->id);
                }
                return $t;
            }
        }
    }
    static function getActiveTariff($user_id){
        $date = new \DateTime();
        $now = $date->format('Y-m-d H:i:s');

        return self::where('user_id',$user_id)
            ->where('begin_time','<=', $now)
            ->where('end_time','>', $now)
            ->where('is_paid','1')
            ->where('is_future','0')
            ->first();
    }
}
