<?php

namespace App\Http\Controllers;



class UserController extends Controller
{

    public function privateAccountForm(){
        return view('controller.user.privateAccountForm');
    }
}
