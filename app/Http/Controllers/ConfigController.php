<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\News as NewsModel;


class ConfigController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => []]);
    }

    static private $config_file = 'config/config.json';

    function announcement($type, Request $request){
        $user = User::getUser();
        if ( is_null($user) || !$user->isAdminAccount() ){
            return response()->json(['success'=>false],403);
        }
        try{
            $data = $request->only(['status','text']);
            $validator = [
                'status' => 'required',
                'text' => 'required',
            ];
            $validator = \Validator::make($data, $validator);
            if ($validator->fails()) {
                $messages = $validator->messages();
                throw new \Exception($messages->first());
            }
            $config = self::getConfig();
            $date = new \DateTime();
            $data['updated_at'] = $date->format('Y-m-d H:i:s');
            $data['author'] = $user->email;
            $config->announcement->$type = $data;
            self::saveConfig($config);
        }catch ( \Exception $e ){
            return response()->json(['success'=>false, 'error'=>$e->getMessage()],500);

        }
        return response()->json(['success'=>true]);
    }

    function instruction(Request $request){

        $data = $request->all();
        $config = self::getConfig();
        if ( isset($config->instructions) ){
            array_push($config->instructions,$data);
        }else{
            $config->instructions = [$data];
        }

        self::saveConfig($config);
        return response()->json(['success'=>true]);
    }

    private function getConfig(){
        if ( file_exists(public_path(self::$config_file)) ){
            return json_decode(file_get_contents(public_path(self::$config_file)));
        }
        $init = json_decode('{"announcement":{"private":{},"business":{}}}');
        return $init;

    }

    private function saveConfig($config){
        file_put_contents(public_path(self::$config_file), json_encode($config));
    }

}
