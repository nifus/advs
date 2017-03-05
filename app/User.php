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
        'name', 'email', 'password', 'surname', 'sex', 'group_id', 'activate_key', 'company', 'contact_email', 'giro_account', 'payment_type', 'paypal_email', 'phone', 'tariff', 'website', 'commercial_country', 'commercial_id', 'commercial_additional', 'address_additional', 'address_city', 'address_number', 'address_street', 'address_zip', 'allow_notifications', 'is_deleted', 'status','blocked_at','permissions','initials'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    /*public function Group()
    {
        return $this->hasOne('App\Group', 'id', 'group_id');
    }*/
    public function Fav()
    {
        return $this->belongsToMany('App\Adv', 'advs_fav', 'user_id', 'adv_id');
    }

    public function Adv()
    {
        return $this->hasMany('App\Adv');
    }

    public function toArray()
    {
        $data = parent::toArray();
        $data['StatusTitle'] = $this->StatusTitle;
        $data['CreateDateWithTime'] = $this->CreateDateWithTime;
        $data['BlockedDateWithTime'] = $this->BlockedDateWithTime;
        return $data;
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = \Hash::make($value);
    }

    public function isDeletedAccount()
    {
        return $this->attributes['is_deleted'] == 1 ? true : false;
    }

    public function getStatusTitleAttribute()
    {
        if ($this->status == 'active') {
            return 'Active';
        } elseif ($this->status == 'email_confirmation') {
            return 'Email confirmation';
        } elseif ($this->status == 'wait_approve') {
            return 'Wait for approve';
        } elseif ($this->status == 'blocked') {
            return 'Blocked';
        }
    }

    public function getPermissionsAttribute()
    {
        return json_decode($this->attributes['permissions']);
    }

    public function setPermissionsAttribute($value)
    {
        $this->attributes['permissions'] = json_encode($value);
    }


    public function getCreateDateWithTimeAttribute()
    {
        $date = new \DateTime($this->created_at);
        return $date->format('d-m-y H:i');
    }

    public function getBlockedDateWithTimeAttribute()
    {
        $date = new \DateTime($this->blocked_at);
        return $date->format('d-m-y H:i');
    }

    public function activate($key)
    {

        if ($this->status != 'wait_approve') {
            throw new \Exception('user_is_activated');
        }
        if ($this->activate_key != $key) {
            throw new \Exception('user_activate_key_invalid');
        }
        $this->update(['status' => 'active']);
    }

    public function resetPassword()
    {
        $new_pass = str_random(8);
        $activate_key = md5(str_random(8));
        $this->update(['password' => $new_pass, 'activate_key' => $activate_key]);
        return $new_pass;
    }

    public function getWatch()
    {
        return $this->with('Fav')->get();
    }

    public function changeEmail($email, $re_email)
    {
        $data = [
            'email' => $email,
            're_email' => $re_email,
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

        $this->update(['email' => $email]);
    }

    public function updateConfirmCode()
    {
        $key = md5(time() . rand(0, 10000));
        $this->update(['activate_key' => $key]);
        return $key;
    }

    public function changePassword($current, $new, $re)
    {
        $data = [
            'current' => $current,
            'new' => $new,
            're' => $re,
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

        if (!\Hash::check($current, $this->password)) {
            throw new \Exception('Wrong Current password');
        }


        $this->update(['password' => $new]);
    }

    public function deleteAccount()
    {
        $this->update(['is_deleted' => 1]);
        if ( $this->isBusinessAccount() || $this->isPrivateAccount() ){
            $advs = $this->Adv()->get();
            foreach ($advs as $adv) {
                $adv->delete();
            }
        }
        return true;
    }

    public function blockAccount()
    {
        $date = new \DateTime();
        $this->update(['status' => 'blocked','blocked_at'=>$date->format('Y-m-d H:i:s')]);

        /*$advs = $this->Adv()->get();
        foreach ($advs as $adv) {
            $adv->delete();
        }*/
        return true;
    }

    public function changeAllowNotifications($value)
    {
        $this->update(['allow_notifications' => $value]);
    }

    public function changePayment($data)
    {
        $this->update($data);
    }

    public function setActivateStatus()
    {
        $this->update(['status' => 'active']);
    }

    public function changeContactData($data)
    {
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

    public function addFavAdv($adv_id)
    {
        $ids = $this->Fav()->pluck('adv_id')->toArray();
        array_push($ids, $adv_id);
        $this->Fav()->sync($ids);
    }

    public function removeFavAdv($adv_id)
    {
        $result = [];
        $ids = $this->Fav()->pluck('adv_id')->toArray();
        foreach ($ids as $id) {
            if ($id != $adv_id) {
                array_push($result, $id);
            }
        }
        $this->Fav()->sync($result);
    }

    public function getActualTariff()
    {
        return Tariff::getActiveTariff($this->id);
    }

    public function isPrivateAccount()
    {
        return $this->attributes['group_id'] == 2 ? true : false;
    }
    public function isBusinessAccount()
    {
        return $this->attributes['group_id'] == 3 ? true : false;
    }

    public function isAdminAccount()
    {
        return $this->attributes['group_id'] == 1 ? true : false;
    }

    public function isWaitApprove()
    {
        return $this->attributes['is_approved'] == 0 ? true : false;
    }

    public function isNotApproved()
    {
        return $this->attributes['is_approved'] == 2 ? true : false;
    }

    public function isActivated()
    {
        return ($this->attributes['status'] != 'email_confirmation' && $this->attributes['status'] != 'wait_approve') ? true : false;
    }

    public function addTariff($tariff_id)
    {
        return Tariff::addNewTariff($this->id, $tariff_id);
    }

    public function getEventsLog()
    {
        return EventsLog::getEventLogByUser($this);
    }

    public function hasPermissions($permission){
        $permissions = $this->permissions;
        if ( is_null($permissions) ){
            return false;
        }
        if( in_array($permission, $permissions)){
            return true;
        }
        return false;
    }

    public function updateAdministratorAccount($data){
        $validator = [
            'sex' => 'required',
            'name' => 'required|min:2',
            'surname' => 'required|min:2',
            //'password' => 'required|min:6',
            'email' => 'required|min:6',
        ];
        if ( isset($data['password'])){
            $validator['password'] = 'required|min:6';
        }
        $validator = \Validator::make($data, $validator);
        if ($validator->fails()) {
            $messages = $validator->messages();
            throw new \Exception($messages->first());
        }

        $count = self::where('email', $data['email'])->count();

        if ($count > 1) {
            throw new \Exception(trans('validation.exist_email'));
        }

       // $data['group_id'] = 1;
        //$data['activate_key'] = md5(time() . rand(0, 10000));
         $this->update($data);
        return true;
    }

    static function createPrivateAccount($data)
    {
        $validator = [
            'sex' => 'required',
            'name' => 'required|min:2',
            'surname' => 'required|min:2',
            'agb' => 'required',
            'password' => 'required|min:6',
            're_password' => 'required|min:6|same:password',
            'email' => 'required|min:6',
            're_email' => 'required|min:6|same:email',
            'captcha' => 'required|captcha'
        ];
        $validator = \Validator::make($data, $validator);
        if ($validator->fails()) {
            $messages = $validator->messages();
            throw new \Exception($messages->first());
        }

        $count = self::where('email', $data['email'])->count();
        if ($count > 0) {
            throw new \Exception(trans('validation.exist_email'));
        }

        $data['group_id'] = 2;
        $data['activate_key'] = md5(time() . rand(0, 10000));
        return self::create($data);

    }

    static function createBusinessAccount($data)
    {
        $validator = [
            'sex' => 'required',
            'name' => 'required|min:2',
            'surname' => 'required|min:2',
            'agb' => 'required',
            'password' => 'required|min:6',
            're_password' => 'required|min:6|same:password',
            'email' => 'required|min:6',
            're_email' => 'required|min:6|same:email',
            'captcha' => 'required|captcha',
            'company' => 'required|min:2',
            'commercial_country' => 'required',
            'commercial_id' => 'required',
            'address_city' => 'required',
            'address_number' => 'required',
            'address_street' => 'required',
            'address_zip' => 'required',

        ];
        $validator = \Validator::make($data, $validator);
        if ($validator->fails()) {
            $messages = $validator->messages();
            throw new \Exception($messages->first());
        }

        $count = self::where('email', $data['email'])->count();
        if ($count > 0) {
            throw new \Exception(trans('validation.exist_email'));
        }

        $data['group_id'] = 3;
        $data['activate_key'] = md5(time() . rand(0, 10000));
        return self::create($data);

    }

    static function createAdministratorAccount($data)
    {
        $validator = [
            'sex' => 'required',
            'name' => 'required|min:2',
            'surname' => 'required|min:2',
            'password' => 'required|min:6',
            'email' => 'required|min:6',
        ];
        $validator = \Validator::make($data, $validator);
        if ($validator->fails()) {
            $messages = $validator->messages();
            throw new \Exception($messages->first());
        }

        $count = self::where('email', $data['email'])->count();
        if ($count > 0) {
            throw new \Exception(trans('validation.exist_email'));
        }

        $data['group_id'] = 1;
        $data['activate_key'] = md5(time() . rand(0, 10000));
        $data['status'] = 'active';
        return self::create($data);

    }

    static function getAllUsers()
    {
        return self::where('is_deleted', '0')->get();
    }

    static function getAllNewBusiness()
    {
        return self::where('group_id', 3)->whereIn('status', ['wait_approve', 'email_confirmation'])->where('is_deleted', '0')->get();
    }

    static function getAllBlocked()
    {
        return self::where('status', 'blocked')->where('is_deleted', '0')->get();
    }

    static function getAllAdministration()
    {
        return self::where('group_id', 1)->where('is_deleted', '0')->get();
    }

    static function getUserByEmail($email)
    {
        $validator = [
            'email' => 'required|email',
        ];
        $validator = \Validator::make(['email' => $email], $validator);
        if ($validator->fails()) {
            $messages = $validator->messages();
            throw new \Exception($messages->first());
        }
        return self::where('email', $email)->first();
    }

    static function getUser($token = null)
    {
        try {
            $parse_token = JWTAuth::getToken();

            if ($parse_token) {
                $user = JWTAuth::setToken($parse_token)->authenticate();
            } else if (!is_null($token)) {
                $user = JWTAuth::setToken($token)->authenticate();
            } else if (isset($_COOKIE['token'])) {
                $user = JWTAuth::setToken($_COOKIE['token'])->authenticate();
            }
            if (false === $user) {
                return null;
            }
            return $user;
        } catch (\Exception $e) {
            return null;
        }

    }

    static function getUserOrDie()
    {
        $user = self::getUser();
        if (is_null($user)) {
            throw new \Exception('User not found');
        }
        return $user;

    }

    static function getUserIds()
    {
        return self::whereIn('group_id', [2, 3])->pluck('id')->toArray();
    }


    static function getTotal($filter)
    {
        $sql = self::orderBy('id', 'DESC');
        if (isset($filter['id'])) {
            $sql = $sql->where('id', $filter['id']);
        }
        if (isset($filter['company'])) {
            $sql = $sql->where('company', 'LIKE', '%' . trim($filter['company']) . '%');
        }
        if (isset($filter['commercial_id'])) {
            $sql = $sql->where('commercial_id', 'LIKE', '%' . trim($filter['commercial_id']) . '%');
        }
        if (isset($filter['name'])) {
            $sql = $sql->where('name', 'LIKE', '%' . trim($filter['name']) . '%');
        }
        if (isset($filter['surname'])) {
            $sql = $sql->where('surname', 'LIKE', '%' . trim($filter['surname']) . '%');
        }
        if (isset($filter['email'])) {
            $sql = $sql->where('email', trim($filter['email']));
        }
        if (isset($filter['address_zip'])) {
            $sql = $sql->where('address_zip', trim($filter['address_zip']));
        }
        if (isset($filter['address_city'])) {
            $sql = $sql->where('address_city', trim($filter['address_city']));
        }
        if (isset($filter['commercial_country'])) {
            $sql = $sql->where('commercial_country', trim($filter['commercial_country']));
        }
        if (isset($filter['group_id']) and sizeof($filter['group_id']) > 0) {
            $sql = $sql->whereIn('group_id', $filter['group_id']);
        }
        if (isset($filter['status']) and sizeof($filter['group_id']) > 0) {
            $sql = $sql->whereIn('status', $filter['status']);
        }

        // dd($sql->getQuery()->toSql());
        $sql = $sql->where('is_deleted', '0')->where('group_id', '>', 1);
        return
            $sql->count();
    }

    static function getByPage($page, $limit, $filter)
    {

        // dd($filter);
        $offset = ($page - 1) * $limit;
        $sql = self::orderBy('id', 'DESC');
        if (isset($filter['id'])) {
            $sql = $sql->where('id', $filter['id']);
        }
        if (isset($filter['company'])) {
            $sql = $sql->where('company', 'LIKE', '%' . trim($filter['company']) . '%');
        }
        if (isset($filter['commercial_id'])) {
            $sql = $sql->where('commercial_id', 'LIKE', '%' . trim($filter['commercial_id']) . '%');
        }
        if (isset($filter['name'])) {
            $sql = $sql->where('name', 'LIKE', '%' . trim($filter['name']) . '%');
        }
        if (isset($filter['surname'])) {
            $sql = $sql->where('surname', 'LIKE', '%' . trim($filter['surname']) . '%');
        }
        if (isset($filter['email'])) {
            $sql = $sql->where('email', trim($filter['email']));
        }
        if (isset($filter['address_zip'])) {
            $sql = $sql->where('address_zip', trim($filter['address_zip']));
        }
        if (isset($filter['address_city'])) {
            $sql = $sql->where('address_city', trim($filter['address_city']));
        }
        if (isset($filter['commercial_country'])) {
            $sql = $sql->where('commercial_country', trim($filter['commercial_country']));
        }
        if (isset($filter['group_id']) and sizeof($filter['group_id']) > 0) {
            $sql = $sql->whereIn('group_id', $filter['group_id']);
        }
        if (isset($filter['status']) and sizeof($filter['group_id']) > 0) {
            $sql = $sql->whereIn('status', $filter['status']);
        }

        // dd($sql->getQuery()->toSql());
        $sql = $sql->where('is_deleted', '0')->where('group_id', '>', 1)->offset($offset)
            ->limit($limit);
        return
            $sql->get();
    }


}
