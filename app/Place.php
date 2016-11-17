<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\AdvAddress;

class Place extends Model
{
    protected $fillable = [
        'city','country','region','zip','created_at','count_advs','updated_at','lng','lat'
    ];


    static function findCity($country, $region, $city){
        return self::where('city',$city)->where('region',$region)->where('country', $country)->first();
    }

    static function findRegion($country, $region){
        return self::where('region',$region)->where('country', $country)->first();
    }
    static function findCountry($country){
        return self::where('country', $country)->first();
    }
    static function createCountry($country){
        return self::create(['country'=>$country]);
    }
    static function createRegion($country, $region){
        return self::create(['country'=>$country,'region'=>$region]);
    }
    static function createCity($country, $region, $city, $zip){
        return self::create(['country'=>$country,'region'=>$region,'city'=>$city,'zip'=>$zip]);
    }
    static function likeCities($key){
        $key = trim($key);
        return self::where('city','LIKE',$key.'%')->
            orWhere('zip','LIKE',$key.'%')->
            orderBy('count_advs','DESC')->get(['id','city','zip']);
    }
}
