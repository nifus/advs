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


        MailTemplate::createTemplate([
            'id'=>1,
            'name' => 'Private account activation link',
            'type' => 'system',
            'path' => 'confirmEmailPrivateAccount',
            'header'=>'Confirm you registration',
            'body'=> 'Good Day [varContactForename] [varContactSurename]! <br><br>
You activation link: <br>
<a href="[varAccountActivationLink]" target="_blank">[varAccountActivationLink]</a>'
        ]);


        MailTemplate::createTemplate([
            'id'=>2,
            'name' => 'Business account confirm link',
            'type' => 'system',
            'header'=>'Confirm you registration',
            'path' => 'confirmEmailBusinessAccount',
            'body'=> 'Good Day [varContactForename] [varContactSurename]! <br><br>
You confirm link: <br>
<a href="[varAccountActivationLink]" target="_blank">[varAccountActivationLink]</a>'
        ]);

        MailTemplate::createTemplate([
            'id'=>3,
            'name' => 'Private account activated',
            'type' => 'system',
            'path' => 'activatePrivateAccount',
            'header'=>'You registration in portal',
            'body'=> 'Good Day [varContactForename] [varContactSurename]! <br><br>
You login: [varAccountEmail]'
        ]);
        MailTemplate::createTemplate([
            'id'=>4,
            'name' => 'Business account activated',
            'type' => 'system',
            'path' => 'activateBusinessAccount',
            'header'=>'You registration in portal',
            'body'=> 'Good Day [varContactForename] [varContactSurename]! <br><br>
You login: [varAccountEmail]'
        ]);

        /*MailTemplate::createTemplate([
            'name' => 'Activation link expired and new activation link',
            'type' => 'system',
            'path' => 'activatePrivateAccount'
        ]);*/

        MailTemplate::createTemplate([
            'name' => 'Account was activated',
            'type' => 'system',
            'path' => 'activatedBusinessAccount',
            'body'=>'text'
        ]);

        MailTemplate::createTemplate([
            'name' => 'Account was blocked',
            'type' => 'system',
            'path' => 'accountBlocked',
            'body'=>'text'
        ]);

        MailTemplate::createTemplate([
            'name' => 'Advert was blocked',
            'type' => 'system',
            'path' => 'advertBlocked',
            'body'=>'text'
        ]);

        MailTemplate::createTemplate([
            'name' => 'Advert was approved',
            'type' => 'system',
            'path' => 'advertApproved',
            'body'=>'text'
        ]);


        MailTemplate::createTemplate([
            'name' => 'Company data was changed',
            'type' => 'info',
            'path' => 'companyDataWasChanged',
            'body'=>'text'
        ]);
        MailTemplate::createTemplate([
            'name' => 'Advert was activated',
            'type' => 'info',
            'path' => 'advertActivated',
            'body'=>'text'
        ]);
        MailTemplate::createTemplate([
            'name' => 'Advert was disabled',
            'type' => 'info',
            'path' => 'advertDisabled',
            'body'=>'text'
        ]);
        MailTemplate::createTemplate([
            'name' => 'advert waiting for payment',
            'type' => 'info',
            'path' => 'advertWaitingPayment',
            'body'=>'text'
        ]);
        MailTemplate::createTemplate([
            'name' => 'Advert soon expired',
            'type' => 'info',
            'path' => 'advertSoonExpired',
            'body'=>'text'
        ]);
        MailTemplate::createTemplate([
            'name' => 'Advert is expired',
            'type' => 'info',
            'path' => 'advertExpired',
            'body'=>'text'
        ]);
        MailTemplate::createTemplate([
            'name' => 'Advert was deleted',
            'type' => 'info',
            'path' => 'advertDeleted',
            'body'=>'text'
        ]);
        MailTemplate::createTemplate([
            'name' => 'Email was changed',
            'type' => 'info',
            'path' => 'emailChanged',
            'body'=>'text'
        ]);
        MailTemplate::createTemplate([
            'name' => 'password was changed',
            'type' => 'info',
            'path' => 'passwordChanged',
            'body'=>'text'
        ]);

    }
}
