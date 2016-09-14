<?php



Route::get('/', ['as'=>'main','uses'=>function () {
    return view('frontApp');
}]);


Route::get('/register/private', ['as'=>'register.private','uses'=> 'UserController@privateAccountForm']);

Route::get('/register/business', ['as'=>'register.business','uses'=> 'UserController@businessAccountForm']);

Route::post('/register', ['as'=>'register','uses'=> 'UserController@store']);



Route::get('/rent', ['as'=>'adv.rent','uses'=>function () {
    return view('frontApp');
}]);
Route::get('/buy', ['as'=>'adv.buy','uses'=>function () {
    return view('frontApp');
}]);

Route::get('/offer', ['as'=>'adv.offer','uses'=>function () {
    return view('frontApp');
}]);




    //  static pages
Route::get('/agb', ['as'=>'agb','uses'=>function () {
    return view('static.agb');
}]);

Route::get('/contacts', ['as'=>'contacts','uses'=>function () {
    return view('static.contacts');
}]);

Route::get('/disclaimer', ['as'=>'disclaimer','uses'=>function () {
    return view('static.disclaimer');
}]);

