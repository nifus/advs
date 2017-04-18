<?php

use Illuminate\Database\Seeder;
use App\MailTemplate;

class VariablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \DB::table('variables')->truncate();


        \App\Variable::create([
            'id'=>1,
            'title' => 'varHelpEmailAddress',
            'value' => 'support@immosterne.de',
            'desc' => 'The messages from „Help“ will be sent to this
email address',
        ]);


        \App\Variable::create([
            'id'=>2,
            'title' => 'VarAgentSearchRange',
            'value' => '100',
            'desc' => 'The range in km which is currently be used in
the real estate agent search',
        ]);


    }
}
