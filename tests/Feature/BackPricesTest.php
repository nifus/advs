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

        $response = $this->json('POST', '/api/config/private-prices?token=' . $token, [
            "sale" => [
                "1_week" => 1,
                "1_month" => 3,
                "2_weeks" => 2,
                "2_months" => 4,
                "3_months" => 5
            ],
            "user_id" => 1,
            "user_name" => "Admin Bunzya",
            "updated_at" => "2017-03-06 08:50:40",
            "rent" => [
                "1_week" => 1,
                "2_weeks" => 2,
                "1_month" => 3,
                "2_months" => 4,
                "3_months" => 5
            ]]);
        $response->assertStatus(200);


        $response = $this->json('POST', '/api/config/business-prices?token=' . $token, [
                "price" => [
                    "1_pack" => 1,
                    "2_pack" => 2,
                    "5_pack" => 3,
                    "10_pack" => 4,
                    "20_pack" => 5,
                    "30_pack" => 6,
                    "40_pack" => 7
                ],
                "user_name" => "Admin Bunzya",
                "updated_at" => "2017-03-06 08:50:51",
                "slot" => [
                    "1_pack" => 1,
                    "2_pack" => 2,
                    "5_pack" => 3,
                    "10_pack" => 4,
                    "20_pack" => 5,
                    "30_pack" => 6,
                    "40_pack" => 7
                ]]
        );
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

        $response = $this->json('POST', '/api/config/private-prices?token=' . $token, [
            "sale" => [
                "1_week" => 1,
                "1_month" => 3,
                "2_weeks" => 2,
                "2_months" => 4,
                "3_months" => 5
            ],
            "user_id" => 1,
            "user_name" => "Admin Bunzya",
            "updated_at" => "2017-03-06 08:50:40",
            "rent" => [
                "1_week" => 1,
                "2_weeks" => 2,
                "1_month" => 3,
                "2_months" => 4,
                "3_months" => 5
            ]]);
        $response->assertStatus(403);


        $response = $this->json('POST', '/api/config/business-prices?token=' . $token, [
                "price" => [
                    "1_pack" => 1,
                    "2_pack" => 2,
                    "5_pack" => 3,
                    "10_pack" => 4,
                    "20_pack" => 5,
                    "30_pack" => 6,
                    "40_pack" => 7
                ],
                "user_name" => "Admin Bunzya",
                "updated_at" => "2017-03-06 08:50:51",
                "slot" => [
                    "1_pack" => 1,
                    "2_pack" => 2,
                    "5_pack" => 3,
                    "10_pack" => 4,
                    "20_pack" => 5,
                    "30_pack" => 6,
                    "40_pack" => 7
                ]]
        );
        $response->assertStatus(403);

    }


}
