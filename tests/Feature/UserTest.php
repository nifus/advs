<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    public function testRegister()
    {

        $response = $this->json('post', '/api/user/private-account', [
            "commercial_country" => "germany",
            "email" => "nifus@nifus.ru",
            "password" => "testpass",
            "sex" => "male",
            "name" => "nifus",
            "surname" => "nifus",
            "re_email" => "nifus@nifus.ru",
            "re_password" => "testpass",
            "agb" => true,
            "test" => 1
        ]);
        $response->assertStatus(200)->assertJson(['user' => ['email' => 'nifus@nifus.ru']]);
        $id = $response->original['user']['id'];
        $user = User::find($id);

        $response = $this->json('get', '/register/activate/' . $user->id . '/' . $user->activate_key);
        $response->assertStatus(200);


        $response = $this->json('post', '/api/user/business-account', [
            "test" => 1,
            "address_additional" => "lenina street",
            "address_city" => "Можга",
            "address_number" => "37",
            "address_street" => "ул. Чапаева",
            "address_zip" => "427794",
            "agb" => true,
            "commercial_country" => "germany",
            "commercial_id" => "2131231",
            "commercial_additional" => null,
            "company" => "Print",
            "contact_email" => null,
            "email" => "aa@gmail.com",
            "giro_account" => null,
            "name" => "Alexander",
            "password" => "testpass",
            "payment_type" => "prepayment",
            "paypal_email" => null,
            "phone" => null,
            "re_email" => "aa@gmail.com",
            "re_password" => "testpass",
            "sex" => "male",
            "surname" => "pushkin",
            "tariff" => null,
            "website" => null,
        ]);
        $response->assertStatus(200)->assertJson(['user' => ['email' => 'aa@gmail.com']]);
        $id = $response->original['user']['id'];
        $user = User::find($id);

        $response = $this->json('get', '/register/confirm/' . $user->id . '/' . $user->activate_key);
        $response->assertStatus(200);

    }

    public function testPublicPages()
    {

        $response = $this->json('get', '/register/private');
        $response->assertStatus(200);
        $response = $this->json('get', '/register/business');
        $response->assertStatus(200);
    }

    /**
     * A basic test example.
     *
     * @return void

    public function testStore()
     * {
     *
     *
     * $response = $this->json('POST', '/api/user', []);
     * $response
     * ->assertStatus(200)
     * ->assertJson([
     * 'success' => false,
     * ]);
     *
     * $response = $this->json('POST', '/api/user/', ['email' => 'asdasds']);
     * $response
     * ->assertStatus(200)
     * ->assertJson([
     * 'success' => false,
     * ]);
     *
     * $response = $this->json('POST', '/api/user/', ['email' => 'nifus@mail.ru']);
     * $response
     * ->assertStatus(200)
     * ->assertJson([
     * 'success' => false,
     * ]);
     *
     * $response = $this->json('POST', '/api/user/', ['email' => 'nifus@mail.ru', 'password' => '']);
     * $response
     * ->assertStatus(200)
     * ->assertJson([
     * 'success' => false,
     * ]);
     *
     * $response = $this->json('POST', '/api/user/', ['email' => 'nifus@mail.ru', 'password' => 'testpass']);
     * $response
     * ->assertStatus(200)
     * ->assertJson([
     * 'success' => false,
     * ]);
     *
     * $response = $this->json('POST', '/api/user/', ['email' => 'unic@mail.ru', 'password' => 'testpass']);
     * $response
     * ->assertStatus(200)
     * ->assertJson(['email' => 'unic@mail.ru']);
     * }*/


    public function testAuthenticate()
    {


        $response = $this->json('POST', '/api/user/authenticate', ['email' => 'admin@gmail.com', 'password' => 'testpass', 'is_admin' => 1]);
        $response->assertStatus(200)
            ->assertJson(['user' => ['email' => 'admin@gmail.com']]);
        $token = $response->original['token'];


        $response = $this->json('GET', '/api/user/get-auth', ['token' => $token]);
        $response
            ->assertStatus(200)
            ->assertJson(['email' => 'admin@gmail.com']);


        $response = $this->json('POST', '/api/user/authenticate', ['email' => 'a.bunzya@gmail.com', 'password' => 'testpass']);
        $response->assertStatus(200)
            ->assertJson(['user' => ['email' => 'a.bunzya@gmail.com']]);
        $token = $response->original['token'];


        $response = $this->json('GET', '/api/user/get-auth?token=' . $token, ['token' => $token]);
        $response
            ->assertStatus(200)
            ->assertJson(['email' => 'a.bunzya@gmail.com']);


        $response = $this->json('POST', '/api/user/forgot-password?token=' . $token, ['email' => 'a.bunzya@gmail.com']);
        $response
            ->assertStatus(200);

    }


    /*public function testUserLogout()
    {
        $response = $this->json('POST', '/api/user/authenticate', ['email' => 'a.bunzya@gmail.com', 'password' => 'testpass']);
        $response->assertStatus(200)
            ->assertJson(['user' => ['email' => 'a.bunzya@gmail.com']]);
        $token = $response->original['token'];




        $response = $this->json('GET', '/api/user/logout?token='.$token, []);
        $response
            ->assertStatus(200);
    }*/


    public function testAdminFunctions()
    {
        ///{id}
        //
        $response = $this->json('POST', '/api/user/authenticate', ['email' => 'admin@gmail.com', 'password' => 'testpass', 'is_admin' => 1]);
        $response
            ->assertStatus(200)
            ->assertJson(['user' => ['email' => 'admin@gmail.com']]);
        $token = $response->original['token'];

        $response = $this->json('POST', '/api/user/3/set-active-status?token=' . $token);
        $response->assertStatus(200);

        $response = $this->json('POST', '/api/user/3/set-block-status?token=' . $token);
        $response->assertStatus(200);

        $response = $this->json('DELETE', '/api/user/3?token=' . $token);
        $response->assertStatus(200);

        $response = $this->json('POST', '/api/user/3?token=' . $token, [
            "sex" => "male",
            "name" => "Bo",
            "contact_email" => "admin@gmail.com",
            "password" => null,
            "surname" => "Homenick",
            "commercial_id" => null,
            "company" => "Maggio Ltd",
            "website" => "http://www.zboncak.com/",
            "address_street" => "Torp Heights",
            "address_number" => "747",
            "address_zip" => "09722-6473",
            "address_city" => "Donatofurt",
            "address_additional" => null,
            "commercial_country" => "Antigua and Barbuda",
            "phone" => "696.312.9232 x31631",
        ]);
        $response->assertStatus(200);
    }

    public function testStuff()
    {
        $response = $this->json('POST', '/api/user/authenticate', ['email' => 'a.bunzya@gmail.com', 'password' => 'testpass']);
        $response
            ->assertStatus(200)
            ->assertJson(['user' => ['email' => 'a.bunzya@gmail.com']]);
        $token = $response->original['token'];

        $response = $this->json('GET', '/api/user/countries?token=' . $token);
        $response->assertStatus(200);
    }

    public function testSettings()
    {
        $response = $this->json('POST', '/api/user/authenticate', ['email' => 'a.bunzya@gmail.com', 'password' => 'testpass']);
        $response
            ->assertStatus(200)
            ->assertJson(['user' => ['email' => 'a.bunzya@gmail.com']]);
        $token = $response->original['token'];

        $response = $this->json('PUT', '/api/user/change-password?token=' . $token, [
            "current_password" => "testpass",
            "password" => "testpass",
            "re_password" => "testpass",
        ]);
        $response->assertStatus(200);

        $response = $this->json('PUT', '/api/user/allow-notifications?token=' . $token, [
            "allow_notifications" => 0
        ]);
        $response->assertStatus(200);

    }
}
