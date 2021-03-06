<?php

namespace App\Http\Controllers;

use App\AdvReport;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Adv as AdvModel;
use App\EventsLog;
use App\User as UserModel;
use App\Category as CategoryModel;

use League\Flysystem\Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AdvController extends Controller
{

    function getBlocked(Request $request)
    {
        $token = $request->get('token');
        $current_user = UserModel::getUser($token);

        if (is_null($current_user) || !$current_user->hasPermissions('advert')) {
            return response()->json(['success' => false], 403);
        }
        $result = [];
        $adverts = AdvModel::getBlocked();
        foreach( $adverts as $advert ){
            $event = $advert->getBlockedEvent();
            array_push($result, array_merge($advert->toArray(),['block_event'=>$event]));
        }

        return response()->json($result);
    }

    function getReports(Request $request)
    {
        $token = $request->get('token');
        $current_user = UserModel::getUser($token);

        if (is_null($current_user) || !$current_user->hasPermissions('advert')) {
            return response()->json(['success' => false], 403);
        }

        $adverts = AdvModel::getReports();
        return response()->json($adverts);
    }

    function createReport($id, Request $request)
    {
        try {
            $token = $request->get('token');
            $user = UserModel::getUser($token);
            if (is_null($user)) {
                return response()->json([], 403);
            }
            $data = $request->only(['reason', 'message']);
            $adv = AdvModel::findOrDie($id);
            $adv->createReport($user, $data);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    function deleteReports($id, Request $request)
    {
        try {
            $token = $request->get('token');
            $current_user = UserModel::getUser($token);

            if (is_null($current_user) || !$current_user->hasPermissions('advert')) {
                return response()->json(['success' => false], 403);
            }

            $advert = AdvModel::findOrDie($id);
            $advert->removeReports();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    function uploadImages(Request $request)
    {
        $token = $request->get('token');
        $user = UserModel::getUser($token);
        if (is_null($user)) {
            return response()->json([], 403);
        }

        $response = [];
        $files = $request->file('files');
        foreach ($files as $file) {
            $result = $user->uploadAdvertImage($file);
            if (is_array($result)) {
                array_push($response, $result);

            }
        }
        return response()->json(['images' => $response]);
    }

    function store(Request $request)
    {
        try {
            $token = $request->get('token');
            $user = UserModel::getUser($token);
            if (is_null($user)) {
                return response()->json([], 403);
            }

            $data = $request->all();
            $adv = AdvModel::createNewAdv($data, $user);
            return response()->json($adv->toArray());
        } catch (\Exception $e) {

            return response()->json($e->getMessage(), 500);
        }
    }

    function update($id, Request $request)
    {
        try {
            $token = $request->get('token');
            $user = UserModel::getUser($token);
            if (is_null($user)) {
                return response()->json([], 403);
            }
            $adv = AdvModel::findOrDie($id);
            if (!$adv->itsAuthor($user->id)) {
                return response()->json([], 403);
            }
            $data = $request->all();
            $adv->updateAdv($data);
            return response()->json($adv->toArray());
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }


    function create()
    {
        $user = UserModel::getUser();
        return view('controller.adv.create', [
            'user' => $user,
            'categories' => CategoryModel::getCategories()
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
    function getUserAdvByIdWithBlockMessage($id)
    {
        try {
            $user = UserModel::getUserOrDie();
            $adv = AdvModel::findOrDie($id);

            if (!$adv->isOwner($user->id)) {
                abort(403, 'Access Error');
            }
            $result = $adv->toArray();
            $result['blocked_event'] = $adv->getBlockedEvent();
            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    function favlist($id, Request $request)
    {
        try {
            $action = $request->get('action');
            $adv = AdvModel::findOrDie($id);
            $user = UserModel::getUser();
            $adv->updateFavs($action, $user);
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    function getAdvById($id)
    {
        try {
            $adv = AdvModel::findOrDie($id);
            $user = UserModel::getUser();
            if (is_null($user)) {
                return response()->json($adv->getArray());
            } else {
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
            if ($adv->is_deleted == '1') {
                return response()->json([], 404);
            }
            if (is_null($user)) {
                return response()->json([], 403);
            }
            if ($adv->user_id != $user->id) {
                return response()->json([], 403);
            }
            if ($adv->status != 'payment_waiting') {
                return response()->json([], 403);
            }

            $payment = $adv->getLastPayment();
            $response = $adv->getArray($user->id);
            $response['LastPayment'] = $payment;


            return response()->json($response);


        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    function getByUser(Request $request, $user_id)
    {
        $token = $request->get('token');
        $current_user = UserModel::getUser($token);

        if (is_null($current_user)) {
            return response()->json(['success' => false], 403);
        }

        $user = UserModel::find($user_id);

        $advs = AdvModel::getByUser($user->id);
        return response()->json($advs);
    }


    function getByCurrentUser()
    {
        $user = UserModel::getUser();
        if (is_null($user)) {
            return response()->json(['success' => false], 403);
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

    /**
     * Function for administrators
     * @param $adv_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function changeStatus($adv_id, Request $request)
    {
        try {
            $status = $request->get('status');
            $message = $request->get('message');
            $current_user = UserModel::getUser();
            if (is_null($current_user) || !$current_user->hasPermissions('advert')) {
                return response()->json(['success' => false], 403);
            }

            $adv = AdvModel::findOrDie($adv_id);
            //if (!$adv->isOwner($user->id)) {
            //abort(403, 'Access Error');
            //  return response()->json(null, 403);
            //}
            $old = $adv->status;
            $adv->changeStatus($status);
            if ( $status=='blocked' ){
                EventsLog::blockAdvert($current_user, $adv, $old, $message);
                $adv->removeReports();
            }else{
                EventsLog::changeAdvertStatus($current_user, $adv, $old, $message);
            }
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    function disable($adv_id)
    {
        try {
            $user = UserModel::getUser();
            $adv = AdvModel::findOrDie($adv_id);
            if (!$adv->isOwner($user->id)) {
                return response()->json(null, 403);
            }
            if ($adv->status!='active'){
                return response()->json(null, 403);
            }
            $old = $adv->status;
            $adv->changeStatus('disabled');
            EventsLog::changeAdvertStatus($user, $adv, $old);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    function activate($adv_id)
    {
        try {
            $user = UserModel::getUser();
            $adv = AdvModel::findOrDie($adv_id);
            if (!$adv->isOwner($user->id)) {
                return response()->json(null, 403);
            }
            if ($adv->status!='disabled'){
                return response()->json(null, 403);
            }
            $old = $adv->status;
            $adv->changeStatus('active');
            EventsLog::changeAdvertStatus($user, $adv, $old);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function getStatistics(Request $request)
    {
        $token = $request->get('token');
        $current_user = UserModel::getUser($token);

        if (is_null($current_user) || !$current_user->hasPermissions('statistics')) {
            return response()->json(['success' => false], 403);
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
                'approve_waiting' => 0

            ],
            'sale' => [
                'total' => 0,
                'payment_waiting' => 0,
                'active' => 0,
                'disabled' => 0,
                'expired' => 0,
                'blocked' => 0,
                'approve_waiting' => 0
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
        if ($current_user->id != $user_stat->id && !$current_user->isAdminAccount()) {
            return response()->json([], 403);
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
                'approve_waiting' => 0

            ],
            'sale' => [
                'total' => 0,
                'payment_waiting' => 0,
                'active' => 0,
                'disabled' => 0,
                'expired' => 0,
                'blocked' => 0,
                'approve_waiting' => 0
            ]
        ];

        foreach ($advs as $adv) {
            $result[$adv->type][$adv->status]++;
            $result[$adv->type]['total']++;
        }
        return response()->json($result);
    }

    public function message($adv_id, Request $request)
    {
        try {
            $adv = AdvModel::findOrDie($adv_id);
            $data = $request->only(['message', 'email', 'phone', 'name', 'sex']);
            $id = $request->ip();
            $adv->sendMessage($data, $id);
            //EventsLog::createAccount($adv);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function viewIncrement($adv_id){
        $adv = AdvModel::findOrDie($adv_id);
        $adv->viewIncrement();
        return response()->json(['success' => true]);
    }
}
