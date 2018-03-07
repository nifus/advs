<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User as UserModel;
use App\Address as Address;

class AddressController extends Controller
{

    function store( Request $request){
        try{
            $user = UserModel::getUser();
            if ( is_null($user) ){
                abort(403);
            }
            $data = $request->all();
            $adv = AdvModel::createNewAdv($data, $user->id);
            return response()->json($adv->toArray());
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



    function search(Request $request)
    {
        dd( $request->all() );

        return response()->json($advs);
    }


}
