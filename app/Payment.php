<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Place;
use App\MessageLog;
use App\Jobs\AdvMessage as AdvMessageJob;


class Payment extends Model
{

    protected $fillable = [
        'adv_id', 'tariff_id', 'type', 'way', 'status', 'payment_log', 'guid', 'paypal_email', 'giro', 'price', 'created_at',
        'updated_at','user_id','slots'
    ];

    public $table = 'payments';

    public function Advert()
    {
        return $this->hasOne('App\Adv', 'id', 'adv_id');
    }

    public function User()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function PrivateTariff()
    {
        return $this->hasOne('App\PrivateTariff', 'id', 'tariff_id');
    }

    public function BusinessTariff()
    {
        return $this->hasOne('App\BusinessTariff', 'id', 'tariff_id');
    }

    public function success()
    {
        $this->update(['status' => 'success']);
        if ($this->type=='advert'){
            $advert = $this->Advert;
            $advert->activate($this);
        }elseif ($this->type=='subscription'){
            $this->User->setSubscription($this->tariff_id);
        }elseif ($this->type=='slot'){
            $this->User->addExtraSlots($this->slots);
        }
        return true;
    }

    public function fail()
    {
        $this->update(['status' => 'error']);
        return true;

    }

    static function getLastPayment($adv_id)
    {
        return self::where('adv_id', $adv_id)->orderBy('id', 'DESC')->first();
    }

    static function createNewPayment($type, User $user, $way, $data)
    {
        if ($way == 'giro') {
            return self::createGiroPayment($user, $type, $data);
        } elseif ($way == 'paypal') {
            return self::createPaypalPayment($user, $type, $data);
        } elseif ($way == 'pre-payment') {
            return self::createPrePayment($user, $type, $data);
        }
        return false;
    }

    static function createGiroPayment(User $user, $type, $data)
    {

        $validator = [
            //'adv_id' => 'required',
            'price' => 'required',
            'account' => 'required',
            'tariff_id' => 'required',
        ];
        if ($type == 'advert') {
            $validator['advert_id'] = 'required';
        } elseif ($type == 'slot') {
            $validator['slots'] = 'required';
        }

        $validator = \Validator::make($data, $validator);
        if ($validator->fails()) {
            $messages = $validator->messages();
            throw new \Exception($messages->first());
        }
        return self::create([
            'user_id' => $user->id,
            'type' => $type,
            'way' => 'giro',
            'giro' => $data['account'],
            'tariff_id' => $data['tariff_id'],
            'price' => $data['price'],
            'adv_id' => $data['advert_id'],
            'status' => 'wait',
            'slots' => isset($data['slots']) ? $data['slots'] : null,

        ]);
    }

    static function createPaypalPayment(User $user, $type, $data)
    {
        $validator = [
            //'adv_id' => 'required',
            'price' => 'required',
            'email' => 'required',
            'tariff_id' => 'required',
        ];
        if ($type == 'advert') {
            $validator['advert_id'] = 'required';
        } elseif ($type == 'slot') {
            $validator['slots'] = 'required';
        }
        $validator = \Validator::make($data, $validator);
        if ($validator->fails()) {
            $messages = $validator->messages();
            throw new \Exception($messages->first());
        }
        return self::create([
            'user_id' => $user->id,
            'type' => $type,
            'way' => 'paypal',
            'paypal_email' => $data['email'],
            'tariff_id' => $data['tariff_id'],
            'price' => $data['price'],
            'adv_id' => $data['advert_id'],
            'slots' => isset($data['slots']) ? $data['slots'] : null,
            'status' => 'wait'
        ]);
    }

    static function createPrePayment(User $user, $type, $data)
    {
        $validator = [
            //'adv_id' => 'required',
            'price' => 'required',
            'guid' => 'required',
            'tariff_id' => 'required',
        ];
        if ($type == 'advert') {
            $validator['advert_id'] = 'required';
        } elseif ($type == 'slot') {
            $validator['slots'] = 'required';
        }
        $validator = \Validator::make($data, $validator);
        if ($validator->fails()) {
            $messages = $validator->messages();
            throw new \Exception($messages->first());
        }
        return self::create([
            'user_id' => $user->id,
            'type' => $type,
            'way' => 'prepayment',
            'guid' => $data['guid'],
            'tariff_id' => $data['tariff_id'],
            'price' => $data['price'],
            'adv_id' => $data['advert_id'],
            'status' => 'wait',
            'slots' => isset($data['slots']) ? $data['slots'] : null,

        ]);
    }

    static function getByPage($page, $limit, $filter)
    {

        $sql = self::orderBy('id', 'DESC');
        if (isset($filter['id'])) {
            $sql = $sql->where('id', $filter['id']);
        }

        if (!is_null($page) && !is_null($limit)) {
            $offset = ($page - 1) * $limit;
            $sql = $sql->offset($offset)->limit($limit);
        }
        return $sql->get();
    }
}
