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
    /**
     * Return list of private tariffs for users
     *
     * @return \Illuminate\Http\JsonResponse
     */
    function getPrivatePrices(){
        return response()->json(PrivateTariff::getTariffs(), 200, [], JSON_NUMERIC_CHECK);
    }

    /**
     * Return list of business tariffs for users
     *
     * @return \Illuminate\Http\JsonResponse
     */
    function getBusinessPrices(){
        return response()->json(BusinessTariff::getTariffs(), 200, [], JSON_NUMERIC_CHECK);
    }

    /**
     * Return list of private tariffs in admin page
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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


    /**
     * Return list of business tariffs in admin page
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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

    function getFutureTariff(){
        try{
            $user = UserModel::getUser();
            if ( is_null($user)){
                throw new \Exception('no user');
            }
            $future_tariff = $user->getFutureTariff();

            return response()->json( [
                'tariff'=>is_null($future_tariff) ? null : $future_tariff->toArray(),
            ] );

        }catch (\Exception $e){
            return response()->json( ['success'=>false,'error'=>$e->getMessage()] );
        }
    }

    function getCurrentTariff(){
        try{
            $user = UserModel::getUser();
            if ( is_null($user)){
                throw new \Exception('no user');
            }
            $current_tariff = $user->getCurrentTariff();

            return response()->json( [
                'tariff'=>is_null($current_tariff) ? null : $current_tariff->toArray()
            ] );

        }catch (\Exception $e){
            return response()->json( ['success'=>false,'error'=>$e->getMessage()] );
        }
    }

    function getSlots(){
        try{
            $user = UserModel::getUser();
            if ( is_null($user)){
                throw new \Exception('no user');
            }
            $current_tariff = $user->getCurrentTariff();

            return response()->json(
                is_null($current_tariff) ? [] : $current_tariff->getSlots()
             );

        }catch (\Exception $e){
            return response()->json( ['success'=>false,'error'=>$e->getMessage()] );
        }
    }

    function endTariff(){
        try{
            $user = UserModel::getUser();
            if ( is_null($user) ){
                abort(403);
            }
            $tariff = $user->getCurrentTariff();
            if ( is_null($tariff) ){
                abort(403);
            }
            $tariff->end();
            $future = $user->getFutureTariff();
            if ( is_null($future) ){
                return response()->json(['success'=>true]);
            }
            $tariff = $future->doCurrentTariff();


        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }


    }
    /*
        function create(){
            $user = UserModel::getUser();
            return view('controller.adv.create', [
                'user' => $user,
                'categories'=>CategoryModel::$categories
            ]);
        }



        function getAll()
        {
            return response()->json( \Config::get('app.tariffs') );
        }*/


}
