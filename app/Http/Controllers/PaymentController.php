<?php

namespace App\Http\Controllers;

use App\EventsLog;
use App\PrivateTariff;
use App\BusinessTariff;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\User as UserModel;
use App\Address as Address;

class PaymentController extends Controller
{



    function store( $type, Request $request){
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


    }



}
