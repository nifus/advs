<?php

namespace App\Http\Controllers;

use App\Variable;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;

class VariableController extends Controller
{

    function update($id, Request $request){
        try{
            $token = $request->get('token');
            $current_user = User::getUser($token);
            if ( is_null($current_user) || !$current_user->hasPermissions('variables') ){
                return response()->json(['success'=>false],403);
            }
            $value = $request->get('value');
            $variable = Variable::find($id);
            $variable->updateValue($value, $current_user);

            return response()->json(Variable::getById($id)->toArray());
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    function getAll(Request $request){
        $token = $request->get('token');
        $current_user = User::getUser($token);
        if ( is_null($current_user) || !$current_user->hasPermissions('variables') ){
            return response()->json(['success'=>false],403);
        }

        $variables = Variable::getAll();
        return response()->json($variables);
    }

    function getById($id){
        $var = Variable::getById($id);
        return response()->json($var);
    }






}
