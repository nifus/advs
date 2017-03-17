<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BackPortalTest extends TestCase
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

        $response = $this->json('GET', '/api/faqs/announcements/business?token=' . $token);
        $response->assertStatus(200);

        $response = $this->json('GET', '/api/faqs/announcements/private?token=' . $token);
        $response->assertStatus(200);

        $response = $this->json('GET', '/api/faqs?token=' . $token);
        $response->assertStatus(200);


        $response = $this->json('POST', '/api/faqs?token=' . $token,[
           "desc"=> "123131231", "title"=> "12", "type"=> "faq"
        ]);
        $response->assertStatus(200);
        $id = $response->original['id'];

        $response = $this->json('POST', '/api/faqs/'.$id.'?token=' . $token,[
            "desc"=> "123131231", "title"=> "12", "type"=> "faq"
        ]);
        $response->assertStatus(200);

        $response = $this->json('DELETE', '/api/faqs/'.$id.'?token=' . $token);
        $response->assertStatus(200);
        ///api/faqs/7
    }


}
