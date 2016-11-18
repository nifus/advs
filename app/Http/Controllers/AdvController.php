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

    function getByUser()
    {
        $user = UserModel::getUser();
        $advs = AdvModel::getByUser($user->id);
        return response()->json($advs);
    }

    function getWatchByUser()
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


    public function getStat()
    {

        $user = UserModel::getUser();
        $advs = AdvModel::getByUserWithStatus($user->id);

        $result = [
            'rent' => [
                'total' => 0,
                'payment_waiting' => 0,
                'active' => 0,
                'disabled' => 0,
                'expired' => 0,
                'blocked' => 0

            ],
            'sell' => [
                'total' => 0,
                'payment_waiting' => 0,
                'active' => 0,
                'disabled' => 0,
                'expired' => 0,
                'blocked' => 0
            ]
        ];

        foreach ($advs as $adv) {
            $result[$adv->type][$adv->status]++;
            $result[$adv->type]['total']++;
        }
        return response()->json($result);
    }
}
