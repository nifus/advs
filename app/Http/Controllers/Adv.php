<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Adv as AdvModel;
use App\User as UserModel;

class Adv extends Controller
{
    function getByUser(){
        $user = UserModel::getUser();
        $advs = AdvModel::getByUser($user->id);
        return response()->json($advs);
    }

    function getWatchByUser(){
        $user = UserModel::getUser();
        $favs = $user->Fav()->get();
        //$advs = AdvModel::getWatchByUser($user->id);
        return response()->json($favs);
    }

    function removeWatch($adv_id){
        $user = UserModel::getUser();
        AdvModel::removeWatch($user->id, $adv_id);
        return response()->json(['success'=>true]);
    }




    public function getStat(){

        $user = UserModel::getUser();
        $advs = AdvModel::getByUserWithStatus($user->id);

        $result = [
            'rent'=>[
                'total'=> 0,
                'payment_waiting'=> 0,
                'active'=> 0,
                'disabled'=> 0,
                'expired'=> 0,
                'blocked'=> 0

            ],
            'sell'=>[
                'total'=> 0,
                'payment_waiting'=> 0,
                'active'=> 0,
                'disabled'=> 0,
                'expired'=> 0,
                'blocked'=> 0
            ]
        ];

        foreach($advs as $adv){
            $result[ $adv->type ][ $adv->status ]++;
            $result[ $adv->type ][ 'total' ]++;
        }
        return response()->json($result);
    }
}
