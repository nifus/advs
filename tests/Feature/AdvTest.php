<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdvTest extends TestCase
{
    use DatabaseTransactions;


    public function testAdminFunctions()
    {
        ///{id}
        //
        $response = $this->json('POST', '/api/user/authenticate', ['email' => 'admin@gmail.com', 'password' => 'testpass', 'is_admin' => 1]);
        $response
            ->assertStatus(200)
            ->assertJson(['user' => ['email' => 'admin@gmail.com']]);
        $token = $response->original['token'];

        $response = $this->json('GET', '/api/adv/by-user/3?token=' . $token);
        $response->assertStatus(200);

        $response = $this->json('GET', '/api/adv/3/statistics?token=' . $token);
        $response->assertStatus(200);

        $response = $this->json('GET', '/api/adv/by-current-user?token=' . $token);
        $response->assertStatus(200);


        $response = $this->json('GET', '/api/adv/watch/by-current-user?token=' . $token);
        $response->assertStatus(200);

        $response = $this->json('GET', '/offer?token=' . $token);
        $response->assertStatus(200);

    }


    public function testStore()
    {

        $response = $this->json('POST', '/api/user/authenticate', ['email' => 'a.bunzya@gmail.com', 'password' => 'testpass']);
        $response
            ->assertStatus(200)
            ->assertJson(['user' => ['email' => 'a.bunzya@gmail.com']]);
        $token = $response->original['token'];

        $response = $this->json('post', '/api/adv?token=' . $token, [
            "token"=>$token,
            "type" => "rent",
            "category" => 1,
            "subcategory" => "Any",
            "photos" => [],
            "address" => [
                "house_number" => "38",
                "street" => "улица Чапаева",
                "city" => "Нижневартовск",
                "region" => "Ханты-мансийский Ао",
                "country" => "RU",
                "zip" => "628617",
                "distances" => [
                    "transport" => 1,
                    "driving" => 2,
                    "autoban" => 1,
                ]
            ],
            "energy" => [
                "class" => "Any",
                "source" => null,
            ],
            "props" => [
                "pets" => "Any",
                "floor_cover" => "Any",
                "air_conditioner" => "By agreement",
                "recommended_usage" => "Any",
            ],
            "finance" => [
                "ancillary_cost_included" => 1
            ],
            "author" => [
                "sex" => "male",
                "name" => "Alex",
                "surname" => "Bunzya",
                "email" => "a.bunzya@gmail.com",
                "phone" => "+79218466469",
            ],
            "air_conditioner" => "By agreement",
            "edp_cabling" => "By agreement",
            "price_type" => "Price per month",
            "development" => "Developed",
            "building_permission" => "Yes",
            "move_date" => null,
            "lat" => 60.944348,
            "lng" => 76.599154,
            "desc" => "1212312",
            "agb" => true,
            "living_area" => 11,
            "floor" => 1,
            "floors" => 1,
            "rooms" => 1,
            "cold_rent" => 122,
            "title" => "123123",
        ]);

        $response->assertStatus(200);
        $response->assertJson(['title' => '123123']);

        $adv_id = $response->original['id'];
        $user_id = $response->original['user_id'];
        $response = $this->json('post', '/api/adv/'.$adv_id.'?token=' . $token, [
            "token"=>$token,
            "type" => "rent",
            "category" => 1,
            "subcategory" => "Any",
            "photos" => [],
            "address" => [
                "house_number" => "38",
                "street" => "улица Чапаева",
                "city" => "Нижневартовск",
                "region" => "Ханты-мансийский Ао",
                "country" => "RU",
                "zip" => "628617",
                "distances" => [
                    "transport" => 1,
                    "driving" => 2,
                    "autoban" => 1,
                ]
            ],
            "energy" => [
                "class" => "Any",
                "source" => null,
            ],
            "props" => [
                "pets" => "Any",
                "floor_cover" => "Any",
                "air_conditioner" => "By agreement",
                "recommended_usage" => "Any",
            ],
            "finance" => [
                "ancillary_cost_included" => 1
            ],
            "author" => [
                "sex" => "male",
                "name" => "Alex",
                "surname" => "Bunzya",
                "email" => "a.bunzya@gmail.com",
                "phone" => "+79218466469",
            ],
            "air_conditioner" => "By agreement",
            "edp_cabling" => "By agreement",
            "price_type" => "Price per month",
            "development" => "Developed",
            "building_permission" => "Yes",
            "move_date" => null,
            "lat" => 60.944348,
            "lng" => 76.599154,
            "desc" => "1212312",
            "agb" => true,
            "living_area" => 11,
            "floor" => 1,
            "floors" => 1,
            "rooms" => 1,
            "cold_rent" => 122,
            "title" => "123123",
            "user_id"=>$user_id
        ]);

        $response->assertStatus(200)
            ->assertJson(['title' => '123123']);

        $response = $this->json('get', '/api/adv/'.$adv_id.'/restore?token=' . $token);
        $response->assertStatus(200);
    }


}
