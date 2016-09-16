<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\Jobs\ActivatePrivateAccount as ActivatePrivateAccountJob;

class UserController extends Controller
{

    public function privateAccountForm(){
        return view('controller.user.privateAccountForm');
    }


    public function createPrivateAccount(Request $request){
        try{
            $data = $request->all();
            $user = User::createPrivateAccount($data);

            dispatch(new ActivatePrivateAccountJob($user));

            return response()->json(['success'=>true,'user'=>$user->toArray()]);
        }catch( \Exception $e ){
            return response()->json(['success'=>false,'error'=>$e->getMessage()]);
        }

    }

    public function activateAccount($user_id, $key){
        $error = null;
        try{
            $user = User::find($user_id);
            if (is_null($user)){
                throw new \Exception('user_not_found');
            }
            $user->activate($key);


        }catch( \Exception $e ){
            $error = trans( 'validation.'.$e->getMessage() );
        }

        return view('controller.user.activateAccount',['error'=>$error]);

    }
}
