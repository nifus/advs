<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Adv as AdvModel;
use App\User as UserModel;
use League\Flysystem\Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

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

    function delete($adv_id){
        try{
            $user = UserModel::getUser();
            if( is_null($user) ){
                abort(403,'Access Error');
                //return response()->json(null,403);
            }
            $adv = AdvModel::findOrDie($adv_id);
            if (!$adv->isOwner($user->id)){
                abort(403,'Access Error');
               // return response()->json(null,403);
            }

            $adv->delete();
            return response()->json(['success'=>true]);
        }catch ( \Exception $e ){
            return response()->json($e->getMessage(),500);
        }

    }

    function changeStatus($adv_id, Request $request){
        try{
            $status = $request->get('status');
            $user = UserModel::getUser();
            if( is_null($user) ){
                abort(403,'Access Error');
                //return response()->json(null,403);
            }
            $adv = AdvModel::findOrDie($adv_id);
            if (!$adv->isOwner($user->id)){
                abort(403,'Access Error');
               // return response()->json(null,403);
            }

            $adv->changeStatus($status);
            return response()->json(['success'=>true]);
        }catch ( \Exception $e ){
            return response()->json($e->getMessage(),500);
        }

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
