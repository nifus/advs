<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\Jobs\ActivatePrivateAccount as ActivatePrivateAccountJob;
use Tymon\JWTAuth\Exceptions\JWTException;

class SearchController extends Controller
{
    public function __construct()
    {
        //$this->middleware('jwt.auth', ['except' => [ 'authenticate','']]);
    }

    function rent() {
        $user = User::getUser();
        return view('controller.search.rent',['user'=>$user]);
    }
}
