<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessageLog extends Model
{
    public $table='message_log';



    protected $fillable = [
        'adv_id','created_at','updated_at','data','ip','is_sent'
    ];


    public function getDataAttribute()
    {
        return json_decode($this->attributes['data']);
    }

    public function setDataAttribute($value)
    {
        $value = json_encode($value);
        $this->attributes['data']=$value;
    }


    static function createMessage($adv_id, $data, $ip){
        return self::create(['data'=>$data,'adv_id'=>$adv_id,'ip'=>$ip,'is_sent'=>'0']);
    }

    static function check($adv_id, $ip){
        $date = new \DateTime();
        $date->modify('-5 min');
        $time = $date->format('Y-m-d H:i:s');

        $count = self::where('adv_id',$adv_id)->where('ip',$ip)->where('created_at','>', $time)->count();
        if ( $count>2 ){
            return false;
        }
        return true;
    }
}
