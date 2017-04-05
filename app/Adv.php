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
        'lng', 'lat',

        'price_type', 'price', 'emphyteusis_per_year',

        'address', 'author', 'category', 'move_date', 'is_deleted', 'energy', 'equipments', 'props', 'subcategory', 'finance', 'floor', 'floors', 'living_area', 'plot_area', 'area',
        'rooms', 'hide_contacts',
        'city_id', 'region_id', 'country_id',
        'edp_cabling', 'air_conditioner', 'number_beds', 'storey_height', 'users_fav', 'length_shop_window', 'development', 'building_permission',

        'disable_date'
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
    static private $energy_source = [
        ['id' => 'Geothermal energy', 'value' => 'Geothermal energy'],
        ['id' => 'Solar', 'value' => 'Solar'],
        ['id' => 'Wood', 'value' => 'Wood'],
        ['id' => 'Gas', 'value' => 'Gas'],
        ['id' => 'Oil', 'value' => 'Oil'],
        ['id' => 'Teleheating', 'value' => 'Teleheating'],
        ['id' => 'Electricity', 'value' => 'Electricity'],
        ['id' => 'Coal', 'value' => 'Coal'],
        ['id' => 'Other', 'value' => 'Other']
    ];

    static private $heating = [
        ['id' => 'Self-contained central heating', 'value' => 'Self-contained central heating'],
        ['id' => 'Centralheating', 'value' => 'Centralheating'],
        ['id' => 'Teleheating', 'value' => 'Teleheating'],
        ['id' => 'Other', 'value' => 'Other'],
    ];

    static private $energy_class = [
        ['id' => 'Any', 'value' => 'Any'],
        ['id' => 'A+', 'value' => 'A+'],
        ['id' => 'A', 'value' => 'A'],
        ['id' => 'B', 'value' => 'B'],
        ['id' => 'C', 'value' => 'C'],
        ['id' => 'D', 'value' => 'D'],
        ['id' => 'E', 'value' => 'E'],
        ['id' => 'F', 'value' => 'F'],
        ['id' => 'G', 'value' => 'G'],
        ['id' => 'H', 'value' => 'H'],
    ];


    public function Owner()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function UsersFav()
    {
        return $this->belongsToMany('App\User', 'advs_fav', 'adv_id', 'user_id');
    }

    function toArray()
    {
        $array = parent::toArray();
        $array['StatusStr'] = $this->StatusStr;
        $array['CreateDateWithTime'] = $this->CreateDateWithTime;
        $array['DisableDateWithTime'] = $this->DisableDateWithTime;
        $array['DescWithBr'] = $this->DescWithBr;

        return $array;
    }

    function getArray($user_id = null)
    {
        $array = parent::toArray();
        if (is_null($user_id)) {
            $array['IsFav'] = false;
        } else {
            $array['IsFav'] = $this->isFav4user($user_id);
        }
        // $array['DeleteDate'] = $this->DeleteDate;
        $array['DescWithBr'] = $this->DescWithBr;
        $array['StatusStr'] = $this->StatusStr;
        return $array;
    }

    public function isFav4user($user_id)
    {
        $ids = json_decode($this->users_fav);
        if (!is_array($ids)) {
            return false;
        }
        if (in_array($user_id, $ids)) {
            return true;
        }
        return false;
    }

    public function changeStatus($status)
    {
        $this->update(['status' => $status]);
    }

    public function getLastPayment()
    {
        return AdvPayment::getLastPayment($this->id);
    }

    public function activate(AdvPayment $payment)
    {
        //$now = new \DateTime();
        $disable_date = new \DateTime();
        if ($this->Owner->isPrivateAccount()) {
            $tariff = $payment->PrivateTariff;

            $disable_date->modify('+' . $tariff->duration);
        } else {
            $tariff = $payment->BusinessTariff;
            //TODO add for business tariff
        }


        $this->update(['status' => 'active', 'disable_date' => $disable_date->format('Y-m-d H:i:s')]);
        return true;
    }

    public function getDescWithBrAttribute()
    {
        return nl2br(htmlspecialchars($this->attributes['desc']));

    }

    public function getCreateDateWithTimeAttribute()
    {
        $date = new \DateTime($this->created_at);
        return $date->format('d-m-y H:i');
    }

    public function getDisableDateWithTimeAttribute()
    {
        $date = new \DateTime($this->disable_date);
        return $date->format('d-m-y H:i');
    }


    public function setMoveDateAttribute($value)
    {
        $date = new \DateTime($value);
        $this->attributes['move_date'] = $date->format('Y-m-d');
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


    public function getPhotosAttribute()
    {
        return json_decode($this->attributes['photos']);
    }

    public function setPhotosAttribute($value)
    {
        $this->attributes['photos'] = json_encode($value);
    }

    public function getMainPhotoAttribute()
    {
        if (is_null($this->attributes['photos'])) {
            return ['preview' => '/images/no-photo.jpg'];
        }
        $images = $this->photos;
        return $images[0];
    }

    /*public function getLastPhotosAttribute()
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
    }*/


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

    public function updateFavs()
    {
        $ids = $this->UsersFav()->pluck('user_id')->toArray();
        $this->update(['users_fav' => json_encode($ids)]);
    }

    public function sendMessage($data, $ip)
    {
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
        if (false === MessageLog::check($this->id, $ip)) {
            throw new \Exception('Many messages');
        }

        $log = MessageLog::createMessage($this->id, $data, $ip);
        dispatch(new AdvMessageJob($this, $log));
        return $log;
    }

    public function itsAuthor($user_id)
    {
        if ($this->attributes['user_id'] == $user_id) {
            return true;
        }
        return false;
    }

    public function updateAdv($data)
    {
        self::advValidation($data);
        if ($this->attributes['status'] == 'blocked') {
            $data['status'] = 'approve_waiting';
        }
        $this->update($data);
    }

    static function getWithStatus()
    {
        return self::where('is_deleted', '0')->get(['status', 'type']);
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

    static function advValidation($data)
    {
        $general_rules = [
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
        $validator = \Validator::make($data, $general_rules);
        if ($validator->fails()) {
            $messages = $validator->messages();
            throw new \Exception($messages->first());
        }


        $rules_by_category = [
            1 => [
                'living_area',
                'floor',
                'rooms',
                'price'
            ],
            2 => [
                'plot_area',
                'floors',
                'rooms',
                'price'
            ],
            3 => [
                'area',
                'price'
            ],
            4 => [
                'area',
                'air_conditioner',
                'edp_cabling',
                'rooms',
                'floor',
                'price_type',
                'price'
            ],
            6 => [
                'area',
                'rooms',
                'floors',
                'number_beds',
                'price_type',
                'price'
            ],
            7 => [
                'area',
                'storey_height',
                'price_type',
                'price'
            ]
        ];
        $validator = \Validator::make($data, $rules_by_category);
        if ($validator->fails()) {
            $messages = $validator->messages();
            throw new \Exception($messages->first());
        }
        return true;
    }

    static function createNewAdv($data, $user_id)
    {

        $data['user_id'] = $user_id;

        $data['status'] = 'payment_waiting';

        self::advValidation($data);

        $country = isset($data['address']['country']) ? $data['address']['country'] : null;
        $region = isset($data['address']['region']) ? $data['address']['region'] : null;
        $city = isset($data['address']['city']) ? $data['address']['city'] : null;
        $zip = isset($data['address']['zip']) ? $data['address']['zip'] : null;
        $city_place = Place::findCity($country, $region, $city);

        if (is_null($city_place)) {
            $city_place = Place::createCity($country, $region, $city, $zip);
        }
        $data['city_id'] = $city_place->id;
        $photos = $data['photos'];
        unset($data['photos']);
        $adv = self::create($data);
        $adv->update(['photos' => $photos]);
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
        if (is_null($category)) {
            foreach (self::$sub_categories as $i => $category) {
                foreach ($category as $j => $equipment) {
                    self::$sub_categories[$i][$j]['title'] = trans('main.subcategory_' . self::$sub_categories[$i][$j]['title']);
                }
            }
            return self::$sub_categories;

        } else {
            foreach (self::$sub_categories[$category] as $j => $sub) {
                self::$sub_categories[$category][$j]['title'] = trans('main.subcategory_' . self::$sub_categories[$category][$j]['title']);
            }
            return self::$sub_categories[$category];
        }


    }

    static function getEquipments($category = null)
    {
        if (is_null($category)) {
            foreach (self::$equipments as $category => $equipments) {
                foreach ($equipments as $i => $equipment) {
                    self::$equipments[$category][$i] = trans('main.equipment_' . self::$equipments[$category][$i]);
                }
            }
            return self::$equipments;

        } else {
            foreach (self::$equipments[$category] as $i => $equipment) {
                self::$equipments[$category][$i] = trans('main.equipment_' . self::$equipments[$category][$i]);
            }
            return self::$equipments[$category];
        }
    }

    static function getEnergySource()
    {
        foreach (self::$energy_source as $i => $source) {
            self::$energy_source[$i]['value'] = trans('main.energy_source_' . self::$energy_source[$i]['value']);
        }
        return self::$energy_source;
    }
    static function getHeatingSource()
    {
        foreach (self::$heating as $i => $source) {
            self::$heating[$i]['value'] = trans('main.heating_' . self::$heating[$i]['value']);
        }
        return self::$heating;
    }
    static function getEnergyClassSource()
    {
        foreach (self::$energy_class as $i => $source) {
            self::$energy_class[$i]['value'] = trans('main.energy_class_' . self::$energy_class[$i]['value']);
        }
        return self::$energy_class;
    }

    static function getByPage($page, $limit, $filter)
    {

        // dd($filter);


        $sql = self::with('Owner')->whereHas('Owner', function ($query) use ($filter) {

            if (isset($filter['account']) && $filter['account'] == 'private') {
                $query->where('group_id', 2);
            } elseif (isset($filter['account']) && $filter['account'] == 'business') {
                $query->where('group_id', 3);
            }
            if (isset($filter['email'])) {
                $query->where('email', $filter['email']);
            }


        })->orderBy('id', 'DESC');
        if (isset($filter['id'])) {
            $sql = $sql->where('id', $filter['id']);
        }
        if (isset($filter['user_id'])) {
            $sql = $sql->where('user_id', $filter['user_id']);
        }
        if (isset($filter['category'])) {
            $sql = $sql->where('category', $filter['category']);
        }
        if (isset($filter['type']) && $filter['type'] != 'all') {
            $sql = $sql->where('type', $filter['type']);
        }
        if (isset($filter['statuses']) && !in_array('all', $filter['statuses'])) {
            $sql = $sql->whereIn('status', $filter['statuses']);
        }

        $sql = $sql->where('is_deleted', '0');


        if (!is_null($page) && !is_null($limit)) {
            $offset = ($page - 1) * $limit;
            $sql = $sql->offset($offset)->limit($limit);
        }

        return $sql->get();
    }


}
