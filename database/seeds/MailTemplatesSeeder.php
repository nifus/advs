<?php

use Illuminate\Database\Seeder;
use App\MailTemplate;

class MailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \DB::table('mail_templates')->truncate();


        MailTemplate::create([
            'name' => 'Account created and activation link',
            'type' => 'system',
            'path' => 'emails.activatePrivateAccount'
        ]);


        MailTemplate::create([
            'name' => 'Activation link expired and new activation link',
            'type' => 'system',
            'path' => 'emails.activatePrivateAccount'
        ]);

        MailTemplate::create([
            'name' => 'Account was activated',
            'type' => 'system',
            'path' => 'emails.activatePrivateAccount'
        ]);

        MailTemplate::create([
            'name' => 'account was blocked',
            'type' => 'system',
            'path' => 'emails.activatePrivateAccount'
        ]);

        MailTemplate::create([
            'name' => 'advert was blocked',
            'type' => 'system',
            'path' => 'emails.activatePrivateAccount'
        ]);

        MailTemplate::create([
            'name' => 'advert was approved',
            'type' => 'system',
            'path' => 'emails.activatePrivateAccount'
        ]);


        MailTemplate::create([
            'name' => 'company data was changed',
            'type' => 'info',
            'path' => 'emails.activatePrivateAccount'
        ]);
        MailTemplate::create([
            'name' => 'advert was activated',
            'type' => 'info',
            'path' => 'emails.activatePrivateAccount'
        ]);
        MailTemplate::create([
            'name' => 'advert was disabled',
            'type' => 'info',
            'path' => 'emails.activatePrivateAccount'
        ]);
        MailTemplate::create([
            'name' => 'advert waiting for payment',
            'type' => 'info',
            'path' => 'emails.activatePrivateAccount'
        ]);
        MailTemplate::create([
            'name' => 'advert soon expired',
            'type' => 'info',
            'path' => 'emails.activatePrivateAccount'
        ]);
        MailTemplate::create([
            'name' => 'advert is expired',
            'type' => 'info',
            'path' => 'emails.activatePrivateAccount'
        ]);
        MailTemplate::create([
            'name' => 'advert was deleted',
            'type' => 'info',
            'path' => 'emails.activatePrivateAccount'
        ]);
        MailTemplate::create([
            'name' => 'email was changed',
            'type' => 'info',
            'path' => 'emails.activatePrivateAccount'
        ]);
        MailTemplate::create([
            'name' => 'password was changed',
            'type' => 'info',
            'path' => 'emails.activatePrivateAccount'
        ]);

    }
}
