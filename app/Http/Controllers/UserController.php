<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\Jobs\ActivatePrivateAccount as ActivatePrivateAccountJob;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function __construct()
    {
        //$this->middleware('jwt.auth', ['except' => [ 'authenticate','']]);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            $user = User::getUserByLogin($credentials['email']);
            if ( is_null($user) ){
                throw new JWTException( 'Пользователь не найден' );
            }
            $token = JWTAuth::fromUser($user);

            //dd($token);
            //if (! $token = JWTAuth::attempt($credentials)) {

            //    event( new SignInErrorEvent($credentials['email'], 'Пользователь не найден' ) );
            // return response()->json(['error' => trans('app.signIn.invalid_credentials')], 401);
            //}
        } catch (JWTException $e) {
            //  event( new SignInErrorEvent($credentials['email'],$e->getMessage()) );
            return response()->json(['success'=>false,'error' => $e->getMessage()], 500);
        }
        //event( new SignInSuccessEvent($credentials['email']) );
        //$user->updateLastLogin();

        return response()->json(['success'=>true,'token'=>$token]);
    }


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
