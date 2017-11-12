<?php

namespace App\Http\Controllers;

use App\Adv;
use App\BusinessTariff;
use App\PrivateTariff;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\Payment;

class PaymentController extends Controller
{
    function emulation($type, $id, Request $request)
    {
        return view('paymentEmulation', ['type' => $type, 'id' => $id, 'redirect' => $request->get('redirect')]);
    }

    function emulationSave($type, $id, Request $request)
    {
        $result = $request->get('result');
        $payment = Payment::find($id);

        if ($result == 'success') {
            $payment->success();
            return response()->redirectTo($request->get('redirect'));
        } else {
            $payment->fail();
            return response()->redirectTo($request->get('redirect'));
        }


    }


    function store($type, $way, Request $request)
    {
        try {
            $user = User::getUser();
            if (is_null($user)) {
                return response()->json([], 403);
            }
            $data = $request->only(['advert_id', 'guid', 'tariff_id', 'email', 'account','slots']);

            if ($type == 'subscription') {
                $tariff = BusinessTariff::find($data['tariff_id']);
                $data['price'] = $tariff->price;
            } elseif ($type == 'slot') {
                $tariff = $user->getCurrentTariff();
                $data['price'] = $tariff->extra*$data['slots'];
                $data['tariff_id'] = $tariff->tariff_id;
            }elseif ($type == 'advert') {
                $tariff = PrivateTariff::find($data['tariff_id']);
                if (is_null($tariff) ){
                    return response()->json([], 403);
                }
                $advert = Adv::findOrDie($data['advert_id']);
                $data['price'] = $advert->type=='rent' ? $tariff->rent_price : $tariff->sale_price;
            }


            $payment = Payment::createNewPayment($type, $user, $way, $data);

            return response()->json($payment);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }


    }


}
