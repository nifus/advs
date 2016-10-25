<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\AdvAddress;
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

    public function setEquipmentsAttribute(){

    }

    public function getEquipmentsAttribute(){

    }

    /*public function setAddressAttribute($value){
        if ( is_array($value) ){
            AdvAddress::addNewAddress();
        }
        return '/uploads/adv/full/'.$this->id.'/'.$images[0];
    }*/


    /*public function setPhotosAttribute($value){

        if (is_array($value) ){
            foreach($value as $image){
                $name = time() . rand(1, 10000) . '.' . pathinfo($image['filename'], PATHINFO_EXTENSION);
                file_put_contents(public_path('uploads/adv/full/' . $name), base64_decode($image['base64']));

                Image::make(public_path('uploads/adv/full/' . $adv->id . '/' . $image))->resize(100, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save(public_path('uploads/adv/preview/' . $adv->id . '/' . $image));
            }
        } elseif ( is_string($value) ) {
            $result = $value;
        } else {
            $result = null;
        }


        if (is_array($value) && isset($value[0]) && isset($value[0]['base64'])) {
            $name = time() . rand(1, 10000) . '.' . pathinfo($value[0]['filename'], PATHINFO_EXTENSION);
            file_put_contents(public_path('uploads/avatar/' . $name), base64_decode($value[0]['base64']));

            $img = \Image::make(public_path('uploads/avatar/' . $name));
            $img->resize(150, 150);
            $img->save(public_path('uploads/avatar/' . $name), 60);

            $result = $name;
        } elseif (is_array($value) && isset($value['base64'])) {
            $name = time() . rand(1, 10000) . '.' . pathinfo($value['filename'], PATHINFO_EXTENSION);
            file_put_contents(public_path('uploads/avatar/' . $name), base64_decode($value['base64']));
            $img = \Image::make(public_path('uploads/avatar/' . $name));
            $img->resize(150, 150);
            $img->save(public_path('uploads/avatar/' . $name), 60);


            $result = $name;
        } elseif (is_array($value) && isset($value[0]) && is_string($value[0])) {
            $result = basename($value[0]);
        } else {
            $result = null;
        }
        $this->attributes['avatar'] = $result;

    }*/

    public function getMainPhotoAttribute(){
        if ( is_null($this->attributes['photos'])){
            return ['images/no-photo.jpg'];
        }
        $images = explode(',', $this->attributes['photos']);
        return '/uploads/adv/full/'.$this->id.'/'.$images[0];
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
        //unset($images[0]);
        foreach($images as $photo){
            array_push($result, '/uploads/adv/preview/'.$this->id.'/'.$photo);
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
            array_push($result, '/uploads/adv/full/'.$this->id.'/'.$photo);
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
