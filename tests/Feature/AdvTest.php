<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdvTest extends TestCase
{
    use DatabaseTransactions;



    public function testAdminFunctions(){
    ///{id}
        //
        $response = $this->json('POST', '/api/user/authenticate', ['email' => 'admin@gmail.com', 'password' => 'testpass']);
        $response
            ->assertStatus(200)
            ->assertJson(['user' => ['email' => 'admin@gmail.com']]);
        $token = $response->original['token'];

        $response = $this->json('GET', '/api/adv/by-user/3?token='.$token);
        $response->assertStatus(200);


    }


}
