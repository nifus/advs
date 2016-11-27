<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Place;
use App\MessageLog;
use App\Jobs\AdvMessage as AdvMessageJob;


class Adv extends Model
{
    protected $hidden = [
         'users_fav',
    ];
    protected $fillable = [
        'title', 'desc', 'created_at', 'type', 'updated_at', 'user_id', 'status', 'photos', 'visited', 'favorite',
        'lng','lat',

        'cold_rent', 'monthly_rent','rental_price','price_type',

        'address', 'author', 'category', 'move_date', 'is_deleted', 'energy', 'equipments', 'props', 'subcategory', 'finance', 'floor', 'floors', 'living_area','plot_area','area',
        'rooms', 'hide_contacts',
        'city_id','region_id','country_id',
        'edp_cabling','air_conditioner','number_beds','storey_height','users_fav','shop_window','development','building_permission'
    ];

    static private $categories = [
        ['id' => 1, 'title' => 'Flat', 'is_sale_only' => false, 'ic_business' => false],
        ['id' => 2, 'title' => 'House', 'is_sale_only' => false, 'ic_business' => false],
        ['id' => 3, 'title' => 'Garage / car space', 'is_sale_only' => false, 'ic_business' => false],

        ['id' => 5, 'title' => 'Building ground', 'is_sale_only' => true, 'ic_business' => false],

        ['id' => 4, 'title' => 'Office / Praxis', 'is_sale_only' => false, 'ic_business' => true],
        ['id' => 6, 'title' => 'Gastronomy / Hotel', 'is_sale_only' => false, 'ic_business' => true],
        ['id' => 7, 'title' => 'Hall / Production / Warehouse', 'is_sale_only' => false, 'ic_business' => true],
        ['id' => 8, 'title' => 'Retail trade', 'is_sale_only' => false, 'ic_business' => true],
        ['id' => 9, 'title' => 'Commercial land', 'is_sale_only' => false, 'ic_business' => true],
    ];

    static private $equipments = [
        1 => [
            "Balcony/Terrace",
            "New building",
            "Build-in kitchen",
            "Garden (shared-use)",
            "Elevator",
            "Garage/parking space",
            "Stepless access",
            "Guest toilet",
            "Cellar"
        ],
        2 => [
            "Balcony/Terrace",
            "New building",
            "Build-in kitchen",
            "Garden (shared-use)",
            "Elevator",
            "Garage/parking space",
            "Stepless access",
            "Guest toilet",
            "Cellar"
        ],
        4 => [
            "Kitchen available",
            "High voltage",
            "Cellar",
            "Stepless access",
            "Elevator"
        ],
        6 => [
            "Kitchen available",
            "High voltage",
            "Cellar",
            "Stepless access",
            "Elevator",
            "Crane runway",
            "Cold storage warehouse",
            "Terrace for guests",
        ],
        7 => [
            "Kitchen available",
            "High voltage",
            "Cellar",
            "Stepless access",
            "Elevator",
            "Ramp",
            "Hydraulic lift",
            "Freight elevator",
            "Crane runway",
        ],
        8 => [
            "Kitchen available",
            "High voltage",
            "Cellar",
            "Stepless access",
            "Elevator",
            "Ramp",
            "Freight elevator",
        ]
    ];
    static private $sub_categories = [
        1 => [
            ['title' => 'Souterrain', 'id' => 'Souterrain'],
            ['title' => 'Loft', 'id' => 'Loft'],
            ['title' => 'Top floor flat', 'id' => 'Top floor flat'],
            ['title' => 'Downstairs flat', 'id' => 'Downstairs flat'],
            ['title' => 'Maisonette', 'id' => 'Maisonette'],
            ['title' => 'Penthouse', 'id' => 'Penthouse'],
            ['title' => 'Raised ground floor', 'id' => 'Raised ground floor'],
            ['title' => 'Terrace flat', 'id' => 'Terrace flat'],
            ['title' => 'Any', 'id' => 'Any'],
        ],
        2 => [
            ['title' => 'Single-family house', 'id' => 'Single-family house'],
            ['title' => 'Mid-terrace town house', 'id' => 'Mid-terrace town house'],
            ['title' => 'End-terrace town house', 'id' => 'End-terrace town house'],
            ['title' => 'Multi-family house', 'id' => 'Multi-family house'],
            ['title' => 'Bungalow', 'id' => 'Bungalow'],
            ['title' => 'Farmhouse', 'id' => 'Farmhouse'],
            ['title' => 'Semi-detached house', 'id' => 'Semi-detached house'],
            ['title' => 'Villa', 'id' => 'Villa'],
            ['title' => 'Castle / Palace', 'id' => 'Castle / Palace'],
            ['title' => 'Any', 'id' => 'Any']
        ],
        3 => [
            ['title' => 'Outside-parking space', 'id' => 'Outside-parking space'],
            ['title' => 'Carport', 'id' => 'Carport'],
            ['title' => 'Garage', 'id' => 'Garage'],
            ['title' => 'Underground carpark', 'id' => 'Underground carpark'],
            ['title' => 'Car park', 'id' => 'Car park'],
            ['title' => 'Any', 'id' => 'Any'],

        ],
        4 => [
            ['title' => 'Loft/Studio', 'id' => 'Loft/Studio'],
            ['title' => 'Office', 'id' => 'Office'],
            ['title' => 'Praxis', 'id' => 'Praxis'],
            ['title' => 'Any', 'id' => 'Any'],
        ],
        5 => [
            ['title' => 'Buildings', 'id' => 'Buildings'],
            ['title' => 'Parking', 'id' => 'Parking'],
            ['title' => 'Garden / Farming', 'id' => 'Garden / Farming'],
            ['title' => 'Any', 'id' => 'Any'],
        ],
        6 => [
            ['title' => 'Bar/Bistro/Cafe', 'id' => 'Bar/Bistro/Cafe'],
            ['title' => 'Club/Disco', 'id' => 'Club/Disco'],
            ['title' => 'Restaurant', 'id' => 'Restaurant'],
            ['title' => 'Hotel', 'id' => 'Hotel'],
            ['title' => 'Pension', 'id' => 'Pension'],
            ['title' => 'Any', 'id' => 'Any']
        ],
        7 => [
            ['title' => 'Hall(+ open space)', 'id' => 'Hall(+ open space)'],
            ['title' => 'Warehouse(+ open space)', 'id' => 'Warehouse(+ open space)'],
            ['title' => 'Industrial building', 'id' => 'Industrial building'],
            ['title' => 'Cold-storage(+ warehouse)', 'id' => 'Cold-storage(+ warehouse)'],
            ['title' => 'Car workshop', 'id' => 'Car workshop'],
            ['title' => 'Any', 'id' => 'Any']
        ],
        8 => [
            ['title' => 'Shopping center', 'id' => 'Shopping center'],
            ['title' => 'Shop', 'id' => 'Shop'],
            ['title' => 'Kiosk', 'id' => 'Kiosk'],
            ['title' => 'Store', 'id' => 'Store'],
            ['title' => 'Any', 'id' => 'Any']
        ],
        9 => [
            ['title' => 'Buildings', 'id' => 'Buildings'],
            ['title' => 'Parking', 'id' => 'Parking'],
            ['title' => 'Garden / Farming', 'id' => 'Garden / Farming'],
            ['title' => 'Any', 'id' => 'Any'],
        ]
    ];

    public function Owner()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
    public function UsersFav()
    {
        return $this->belongsToMany('App\User', 'advs_fav', 'adv_id','user_id');
    }
    function toArray()
    {
        $array = parent::toArray();
        $array['StatusStr'] = $this->StatusStr;
        return $array;
    }
    function getArray($user_id=null)
    {
        $array = parent::toArray();
        if ( is_null($user_id)){
            $array['IsFav'] = false;
        }else{
            $array['IsFav'] = $this->isFav4user($user_id);
        }
        // $array['DeleteDate'] = $this->DeleteDate;
        //  $array['DeleteDate'] = $this->DeleteDate;
        $array['StatusStr'] = $this->StatusStr;
        return $array;
    }

    public function isFav4user($user_id){
        $ids = json_decode($this->users_fav);
        if ( !is_array($ids) ){
            return false;
        }
        if ( in_array($user_id,$ids) ){
            return true;
        }
        return false;
    }
    public function changeStatus($status)
    {
        $this->update(['status' => $status]);
    }

    public function setAddressAttribute($value)
    {
        $this->attributes['address'] = json_encode($value);
    }
    public function getAddressAttribute()
    {
        return json_decode($this->attributes['address']);
    }

    public function setAuthorAttribute($value)
    {
        $this->attributes['author'] = json_encode($value);
    }
    public function getAuthorAttribute()
    {
        return json_decode($this->attributes['author']);
    }

    public function setEnergyAttribute($value)
    {
        $this->attributes['energy'] = json_encode($value);
    }

    public function getEnergyAttribute()
    {
        return json_decode($this->attributes['energy']);
    }

    public function setEquipmentsAttribute($value)
    {
        $this->attributes['equipments'] = json_encode($value);
    }

    public function getEquipmentsAttribute()
    {
        return json_decode($this->attributes['equipments']);

    }

    public function setFinanceAttribute($value)
    {
        $this->attributes['finance'] = json_encode($value);
    }

    public function getFinanceAttribute()
    {
        return json_decode($this->attributes['finance']);
    }


    public function setPropsAttribute($value)
    {
        $this->attributes['props'] = json_encode($value);

    }

    public function getPropsAttribute()
    {
        return json_decode($this->attributes['props']);
    }




    public function setPhotosAttribute($value)
    {

        if (is_array($value)) {
            $result = [];

            foreach ($value as $image) {
                $name = time() . rand(1, 10000) . '.' . pathinfo($image['filename'], PATHINFO_EXTENSION);
                file_put_contents(public_path('uploads/adv/full/' . $this->attributes['user_id'] . '/' . $name), base64_decode($image['base64']));

                \Image::make(public_path('uploads/adv/full/' . $this->attributes['user_id'] . '/' . $name))->resize(100, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save(public_path('uploads/adv/preview/' . $this->attributes['user_id'] . '/' . $name));
                array_push($result, $name);
            }

            $result = sizeof($result)>0 ? json_encode($result) : null;
        } elseif (is_string($value)) {
            $result = $value;
        } else {
            $result = null;
        }

        $this->attributes['photos'] = $result;
    }

    public function getMainPhotoAttribute()
    {
        if (is_null($this->attributes['photos'])) {
            return ['/images/no-photo.jpg'];
        }
        $images = explode(',', $this->attributes['photos']);
        return '/uploads/adv/full/' . $this->attributes['user_id'] . '/' . $images[0];
    }

    public function getLastPhotosAttribute()
    {
        if (is_null($this->attributes['photos'])) {
            return null;
        }
        $images = explode(',', $this->attributes['photos']);
        if (sizeof($images) == 1) {
            return null;
        }
        $result = [];
        //unset($images[0]);
        foreach ($images as $photo) {
            array_push($result, '/uploads/adv/preview/' . $this->attributes['user_id'] . '/' . $photo);
        }
        return $result;
    }

    public function getPhotosAttribute()
    {
        if (is_null($this->attributes['photos'])) {
            return ['/images/no-photo.jpg'];
        }

        $photos = json_decode($this->attributes['photos']);
        $result = [];
        foreach ($photos as $photo) {
            array_push($result, '/uploads/adv/full/' . $this->attributes['user_id'] . '/' . $photo);
        }
        return $result;
    }

    public function getStatusStrAttribute()
    {
        switch ($this->attributes['status']) {
            case('payment_waiting'):
                return 'Waiting for payment';
                break;
            case('active'):
                return 'Active';
                break;
            case('disabled'):
                return 'Disabled';
                break;
            case('expired'):
                return 'Expired';
                break;
            case('blocked'):
                return 'BLOCKED';
                break;
        }
    }

    public function delete()
    {
        $this->update(['is_deleted' => '1']);
    }

    public function disable()
    {
        $this->update(['status' => 'disabled']);
    }

    public function enable()
    {
        $this->update(['status' => 'enable']);
    }

    public function isOwner($user_id)
    {
        if ($this->user_id != $user_id) {
            return false;
        }
        return true;
    }

    public function updateFavs(){
        $ids = $this->UsersFav()->pluck('user_id')->toArray();
        $this->update(['users_fav'=>json_encode($ids)]);
    }

    public function sendMessage($data, $ip){
        $validator = [
            'sex' => 'required',
            'name' => 'required|min:2',
            'email' => 'required|email',
            'message' => 'required|min:2',
        ];
        $validator = \Validator::make($data, $validator);
        if ($validator->fails()) {
            $messages = $validator->messages();
            throw new \Exception($messages->first());
        }
        if ( false===MessageLog::check($this->id, $ip) ){
            throw new \Exception('Many messages');
        }
        $log = MessageLog::createMessage($this->id, $data, $ip);
        dispatch( new AdvMessageJob($this, $log) );

        return $log;
    }

    static function getByUserWithStatus($user_id)
    {
        return self::where('user_id', $user_id)->where('is_deleted', '0')->get(['status', 'type']);
    }

    static function getByUser($user_id)
    {
        return self::where('user_id', $user_id)->where('is_deleted', '0')->get();
    }

    static function removeWatch($user_id, $adv_id)
    {
        \DB::table('advs_fav')->where('user_id', $user_id)->where('adv_id', $adv_id)->delete();
    }

    static function findOrDie($id)
    {
        $adv = self::find($id);
        if (is_null($adv)) {
            throw new \Exception('Adv not found');
        }
        return $adv;
    }

    static function createNewAdv($data, $user_id)
    {

        $data['user_id'] = $user_id;

        $data['status'] = 'payment_waiting';

        $general_riles = [
            'type' => 'required',
            'title' => 'required',
            'category' => 'required',
            'subcategory' => 'required',
            'agb' => 'required',
            'desc' => 'required',
            'address.city' => 'required',
            'address.country' => 'required',
            'address.house_number' => 'required',
            'address.street' => 'required',
            'address.zip' => 'required',
            'author.email' => 'required',
            'author.name' => 'required',
            'author.phone' => 'required',
            'author.sex' => 'required',
            'author.surname' => 'required',
            'user_id' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ];
        $validator = \Validator::make($data, $general_riles);
        if ($validator->fails()) {
            $messages = $validator->messages();
            throw new \Exception($messages->first());
        }


        $rules_by_category = [
            1 => [
                'living_area',
                'floor',
                'rooms',
                'cold_rent'
            ],
            2 => [
                'plot_area',
                'floors',
                'rooms',
                'cold_rent'
            ],
            3 => [
                'area',
                'monthly_rent'
            ],
            4 => [
                'area',
                'air_conditioner',
                'edp_cabling',
                'rooms',
                'floor',
                'price_type',
                'rental_price'
            ],
            6 => [
                'area',
                'rooms',
                'floors',
                'number_beds',
                'price_type',
                'rental_price'
            ],
            7 => [
                'area',
                'storey_height',
                'price_type',
                'rental_price'
            ]
        ];
        $validator = \Validator::make($data, $rules_by_category);
        if ($validator->fails()) {
            $messages = $validator->messages();
            throw new \Exception($messages->first());
        }

        $country = isset($data['address']['country']) ? $data['address']['country'] : null;
        $region = isset($data['address']['region']) ? $data['address']['region'] : null;
        $city = isset($data['address']['city']) ? $data['address']['city'] : null;
        $zip = isset($data['address']['zip']) ? $data['address']['zip'] : null;
        $city_place = Place::findCity($country, $region, $city);

        if ( is_null($city_place) ){
            $city_place = Place::createCity($country, $region, $city, $zip);
        }
        $data['city_id'] = $city_place->id;
        $photos = $data['photos'];
        unset($data['photos']);
        $adv = self::create($data);
        $adv->update(['photos'=>$photos]);
        return $adv;
    }



    static function getCategories()
    {
        foreach (self::$categories as $i => $category) {
            self::$categories[$i]['title'] = trans('main.category_' . self::$categories[$i]['title']);
        }
        return self::$categories;
    }

    static function getSubCategories($category = null)
    {
        if ( is_null($category) ){
            foreach (self::$sub_categories as $i => $category) {
                foreach ($category as $j => $equipment) {
                    self::$sub_categories[$i][$j]['title'] = trans('main.subcategory_' . self::$sub_categories[$i][$j]['title']);
                }
            }
            return self::$sub_categories;

        }else{
            foreach (self::$sub_categories[$category] as $j => $sub) {
                self::$sub_categories[$category][$j]['title'] = trans('main.subcategory_' . self::$sub_categories[$category][$j]['title']);
            }
            return self::$sub_categories[$category];
        }


    }

    static function getEquipments($category = null)
    {
        if ( is_null($category) ) {
            foreach (self::$equipments as $category => $equipments) {
                foreach ($equipments as $i => $equipment) {
                    self::$equipments[$category][$i] = trans('main.equipment_' . self::$equipments[$category][$i]);
                }
            }
            return self::$equipments;

        }else{
            foreach (self::$equipments[$category] as $i => $equipment) {
                self::$equipments[$category][$i] = trans('main.equipment_' . self::$equipments[$category][$i]);
            }
            return self::$equipments[$category];
        }
    }

}
