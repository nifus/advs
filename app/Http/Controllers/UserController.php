<?php

namespace App\Http\Controllers;

use App\BusinessTariff;
use App\Tariff;
use Illuminate\Http\Request;
use App\User;
use App\Adv;
use App\EventsLog;
use App\Jobs\ForgotPassword as ForgotPasswordJob;
use App\Jobs\NewPassword as NewPasswordJob;
use App\Jobs\ConfirmCode as ConfirmCodeJob;
use App\Jobs\ActivateBusinessAccount;
use App\Jobs\ActivatePrivateAccount;
use App\Jobs\EmailChanged;
use App\Jobs\PasswordChanged;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['authenticate', 'forgotPassword', 'getAuth','dashboard','privateAccountForm','businessAccountForm','createPrivateAccount','forgotPassword','resetPassword','createBusinessAccount','activateAccount','confirmAccount']]);
    }


    public function dashboard()
    {
        return view('controller.user.dashboard');
    }

    public function privateAccountForm()
    {
        return view('controller.user.privateAccountForm');
    }

    public function businessAccountForm()
    {
        $tariffs = BusinessTariff::getTariffs();
        return view('controller.user.businessAccountForm',['tariffs'=>$tariffs]);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password', 'remember', 'is_admin');
        try {

            $user = User::getUserByEmail($credentials['email']);
            if (is_null($user)) {
                throw new JWTException(trans('main.error_user_not_found'));
            }

            if (false === $user->isActivated()) {
                throw new JWTException(trans('main.error_user_not_activated'));
            }
            if ($user->isDeletedAccount()) {
                throw new JWTException(trans('main.error_user_was_deleted'));
            }
            if ($user->isBusinessAccount() && $user->isWaitApprove()) {
                throw new JWTException(trans('main.error_user_wait_approve'));
            }/* elseif ($user->isBusinessAccount() && $user->isNotApproved()) {
                throw new JWTException(trans('main.error_user_not_approved'));
            }*/
            if ($credentials['is_admin'] && !$user->isAdminAccount()) {
                throw new JWTException(trans('main.error_user_not_admin'));
            }
            if (!\Hash::check($credentials['password'], $user->password)) {
                throw new JWTException(trans('main.error_user_not_found'));
            }

            if (!$credentials['is_admin'] && $user->isAdminAccount() ){
                throw new JWTException(trans('main.error_user_is_admin'));
            }

            $token = JWTAuth::fromUser($user);
        } catch (JWTException $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 403);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 403);
        }
        return response()->json(['success' => true, 'token' => $token, 'user' => $user->toArray()]);
    }


    public function createPrivateAccount(Request $request)
    {

        try {
            $data = $request->only(['email','password','sex','name','surname','re_email','re_password','agb','captcha','test']);
            $user = User::createPrivateAccount($data);

            return response()->json(['success' => true, 'user' => $user->afterRegisterArray()]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function forgotPassword(Request $request)
    {
        try {
            $email = $request->get('email');
            $user = User::getUserByEmail($email);
            if (!is_null($user)){
                dispatch(new ForgotPasswordJob($user));
                return response()->json(['success' => true, 'message' => 'We sent reset link to you email']);
            }
            return response()->json(['success' => false, 'error' => 'User not found']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function resetPassword($user_id, $key)
    {
        $error = null;
        try {
            $user = User::find($user_id);
            if (is_null($user)) {
                throw new \Exception('user_not_found');
            }
            if ($user->activate_key != $key) {
                throw new \Exception('invalid_reset_link');
            }
            $new_pass = $user->resetPassword($key);
            dispatch(new NewPasswordJob($user, $new_pass));


        } catch (\Exception $e) {
            $error = trans('validation.' . $e->getMessage());
        }

        return view('controller.user.resetPassword', ['error' => $error]);
    }

    public function changeEmail(Request $request)
    {
        $data = $request->only(['email', 're_email', 'code']);
        try {
            $user = User::getUser();
            if ($user->activate_key != $data['code']) {
                throw new \Exception('Invalid code');
            }
            $old_email = $user->email;
            $user->changeEmail($data['email'], $data['re_email']);
            dispatch(new EmailChanged($user));
            EventsLog::changeEmail($user, $old_email);
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            $error = trans('validation.' . $e->getMessage());
            return response()->json(['error' => $error], 500);
        }
    }

    public function sendConfirmCode(Request $request)
    {
        $email = $request->get('email');
        try {
            $user = User::getUser();
            $code = $user->updateConfirmCode();
            dispatch(new ConfirmCodeJob($user, $code, $email));
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function changePayment(Request $request)
    {
        $data = $request->only(['payment_type', 'paypal_email', 'giro_account']);
        try {
            $user = User::getUser();
            $old_fields = [
                'payment_type'=>$user->payment_type,
                'paypal_email'=>$user->paypal_email,
                'giro_account'=>$user->giro_account,
            ];
            $user->changePayment($data);
            EventsLog::changePayment($user, $old_fields);
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            $error = trans('validation.' . $e->getMessage());
            return response()->json(['error' => $error], 500);
        }
    }

    public function changeContactData(Request $request)
    {
        $data = $request->only(['sex', 'name', 'surname', 'address_zip', 'address_city', 'address_street', 'address_number', 'address_additional', 'phone']);

        try {
            $user = User::getUser();
            $old_fields = $user->toArray();
            $user->changeContactData($data);
            EventsLog::changeContactData($user, $old_fields);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            $error = trans('validation.' . $e->getMessage());
            return response()->json(['error' => $error], 500);
        }
    }


    public function changePassword(Request $request)
    {
        $data = $request->only(['current_password', 'password', 're_password']);

        try {
            $user = User::getUser();
            $user->changePassword($data['current_password'], $data['password'], $data['re_password']);
            dispatch(new PasswordChanged($user,$data['password']));
            EventsLog::changePassword($user);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            $error = trans('validation.' . $e->getMessage());
            return response()->json(['error' => $error], 500);
        }
    }

    public function deleteAccount()
    {
        try {
            $user = User::getUser();
            $user->deleteAccount();
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            $error = trans('validation.' . $e->getMessage());
            return response()->json(['error' => $error], 500);
        }
    }

    public function getEventsLog($user_id){
        try {
            $user = User::getUser();
            if (is_null($user) || false===$user->isAdminAccount()){
                return response()->json(null, 403);
            }

            $user = User::find($user_id);
            return response()->json($user->getEventsLog());

        } catch (\Exception $e) {
            $error = trans('validation.' . $e->getMessage());
            return response()->json(['error' => $error], 500);
        }
    }

    public function deleteAccountById($user_id)
    {
        try {
            $user = User::getUser();
            if (is_null($user) || false===$user->isAdminAccount()){
                return response()->json(null, 403);
            }

            $user = User::find($user_id);
            $user->deleteAccount();
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            $error = trans('validation.' . $e->getMessage());
            return response()->json(['error' => $error], 500);
        }
    }

    public function setBlockStatus($user_id)
    {
        try {
            $user = User::getUser();
            if (is_null($user) || false===$user->isAdminAccount()){
                return response()->json(null, 403);
            }

            $user = User::find($user_id);
            $user->blockAccount();
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            $error = trans('validation.' . $e->getMessage());
            return response()->json(['error' => $error], 500);
        }
    }

    public function allowNotifications(Request $request)
    {
        $allow_notifications = $request->get('allow_notifications');
        try {
            $user = User::getUser();
            $user->changeAllowNotifications($allow_notifications);
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            $error = trans('validation.' . $e->getMessage());
            return response()->json(['error' => $error], 500);
        }
    }

    public function createBusinessAccount(Request $request)
    {
        try {
            $data = $request->only(['address_additional', 'address_city', 'address_number', 'address_street', 'address_zip', 'agb', 'captcha', 'commercial_country', 'commercial_id', 'commercial_additional', 'company', 'contact_email', 'email', 'giro_account', 'name', 'password', 'payment_type', 'paypal_email', 'phone', 're_email', 're_password', 'sex', 'surname', 'tariff', 'website']);
            $user = User::createBusinessAccount($data);



            return response()->json(['success' => true, 'user' => $user->toArray()]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function createAdministratorAccount(Request $request){
        try {
            $user = User::getUser();
            if (!$user || !$user->hasPermissions('administration') ){
                return response()->json(null, 403);
            }
            $data = $request->only(['sex', 'name', 'email', 'password', 'surname', 'initials','permissions']);
            $user = User::createAdministratorAccount($data);

           // dispatch(new ActivatePrivateAccountJob($user));

            return response()->json($user->toArray());
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function updateAdministratorAccount(Request $request, $id){
        try {
            $user = User::getUser();
            if (!$user || !$user->hasPermissions('administration') ){
                return response()->json(null, 403);
            }
            $data = $request->only(['sex', 'name', 'email', 'password', 'surname', 'initials','permissions']);
            $user = User::find($id);
            $user->updateAdministratorAccount($data);
            return response()->json($user->toArray());
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function updateAccount(Request $request, $id){
        try {

            $user = User::getUser();
            if (!$user || !$user->hasPermissions('accounts') ){
                return response()->json(null, 403);
            }
            $data = $request->only(['sex', 'name', 'contact_email', 'password', 'surname', 'commercial_id','company','website','address_street','address_number','address_zip','address_city','address_additional','commercial_country','phone']);

            $user = User::find($id);
            $user->updateAccount($data);
            return response()->json($user->toArray());
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    public function deleteAdministratorAccount($id){
        try {
            $user = User::getUser();
            if ( !$user || !$user->hasPermissions('administration')  ){
                return response()->json(null, 403);
            }

            $user = User::find($id);
            $user->deleteAccount();
            // dispatch(new ActivatePrivateAccountJob($user));
            return response()->json(['success'=>true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function activateAccount($user_id, $key)
    {
        $error = null;
        try {
            $user = User::find($user_id);
            if (is_null($user)) {
                throw new \Exception('user_not_found');
            }
            $user->activateAccount($key);
        } catch (\Exception $e) {
            $error = trans('validation.' . $e->getMessage());
        }

        return view('controller.user.activateAccount', ['error' => $error]);
    }

    public function confirmAccount($user_id, $key)
    {
        $error = null;
        try {
            $user = User::find($user_id);
            if (is_null($user)) {
                throw new \Exception('user_not_found');
            }
            $user->confirmAccount($key);
        } catch (\Exception $e) {
            $error = trans('validation.' . $e->getMessage());
        }

        return view('controller.user.confirmAccount', ['error' => $error]);
    }

    public function getAuth(Request $request)
    {
        $token = $request->get('token');
        try {
            $user = User::getUser($token);
            if (is_null($user)) {
                throw new \Exception('no user');
            }
            if ($user->isDeletedAccount()) {
                JWTAuth::invalidate(JWTAuth::getToken());
                throw new \Exception('no user');
            }

            return response()->json($user->toArray());
        } catch (\Exception $e) {
            return response()->json(null, 403);
        }
    }

    public function getAllCountries(){
        $countries = User::whereNotNull('commercial_country')->groupBy('commercial_country')->pluck('commercial_country');
        return response()->json($countries);
    }

    public function search(Request $request){
        $data = $request->only(['page','limit','filters']);

        $rows = User::getByPage($data['page'], $data['limit'], $data['filters']);
        return response()->json(['total'=>User::getTotal($data['filters']),'rows'=>$rows->toArray()]);
    }

    public function setActiveStatus($user_id){
        $current_user = User::getUser();
        if (is_null($current_user) || !$current_user->hasPermissions('accounts')){
            return response()->json(null, 403);
        }
        $user = User::find($user_id);

        $result = $user->setActivateStatus();
        if ( true===$result ){
            EventsLog::accountIsActivated($user, $current_user);
            if ($user->isPrivateAccount()){
                dispatch(new ActivatePrivateAccount($user));
            }elseif($user->isBusinessAccount()){
                dispatch(new ActivateBusinessAccount($user));
            }
        }


        return response()->json(['success'=>true]);
    }

    public function getAllNewBusiness(){
        $user = User::getUser();
        if (is_null($user) || false===$user->isAdminAccount()){
            return response()->json(null, 403);
        }
        return response()->json(User::getAllNewBusiness());
    }
    public function getAllBlocked(){
        $user = User::getUser();
        if (is_null($user) || false===$user->isAdminAccount()){
            return response()->json(null, 403);
        }
        return response()->json(User::getAllBlocked());
    }
    public function getAllAdministration(){
        $user = User::getUser();
        if (is_null($user) || false===$user->isAdminAccount() || !$user->hasPermissions('administration')){
            return response()->json(null, 403);
        }
        return response()->json(User::getAllAdministration());
    }

    public function getStatistics(Request $request){
        $token = $request->get('token');
        $current_user = User::getUser($token);
        if ( is_null($current_user) || !$current_user->hasPermissions('statistics') ){
            return response()->json(['success'=>false],403);
        }
        $users = User::getOnlyGroups();

        $result = [
            '1'=>0,
            '2' => 0,
            '3' => 0,
        ];

        foreach ($users as $user) {
            $result[$user->group_id]++;
        }
        return response()->json(['private'=>$result['2'],'business'=>$result['3'],'total'=>$result['2']+$result['3']]);
    }


}
