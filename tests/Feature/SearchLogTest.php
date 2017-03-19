<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SearchLogTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */


    public function testSearchLog()
    {

        $response = $this->json('POST', '/api/user/authenticate', ['email' => 'a.bunzya@gmail.com', 'password' => 'testpass']);
        $response
            ->assertStatus(200)
            ->assertJson(['user' => ['email' => 'a.bunzya@gmail.com']]);
        $token = $response->original['token'];



        $response = $this->json('POST', '/api/search/accounts?token=' . $token,
            ["query" => ["adv_type" => "all", "account" => "all", "statuses" => ["all"]] ] );
        $response
            ->assertStatus(200)
            ->assertJson(['type' => "accounts"]);
        $id = $response->original['id'];

        $response = $this->json('POST', '/api/search/'.$id.'/query-update?token=' . $token,
            ["query" => ["adv_type" => "all", "account" => "all", "statuses" => ["all"]] ] );
        $response
            ->assertStatus(200);
        $response = $this->json('POST', '/api/search/'.$id.'/config-update?token=' . $token,
            ["config" => ["page" => 2] ] );
        $response
            ->assertStatus(200);

        $response = $this->json('GET', '/api/search/'.$id.'?token=' . $token);
        $response
            ->assertStatus(200)
            ->assertJson(['search'=>['id' => $id]]);



        $response = $this->json('POST', '/api/search/advs?token=' . $token,
            ["query" => ["adv_type" => "all", "account" => "all", "statuses" => ["all"]] ] );
        $response
            ->assertStatus(200)
            ->assertJson(['type' => "advs"]);
        $id = $response->original['id'];

        $response = $this->json('POST', '/api/search/'.$id.'/query-update?token=' . $token,
            ["query" => ["type" => "rent"] ] );
        $response
            ->assertStatus(200);

        $response = $this->json('POST', '/api/search/'.$id.'/config-update?token=' . $token,
            ["config" => ["page" => 2] ] );
        $response
            ->assertStatus(200);

        $response = $this->json('GET', '/search/'.$id.'' );
        $response
            ->assertStatus(200);
    }


}
