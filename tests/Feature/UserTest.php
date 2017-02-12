<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStore()
    {
        $response = $this->json('POST', '/api/user/', []);
        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => false,
            ]);

        $response = $this->json('POST', '/api/user/', ['email' => 'asdasds']);
        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => false,
            ]);

        $response = $this->json('POST', '/api/user/', ['email' => 'nifus@mail.ru']);
        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => false,
            ]);

        $response = $this->json('POST', '/api/user/', ['email' => 'nifus@mail.ru', 'password' => '']);
        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => false,
            ]);

        $response = $this->json('POST', '/api/user/', ['email' => 'nifus@mail.ru', 'password' => 'testpass']);
        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => false,
            ]);

        $response = $this->json('POST', '/api/user/', ['email' => 'unic@mail.ru', 'password' => 'testpass']);
        $response
            ->assertStatus(200)
            ->assertJson(['email' => 'unic@mail.ru']);
    }

    public function testLogin()
    {

        $response = $this->json('POST', '/api/user/authenticate', ['email' => 'unic@mail.ru']);
        $response
            ->assertStatus(500)
            ;
        $response = $this->json('POST', '/api/user/authenticate', []);
        $response
            ->assertStatus(500);


        $response = $this->json('POST', '/api/user/', ['email' => 'unic@mail.ru', 'password' => 'testpass']);
        $response
            ->assertStatus(200)
            ->assertJson(['email' => 'unic@mail.ru']);

        $response = $this->json('POST', '/api/user/authenticate', ['email' => 'unic@mail.ru', 'password' => 'testpass']);
        $response
            ->assertStatus(200)
            ->assertJson(['user' => ['email' => 'unic@mail.ru']]);
    }

    public function testAuthenticate()
    {
        $response = $this->json('POST', '/api/user/', ['email' => 'unic@mail.ru', 'password' => 'testpass']);
        $response
            ->assertStatus(200)
            ->assertJson(['email' => 'unic@mail.ru']);

        $response = $this->json('POST', '/api/user/authenticate', ['email' => 'unic@mail.ru', 'password' => 'testpass']);
        $response
            ->assertStatus(200)
            ->assertJson(['user' => ['email' => 'unic@mail.ru']]);
        $token = $response->original['token'];


        $response = $this->json('GET', '/api/user/get-auth?token='.$token, []);
        $response
            ->assertStatus(200)
            ->assertJson(['email' => 'unic@mail.ru']);
    }


    public function testUserLogout()
    {
        $response = $this->json('POST', '/api/user/', ['email' => 'unic@mail.ru', 'password' => 'testpass']);
        $response
            ->assertStatus(200)
            ->assertJson(['email' => 'unic@mail.ru']);

        $response = $this->json('POST', '/api/user/authenticate', ['email' => 'unic@mail.ru', 'password' => 'testpass']);
        $response
            ->assertStatus(200)
            ->assertJson(['user' => ['email' => 'unic@mail.ru']]);
        $token = $response->original['token'];


        $response = $this->json('GET', '/api/user/get-auth?token='.$token, []);
        $response
            ->assertStatus(200)
            ->assertJson(['email' => 'unic@mail.ru']);


        $response = $this->json('GET', '/api/user/logout?token='.$token, []);
        $response
            ->assertStatus(200);
    }
}
