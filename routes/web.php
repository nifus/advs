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


    Route::get('/adv/{id}/statistics', 'AdvController@getStatisticsById' );
    Route::get('/adv/statistics', 'AdvController@getStatistics' );
    Route::get('/user/statistics', 'UserController@getStatistics' );
    // TODO
    Route::get('/user/statistics/subscriptions', 'UserController@getSubscriptionsStatistics' );




    Route::group(['prefix'=>'search'], function () {
        Route::post('/{type}', 'SearchController@createSearch')->where('type','advs|accounts');
        Route::get('/{id}', 'SearchController@getSearch');
        Route::post('/{id}/update', 'SearchController@searchUpdate')->where('id','[0-9]*');
        Route::post('/{id}', ['as'=>'adv.search','uses'=>'SearchController@search'])->where('id','[0-9]*');
    });

    Route::group(['prefix'=>'user'], function () {
        Route::get('/get-all-administration', 'UserController@getAllAdministration' );
        Route::get('/get-all-new-business', 'UserController@getAllNewBusiness' );
        Route::get('/get-all-blocked', 'UserController@getAllBlocked' );
        Route::get('/get-auth', 'UserController@getAuth' );

        Route::post('/administrator', 'UserController@createAdministratorAccount' );
        Route::post('/{id}/administrator', 'UserController@updateAdministratorAccount' );
        Route::delete('/{id}/administrator', 'UserController@deleteAdministratorAccount' );

        Route::post('/authenticate', 'UserController@authenticate' );
        Route::post('/forgot-password', 'UserController@forgotPassword' );
        Route::put('/change-email', 'UserController@changeEmail' );
        Route::post('/send-confirm-code', 'UserController@sendConfirmCode' );

        Route::put('/change-password', 'UserController@changePassword' );
        Route::put('/allow-notifications', 'UserController@allowNotifications' );
        Route::put('/change-payment', 'UserController@changePayment' );
        Route::put('/change-contact-data', 'UserController@changeContactData' );
        Route::delete('/', 'UserController@deleteAccount' );

        Route::post('/{id}/set-block-status', 'UserController@setBlockStatus' );
        Route::post('/{id}/set-active-status', 'UserController@setActiveStatus' );
        Route::get('/{id}/events-log', 'UserController@getEventsLog' );
        Route::delete('/{id}', 'UserController@deleteAccountById' );

        Route::group(['prefix'=>'tariff'], function () {
            Route::get('/', 'TariffController@getUserTariff');
            Route::post('/', 'TariffController@store');
        });

        Route::post('/private-account', 'UserController@createPrivateAccount' );
        Route::post('/business-account', 'UserController@createBusinessAccount' );

        Route::group(['prefix'=>'advs'], function () {
            Route::post('/', 'AdvController@store' );
            Route::post('/{id}', 'AdvController@update' );

            Route::get('/{id}', 'AdvController@getUserAdvById' );
            Route::put('/', 'AdvController@getByUser' );

            Route::delete('{id}', 'AdvController@delete' );
            Route::put('{id}/status', 'AdvController@changeStatus' );


        });
        Route::put('/watch-advs', 'AdvController@getWatchByUser' );
        Route::delete('/watch-advs/{id}', 'AdvController@removeWatch' );




    });

    Route::get('/news/{type}', 'News@getLastNews' );
    //Route::get('/config', 'ConfigController@getConfig' );

    Route::get('/advs/{id}', 'AdvController@getAdvById' );
    Route::post('/advs/{id}/fav', 'AdvController@favlist' );
    Route::post('/advs/{id}/message', 'AdvController@message' );

    Route::get('/adv-data-sets', function(){
        $sets = [
            'sub_categories'=>Adv::getSubCategories(),
            'equipments'=>Adv::getEquipments(),
            'categories'=>Category::getCategories(),
            'agb' =>  \Config::get('app.agb'),
            'disabled_msg'=>\Config::get('app.disabled_msg'),
            'google_key'=> ''
        ];
        return response()->json($sets);
    } );

   // Route::get('/address', 'AddressController@search');
    Route::get('/search/cities/{key}', 'SearchController@findCity');




    Route::get('/tariffs', 'TariffController@getAll' );



    Route::group(['prefix'=>'back'], function () {
        Route::post('/config/announcement/{type}', 'ConfigController@announcement' )->where('type','private|business');
        Route::post('/config/instruction', 'ConfigController@instruction' );
        Route::post('/config/faq', 'ConfigController@faq' );
        Route::post('/config/private-prices', 'ConfigController@privatePrices' );
        Route::post('/config/business-prices', 'ConfigController@businessPrices' );

        Route::post('/faqs', 'FaqController@store' );
        Route::get('/faqs', 'FaqController@getAll' );
        Route::post('/faqs/{id}', 'FaqController@update' );
        Route::delete('/faqs/{id}', 'FaqController@delete' );

        Route::group(['prefix'=>'users'], function () {
            Route::get('/countries', 'UserController@getAllCountries');
            Route::post('/search', 'UserController@search');
        });
    });
});





View::composer(['frontApp','privateApp'], function ($view) {
    $user = User::getUser();
    $view->with('composer_header_menu', View::make('composer.headerMenu', ['user' => $user]));
});
