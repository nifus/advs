<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NewsTest extends TestCase
{
    use DatabaseTransactions;



    public function testAdminFunctions(){
    ///{id}
        //
        $response = $this->json('POST', '/api/user/authenticate', ['email' => 'admin@gmail.com', 'password' => 'testpass','is_admin'=>1]);
        $response
            ->assertStatus(200)
            ->assertJson(['user' => ['email' => 'admin@gmail.com']]);
        $token = $response->original['token'];

        $response = $this->json('GET', '/api/news/private?token='.$token);
        $response->assertStatus(200);

        $response = $this->json('GET', '/api/news/business?token='.$token);
        $response->assertStatus(200);

    }


}
