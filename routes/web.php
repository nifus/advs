<?php

use App\User;



Route::get('/', ['as'=>'main','uses'=>'DashboardController@index']);



Route::group(['prefix'=>'register'], function () {
    Route::get('/private', ['as'=>'register.private','uses'=> 'UserController@privateAccountForm']);
    Route::get('/business', ['as'=>'register.business','uses'=> 'UserController@businessAccountForm']);

    Route::get('/activate/{user_id}/{key}', ['as'=>'register.activate','uses'=> 'UserController@activateAccount']);

});

Route::group(['prefix'=>'user'], function () {
    Route::get('/reset/{user_id}/{key}', ['as'=>'user.reset','uses'=> 'UserController@resetPassword']);
    Route::get('/', ['as'=>'user.dashboard','uses'=> 'UserController@dashboard']);


});



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






Route::group(['prefix'=>'api'], function () {

    Route::group(['prefix'=>'user'], function () {
        Route::get('/get-auth', 'UserController@getAuth' );
        Route::post('/authenticate', 'UserController@authenticate' );
        Route::post('/forgot-password', 'UserController@forgotPassword' );

        Route::post('/private-account', 'UserController@createPrivateAccount' );
        Route::post('/business-account', 'UserController@createBusinessAccount' );
        Route::get('/adv-stat', 'Adv@getStat' );
        Route::put('/advs', 'Adv@getByUser' );
        Route::put('/watch-advs', 'Adv@getWatchByUser' );
    });

    Route::get('/news/{type}', 'News@getLastNews' );


});





View::composer(['frontApp','privateApp'], function ($view) {
    $user = User::getUser();
    $view->with('composer_header_menu', View::make('composer.headerMenu', ['user' => $user]));
});
