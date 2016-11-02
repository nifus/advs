<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\Adv;
use App\SearchLog;
use App\Jobs\ActivatePrivateAccount as ActivatePrivateAccountJob;
use Tymon\JWTAuth\Exceptions\JWTException;

class SearchController extends Controller
{
    public function __construct()
    {
        //$this->middleware('jwt.auth', ['except' => [ 'authenticate','']]);
    }

    function rent( Request $request) {
        $id = $request->get('id');
        if ($id){
            $log = SearchLog::find($id);
        }else{
            $log = null;
        }
        //$user = User::getUser();
        return view('controller.search.rent',[
            'categories'=>Adv::getCategories(),
            'search'=>$log
        ]);
    }

    function buy( Request $request ) {
        $id = $request->get('id');
        if ($id){
            $log = SearchLog::find($id);
        }else{
            $log = null;
        }
        //$user = User::getUser();
        return view('controller.search.buy',[
            'categories'=>Adv::getCategories(),
            'search'=>$log
        ]);
    }

    function createSearch( Request $request ){
        $query = $request->get('query');
        $log = SearchLog::create(['query'=>json_encode($query)]);
        return response()->json(['success'=>true,'id'=>$log->id]);
    }

    function getSearch($id){
        $log = SearchLog::find($id);
        return response()->json(['success'=>true,'search'=>$log->toArray()], 200, [], JSON_NUMERIC_CHECK);
    }

    function searchResult($id){
        $log = SearchLog::find($id);

        return view('controller.search.result',[
            'search'=>$log
        ]);
    }
}
