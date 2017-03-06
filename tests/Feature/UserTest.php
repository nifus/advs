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
    /*public function testStore()
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
    }*/

    public function testLogin()
    {

        /*$response = $this->json('POST', '/api/user/authenticate', ['email' => 'admin@gmail.com']);
        $response->assertStatus(403);


        $response = $this->json('POST', '/api/user/authenticate', []);
        $response->assertStatus(403);



        $response = $this->json('POST', '/api/user/authenticate',
            [ 'email' => 'admin@gmail.com', 'password' => 'testpass','is_admin'=>1]
        );
        $response
            ->assertStatus(200)
            ->assertJson(['user' => ['email' => 'admin@gmail.com']]);*/
    }

    public function testAuthenticate()
    {


       /* $response = $this->json('POST', '/api/user/authenticate', ['email' => 'admin@gmail.com', 'password' => 'testpass']);
        $response->assertStatus(200)
            ->assertJson(['user' => ['email' => 'admin@gmail.com']]);
        $token = $response->original['token'];


        $response = $this->json('GET', '/api/user/get-auth',['token'=>$token]);
        $response
            ->assertStatus(200)
            ->assertJson(['email' => 'admin@gmail.com']);*/
    }

    /*
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
        }*/


    public function testAdminFunctions(){
    ///{id}
        //
        $response = $this->json('POST', '/api/user/authenticate', ['email' => 'admin@gmail.com', 'password' => 'testpass']);
        $response
            ->assertStatus(200)
            ->assertJson(['user' => ['email' => 'admin@gmail.com']]);
        $token = $response->original['token'];

        $response = $this->json('POST', '/api/user/3/set-active-status?token='.$token);
        $response->assertStatus(200);

        $response = $this->json('POST', '/api/user/3/set-block-status?token='.$token);
        $response->assertStatus(200);

        $response = $this->json('DELETE', '/api/user/3?token='.$token);
        $response->assertStatus(200);
    }

    public function testStuff(){
        $response = $this->json('POST', '/api/user/authenticate', ['email' => 'a.bunzya@gmail.com', 'password' => 'testpass']);
        $response
            ->assertStatus(200)
            ->assertJson(['user' => ['email' => 'a.bunzya@gmail.com']]);
        $token = $response->original['token'];

        $response = $this->json('GET', '/api/user/countries?token='.$token);
        $response->assertStatus(200);
    }
}
