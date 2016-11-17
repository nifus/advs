<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\Adv;
use App\SearchLog;
use App\Place;
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
            $place = Place::find($log->query->city_id);

        }else{
            $log = null;
            $place = null;
        }
        //$user = User::getUser();
        return view('controller.search.rent',[
            'categories'=>Adv::getCategories(),
            'search'=>$log,
            'place'=>$place,
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
        $place = Place::find($log->query->city_id);

        return response()->json(['success'=>true,'search'=>$log->toArray(),'place'=>$place->toArray()], 200, [], JSON_NUMERIC_CHECK);
    }

    function searchResult($id){
        $log = SearchLog::find($id);
        $search_back = $log->query->type=='rent' ? route('adv.rent') : route('adv.sale');
        $search_back.='?id='.$id;

        return view('controller.search.result',[
            'search'=>$log,
            'search_back'=>$search_back,
            //'result'=>$result->toArray()
        ]);
    }

    function search($id){
        $log = SearchLog::find($id);

        $place = Place::find($log->query->city_id);

        //SELECT id, ( 3959 * acos( cos( radians(37) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(-122) ) + sin( radians(37) ) * sin( radians( lat ) ) ) ) AS distance FROM markers HAVING distance < 25 ORDER BY distance LIMIT 0 , 20;

        $sql = Adv::where('category',$log->query->category);

        //$sql = $sql->where('city_id', $log->query->city_id);

        //$count = $sql->count();
        $result = $sql->get();

        return response()->json(['search'=>$log->toArray(),'advs'=>$result->toArray(),'city'=>$place->toArray()]);
    }

    function searchUpdate($id, Request $request ){
        $fields = $request->only(['per_page','sortby']);
        $log = SearchLog::find($id);
        $log->update(['config'=>$fields]);
        return response()->json(['search'=>$log->toArray()]);

    }

    function findCity($name){
        $result = Place::likeCities($name);
        return response()->json(['cities'=>$result->toArray()]);

    }
}
