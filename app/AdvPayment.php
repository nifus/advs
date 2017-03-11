<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Place;
use App\MessageLog;
use App\Jobs\AdvMessage as AdvMessageJob;


class AdvPayment extends Model
{

    protected $fillable = [
        'title', 'desc', 'created_at', 'type', 'updated_at', 'user_id', 'status', 'photos', 'visited', 'favorite',
        'lng','lat',
    ];

    public $table='advs_payments';


    public function Owner()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
    public function UsersFav()
    {
        return $this->belongsToMany('App\User', 'advs_fav', 'adv_id','user_id');
    }


}
