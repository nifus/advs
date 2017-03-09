<?php

namespace App\Http\Controllers;

use App\EventsLog;
use App\PrivateTariff;
use App\BusinessTariff;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\User as UserModel;
use App\Address as Address;

class TariffController extends Controller
{

    function getPrivate(Request $request){
        try{
            $user = UserModel::getUser($request->get('token'));
           // dd($user);
            if ( is_null($user) || !$user->hasPermissions('prices') ){
                return response()->json([], 403);
            }
            $prev_changed = EventsLog::getLastChangedPrivateTariff($user);
            return response()->json(['tariffs'=>PrivateTariff::getTariffs(),'prev_user'=>$prev_changed], 200, [], JSON_NUMERIC_CHECK);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

    }
    function getBusiness(Request $request){
        try{
            $user = UserModel::getUser($request->get('token'));
            if ( is_null($user) || !$user->hasPermissions('prices') ){
                return response()->json([], 403);
            }
            $prev_changed = EventsLog::getLastChangedBusinessTariff($user);
            return response()->json(['tariffs'=>BusinessTariff::getTariffs(),'prev_user'=>$prev_changed], 200, [], JSON_NUMERIC_CHECK);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
    function updatePrivate(Request $request){
        $user = UserModel::getUser($request->get('token'));
        if ( is_null($user) || !$user->hasPermissions('prices') ){
            return response()->json([], 403);
        }

        EventsLog::changePrivateTariff($user, PrivateTariff::getTariffs() );
        $tariffs = $request->all();
        foreach($tariffs as $tariff){
            if (!isset($tariff['id'])){
                continue;
            }
            $private_obj = PrivateTariff::find($tariff['id']);
            if (!is_null($private_obj) ){
                $private_obj->updatePrice($tariff['rent_price'],$tariff['sale_price']);
            }
        }
        return response()->json();
    }

    function updateBusiness(Request $request){
        $user = UserModel::getUser($request->get('token'));
        if ( is_null($user) || !$user->hasPermissions('prices') ){
            return response()->json([], 403);
        }

        EventsLog::changeBusinessTariff($user, BusinessTariff::getTariffs() );
        $tariffs = $request->all();
       // dd($tariffs);
        foreach($tariffs as $tariff){
            if (!isset($tariff['id'])){
                continue;
            }
            $business_obj = BusinessTariff::find($tariff['id']);
            if (!is_null($business_obj) ){
                $business_obj->updatePrice($tariff['price'],$tariff['price_extra_slots']);
            }
        }
        return response()->json();
    }


    /*function store( Request $request){
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

    function create(){
        $user = UserModel::getUser();
        return view('controller.adv.create', [
            'user' => $user,
            'categories'=>CategoryModel::$categories
        ]);
    }

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
    }*/


}
