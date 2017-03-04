<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BackConfigTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */


    public function testAnnouncement()
    {

        $response = $this->json('POST', '/api/user/authenticate',
            ['email' => 'admin@gmail.com', 'password' => 'testpass', 'is_admin' => 1]
        );
        $response
            ->assertStatus(200)
            ->assertJson(['user' => ['email' => 'admin@gmail.com']]);
        $token = $response->original['token'];

        $response = $this->json('POST', '/api/back/config/announcement/private?token='.$token,
            ['status' => '1', 'text' => 'test test']
        );
        $response->assertStatus(200)
            ->assertJson(['success' => true]);

    }


}
