<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Variable extends Model
{
    public $table='variables';



    protected $fillable = [
        'user_id','created_at','updated_at','title','value','desc'
    ];

    public function User(){
        return $this->hasOne('App\User','id','user_id');
    }

    public function updateValue($value, User $user){
        $data = ['value'=>$value, 'user_id'=>$user->id];
        self::update($data);
        return true;
    }

    static function getAll(){
        return self::with('User')->get();
    }
    static function getById($id){
        return self::with('User')->where('id',$id)->first();
    }
}
