<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\AdvPayment;

class PaymentController extends Controller
{
    function emulation($type, $id){
        return view('paymentEmulation',['type'=>$type,'id'=>$id]);
    }

    function emulationSave($type, $id, Request $request){
        $result = $request->get('result');
        $payment = AdvPayment::find($id);

        if ($result=='success'){
            $payment->success();
            return response()->redirectTo('/');
        }else{
            $payment->fail();
            return response()->redirectTo('/offer');
        }


    }


    function store( $type, Request $request){
        try{
            $user = User::getUser();
            if ( is_null($user) ){
                return response()->json([],403);
            }
            $data = $request->only(['adv_id','guid','price','tariff_id','email','account']);

            $payment = AdvPayment::createNewPayment($type,$data);

            return response()->json($payment);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }


    }



}
