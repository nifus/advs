<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Place;
use App\MessageLog;
use App\Jobs\AdvMessage as AdvMessageJob;


class AdvPayment extends Model
{

    protected $fillable = [
        'adv_id', 'tariff_id', 'payment_type', 'status', 'payment_log', 'guid', 'paypal_email', 'giro', 'price', 'created_at',
        'updated_at',
    ];

    public $table='advs_payments';

    public function Advert(){
        return $this->hasOne('App\Adv','id','adv_id');
    }

    public function PrivateTariff(){
        return $this->hasOne('App\PrivateTariff','id','tariff_id');
    }
    public function BusinessTariff(){
        return $this->hasOne('App\BusinessTariff','id','tariff_id');
    }

    public function success()
    {
        $this->update(['status'=>'success']);
        $advert  = $this->Advert;
        $advert->activate($this);
        return true;
    }
    public function fail()
    {
        $this->update(['status'=>'error']);
        return true;

    }

    static function getLastPayment($adv_id){
        return self::where('adv_id',$adv_id)->orderBy('id','DESC')->first();
    }

    static function createNewPayment($type, $data){
        //giro|paypal|pre-payment
        if ($type=='giro'){
            return self::createGiroPayment($data);
        }elseif($type=='paypal'){
            return self::createPaypalPayment($data);
        }elseif($type=='pre-payment'){
            return self::createPrePayment($data);
        }
        return false;
    }

    static function createGiroPayment($data){
        $validator = [
            'adv_id' => 'required',
            'price' => 'required',
            'account' => 'required',
            'tariff_id' => 'required',
        ];
        $validator = \Validator::make($data, $validator);
        if ($validator->fails()) {
            $messages = $validator->messages();
            throw new \Exception($messages->first());
        }
        return self::create([
            'payment_type'=>'giro',
            'account'=>$data['account'],
            'tariff_id'=>$data['tariff_id'],
            'price'=>$data['price'],
            'adv_id'=>$data['adv_id'],
            'status'=>'wait'
        ]);
    }

    static function createPaypalPayment($data){
        $validator = [
            'adv_id' => 'required',
            'price' => 'required',
            'email' => 'required',
            'tariff_id' => 'required',
        ];
        $validator = \Validator::make($data, $validator);
        if ($validator->fails()) {
            $messages = $validator->messages();
            throw new \Exception($messages->first());
        }
        return self::create([
            'payment_type'=>'paypal',
            'paypal_email'=>$data['email'],
            'tariff_id'=>$data['tariff_id'],
            'price'=>$data['price'],
            'adv_id'=>$data['adv_id'],
            'status'=>'wait'
        ]);
    }
    static function createPrePayment($data){
        $validator = [
            'adv_id' => 'required',
            'price' => 'required',
            'guid' => 'required',
            'tariff_id' => 'required',
        ];
        $validator = \Validator::make($data, $validator);
        if ($validator->fails()) {
            $messages = $validator->messages();
            throw new \Exception($messages->first());
        }
        return self::create([
            'payment_type'=>'paypal',
            'guid'=>$data['guid'],
            'tariff_id'=>$data['tariff_id'],
            'price'=>$data['price'],
            'adv_id'=>$data['adv_id'],
            'status'=>'wait'
        ]);
    }
}
