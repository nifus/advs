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
            'id' => 1,
            'name' => 'Private account activation link',
            'type' => 'system',
            'path' => 'confirmEmailPrivateAccount',
            'header' => 'Confirm you registration',
            'body' => 'Good Day [varContactTitle] [varContactForename] [varContactSurname]! <br><br>
You activation link: <br>
<a href="[varAccountActivationLink]" target="_blank">[varAccountActivationLink]</a>',
            'variables' => 'varContactTitle|varContactForename|varContactSurname|varAccountActivationLink'
        ]);


        MailTemplate::createTemplate([
            'id' => 2,
            'name' => 'Business account confirm link',
            'type' => 'system',
            'header' => 'Confirm you registration',
            'path' => 'confirmEmailBusinessAccount',
            'body' => 'Good Day [varContactTitle] [varContactForename] [varContactSurname]! <br><br>
You confirm link: <br>
<a href="[varAccountConfirmLink]" target="_blank">[varAccountConfirmLink]</a>',
            'variables' => 'varContactTitle|varContactForename|varContactSurname|varAccountConfirmLink'

        ]);

        MailTemplate::createTemplate([
            'id' => 3,
            'name' => 'Private account activated',
            'type' => 'system',
            'path' => 'activatePrivateAccount',
            'header' => 'You registration in portal',
            'body' => 'Good Day [varContactTitle] [varContactForename] [varContactSurname]! <br><br>
You login: [varAccountEmail]',
            'variables' => 'varContactTitle|varContactForename|varContactSurname|varAccountEmail'
        ]);
        MailTemplate::createTemplate([
            'id' => 4,
            'name' => 'Business account activated',
            'type' => 'system',
            'path' => 'activateBusinessAccount',
            'header' => 'You registration in portal',
            'body' => 'Good Day [varContactTitle] [varContactForename] [varContactSurname]! <br><br>
You login: [varAccountEmail]',
            'variables' => 'varContactTitle|varContactForename|varContactSurname|varAccountEmail'
        ]);

        MailTemplate::createTemplate([
            'id' => 5,
            'header' => 'Email was changed',
            'name' => 'Email was changed',
            'type' => 'info',
            'path' => 'emailChanged',
            'body' => 'Good Day [varContactTitle] [varContactForename] [varContactSurname]! <br><br>Email was changed',
            'variables' => 'varContactTitle|varContactForename|varContactSurname|varAccountEmail'

        ]);

        MailTemplate::createTemplate([
            'id' => 6,
            'header' => 'Password was changed',
            'name' => 'Password was changed',
            'type' => 'info',
            'path' => 'passwordChanged',
            'body' => 'Good Day [varContactTitle] [varContactForename] [varContactSurname]! <br><br>Password was changed<br>New password:[varNewPassword]',
            'variables' => 'varContactTitle|varContactForename|varContactSurname|varNewPassword'

        ]);
        MailTemplate::createTemplate([
            'id' => 7,
            'header' => 'Confirm change email',
            'name' => 'Confirm change email',
            'type' => 'system',
            'path' => 'confirmChangeEmail',
            'body' => 'Good Day [varContactTitle] [varContactForename] [varContactSurname]! <br><br>You confirm code:[varConfirmCode]<br>',
            'variables' => 'varContactTitle|varContactForename|varContactSurname|varConfirmCode'

        ]);

        MailTemplate::createTemplate([
            'id' => 8,
            'header' => 'Forgot password',
            'name' => 'Forgot password',
            'type' => 'system',
            'path' => 'forgotPassword',
            'body' => 'Good Day [varContactTitle] [varContactForename] [varContactSurname]! <br><br>You reset password link: <br><a href="[varForgotLink]">[varForgotLink]</a>',
            'variables' => 'varContactTitle|varContactForename|varContactSurname|varForgotLink'

        ]);

        MailTemplate::createTemplate([
            'id' => 9,
            'header' => 'New password',
            'name' => 'New password',
            'type' => 'system',
            'path' => 'newPassword',
            'body' => 'Good Day [varContactTitle] [varContactForename] [varContactSurname]! <br><br>You credentials: <br><br>
Login:[varAccountEmail]<br>New password:[varNewPassword]',
            'variables' => 'varContactTitle|varContactForename|varContactSurname|varNewPassword|varAccountEmail'

        ]);

        MailTemplate::createTemplate([
            'id' => 10,
            'header' => 'New message',
            'name' => 'New message',
            'type' => 'system',
            'path' => 'newMessage',
            'body' => 'Good Day [varContactTitle] [varContactForename] [varContactSurname]! <br><br>
You have new message by advert <a target="_blank" href="[varAdvertUrl]">[varAdvertTitle]</a> . <br>

<strong>Name</strong>: [varMessageFullName] <br>
<strong>Email</strong>: [varMessageEmail] <br>
@if (!is_null($varMessagePhone))
<strong>Phone</strong>: [varMessagePhone] <br>
@endif
<hr>
<strong>Client message</strong> <br>
[varMessage]
<hr>
<br><br>

You can use replay to answer for this message.
',
            'variables' => 'varContactTitle|varContactForename|varContactSurname|varAdvertUrl|varAdvertTitle|varMessageFullName|varMessageEmail|varMessagePhone|varMessage'

        ]);

        MailTemplate::createTemplate([
            'id' => 11,
            'header' => 'New for administrator',
            'name' => 'New for administrator',
            'type' => 'system',
            'path' => 'message4Administrator',
            'body' => '[varContactTitle] [varContactForename] [varContactSurname] ([varContactId]) writes to you: <br><br>
[varMessageForAdministrator]
<hr>
<br><br>
You can use replay to answer for this message.
',
            'variables' => 'varContactTitle|varContactForename|varContactSurname|varMessageForAdministrator|varContactId'

        ]);

        /*MailTemplate::createTemplate([
            'name' => 'Activation link expired and new activation link',
            'type' => 'system',
            'path' => 'activatePrivateAccount'
        ]);

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
            'body' => 'text'
        ]);

        MailTemplate::createTemplate([
            'name' => 'Advert was blocked',
            'type' => 'system',
            'path' => 'advertBlocked',
            'body' => 'text'
        ]);

        MailTemplate::createTemplate([
            'name' => 'Advert was approved',
            'type' => 'system',
            'path' => 'advertApproved',
            'body' => 'text'
        ]);


        MailTemplate::createTemplate([
            'name' => 'Company data was changed',
            'type' => 'info',
            'path' => 'companyDataWasChanged',
            'body' => 'text'
        ]);
        MailTemplate::createTemplate([
            'name' => 'Advert was activated',
            'type' => 'info',
            'path' => 'advertActivated',
            'body' => 'text'
        ]);
        MailTemplate::createTemplate([
            'name' => 'Advert was disabled',
            'type' => 'info',
            'path' => 'advertDisabled',
            'body' => 'text'
        ]);
        MailTemplate::createTemplate([
            'name' => 'advert waiting for payment',
            'type' => 'info',
            'path' => 'advertWaitingPayment',
            'body' => 'text'
        ]);
        MailTemplate::createTemplate([
            'name' => 'Advert soon expired',
            'type' => 'info',
            'path' => 'advertSoonExpired',
            'body' => 'text'
        ]);
        MailTemplate::createTemplate([
            'name' => 'Advert is expired',
            'type' => 'info',
            'path' => 'advertExpired',
            'body' => 'text'
        ]);
        MailTemplate::createTemplate([
            'name' => 'Advert was deleted',
            'type' => 'info',
            'path' => 'advertDeleted',
            'body' => 'text'
        ]);*/


    }
}
