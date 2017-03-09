<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrivateTariff extends Model
{
    public $table='private_tariffs';

    protected $fillable = ['duration' ,'rent_price','sale_price'];

    public $timestamps = false;

    public function updatePrice($rent_price, $sale_price){
        $this->update(['rent_price'=>$rent_price,'sale_price'=>$sale_price]);
        return true;
    }

    static function getTariffs(){
        return self::orderBy('id','ASC')->get();
    }
}
