<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Adv as AdvModel;
use App\User as UserModel;
use App\Category as CategoryModel;

use League\Flysystem\Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AdvController extends Controller
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

    function update( $id,  Request $request){
        try{
            $user = UserModel::getUser();
            if ( is_null($user) ){
                abort(403);
            }
            $adv = AdvModel::findOrDie($id);
            if ( !$adv->itsAuthor($user->id) ){
                abort(403);
            }
            $data = $request->all();
            $adv->updateAdv($data);
            return response()->json($adv->toArray());
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }



    function create(){
        $user = UserModel::getUser();
        return view('controller.adv.create', [
            'user' => $user,
            'categories'=>CategoryModel::getCategories()
        ]);
    }

    function simplePreview($id)
    {
        $user = UserModel::getUser();
        $adv = AdvModel::findOrDie($id);
        return view('controller.adv.preview', ['user' => $user, 'adv' => $adv]);
    }

    function preview($id)
    {
        $user = UserModel::getUser();
        $adv = AdvModel::findOrDie($id);
        return view('controller.adv.preview', ['user' => $user, 'adv' => $adv]);
    }

    function getUserAdvById($id)
    {
        try {
            $user = UserModel::getUserOrDie();
            $adv = AdvModel::findOrDie($id);

            if (!$adv->isOwner($user->id)) {
                abort(403, 'Access Error');
            }
            return response()->json($adv->toArray());

        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    function favlist($id,Request $request){
        try {
            $action = $request->get('action');
            $adv = AdvModel::findOrDie($id);
            $user = UserModel::getUser();
            if (is_null($user)){
                throw new \Exception('No user');
            }
            $action=='add' ? $user->addFavAdv($adv->id) : $user->removeFavAdv($adv->id);

            $adv->updateFavs();
            return response()->json(['success'=>true]);

        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    function getAdvById($id)
    {
        try {
            $adv = AdvModel::findOrDie($id);
            $user = UserModel::getUser();
            if (is_null($user)){
                return response()->json($adv->getArray());
            }else{
                return response()->json($adv->getArray($user->id));
            }

        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    function getRestoreAdvertById($id)
    {
        try {
            $adv = AdvModel::findOrDie($id);
            $user = UserModel::getUser();
            if ( $adv->is_deleted=='1' ){
                return response()->json([], 404);
            }
            if (is_null($user)){
                return response()->json([], 403);
            }
            if ($adv->user_id!=$user->id){
                return response()->json([], 403);
            }
            if ($adv->status!='payment_waiting'){
                return response()->json([], 403);
            }

            return response()->json($adv->getArray($user->id));


        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    function getByUser(Request $request, $user_id)
    {
        $token = $request->get('token');
        $current_user = UserModel::getUser($token);

        if ( is_null($current_user) || !$current_user->hasPermissions('accounts') ){
            return response()->json(['success'=>false],403);
        }

        $user = UserModel::find($user_id);

        $advs = AdvModel::getByUser($user->id);
        return response()->json($advs);
    }


    function getByCurrentUser()
    {
        $user = UserModel::getUser();
        if (is_null($user)){
            return response()->json(['success'=>false],403);
        }
        $advs = AdvModel::getByUser($user->id);
        return response()->json($advs);
    }

    function getWatchByCurrentUser()
    {
        $user = UserModel::getUser();
        $favs = $user->Fav()->get();
        //$advs = AdvModel::getWatchByUser($user->id);
        return response()->json($favs);
    }

    function removeWatch($adv_id)
    {
        $user = UserModel::getUser();
        AdvModel::removeWatch($user->id, $adv_id);
        return response()->json(['success' => true]);
    }

    function delete($adv_id)
    {
        try {
            $user = UserModel::getUser();
            if (is_null($user)) {
                abort(403, 'Access Error');
                //return response()->json(null,403);
            }
            $adv = AdvModel::findOrDie($adv_id);
            if (!$adv->isOwner($user->id)) {
                abort(403, 'Access Error');
                // return response()->json(null,403);
            }

            $adv->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

    }

    function changeStatus($adv_id, Request $request)
    {
        try {
            $status = $request->get('status');
            $user = UserModel::getUser();
            if (is_null($user)) {
                abort(403, 'Access Error');
                //return response()->json(null,403);
            }
            $adv = AdvModel::findOrDie($adv_id);
            if (!$adv->isOwner($user->id)) {
                abort(403, 'Access Error');
                // return response()->json(null,403);
            }

            $adv->changeStatus($status);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

    }


    public function getStatistics(Request $request){
        $token = $request->get('token');
        $current_user = UserModel::getUser($token);

        if ( is_null($current_user) || !$current_user->hasPermissions('statistics') ){
            return response()->json(['success'=>false],403);
        }
        $advs = AdvModel::getWithStatus();

        $result = [
            'rent' => [
                'total' => 0,
                'payment_waiting' => 0,
                'active' => 0,
                'disabled' => 0,
                'expired' => 0,
                'blocked' => 0,
                'approve_waiting'=>0

            ],
            'sale' => [
                'total' => 0,
                'payment_waiting' => 0,
                'active' => 0,
                'disabled' => 0,
                'expired' => 0,
                'blocked' => 0,
                'approve_waiting'=>0
            ]
        ];

        foreach ($advs as $adv) {
            $result[$adv->type][$adv->status]++;
            $result[$adv->type]['total']++;
        }
        return response()->json($result);
    }

    public function getStatisticsById($user_id)
    {
        $current_user = UserModel::getUser();
        $user_stat = UserModel::find($user_id);
        if ($current_user->id!=$user_stat->id && !$current_user->isAdminAccount() ){
            return response()->json([],403);
        }

        $advs = AdvModel::getByUserWithStatus($user_stat->id);

        $result = [
            'rent' => [
                'total' => 0,
                'payment_waiting' => 0,
                'active' => 0,
                'disabled' => 0,
                'expired' => 0,
                'blocked' => 0,
                'approve_waiting'=>0

            ],
            'sale' => [
                'total' => 0,
                'payment_waiting' => 0,
                'active' => 0,
                'disabled' => 0,
                'expired' => 0,
                'blocked' => 0,
                'approve_waiting'=>0
            ]
        ];

        foreach ($advs as $adv) {
            $result[$adv->type][$adv->status]++;
            $result[$adv->type]['total']++;
        }
        return response()->json($result);
    }

    public function message($adv_id, Request $request){
        try{
            $adv = AdvModel::findOrDie($adv_id);
            $data = $request->only(['message','email','phone','name','sex']);
            $id = $request->ip();
            $adv->sendMessage($data,$id);

            return response()->json(['success'=>true]);
        }catch( \Exception $e ){
            return response()->json(['success'=>false,'error'=>$e->getMessage()]);
        }


    }
}
