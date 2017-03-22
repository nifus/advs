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
            //$place = Place::find($log->query->city_id);

        }else{
            $log = null;
            $place = null;
        }
        //$user = User::getUser();
        return view('controller.search.rent',[
            'categories'=>Adv::getCategories(),
            'search'=>$log,
            'place'=>null,
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

    function searchResult($id){
        $log = SearchLog::find($id);
        if ( is_null($log) || $log->type!='advs' ){
            abort(404);
        }

        $search_back = $log->query['type']=='rent' ? route('adv.rent') : route('adv.sale');
        $search_back.='?id='.$id;

        return view('controller.search.result',[
            'search'=>$log,
            'search_back'=>$search_back,
            //'result'=>$result->toArray()
        ]);
    }

    function createSearch( $type, Request $request ){
        $query = $request->get('query');

        if ($type=='accounts'){
            $count = User::getTotal($query);
        }else{
            $sql =Adv::orderBy('title','ASC');
            $count = $sql->count();
        }

        $log = SearchLog::create(['query'=>($query),'type'=>$type, 'number_of_results'=>$count]);
        return response()->json($log->toArray());
    }

    function getSearch($id){
        $log = SearchLog::find($id);
        if (is_null($log)){
            return response()->json(['success'=>false],404);
        }
        //$place = Place::find($log->query->city_id);
        return response()->json(['success'=>true,'search'=>$log,'place'=>null], 200, [], JSON_NUMERIC_CHECK);
    }



    function search($id, Request $request ){
        $configs = $request->only(['page','per_page']);
        $log = SearchLog::find($id);
        $config = $log->config;
        foreach($configs as $field=>$value ){
            $config[$field]=$value;
        }
        $log->update(['config'=>$config]);
        $user = User::getUser();
        $user_id = !is_null($user) ? $user->id : null;
        //$place = Place::find($log->query->city_id);

        //SELECT id, ( 3959 * acos( cos( radians(37) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(-122) ) + sin( radians(37) ) * sin( radians( lat ) ) ) ) AS distance FROM markers HAVING distance < 25 ORDER BY distance LIMIT 0 , 20;
        if ($log->type=='advs'){
            //if ($user->isAdminAccount()){
               // $sql =Adv::with('Owner')->orderBy('created_at','DESC');
            //}else{
           //     $sql =Adv::with('Owner')->orderBy('title','ASC');
          //  }
            $advs = Adv::getByPage($log->config['page'],$log->config['per_page'],$log->query);
            $result = [];
            foreach($advs as $adv){
                array_push($result, $adv->getArray($user_id) );
            }
        }else{
            $result = User::getByPage($log->config['page'],$log->config['per_page'],$log->query);
        }


        return response()->json(['search'=>$log->toArray(),'rows'=>$result]);
    }

    function searchConfigUpdate($id, Request $request ){
        // $fields = $request->only(['per_page','sortby','display_map','lat','lng','zoom','page']);
        $config = $request->get('config');

        $log = SearchLog::find($id);
        $log->update(['config'=>$config]);
        return response()->json(['search'=>$log->toArray()]);

    }

    function searchQueryUpdate($id, Request $request ){
       // $fields = $request->only(['per_page','sortby','display_map','lat','lng','zoom','page']);
        $query = $request->get('query');
        $log = SearchLog::find($id);

        if ($log->type=='accounts'){
            $count = User::getTotal($query);
        }else{
            $sql =Adv::orderBy('title','ASC');
            $count = $sql->count();
        }
       // $config = $log->config;
       // $config['page']=1;
        //$data['number_of_results'] = $count;
        $log->update(['query'=>$query,'number_of_results'=>$count]);
        return response()->json(['search'=>$log->toArray()]);

    }

    function findCity($name){
        $result = Place::likeCities($name);
        return response()->json(['cities'=>$result->toArray()], 200, [], JSON_NUMERIC_CHECK);

    }



}
