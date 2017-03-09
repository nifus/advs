<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BusinessTariff extends Model
{
    public $table='business_tariffs';

    protected $fillable = ['title' ,'number_of_slots','price','price_extra_slots'];
    public $timestamps = false;

    public function updatePrice($price, $price_extra_slots){
        $this->update(['price'=>$price,'price_extra_slots'=>$price_extra_slots]);
        return true;
    }

    static function getTariffs(){
        return self::orderBy('id','ASC')->get();
    }
}
