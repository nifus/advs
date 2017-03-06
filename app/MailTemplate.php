<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class MailTemplate extends Model
{
    public $table='mail_templates';



    protected $fillable = [
        'name','created_at','updated_at','body','header','user_id','type'
    ];

    public function User(){
        return $this->hasOne('App\User','id','user_id');
    }

    public function updateByUser($date, User $user){
        $date['user_id']=$user->id;
        $this->update($date);
        return true;
    }

    static function getAll(){
        return self::with('User')->get();
    }

    static function getById($id){
        return self::with('User')->find($id);
    }

}
