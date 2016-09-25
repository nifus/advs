<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Adv extends Model
{
    protected $fillable = [
        'title','desc','created_at','type','updated_at','total_rent','cold_rent','ancillary_cost','heating_cost','caution_money','user_id','status','photos','visited','favorite','area','rooms','floor','floors','address_id','living_area','number_of_garage','pets','move_date'
    ];

    static function getByUserWithStatus($user_id){
        return self::where('user_id', $user_id)->get(['status','type']);
    }

    static function getByUser($user_id){
        return self::where('user_id', $user_id)->get();
    }
}
