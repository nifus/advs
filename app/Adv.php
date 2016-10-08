<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Adv extends Model
{
    protected $fillable = [
        'title','desc','created_at','type','updated_at','total_rent','cold_rent','ancillary_cost','heating_cost','caution_money','user_id','status','photos','visited','favorite','area','rooms','floor','floors','address_id','living_area','number_of_garage','pets','move_date','is_deleted'
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

    public function changeStatus($status){
        $this->update(['status'=>$status]);
    }


    public function getMainPhotoAttribute(){
        if ( is_null($this->attributes['photos'])){
            return ['images/no-photo.jpg'];
        }
        $images = explode(',', $this->attributes['photos']);
        return '/uploads/adv/'.$images[0];
    }
    public function getLastPhotosAttribute(){
        if ( is_null($this->attributes['photos'])){
            return null;
        }
        $images = explode(',', $this->attributes['photos']);
        if (sizeof($images)==1){
            return null;
        }
        $result = [];
        unset($images[0]);
        foreach($images as $photo){
            array_push($result, '/uploads/adv/'.$photo);
        }
        return $result;
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

    public function delete(){
        $this->update(['is_deleted'=>'1']);
    }

    public function disable(){
        $this->update(['status'=>'disabled']);
    }

    public function enable(){
        $this->update(['status'=>'enable']);
    }

    public function isOwner($user_id){
        if ( $this->user_id!=$user_id ){
            return false;
        }
        return true;
    }

    static function getByUserWithStatus($user_id){
        return self::where('user_id', $user_id)->where('is_deleted','0')->get(['status','type']);
    }

    static function getByUser($user_id){
        return self::where('user_id', $user_id)->where('is_deleted','0')->get();
    }

    static function removeWatch($user_id, $adv_id){
        \DB::table('advs_fav')->where('user_id', $user_id)->where('adv_id', $adv_id)->delete();
    }

    static function findOrDie($id){
        $adv = self::find($id);
        if( is_null($adv) ){
            throw new \Exception('Adv not found');
        }
        return $adv;
    }

}
