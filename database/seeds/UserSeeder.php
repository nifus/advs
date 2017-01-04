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
            'is_activated'=>'1',
            'group_id'=>'1',
            'surname'=>'Bunzya',
            'sex'=>'male',
            'password'=>'testpass',
            'activate_key'=>1
        ]);
        User::create([
                'name'=>'Alex',
                'email'=>'a.bunzya@gmail.com',
                'is_activated'=>'1',
                'group_id'=>'2',
                'surname'=>'Bunzya',
                'sex'=>'male',
                'password'=>'testpass',
                'activate_key'=>1
            ]);

    }
}
