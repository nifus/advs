<?php

use Illuminate\Database\Seeder;
use App\Adv;
use App\User;
use App\Place;
use Intervention\Image\ImageManager;


class AdvSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $faker = \Faker\Factory::create();
        $faker->addProvider(new \Faker\Provider\Lorem($faker));
        $faker->addProvider(new \Faker\Provider\DateTime($faker));
        $users = User::getUserIds();
        $places= Place::get()->toArray();
        $images = array_diff(scandir(public_path('uploads/sample')), array('..', '.', '.gitignore'));

        \DB::table('advs')->truncate();
        \DB::table('advs_fav')->truncate();

        for ($i = 0; $i < 1500; $i++) {

            $category = $faker->numberBetween(1,9);
            $eq_category = $faker->randomElement([1,2,4,6,7,8]);
            $user = $faker->randomElement($users);

            @mkdir(public_path('uploads/adv/full/' . $user));
            @mkdir(public_path('uploads/adv/preview/' . $user));
            foreach ($images as $image) {
                copy(public_path('uploads/sample/' . $image), public_path('uploads/adv/full/' . $user . '/' . $image));
                Image::make(public_path('uploads/adv/full/' . $user . '/' . $image))->resize(100, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save(public_path('uploads/adv/preview/' . $user . '/' . $image));
            }

            $place = $faker->randomElement($places);

            $adv = Adv::create([
                'user_id' => $user,
                'status' => $faker->randomElement(['payment_waiting', 'active', 'disabled', 'expired', 'blocked']),
                'type' => $faker->randomElement(['rent', 'sale']),
                'photos' => json_encode($images),
                'visited' => $faker->randomDigitNotNull,
                'favorite' => $faker->randomDigitNotNull,
                'move_date'=> $faker->dateTimeThisMonth(),
                'is_deleted'=> $faker->randomElement(['0', '1']),
                'title' => $faker->sentence,
                'category' => $category,
                'desc' => $faker->text,
                'subcategory'=> $faker->randomElement(Adv::getSubCategories($category))['id'],
                'equipments'=> $faker->randomElements( Adv::getEquipments($eq_category) ),
                'created_at' => $faker->dateTimeThisMonth(),
                'updated_at' => $faker->dateTimeThisMonth(),
                'living_area' => $faker->randomDigitNotNull,
                'plot_area' => $faker->randomDigitNotNull,
                'area' => $faker->randomDigitNotNull,
                'floor' => $faker->randomDigitNotNull,
                'floors' => $faker->randomDigitNotNull,
                'rooms' => $faker->randomDigitNotNull,
                'number_beds' => $faker->randomDigitNotNull,

                'cold_rent' => round($faker->randomFloat(10000000), 2),
                'monthly_rent' => round($faker->randomFloat(10000000), 2),
                'rental_price' => round($faker->randomFloat(10000000), 2),
                'storey_height' => round($faker->randomFloat(10000000), 2),
                'price_type' => 'Price per month',
                'lng' => $place['lng'],
                'lat' => $place['lat'],
                'city_id' => $place['id'],

                'address'=>[
                    "city"=>"Приозерск",
                    "region"=>"Ленинградская область",
                    "country"=>"RU",
                    "house_number"=>"37",
                    "street"=>"Чапаева",
                    "zip"=>"610035",
                    "distances"=>[
                        "transport"=>1,
                        "driving"=>2,
                        "autoban"=>3,
                        "airport"=>4
                    ],
                    "display_house"=>true
                ],
                "energy"=>[
                    "class"=>"Any",
                    "source"=>"Geothermal energy",
                    "modernization_year"=>1,
                    "heating"=>"Self-contained central heating",
                    "pass"=>"Available",
                    "pass_date"=>"Till 30.04.14 (EnEV 2009)",
                    "pass_type"=>"Requirement pass",
                    "consumption"=>"1234"
                ],
                "author"=>[
                    "sex"=>"male",
                    "name"=>"Alex",
                    "surname"=>"Bunzya",
                    "email"=>"a.bunzya@gmail.com",
                    "phone"=>"1231321222",
                    "hide_contacts"=>true,
                    "IsPrivate"=> $faker->randomElement([true, false])
                ],
                "finance"=>[
                    "ancillary_cost_included"=>"0",
                    "no_ancillary_cost"=>true,
                    "no_caution_money"=>false,
                    "ancillary_cost"=>2,
                    "heating_cost"=>3,
                    "total_cost"=>4,
                    "caution_money"=>5
                ],
                "props"=>[
                    "pets"=>"Any",
                    "floor_cover"=>"Any",
                    "build_year"=>1222,
                    "number_of_garage"=>5,
                    "garage"=>"Duplex"
                ]

                //air_conditioner
                //edp_cabling

            ]);






        }
        for ($i = 1; $i < 100; $i++) {
            \DB::table('advs_fav')->insert([
                    ['user_id' => 1, 'adv_id' => $i],
                ]
            );
        }
    }
}
