<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\MailTemplate;


class MailTemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => []]);
    }




    function update(Request $request, $id){

        $user = User::getUser();
        if ( is_null($user) || !$user->isAdminAccount() ){
            return response()->json(['success'=>false],403);
        }
        try{
            $data = $request->only(['body','header','user_id']);
            $template = MailTemplate::find($id);
            if ( is_null($template)){
                return response()->json(['success'=>false],404);
            }
            $template->updateByUser($data, $user);
        }catch ( \Exception $e ){
            return response()->json(['success'=>false, 'error'=>$e->getMessage()],500);
        }
        return response()->json( MailTemplate::getById($id)->toArray() );
    }



    function getAll(){
        $user = User::getUser();
        if ( is_null($user) || !$user->isAdminAccount() ){
            return response()->json(['success'=>false],403);
        }
        $templates = MailTemplate::getAll();
        return response()->json($templates);
    }

}
