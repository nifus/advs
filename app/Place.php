<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\AdvAddress;
use League\Flysystem\Exception;

class Place extends Model
{
    protected $fillable = [
        'city', 'country', 'region', 'zip', 'created_at', 'count_advs', 'updated_at', 'lng', 'lat', 'radius', 'google_place_id',
        'geo_ip_id'
    ];


    static function findCity($country, $region = null, $city, $place_id = null, $geo_id = null)
    {
        $place = null;
        if (!is_null($place_id)){
            $place = self::where('google_place_id', $place_id)->first();
        }
        if (!is_null($geo_id)){
            $place = self::where('geo_ip_id', $geo_id)->first();
        }
        if (is_null($place)){
            $query = self::where('city', $city)->where('country', $country);
            if (!is_null($region)) {
                $query = $query->where('region', $region);
            }
            return $query->first();
        }
        return $place;
    }

    static function findRegion($country, $region)
    {
        return self::where('region', $region)->where('country', $country)->first();
    }

    static function findCountry($country)
    {
        return self::where('country', $country)->first();
    }

    static function createCountry($country)
    {
        return self::create(['country' => $country]);
    }

    static function createRegion($country, $region)
    {
        return self::create(['country' => $country, 'region' => $region]);
    }

    static function createCity($country, $region=null, $city, $zip=null)
    {
        $result = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . $city . ',' . $country);
        if (isset($result->results[0])) {
            throw new \Exception('Bad city');
        }
        $result = json_decode($result);
        // dd($result->results[0]);
        return self::create([
            'place_id' => $result->results[0]->place_id,
            'country' => $country,
            'region' => $region,
            'city' => $city,
            'zip' => $zip,
            'lat' => $result->results[0]->geometry->location->lat,
            'lng' => $result->results[0]->geometry->location->lng,
        ]);
    }

    static function likeCities($key)
    {
        $key = trim($key);
        return self::where('city', 'LIKE', $key . '%')->
        orWhere('zip', 'LIKE', $key . '%')->
        orderBy('count_advs', 'DESC')->get(['id', 'city', 'zip', 'lat', 'lng']);
    }
}
