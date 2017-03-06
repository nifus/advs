<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BackFaqTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */


    public function testFaq()
    {

        /*$response = $this->json('POST', '/api/user/authenticate',
            ['email' => 'admin@gmail.com', 'password' => 'testpass', 'is_admin' => 1]
        );
        $response
            ->assertStatus(200)
            ->assertJson(['user' => ['email' => 'admin@gmail.com']]);
        $token = $response->original['token'];

        $response = $this->json('POST', '/api/back/faqs?token='.$token,
            ['title' => '1', 'desc' => '2','type'=>'faq']
        );
        $response->assertStatus(200)
            ->assertJson(['title' => '1']);
        $id = $response->original['id'];

        $response = $this->json('POST', '/api/back/faqs/'.$id.'?token='.$token,
            ['title' => '1', 'desc' => '2','type'=>'faq']
        );
        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $response = $this->json('DELETE', '/api/back/faqs/'.$id.'?token='.$token,
            []
        );
        $response->assertStatus(200)
            ->assertJson(['success' => true]);*/

    }


}
