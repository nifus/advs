<?php

use App\User;
use App\Category;
use App\Adv;

Route::get('/', ['as' => 'main', 'uses' => 'DashboardController@index']);


Route::group(['prefix' => 'register'], function () {
    Route::get('/private', ['as' => 'register.private', 'uses' => 'UserController@privateAccountForm']);
    Route::get('/business', ['as' => 'register.business', 'uses' => 'UserController@businessAccountForm']);
    Route::get('/activate/{user_id}/{key}', ['as' => 'register.activate', 'uses' => 'UserController@activateAccount']);
    Route::get('/confirm/{user_id}/{key}', ['as' => 'register.confirm', 'uses' => 'UserController@confirmAccount']);
});

Route::group(['prefix' => 'user'], function () {
    Route::get('/reset/{user_id}/{key}', ['as' => 'user.reset', 'uses' => 'UserController@resetPassword']);
    Route::get('/', ['as' => 'user.dashboard', 'uses' => 'UserController@dashboard']);
});

Route::group(['prefix' => 'payment'], function () {
    Route::post('/emulation/{type}/{id}', 'PaymentController@emulation')->where('type', 'giro|paypal|pre-payment')->where('id', '[0-9]*');
    Route::post('/emulation/{type}/{id}/end', 'PaymentController@emulationSave')->where('type', 'giro|paypal|pre-payment')->where('id', '[0-9]*');
});


Route::get('/rent', ['as' => 'adv.rent', 'uses' => 'SearchController@rent']);
Route::get('/buy', ['as' => 'adv.sale', 'uses' => 'SearchController@buy']);


Route::get('/offer', ['as' => 'adv.offer', 'uses' => 'AdvController@create']);

Route::get('/adv/{id}', ['as' => 'adv.preview', 'uses' => 'AdvController@preview'])->where('id', '[0-9]*');
Route::get('/search/{id}', ['as' => 'adv.search', 'uses' => 'SearchController@searchResult'])->where('id', '[0-9]*');


//  static pages
Route::get('/agb', ['as' => 'agb', 'uses' => function () {
    return view('static.agb');
}]);

Route::get('/contacts', ['as' => 'contacts', 'uses' => function () {
    return view('static.contacts');
}]);

Route::get('/disclaimer', ['as' => 'disclaimer', 'uses' => function () {
    return view('static.disclaimer');
}]);


Route::group(['prefix' => 'api'], function () {


    Route::group(['prefix' => 'user'], function () {
        Route::get('/statistics', 'UserController@getStatistics');
        // TODO
        Route::get('/statistics/subscriptions', 'UserController@getSubscriptionsStatistics');

        Route::get('/countries', 'UserController@getAllCountries');
        Route::post('/search', 'UserController@search');


        Route::get('/get-all-administration', 'UserController@getAllAdministration');
        Route::get('/get-all-new-business', 'UserController@getAllNewBusiness');
        Route::get('/get-all-blocked', 'UserController@getAllBlocked');
        Route::get('/get-auth', 'UserController@getAuth');

        Route::post('/administrator', 'UserController@createAdministratorAccount');
        Route::post('/{id}/administrator', 'UserController@updateAdministratorAccount')->where('id', '[0-9]*');
        Route::delete('/{id}/administrator', 'UserController@deleteAdministratorAccount')->where('id', '[0-9]*');

        Route::post('/authenticate', 'UserController@authenticate');
        Route::post('/forgot-password', 'UserController@forgotPassword');
        Route::put('/change-email', 'UserController@changeEmail');
        Route::post('/send-confirm-code', 'UserController@sendConfirmCode');

        Route::put('/change-password', 'UserController@changePassword');
        Route::put('/allow-notifications', 'UserController@allowNotifications');
        Route::put('/change-payment', 'UserController@changePayment');
        Route::put('/change-contact-data', 'UserController@changeContactData');
        Route::delete('/', 'UserController@deleteAccount');

        Route::post('{id}/set-block-status', 'UserController@setBlockStatus')->where('id', '[0-9]*');
        Route::post('{id}/set-active-status', 'UserController@setActiveStatus')->where('id', '[0-9]*');
        Route::get('{id}/events-log', 'UserController@getEventsLog')->where('id', '[0-9]*');
        Route::delete('{id}', 'UserController@deleteAccountById')->where('id', '[0-9]*');
        Route::post('{id}', 'UserController@updateAccount')->where('id', '[0-9]*');

        Route::group(['prefix' => 'tariff'], function () {
            Route::get('/', 'TariffController@getUserTariff');
            Route::post('/', 'TariffController@store');
        });

        Route::post('/private-account', 'UserController@createPrivateAccount');
        Route::post('/business-account', 'UserController@createBusinessAccount');

        Route::post('/send-message-for-administrator', 'UserController@sendMessage4Administrator');
        Route::post('/active-profile', 'UserController@activateProfile');
        Route::post('/deactivate-profile', 'UserController@deactivateProfile');
        Route::get('/profile', 'UserController@getProfile');
        Route::post('/profile', 'UserController@updateProfile');
    });


    Route::group(['prefix' => 'adv'], function () {
        Route::get('/{id}/statistics', 'AdvController@getStatisticsById')->where('id', '[0-9]*');
        Route::get('/statistics', 'AdvController@getStatistics');
        Route::get('/blocked', 'AdvController@getBlocked');
        Route::get('/reports', 'AdvController@getReports');

        Route::post('/', 'AdvController@store');
        Route::post('/{id}', 'AdvController@update')->where('id', '[0-9]*');

        Route::get('/{id}', 'AdvController@getUserAdvById')->where('id', '[0-9]*');
        Route::get('/{id}/with-block-message', 'AdvController@getUserAdvByIdWithBlockMessage')->where('id', '[0-9]*');
        Route::get('/{id}/restore', 'AdvController@getRestoreAdvertById')->where('id', '[0-9]*');
        Route::get('/by-user/{id}', 'AdvController@getByUser')->where('id', '[0-9]*');
        Route::get('/by-current-user', 'AdvController@getByCurrentUser')->where('id', '[0-9]*');

        Route::delete('{id}', 'AdvController@delete')->where('id', '[0-9]*');
        Route::post('{id}/status', 'AdvController@changeStatus')->where('id', '[0-9]*');
        Route::post('{id}/disable', 'AdvController@disable')->where('id', '[0-9]*');
        Route::post('{id}/activate', 'AdvController@activate')->where('id', '[0-9]*');
        Route::post('{id}/view', 'AdvController@viewIncrement')->where('id', '[0-9]*');

        Route::get('/watch/by-current-user', 'AdvController@getWatchByCurrentUser');
        Route::delete('/watch/{id}', 'AdvController@removeWatch')->where('id', '[0-9]*');

        Route::get('/{id}', 'AdvController@getAdvById')->where('id', '[0-9]*');
        Route::post('/{id}/fav', 'AdvController@favlist')->where('id', '[0-9]*');
        Route::post('/{id}/message', 'AdvController@message')->where('id', '[0-9]*');

        Route::post('/upload-images', 'AdvController@uploadImages');
        Route::post('/{id}/report', 'AdvController@createReport')->where('id', '[0-9]*');
        Route::delete('/{id}/report', 'AdvController@deleteReports')->where('id', '[0-9]*');
    });

    Route::group(['prefix' => 'payment'], function () {
        //Route::post('/{type}', 'PaymentController@store' )->where('type','giro|paypal|pre-payment');
        Route::post('/{type}/{way}', 'PaymentController@store')->where('way', 'giro|paypal|pre-payment')->where('type', 'subscription|advert|slot');

    });

    Route::group(['prefix' => 'tariff'], function () {
        Route::get('/private', 'TariffController@getPrivate');
        Route::get('/private-prices', 'TariffController@getPrivatePrices');
        Route::get('/business', 'TariffController@getBusiness');
        Route::get('/business-prices', 'TariffController@getBusinessPrices');

        Route::post('/private', 'TariffController@updatePrivate');
        Route::post('/business', 'TariffController@updateBusiness');

        Route::get('/current', 'TariffController@getCurrentTariff');
        Route::get('/slots', 'TariffController@getSlots');
        Route::get('/future', 'TariffController@getFutureTariff');
        Route::get('/end', 'TariffController@endTariff');

    });
    // @depricated
    //Route::get('/tariffs', 'TariffController@getAll' );


    Route::group(['prefix' => 'search'], function () {
        Route::get('/cities/{key}', 'SearchController@findCity');
        Route::post('/{type}', 'SearchController@createSearch')->where('type', 'advs|accounts|invoices');
        Route::get('/{id}', 'SearchController@getSearch')->where('id', '[0-9]*');
        Route::post('/{id}/config-update', 'SearchController@searchConfigUpdate')->where('id', '[0-9]*');
        Route::post('/{id}/query-update', 'SearchController@searchQueryUpdate')->where('id', '[0-9]*');
        Route::post('/{id}', ['as' => 'adv.search', 'uses' => 'SearchController@search'])->where('id', '[0-9]*');
    });

    Route::group(['prefix' => 'faqs'], function () {
        Route::post('/', 'FaqController@store');
        Route::get('/', 'FaqController@getAll');
        Route::get('/announcements/private', 'FaqController@getPrivateAnnouncements');
        Route::get('/announcements/business', 'FaqController@getBusinessAnnouncements');

        Route::post('/{id}', 'FaqController@update')->where('id', '[0-9]*');
        Route::delete('/{id}', 'FaqController@delete')->where('id', '[0-9]*');
    });


    Route::get('/mail/templates', 'MailTemplateController@getAll');
    Route::post('/mail/templates/{id}', 'MailTemplateController@update')->where('id', '[0-9]*');


    /* depricated
    Route::post('/config/announcement/{type}', 'ConfigController@announcement' )->where('type','private|business');
    Route::post('/config/instruction', 'ConfigController@instruction' );
    Route::post('/config/faq', 'ConfigController@faq' );
    Route::post('/config/variables', 'ConfigController@variables' );
    */


    Route::get('/variables', 'VariableController@getAll')->where('id', '[0-9]*');
    Route::get('/variables/{id}', 'VariableController@getById')->where('id', '[0-9]*');
    Route::post('/variables/{id}', 'VariableController@update');


    // Route::get('/address', 'AddressController@search');
    Route::get('/news/{type}', 'News@getLastNews');


    Route::get('/adv-data-sets', function () {
        $sets = [
            'categories' => Category::getCategories(),
            'sub_categories' => Adv::getSubCategories(),
            'equipments' => Adv::getEquipments(),
            'support_email' => \Config::get('variables.varHelpEmailAddress'),
            'energy_class' => Adv::getEnergyClassSource(),
            'heating' => Adv::getHeatingSource(),
            'energy_source' => Adv::getEnergySource(),
        ];
        return response()->json($sets);
    });

    Route::post('/change-city', function (\Illuminate\Http\Request $request){
        $data = $request->all();

        $place = \App\Place::findCity($data['country'], null, $data['city'],$data['place_id']);
        if(is_null($place)){
            $cityDetect = [
                'id' => null,
                'found' => false,
                'autodetect' => false,
                'ip' => $_SERVER['REMOTE_ADDR'],
                'city' => $data['city'],
                'iso' => null,
                'country' => $data['country'],
            ];
        }else{
            $cityDetect = [
                'id' => $place->id,
                'found' => false,
                'autodetect' => false,
                'ip' => $_SERVER['REMOTE_ADDR'],
                'city' => $data['city'],
                'iso' => null,
                'country' => $data['country'],
            ];
        }

        Cookie::queue(
            Cookie::make('city-detect', json_encode($cityDetect), 60 * 60 * 60));
        return response()->json([]);
    });


});


use GeoIp2\Database\Reader;


View::composer(['frontApp', 'privateApp'], function ($view) {
    $city = Cookie::get('city-detect');
    if (is_null($city)) {
        $ip = ($_SERVER['REMOTE_ADDR']=='192.168.1.1') ? '176.117.211.151' : ($_SERVER['REMOTE_ADDR']);





        // first entry
       // $reader = new Reader(storage_path('geo_lite/GeoLite2-City.mmdb'));
        try {
            //if($_SERVER['REMOTE_ADDR']=='192.168.1.1'){
            //    $record = $reader->city('46.91.134.229');
            //}else{
            //    $record = $reader->city($_SERVER['REMOTE_ADDR']);
            //}
            $response = json_decode(file_get_contents('http://ip-api.com/json/'.$ip));
            if ($response->status=='fail'){
                throw new Exception();
            }
            $place = \App\Place::findCity($response->country, $response->regionName, $response->city);
            $cityDetect = (object)[
                'found' => true,
                'autodetect' => true,
                'ip' => $_SERVER['REMOTE_ADDR'],
                'city' => $response->city,
                'region' => $response->regionName,
                'iso' => $response->countryCode,
                'country' => $response->country,
                'coordinates' => [
                    'latitude' => $response->lat,
                    'longitude' => $response->lon,
                ],
                'postcode' => $response->zip
            ];
            if (!is_null($place)){
                $cityDetect->id=$place->id;
            }

            Cookie::queue(
                Cookie::make('city-detect', json_encode($cityDetect), 60 * 60 * 60));


        } catch (Exception $e) {
            $cityDetect = (object)[
                'found' => false,
                'autodetect' => true,
                'ip' => $_SERVER['REMOTE_ADDR'],
                'city' => null,
                'iso' => null,
                'country' => 'Germany',
                'coordinates' => null,
                'postcode' => null
            ];

            Cookie::queue(
                Cookie::make('city-detect', json_encode($cityDetect), 60 * 60 * 60)
            );
        }
    } else {
        try{
            $cityDetect = json_decode(Cookie::get('city-detect'));
        }catch ( Exception $e ){
            $cityDetect = null;
        }
      //  dd();
    }


    $user = User::getUser();
    $view->with('composer_header_menu', View::make('composer.headerMenu',
        [
            'user' => $user,
            'cityDetect'=> $cityDetect
        ]));
});
