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

        for ($i = 0; $i < 100; $i++) {

            $category = $faker->numberBetween(1,9);
            $eq_category = $faker->randomElement([1,2,4,6,7,8]);

            $place = $faker->randomElement($places);

            $adv = Adv::create([
                'user_id' => $faker->randomElement($users),
                'status' => $faker->randomElement(['payment_waiting', 'active', 'disabled', 'expired', 'blocked']),
                'type' => $faker->randomElement(['rent', 'sale']),
                'photos' => implode(',', $images),
                'visited' => $faker->randomDigitNotNull,
                'favorite' => $faker->randomDigitNotNull,
                'move_date'=> $faker->dateTimeThisMonth(),
                'is_deleted'=> $faker->randomElement(['0', '1']),
                'title' => $faker->sentence,
                'category' => $category,
                'desc' => $faker->text,
                'props'=> '',
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


                //air_conditioner
                //edp_cabling

            ]);


            @mkdir(public_path('uploads/adv/full/' . $adv->id));
            @mkdir(public_path('uploads/adv/preview/' . $adv->id));
            foreach ($images as $image) {
                copy(public_path('uploads/sample/' . $image), public_path('uploads/adv/full/' . $adv->id . '/' . $image));
                Image::make(public_path('uploads/adv/full/' . $adv->id . '/' . $image))->resize(100, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save(public_path('uploads/adv/preview/' . $adv->id . '/' . $image));
            }



        }
        for ($i = 1; $i < 100; $i++) {
            \DB::table('advs_fav')->insert([
                    ['user_id' => 1, 'adv_id' => $i],
                ]
            );
        }
    }
}
