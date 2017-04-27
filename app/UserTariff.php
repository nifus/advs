<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTariff extends Model
{
    public $table = 'users_tariffs';

    protected $fillable = [
        'user_id', 'created_at', 'updated_at', 'tariff_id', 'is_future', 'is_paid', 'begin_time', 'end_time', 'slots', 'extra', 'price', 'is_end'
    ];

    public function Details()
    {
        return $this->hasMany('App\UserTariffSlot');
    }

    public function TariffDetails()
    {
        return $this->hasOne('App\BusinessTariff', 'id', 'tariff_id');
    }

    public function getSlots()
    {
        return UserTariffSlot::getSlots($this->user_id, $this->id);
    }

    public function addExtraSlots($slots)
    {
        for ($i = 0; $i < $slots; $i++) {
            UserTariffSlot::createSlot($this->user_id, $this->id, '1');
        }
        return true;
    }

    public function end()
    {
        $this->update(['is_end'=>'1']);
       // $slots = $this->getSlots();
       // foreach ($slots as $slot){
        //    $slot->
       // }
    }

    public function doCurrentTariff(){
        $this->update(['is_future'=>'0']);
    }

    static function addNewTariff(User $user, $tariff_id, $is_future = '0')
    {
        $date = new \DateTime();
        if ($user->isBusinessAccount()) {
            $tariff = BusinessTariff::find($tariff_id);
            $t = self::create([
                'user_id' => $user->id,
                'begin_time' => $date->format('Y-m-d H:i:s'),
                'end_time' => $date->modify('+1 month')->format('Y-m-d H:i:s'),
                'is_paid' => '1',
                'is_future' => $is_future,
                'tariff_id' => $tariff->id,
                'price' => $tariff->price,
                'extra' => $tariff->price_extra_slots,
                'slots' => $tariff->number_of_slots,
            ]);
            if ($is_future == '0') {
                for ($i = 0; $i < $t->slots; $i++) {
                    UserTariffSlot::createSlot($user->id, $t->id);
                }
            }

        } else {
            $tariff = PrivateTariff::find($tariff_id);
        }

        return $t;
    }

    static function getCurrentTariff($user_id)
    {
        $date = new \DateTime();
        $now = $date->format('Y-m-d H:i:s');

        return self::with('TariffDetails')->where('user_id', $user_id)
           // ->where('begin_time', '<=', $now)
           //// ->where('end_time', '>', $now)
            ->where('is_end', '0')
            ->where('is_paid', '1')
            ->where('is_future', '0')
            ->first();
    }

    static function getFutureTariff($user_id)
    {
        //$date = new \DateTime();
        // $now = $date->format('Y-m-d H:i:s');

        return self::with('TariffDetails')->where('user_id', $user_id)
            //->where('begin_time','<=', $now)
            //->where('end_time','>', $now)
            ->where('is_paid', '1')
            ->where('is_future', '1')
            ->where('is_end', '0')
            ->first();
    }
}
