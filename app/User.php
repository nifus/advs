<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use JWTAuth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','surname','sex','group_id','is_activated','activate_key','is_approved','company','contact_email','giro_account','payment_type','paypal_email','phone','tariff','website','commercial_country' ,'commercial_id','commercial_additional','address_additional','address_city','address_number','address_street','address_zip'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function Group()
    {
        return $this->hasOne('App\Group', 'id', 'group_id');
    }

    public function setPasswordAttribute($value){
        $this->attributes['password'] = \Hash::make($value);
    }


    public function activate($key){

        if ($this->is_activated==1){
            throw new \Exception('user_is_activated');
        }
        if ($this->activate_key!=$key){
            throw new \Exception('user_activate_key_invalid');
        }
        $this->update(['is_activated'=>1]);
    }

    public function resetPassword(){
        $new_pass = str_random(8);
        $activate_key = md5(str_random(8));
        $this->update(['password'=>$new_pass,'activate_key'=>$activate_key]);
        return $new_pass;
    }

    static function createPrivateAccount($data){
        $validator = [
            'sex' => 'required',
            'name' => 'required|min:2',
            'surname' => 'required|min:2',
            'agb' => 'required',
            'password' => 'required|min:6',
            're_password' => 'required|min:6|same:password',
            'email' => 'required|min:6',
            're_email' => 'required|min:6|same:email',
            'captcha'=>'required|captcha'
        ];
        $validator = \Validator::make($data, $validator);
        if ($validator->fails()) {
            $messages = $validator->messages();
            throw new \Exception($messages->first());
        }

        $count = self::where('email', $data['email'])->count();
        if ($count>0){
            throw new \Exception( trans('validation.exist_email') );
        }

        $data['group_id']=2;
        $data['activate_key']=md5( time().rand(0, 10000));
        return self::create($data);

    }

    static function createBusinessAccount($data){
        $validator = [
            'sex' => 'required',
            'name' => 'required|min:2',
            'surname' => 'required|min:2',
            'agb' => 'required',
            'password' => 'required|min:6',
            're_password' => 'required|min:6|same:password',
            'email' => 'required|min:6',
            're_email' => 'required|min:6|same:email',
            'captcha'=>'required|captcha',
            'company'=>'required|min:2',
            'commercial_country'=>'required',
            'commercial_id'=>'required',
            'address_city'=>'required',
            'address_number'=>'required',
            'address_street'=>'required',
            'address_zip'=>'required',

        ];
        $validator = \Validator::make($data, $validator);
        if ($validator->fails()) {
            $messages = $validator->messages();
            throw new \Exception($messages->first());
        }

        $count = self::where('email', $data['email'])->count();
        if ($count>0){
            throw new \Exception( trans('validation.exist_email') );
        }

        $data['group_id']=3;
        $data['activate_key']=md5( time().rand(0, 10000));
        return self::create($data);

    }

    static function getUserByLogin($login){
        return self::where('email', $login)
            ->where('is_activated', 1)
            ->get()->first();

    }

    static function getUser(){
        if (!isset($_COOKIE['token'])){
            return null;
        }
        try{
            return JWTAuth::setToken( $_COOKIE['token'] )->authenticate();
        }catch( \Exception $e){
            return null;
        }

    }
}
