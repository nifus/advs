<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Adv extends Model
{
    protected $fillable = [
        'title','desc','created_at','type','updated_at','total_rent','cold_rent','ancillary_cost','heating_cost','caution_money','user_id','status','photos','visited','favorite','area','rooms','floor','floors','address_id','living_area','number_of_garage','pets','move_date'
    ];

    function toArray(){
        $array = parent::toArray();

       // $array['CreatedDate'] = $this->CreatedDate;
        //$array['EndDate'] = $this->EndDate;
       // $array['DeleteDate'] = $this->DeleteDate;
      //  $array['DeleteDate'] = $this->DeleteDate;
        $array['StatusStr'] = $this->StatusStr;
        return $array;
    }



    public function getPhotosAttribute(){
        if ( is_null($this->attributes['photos'])){
            return ['images/no-photo.jpg'];
        }
        $photos = explode(',',$this->attributes['photos']);
        $result = [];
        foreach($photos as $photo){
            array_push($result, '/uploads/adv/'.$photo);
        }
        return $result;
    }

    public function getStatusStrAttribute(){
        switch($this->attributes['status']){
            case('payment_waiting'):
                return 'Waiting for payment';
                break;
            case('active'):
                return 'Active';
                break;
            case('disabled'):
                return 'Disabled';
                break;
            case('expired'):
                return 'Expired';
                break;
            case('blocked'):
                return 'BLOCKED';
                break;
        }
    }

    static function getByUserWithStatus($user_id){
        return self::where('user_id', $user_id)->get(['status','type']);
    }

    static function getByUser($user_id){
        return self::where('user_id', $user_id)->get();
    }
}
