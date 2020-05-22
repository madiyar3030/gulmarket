<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['prefix' => 'v1'], function () {
    Route::post('image/store', 'Admin\MainController@storeImage')->name('image.store');
    Route::delete('image/destroy', 'Admin\MainController@destroyImage')->name('image.destroy');

    Route::post('sign_up', 'RestControllers\UserController@register');
    Route::post('sign_in', 'RestControllers\UserController@authenticate');
    Route::post('social/sign_in', 'RestControllers\UserController@socialSignIn');
    Route::post('user/change-password', 'RestControllers\UserController@changePassword');

    Route::group(['namespace' => 'RestControllers'], function () {
        Route::get('cities', 'ListController@getCities');
        Route::group(['prefix' => 'wines'], function () {
            Route::get('countries', 'ListController@getWineCountries');
            Route::get('classes', 'ListController@getWineClasses');
            Route::get('manufacturers', 'ListController@getWineManufacturers');
        });

        Route::get('info', 'ListController@getInfo');
        Route::get('faq', 'ListController@getFaqs');
        Route::get('contacts/{id}', 'ListController@getContacts');
        Route::get('shipping/{id}', 'ListController@getShipping');
        Route::get('faq', 'ListController@getFaqs');
        Route::get('cats', 'ListController@getCats');
        Route::get('cats/{catId}', 'ListController@getCat');
        Route::get('cats/{catId}/items', 'ItemController@getItemsByCat');
        Route::get('sub_cats/{subCatId}/items', 'ItemController@getItemsBySubCat');

        Route::group(['middleware' => ['jwt.verify']], function () {
            Route::get('item/{itemId}', 'ItemController@getItem');
            Route::post('auth', 'UserController@getAuthenticatedUser');
            Route::get('auth', 'UserController@getUser');
            Route::group(['prefix' => 'profile'], function () {
                Route::post('/', 'UserController@updateProfile');
                Route::post('address', 'UserController@userAddress');
                Route::get('history', 'UserController@getHistory');
            });
            Route::group(['prefix' => 'basket'], function () {
                Route::get('/', 'BasketController@index');
                Route::post('/', 'BasketController@store');
                Route::post('/update', 'BasketController@update');
                Route::delete('/all', 'BasketController@destroyAll');
                Route::delete('/{basketId}', 'BasketController@destroy');
            });
            Route::group(['prefix' => 'order'], function () {
                Route::post('/', 'OrderController@order');
                Route::get('/', 'OrderController@getOrders');
                Route::get('/{id}', 'OrderController@getOrder');
                Route::get('/check/{id}', 'OrderController@checkOrderPayment');
            });
            Route::group(['prefix' => 'chat'], function () {
                Route::get('/', 'ChatController@getChat');
                Route::get('/send', 'ChatController@sendFromUser');
            });
        });
    });

    Route::group(['prefix' => 'payment'], function () {
        Route::get('check', 'PaymentController@checkStatus');
        Route::get('create', 'PaymentController@test')->name('createPayment');
//        Route::get('create', 'PaymentController@create')->name('createPayment');
        Route::post('approve', 'PaymentController@approve')->name('approvePayment');
        Route::get('decline', 'PaymentController@decline2')->name('declinePayment2');
        Route::post('decline', 'PaymentController@decline')->name('declinePayment');
        Route::post('cancel', 'PaymentController@cancel')->name('cancelPayment');
    });
});


Route::get('json', 'Controller@JsonParse')->name('JsonParse');
