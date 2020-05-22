<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return view('welcome');
})->name('welcome');
Route::get('users', 'Admin\ClientController@getUsers')->name('users.index');
Route::group(['prefix' => '/admin', 'namespace' => 'Admin'], function () {
    Route::get('/sign-in', 'MainController@viewSignIn')->name('viewSignIn');
    Route::post('/sign-in', 'MainController@signIn')->name('signIn');
    Route::get('/sign-up', 'MainController@viewSignUp')->name('viewSignUp');
    Route::get('/sign-out', 'MainController@signOut')->name('signOut');

    Route::group(['middleware' => 'accessAdmin'], function () {

        Route::get('/', 'MainController@viewIndex')->name('viewIndex');
        Route::get('/info', 'MainController@viewInfo')->name('viewInfo');
        Route::post('/info', 'MainController@saveInfo')->name('saveInfo');

        Route::resource('manager', 'ManagerController')->only([
            'index', 'destroy', 'update', 'edit', 'store', 'show'
        ]);
        Route::resource('role', 'RoleController')->only([
            'destroy', 'update', 'edit', 'store', 'show'
        ]);
        Route::resource('client', 'ClientController')->only([
            'index', 'destroy', 'show', 'edit'
        ]);

        Route::resource('order', 'OrderController')->only([
            'index', 'destroy', 'update', 'edit', 'store', 'show'
        ]);
        Route::resource('item', 'ItemController');
        Route::resource('chat', 'ChatController')->only([
            'index', 'destroy', 'update', 'edit', 'store', 'show'
        ]);
        Route::resource('category', 'CategoryController')->only([
            'index', 'destroy', 'update', 'edit', 'store', 'show'
        ]);
        Route::resource('subcategory', 'SubCategoryController')->only([
            'destroy', 'update', 'edit', 'store'
        ]);
        Route::resource('city', 'CityController')->only([
            'index', 'destroy', 'update', 'edit', 'store'
        ]);
        Route::resource('wine', 'WineController')->only([
            'index', 'destroy', 'update', 'edit', 'store'
        ]);
        Route::resource('contacts', 'ContactController')->only([
            'index', 'destroy', 'update', 'edit', 'store'
        ]);
        Route::resource('shipping', 'ShippingController')->only([
            'index', 'destroy', 'update', 'edit', 'store'
        ]);
        Route::resource('faqs', 'FAQController')->only([
            'index', 'destroy', 'update', 'edit', 'store'
        ]);
    });
});
