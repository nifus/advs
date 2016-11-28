<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User as UserModel;
use App\Address as Address;

class TariffController extends Controller
{

    function store( Request $request){
        try{
            $user = UserModel::getUser();
            if ( is_null($user) ){
                abort(403);
            }
            $tariff_id = $request->get('tariff_id');
            if ( is_null($tariff_id) ){
                abort(403);
            }
            $tariff = $user->addTariff($tariff_id);

            return response()->json(['success'=>true]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }


    }/*

    function create(){
        $user = UserModel::getUser();
        return view('controller.adv.create', [
            'user' => $user,
            'categories'=>CategoryModel::$categories
        ]);
    }*/

    function getUserTariff(){
        try{
            $user = UserModel::getUser();
            if ( is_null($user)){
                throw new \Exception('no user');
            }
            $tariff = $user->getActualTariff();

            return response()->json( [
                'success'=>true,
                'tariff'=>is_null($tariff) ? null : $tariff->toArray(),
                'tariff_details'=>is_null($tariff) ||  is_null($tariff->Details) ? null : $tariff->Details()->get()->toArray(),
                'tariffs'=>\Config::get('app.tariffs')
            ] );

        }catch (\Exception $e){
            return response()->json( ['success'=>false,'error'=>$e->getMessage()] );

        }

    }

    function getAll()
    {
        return response()->json( \Config::get('app.tariffs') );
    }


}
