<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BackPricesTest extends TestCase
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

        $response = $this->json('get', '/api/tariff/private?token=' . $token);
        $response->assertStatus(200);

        $response = $this->json('get', '/api/tariff/business?token=' . $token);
        $response->assertStatus(200);


        $response = $this->json('POST', '/api/tariff/private?token=' . $token, [
            [
                "id" => 1,
                "duration" => "1 week",
                "rent_price" => 30,
                "sale_price" => 40
            ],
            [
                "id" => 2,
                "duration" => "1 week",
                "rent_price" => 30,
                "sale_price" => 40
            ]

        ]);

        $response->assertStatus(200);

        $response = $this->json('POST', '/api/tariff/business?token=' . $token, [
            [
                "id" => 1,
                "title" => "Pack 1",
                "number_of_slots" => 1,
                "price" => 30,
                "price_extra_slots" => 30,
            ],
            [
                "id" => 2,
                "title" => "Pack 1",
                "number_of_slots" => 1,
                "price" => 30,
                "price_extra_slots" => 30,
            ]

        ]);
        $response->assertStatus(200);

    }



}
