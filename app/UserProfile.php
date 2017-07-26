<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    public $table = 'users_profile';

    protected $fillable = [
        'user_id', 'created_at', 'updated_at', 'specializations', 'services', 'company_data_source', 'company_data', 'person_data_source', 'person_data', 'about','logo'
    ];

    public function getLogoAttribute()
    {
        if ( !empty($this->attributes['logo'])){
            return ('/uploads/profile/' .$this->attributes['logo']);
        }

    }

    public function setLogoAttribute($value)
    {
        if (is_array($value) && isset($value[0]) && isset($value[0]['base64'])) {

            $name = time() . rand(1, 10000) . '.' . pathinfo($value[0]['filename'], PATHINFO_EXTENSION);
            file_put_contents(public_path('uploads/profile/' . $name), base64_decode($value[0]['base64']));
            $result = $name;
            $this->attributes['logo'] = $value[0]['filename'];

        } elseif (is_array($value) && isset($value[0]) && is_string($value[0])) {

            $result = basename($value[0]);
        } else {
            $result = null;
        }
        $this->attributes['logo'] = $result;

    }

    public function getPersonDataAttribute()
    {
        return json_decode($this->attributes['person_data']);
    }

    public function setPersonDataAttribute($value)
    {
        $this->attributes['person_data'] = json_encode($value);
    }

    public function getCompanyDataAttribute()
    {
        return json_decode($this->attributes['company_data']);
    }

    public function setCompanyDataAttribute($value)
    {
        $this->attributes['company_data'] = json_encode($value);
    }

    public function getSpecializationsAttribute()
    {
        return json_decode($this->attributes['specializations']);
    }

    public function setSpecializationsAttribute($value)
    {
        $this->attributes['specializations'] = json_encode($value);
    }

    public function getServicesAttribute()
    {
        return json_decode($this->attributes['services']);
    }

    public function setServicesAttribute($value)
    {
        $this->attributes['services'] = json_encode($value);
    }

    static function createProfile(User $user, $data){
        $data['user_id'] = $user->id;
        return self::create($data);
    }

}
