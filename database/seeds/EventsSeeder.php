<?php

use Illuminate\Database\Seeder;
use App\User;

class EventsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        \DB::table('events_log')->truncate();
        $users = User::getAllUsers();
        foreach($users as $user){
            for($i=0;$i<100;$i++){
                \App\EventsLog::create([
                    'user_id'=>$user->id,
                    'type'=>$faker->randomElement(['system', 'mail', 'owner', 'initials']),
                    'action'=>$faker->randomElement(['createAccount', 'accountEmailIsConfirmed', 'accountIsActivated']),
                ]);
            }
        }




    }
}
