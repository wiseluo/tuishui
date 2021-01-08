<?php
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
//外部系统对接接口
Route::group(['namespace'=> 'External'], function(){
    Route::group(['middleware'=> 'auth.external'], function(){
        Route::post('/order/{id}', 'OrderController@send');
    });

    //退税给电商园的订单统计
    Route::get('tstodsy_order_statistics', 'OrderController@dsyOrderStatistics');
    //退税给电商园的订单数据
    Route::get('tstodsy_order', 'OrderController@dsyOrder');
    
});
