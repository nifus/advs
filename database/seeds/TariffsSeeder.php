<?php

use Illuminate\Database\Seeder;
use App\User;

class TariffsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \DB::table('business_tariffs')->truncate();
        \DB::table('private_tariffs')->truncate();

        $tariffs = [
            ['id'=>1, 'title'=>'Pack 1', 'number_of_slots'=>1, 'price'=>30, 'price_extra_slots'=>30],
            ['id'=>2, 'title'=>'Pack 2', 'number_of_slots'=>2, 'price'=>55, 'price_extra_slots'=>27.5],
            ['id'=>3, 'title'=>'Pack 5', 'number_of_slots'=>5, 'price'=>125, 'price_extra_slots'=>25],
            ['id'=>4, 'title'=>'Pack 10', 'number_of_slots'=>10, 'price'=>225, 'price_extra_slots'=>22.5],
            ['id'=>5, 'title'=>'Pack 20', 'number_of_slots'=>20, 'price'=>400, 'price_extra_slots'=>20],
            ['id'=>6, 'title'=>'Pack 30', 'number_of_slots'=>30, 'price'=>525, 'price_extra_slots'=>17.5],
            ['id'=>7, 'title'=>'Pack 40', 'number_of_slots'=>40, 'price'=>600, 'price_extra_slots'=>15],
        ];
        foreach($tariffs as $tariff){
            \App\BusinessTariff::create($tariff);
        }

        $tariffs = [
            ['id'=>1, 'duration'=>'1 week', 'rent_price'=>30, 'sale_price'=>40],
            ['id'=>2, 'duration'=>'2 weeks',  'rent_price'=>60, 'sale_price'=>90],
            ['id'=>3, 'duration'=>'1 month',  'rent_price'=>90, 'sale_price'=>80],
            ['id'=>4, 'duration'=>'2 months',  'rent_price'=>120, 'sale_price'=>110],
            ['id'=>5, 'duration'=>'3 months',  'rent_price'=>160, 'sale_price'=>170],

            ];
        foreach($tariffs as $tariff){
            \App\PrivateTariff::create($tariff);
        }

    }
}
