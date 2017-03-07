<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Faq;


class FaqController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => []]);
    }


    function store(Request $request){
        $user = User::getUser();
        if ( is_null($user) || !$user->hasPermissions('portal') ){
            return response()->json(['success'=>false],403);
        }
        try{
            $data = $request->only(['title','type','desc']);
            $faq = Faq::createElement($data);
        }catch ( \Exception $e ){
            return response()->json(['success'=>false, 'error'=>$e->getMessage()],500);

        }
        return response()->json($faq);
    }

    function update(Request $request, $id){

        $user = User::getUser();
        if ( is_null($user) || !$user->hasPermissions('portal') ){
            return response()->json(['success'=>false],403);
        }
        try{
            $data = $request->only(['title','type','desc']);
            $faq = Faq::find($id);
            if ( is_null($faq)){
                return response()->json(['success'=>false],404);
            }
            $faq->updateElement($data);
        }catch ( \Exception $e ){
            return response()->json(['success'=>false, 'error'=>$e->getMessage()],500);

        }
        return response()->json(['success'=>true]);
    }

    function delete( $id){

        $user = User::getUser();
        if ( is_null($user) || !$user->hasPermissions('portal') ){
            return response()->json(['success'=>false],403);
        }
        try{
            $faq = Faq::find($id);
            if ( is_null($faq)){
                return response()->json(['success'=>false],404);
            }
            $faq->delete();
        }catch ( \Exception $e ){
            return response()->json(['success'=>false, 'error'=>$e->getMessage()],500);

        }
        return response()->json(['success'=>true]);
    }

    function getAll(){
        $faqs = Faq::getAll();
        return response()->json($faqs);
    }

}
