<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BackAdministratorTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */


    public function testHeaders()
    {

        $response = $this->json('POST', '/api/user/authenticate',
            ['email' => 'admin@gmail.com', 'password' => 'testpass', 'is_admin' => 1]
        );
        $response
            ->assertStatus(200)
            ->assertJson(['user' => ['email' => 'admin@gmail.com']]);
        $token = $response->original['token'];

        $response = $this->json('GET', '/api/user/get-all-administration?token='.$token);
        $response->assertStatus(200);

        $response = $this->json('DELETE', '/api/user/1/administrator?token='.$token);
        $response->assertStatus(200);

        $response = $this->json('POST', '/api/user/1/administrator?token='.$token,['sex'=>'male','password'=>'testpasss']);
        $response->assertStatus(200);

        $response = $this->json('POST', '/api/user/administrator?token='.$token,['sex'=>'male','password'=>'testpasss']);
        $response->assertStatus(200);
    }

    public function testAccess()
    {

        $response = $this->json('POST', '/api/user/authenticate',
            ['email' => 'nifus@mail.ru', 'password' => 'testpass', 'is_admin' => 1]
        );
        $response
            ->assertStatus(200)
            ->assertJson(['user' => ['email' => 'nifus@mail.ru']]);
        $token = $response->original['token'];

        $response = $this->json('GET', '/api/user/get-all-administration?token='.$token);
        $response->assertStatus(403);

        $response = $this->json('DELETE', '/api/user/3/administrator?token='.$token);
        $response->assertStatus(403);

        $response = $this->json('POST', '/api/user/1/administrator?token='.$token,['sex'=>'male','password'=>'testpasss']);
        $response->assertStatus(403);

    }


}
