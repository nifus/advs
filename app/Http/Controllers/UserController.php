<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\Adv;
use App\Jobs\ActivatePrivateAccount as ActivatePrivateAccountJob;
use App\Jobs\ForgotPassword as ForgotPasswordJob;
use App\Jobs\NewPassword as NewPasswordJob;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function __construct()
    {
        //$this->middleware('jwt.auth', ['except' => [ 'authenticate','']]);
    }


    public function dashboard(){
        return view('controller.user.dashboard');

    }

    public function privateAccountForm(){
        return view('controller.user.privateAccountForm');
    }

    public function businessAccountForm(){
        $tariffs = config('app.tariffs');
        return view('controller.user.businessAccountForm',['tariffs'=>$tariffs]);
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
        } catch (JWTException $e) {
            return response()->json(['success'=>false,'error' => $e->getMessage()], 500);
        }
        return response()->json(['success'=>true,'token'=>$token]);
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

    public function forgotPassword(Request $request){
        try{
            $email = $request->get('email');
            $user = User::getUserByLogin($email);
            dispatch(new ForgotPasswordJob($user));

            return response()->json(['success'=>true,'message'=>'We sent reset link to you email']);
        }catch( \Exception $e ){
            return response()->json(['success'=>false,'error'=>$e->getMessage()]);
        }
    }

    public function resetPassword($user_id, $key){
        $error = null;
        try{
            $user = User::find($user_id);
            if (is_null($user)){
                throw new \Exception('user_not_found');
            }
            if ($user->activate_key!=$key){
                throw new \Exception('invalid_reset_link');
            }
            $new_pass = $user->resetPassword($key);
            dispatch(new NewPasswordJob($user, $new_pass));


        }catch( \Exception $e ){
            $error = trans( 'validation.'.$e->getMessage() );
        }

        return view('controller.user.resetPassword',['error'=>$error]);
    }

    public function changeEmail(Request $request){
        $data = $request->only(['email','re_email']);
        try{
            $user = User::getUser();
            $user->changeEmail($data['email'], $data['re_email']);
            return response()->json(['success'=>true]);

        }catch( \Exception $e ){
            $error = trans( 'validation.'.$e->getMessage() );
            return response()->json(['error'=>$error], 500);
        }
    }
    public function changePassword(Request $request){
        $data = $request->only(['current_password','password','re_password']);
        try{
            $user = User::getUser();
            $user->changePassword($data['current_password'], $data['password'], $data['re_password']);
            return response()->json(['success'=>true]);

        }catch( \Exception $e ){
            $error = trans( 'validation.'.$e->getMessage() );
            return response()->json(['error'=>$error], 500);
        }
    }

    public function allowNotifications(Request $request){
        $allow_notifications = $request->get('allow_notifications');
        try{
            $user = User::getUser();
            $user->changeAllowNotifications( $allow_notifications );
            return response()->json(['success'=>true]);

        }catch( \Exception $e ){
            $error = trans( 'validation.'.$e->getMessage() );
            return response()->json(['error'=>$error], 500);
        }
    }

    public function createBusinessAccount(Request $request){
        try{
            $data = $request->only(['address_additional','address_city','address_number','address_street','address_zip','agb','captcha','commercial_country','commercial_id','commercial_additional','company','contact_email','email','giro_account','name','password','payment_type','paypal_email','phone','re_email','re_password','sex','surname','tariff','website']);
            $user = User::createBusinessAccount($data);

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

    public function getAuth()
    {
        try{
            $user = User::getUser();
            /*if ( $user->is_deleted=='1' ){
                JWTAuth::invalidate(JWTAuth::getToken());
                throw new \Exception('no user');
            }*/
            return response()->json($user->toArray()  );
        }catch( \Exception $e ){
            return response()->json( null );
        }
    }



}
