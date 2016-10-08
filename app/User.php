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
        'name', 'email', 'password','surname','sex','group_id','is_activated','activate_key','is_approved','company','contact_email','giro_account','payment_type','paypal_email','phone','tariff','website','commercial_country' ,'commercial_id','commercial_additional','address_additional','address_city','address_number','address_street','address_zip','allow_notifications','is_deleted'
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
    public function Fav()
    {
        return $this->belongsToMany('App\Adv', 'advs_fav', 'user_id','adv_id');
    }
    public function Adv()
    {
        return $this->hasMany('App\Adv');
    }

    public function setPasswordAttribute($value){
        $this->attributes['password'] = \Hash::make($value);
    }

    public function isDeletedAccount(){
        return $this->attributes['is_deleted']==1 ? true : false;
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

    public function getWatch(){
        return $this->with('Fav')->get();
    }

    public function changeEmail($email, $re_email){
        $data = [
            'email'=>$email,
            're_email'=>$re_email,
        ];
        $validator = [
            'email' => 'required|email|min:6|unique:users,email',
            're_email' => 'required|min:6|same:email'
        ];
        $validator = \Validator::make($data, $validator);
        if ($validator->fails()) {
            $messages = $validator->messages();
            throw new \Exception($messages->first());
        }

        $this->update(['email'=>$email]);
    }

    public function changePassword($current, $new, $re){
        $data = [
            'current'=>$current,
            'new'=>$new,
            're'=>$re,
        ];
        $validator = [
            'current' => 'required|min:6',
            'new' => 'required|min:6',
            're' => 'required|min:6|same:new'
        ];
        $validator = \Validator::make($data, $validator);
        if ($validator->fails()) {
            $messages = $validator->messages();
            throw new \Exception($messages->first());
        }

        if ( !\Hash::check($current, $this->password) ){
            throw new \Exception('Wrong Current password');
        }


        $this->update(['password'=>$new]);
    }

    public function deleteAccount(){
        $this->update(['is_deleted'=>1]);

        $advs = $this->Adv()->get();
        foreach($advs as $adv){
            $adv->delete();
        }
    }

    public function changeAllowNotifications($value){
        $this->update(['allow_notifications'=>$value]);
    }

    public function changePayment($data){
        $this->update($data);
    }
    public function changeContactData($data){
        $validator = [
            'sex' => 'required',
            'name' => 'required|min:2',
            'surname' => 'required|min:2',

        ];
        $validator = \Validator::make($data, $validator);
        if ($validator->fails()) {
            $messages = $validator->messages();
            throw new \Exception($messages->first());
        }


        $this->update($data);
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
    static function getUserOrDie(){
       $user = self::getUser();
        if ( is_null($user) ){
            throw new \Exception('User not found');
        }
        return $user;

    }

    static function getUserIds(){
        return self::whereIn('group_id',[2,3])->pluck('id')->toArray();
    }
}
