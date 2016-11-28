<?php

use App\User;
use App\Category;
use App\Adv;


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


Route::get('/rent', ['as'=>'adv.rent','uses'=> 'SearchController@rent'] );
Route::get('/buy', ['as'=>'adv.sale','uses'=> 'SearchController@buy'] );



Route::get('/offer', ['as'=>'adv.offer','uses'=>'AdvController@create']);

Route::get('/adv/{id}', ['as'=>'adv.preview','uses'=>'AdvController@preview'])->where('id','[0-9]*');
Route::get('/search/{id}', ['as'=>'adv.search','uses'=>'SearchController@searchResult'])->where('id','[0-9]*');


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
        Route::put('/change-email', 'UserController@changeEmail' );
        Route::post('/send-confirm-code', 'UserController@sendConfirmCode' );
        Route::put('/change-password', 'UserController@changePassword' );
        Route::put('/allow-notifications', 'UserController@allowNotifications' );
        Route::put('/change-payment', 'UserController@changePayment' );
        Route::put('/change-contact-data', 'UserController@changeContactData' );
        Route::delete('/', 'UserController@deleteAccount' );


        Route::post('/private-account', 'UserController@createPrivateAccount' );
        Route::post('/business-account', 'UserController@createBusinessAccount' );
        Route::get('/adv-stat', 'AdvController@getStat' );

        Route::group(['prefix'=>'advs'], function () {
            Route::post('/', 'AdvController@store' );

            Route::get('/{id}', 'AdvController@getUserAdvById' );
            Route::put('/', 'AdvController@getByUser' );

            Route::delete('{id}', 'AdvController@delete' );
            Route::put('{id}/status', 'AdvController@changeStatus' );


        });
        Route::put('/watch-advs', 'AdvController@getWatchByUser' );
        Route::delete('/watch-advs/{id}', 'AdvController@removeWatch' );


    });

    Route::get('/news/{type}', 'News@getLastNews' );

    Route::get('/advs/{id}', 'AdvController@getAdvById' );

    Route::post('/advs/{id}/fav', 'AdvController@favlist' );
    Route::post('/advs/{id}/message', 'AdvController@message' );

    Route::get('/adv-data-sets', function(){
        $sets = [
            'sub_categories'=>Adv::getSubCategories(),
            'equipments'=>Adv::getEquipments(),
        ];
        return response()->json($sets);
    } );

   // Route::get('/address', 'AddressController@search');
    Route::get('/search/cities/{key}', 'SearchController@findCity');
    Route::post('/search', 'SearchController@createSearch');
    Route::get('/search/{id}', 'SearchController@getSearch');


    Route::get('/tariffs', 'TariffController@getAll' );

    Route::post('/search/{id}', ['as'=>'adv.search','uses'=>'SearchController@search'])->where('id','[0-9]*');
    Route::post('/search/{id}/update', 'SearchController@searchUpdate')->where('id','[0-9]*');

});





View::composer(['frontApp','privateApp'], function ($view) {
    $user = User::getUser();
    $view->with('composer_header_menu', View::make('composer.headerMenu', ['user' => $user]));
});
