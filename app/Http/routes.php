<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::group(['prefix' => 'api'], function () {
    Route::group(['prefix' => 'v1', 'middleware' => 'throttle:60,1'], function () {
        Route::get('/exams', ['as' => 'api.v1.exams', 'uses' => 'APIv1Controller@exams']);
        Route::post('/subscribe', ['as' => 'api.v1.subscribe', 'uses' => 'APIv1Controller@subscribe']);
        Route::post('/unsubscribe', ['as' => 'api.v1.unsubscribe', 'uses' => 'APIv1Controller@unsubscribe']);
        Route::post('/visit', ['as' => 'api.v1.visit', 'uses' => 'APIv1Controller@visit']);
        Route::post('/favorite', ['as' => 'api.v1.favorite', 'uses' => 'APIv1Controller@favorite']);
    });

    Route::group(['prefix' => 'v2', 'middleware' => 'throttle:60,1'], function () {
        Route::get('/exams', ['as' => 'api.v2.exams', 'uses' => 'APIv2Controller@exams']);
        Route::post('/subscribe', ['as' => 'api.v2.subscribe', 'uses' => 'APIv2Controller@subscribe']);
        Route::post('/exam', ['as' => 'api.v2.exam', 'uses' => 'APIv2Controller@exam']);
        Route::post('/visitor/action', ['as' => 'api.v2.visitor.action', 'uses' => 'APIv2Controller@visitorAction']);
        Route::post('/message', ['as' => 'api.v2.message', 'uses' => 'APIv2Controller@message']);
        Route::post('/unsubscribe', ['as' => 'api.v2.unsubscribe', 'uses' => 'APIv2Controller@unsubscribe']);
        Route::post('/visit', ['as' => 'api.v2.visit', 'uses' => 'APIv2Controller@visit']);
        Route::post('/favorite', ['as' => 'api.v2.favorite', 'uses' => 'APIv2Controller@favorite']);
    });

});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::get('/', 'FrontController@home')->name('home');

Route::auth();
Route::get('/exams/customs', 'ExamController@customs')->name('exams.customs.index');
Route::post('/exams/{exam}/activate', ['as' => 'exams.activate', 'uses' => 'ExamController@activate']);
Route::resource('exams', 'ExamController');

Route::resource('visits', 'VisitController');
Route::resource('favorites', 'FavoriteController');

Route::get('visitors/statistics', 'VisitorController@statistics')->name('visitors.statistics');
Route::resource('visitors', 'VisitorController');

Route::get('notification/test', 'NotificationController@test')->name('notification.test');
Route::resource('notification', 'NotificationController');
Route::post('notification/{notification}/send', 'NotificationController@send')->name('notification.send');

Route::group(['prefix' => 'logs'], function () {
    Route::get('/', ['as' => 'log-viewer::dashboard', 'uses' => 'LogController@index',]);
    Route::get('/lists', ['as' => 'log-viewer::logs.list', 'uses' => 'LogController@listLogs',]);
    Route::delete('delete', ['as' => 'log-viewer::logs.delete', 'uses' => 'LogController@delete',]);
    Route::group(['prefix' => '{date}',], function () {
        Route::get('/', ['as' => 'log-viewer::logs.show', 'uses' => 'LogController@show',]);
        Route::get('download', ['as' => 'log-viewer::logs.download', 'uses' => 'LogController@download',]);
        Route::get('{level}', ['as' => 'log-viewer::logs.filter', 'uses' => 'LogController@showByLevel',]);
    });
});
