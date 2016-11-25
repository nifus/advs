<?php

use Illuminate\Database\Seeder;
use App\Adv;
use App\Place;
use App\User;
use Intervention\Image\ImageManager;


class PlaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cities = [
            'Приозерск, Ленинградская область',
            'Кузнечное, Ленинградская область',
            'Санкт-Петербург',
            'Москва',
            'Ростов-на-Дону',
            'Севастьяново, Ленинградская область'
        ];

        $faker = Faker\Factory::create();
        $faker->addProvider(new Faker\Provider\en_US\Address($faker));


        \DB::table('places')->truncate();

        for($i=0;$i<100;$i++){
            Place::create([
                'city' => $faker->city,
                'lat' => $faker->latitude,
                'lng' => $faker->longitude,
            ]);
            //$url = "https://maps.google.com/maps/api/geocode/json?address=".urlencode($city);
            //$resp_json = file_get_contents($url);
            //$resp = json_decode($resp_json, true);
            /*if ($resp['status'] == 'OK') {

                // get the important data
                $lati = $resp['results'][0]['geometry']['location']['lat'];
                $longi = $resp['results'][0]['geometry']['location']['lng'];

                // verify if data is complete
                if ($lati && $longi) {
                    Place::create([
                        'city' => $city,
                        'lat' => $lati,
                        'lng' => $longi,
                    ]);

                }

            }*/


        }

    }
}
