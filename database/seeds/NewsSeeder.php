<?php

use Illuminate\Database\Seeder;
use App\News;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('news')->truncate();

        $faker = \Faker\Factory::create();
        $faker->addProvider(new \Faker\Provider\Lorem($faker));
        $faker->addProvider(new \Faker\Provider\DateTime($faker));

        for( $i=0;$i<100;$i++){
            News::create([
                'title'=>$faker->sentence,
                'desc'=>$faker->paragraph,
                'created_at'=>$faker->dateTimeThisMonth(),
                'type'=>$faker->randomElement(['private','business']),
            ]);
        }
    }
}
