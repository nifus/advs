<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \DB::table('users')->truncate();

        User::create([
            'name'=>'Admin',
            'email'=>'admin@gmail.com',
           // 'is_activated'=>'1',
            'group_id'=>'1',
            'surname'=>'Bunzya',
            'sex'=>'male',
            'password'=>'testpass',
            'activate_key'=>1,
            'status'=>'active',
            'permissions'=>["portal","advert","accounts","administration","payment","prices","statistics","mailing"]
        ]);
        User::create([
            'name'=>'Admin',
            'email'=>'nifus@mail.ru',
            //'is_activated'=>'1',
            'status'=>'active',
            'group_id'=>'1',
            'surname'=>'No Any Access',
            'sex'=>'male',
            'password'=>'testpass',
            'activate_key'=>1,
            'permissions'=>null
        ]);
        User::create([
                'name'=>'Alex',
                'email'=>'a.bunzya@gmail.com',
               // 'is_activated'=>'1',
                'status'=>'active',
                'group_id'=>'2',
                'surname'=>'Bunzya',
                'sex'=>'male',
                'password'=>'testpass',
                'activate_key'=>1
            ]);

        $faker = Faker\Factory::create();
        $faker->addProvider(new Faker\Provider\en_US\Person($faker));
        $faker->addProvider(new Faker\Provider\Internet($faker));
        $faker->addProvider(new Faker\Provider\en_US\Company($faker));
        $faker->addProvider(new Faker\Provider\en_US\PhoneNumber($faker));
        $faker->addProvider(new Faker\Provider\Internet($faker));
        $faker->addProvider(new Faker\Provider\en_US\Address($faker));


//
        for($i=0;$i<100;$i++){
            User::create([
                'name'=>$faker->firstName,
                'email'=>$faker->email,
                'group_id'=>$faker->randomElement([2,3]),
                'surname'=>$faker->lastName,
                'sex'=>$faker->randomElement(['male', 'female']),
                'password'=>'testpass',
                'company'=>$faker->company,
                'contact_email'=>$faker->email,
                'payment_type'=>$faker->randomElement(['paypal', 'giropay', 'prepayment']),
                'phone'=>$faker->phoneNumber,
                'tariff'=>$faker->randomDigit(1,7),
                'website'=>$faker->url,
                'commercial_country'=>$faker->country,
              //  'commercial_id'=>$faker->company,
                'address_city'=>$faker->city,
                'address_zip'=>$faker->postcode,
                'address_number'=>$faker->buildingNumber,
                'address_street'=>$faker->streetName,
                'status'=>$faker->randomElement(['active','email_confirmation','wait_approve','blocked']),
                'activate_key'=>1


            ]);
        }

    }
}
