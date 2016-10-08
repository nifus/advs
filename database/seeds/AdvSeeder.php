<?php

use Illuminate\Database\Seeder;
use App\Adv;
use App\User;
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
        require 'vendor/autoload.php';

        $faker = \Faker\Factory::create();
        $faker->addProvider(new \Faker\Provider\Lorem($faker));
        $faker->addProvider(new \Faker\Provider\DateTime($faker));
        $users = User::getUserIds();
        $images = array_diff(scandir(public_path('uploads/sample')), array('..', '.', '.gitignore'));
        \DB::table('advs')->truncate();
        \DB::table('advs_fav')->truncate();

        for ($i = 0; $i < 20; $i++) {


            $adv = Adv::create([
                'title' => $faker->sentence,
                'desc' => $faker->text,
                'created_at' => $faker->dateTimeThisMonth(),
                'total_rent' => round($faker->randomFloat(10000000), 2),
                'cold_rent' => round($faker->randomFloat(10000000), 2),
                'ancillary_cost' => round($faker->randomFloat(10000000), 2),
                'heating_cost' => round($faker->randomFloat(10000000), 2),
                'caution_money' => round($faker->randomFloat(10000000), 2),
                'address_id' => 1,
                'photos' => implode(',', $images),
                'user_id' => $faker->randomElement($users),
                'area' => $faker->randomDigitNotNull,
                'rooms' => $faker->randomDigitNotNull,
                'floor' => $faker->randomDigitNotNull,
                'floors' => $faker->randomDigitNotNull,
                'visited' => $faker->randomDigitNotNull,
                'favorite' => $faker->randomDigitNotNull,
                'status' => $faker->randomElement(['payment_waiting', 'active', 'disabled', 'expired', 'blocked']),
                'type' => $faker->randomElement(['rent', 'sell']),
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
