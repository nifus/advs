<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
class EventsLog extends Model
{
    public $table='events_log';



    protected $fillable = [
        'type','created_at','updated_at','user_id','adv_id','additional_fields','action'
    ];
    public function getAdditionalFieldsAttribute(){
         return json_decode($this->attributes['additional_fields']);
    }
    public function setAdditionalFieldsAttribute($fields){
        $this->attributes['additional_fields'] = json_encode($fields);
    }

    public function toArray()
    {
        $data = parent::toArray();
        $date = new \DateTime($this->created_at);
        $data['DateWithTime'] = $date->format('d-m-y H:i');
        $data['FullDesc'] = $this->getFullDesc();


        return $data;
    }

    public function getFullDesc(){
        if ($this->action=='createAccount'){
            return 'Account was created';
        }elseif ($this->action=='accountEmailIsConfirmed'){
            return 'Email address was confirmed';
        }elseif ($this->action=='accountIsActivated'){
            return 'Account status set to „Active“';
        }
    }

    static function createAccount(User $user){
        self::create(['type'=>'system','action'=>'createAccount','user_id'=>$user->id,'additional_fields'=>$user->toArray()]);
    }

    static function accountEmailIsConfirmed(User $user){
        self::create(['type'=>'system','action'=>'accountEmailIsConfirmed','user_id'=>$user->id]);
    }
    static function accountIsActivated(User $user, User $administrator){
        self::create(['type'=>'system','action'=>'accountIsActivated','user_id'=>$user->id,'additional_fields'=>[
            'administrator_id'=>$administrator->id
        ]]);
    }

    static function getEventLogByUser(User $user){
        return self::where('user_id', $user->id)->orderBy('id','DESC')->get();
    }
}