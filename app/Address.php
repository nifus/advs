<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\AdvAddress;

class Address extends Model
{
    protected $fillable = [
        'street','house_number','zip','city','country','build_year','floors','energy_last_modernization',
        'energy'
    ];




    static function createNewAdv($data, $user_id){
       // dd($data);
        $validator = [
            'category' => 'required',
            'agb_agree' => 'required',
        ];
        $validator = \Validator::make($data, $validator);
        if ($validator->fails()) {
            $messages = $validator->messages();
            throw new \Exception($messages->first());
        }
        return self::create($data);
    }

}
