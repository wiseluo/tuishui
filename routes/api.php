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

Route::group(['namespace'=> 'Api', 'middleware'=> ['reject.empty.values']], function(){
    Route::post('login', 'LoginController@login');
    Route::post('register', 'RegisterController@register');

    Route::group(['middleware'=> 'jwt.api'], function(){
        Route::post('password/reset', 'ResetPasswordController@resetPassword');

        Route::resource('links', 'LinkController');//联系人
        Route::resource('deposit', 'DepositController');//存货点
     	Route::resource('receive', 'ReceiveController');//收货人
        Route::get('country', 'CommonController@countryIndex');//国家下拉列表
        Route::get('port', 'CommonController@portIndex');
        Route::get('country/list', 'CommonController@countryList');//获取国家选择列表
        Route::get('unit/list', 'CommonController@unitList');//获取单位列表
        Route::get('district/list', 'CommonController@districtList');//获取境内货源地列表

        Route::get('download/clearance_material/{id}', 'DownloadController@clearanceMaterialExcel');//下载报关资料
        Route::get('download/invoice_material/{id}', 'DownloadController@invoiceMaterialExcel');//开票资料下载

        //产品管理
        Route::get('/product', 'ProductController@index');
        Route::get('/product/{id}', 'ProductController@show');
        Route::post('/product', 'ProductController@save');
        Route::post('/product/delete/{id}', 'ProductController@delete');
        Route::post('/product/update/{id}', 'ProductController@update');
        //产品选择
        Route::get('/product/product_choose', 'ProductController@productChoose');
    
        //品牌管理
        Route::resource('brand','BrandController');

        //开票人管理
        //Route::resource('drawer', 'DrawerController');
        Route::get('/drawer', 'DrawerController@index');
        Route::get('/drawer/{id}', 'DrawerController@show');
        Route::post('/drawer/update/{id}', 'DrawerController@update')->name('apiDrawerUpdate');
        Route::post('/drawer/retrieve/{id}', 'DrawerController@retrieve');
        Route::post('/drawer/update_done/{id}', 'DrawerController@updateDone')->name('apiDrawerUpdateDone');
        Route::post('/drawer/relate_product/{id}', 'DrawerController@relateProducts')->name('apiDrawerRelateProducts');//开票人添加产品
        //开票人产品选择
        Route::get('/drawer_products', 'DrawerController@drawerProducts');
        Route::get('/drawer_products/detail/{id}', 'DrawerController@drawerProductDetail');
    
        //添加产品从报关系统获取数据
        Route::get('/custom_sys/product_list', 'CurlController@productList');
        Route::get('/custom_sys/element', 'CurlController@getElement');

        //订单
        //Route::resource('order', 'OrderController');
        Route::get('/order', 'OrderController@index');
        Route::get('/order/{id}', 'OrderController@show');
        Route::get('/order/create', 'OrderController@create');
        Route::post('/order/draft_save', 'OrderController@draftSave')->name('apiOrderDraftSave');
        Route::post('/order/draft_update/{id}', 'OrderController@draftUpdate')->name('apiOrderDraftUpdate');
        Route::post('/order/save', 'OrderController@save')->name('apiOrderSave');
        Route::post('/order/update/{id}', 'OrderController@update')->name('apiOrderUpdate');
        Route::post('/order/delete/{id}', 'OrderController@delete');
        Route::post('/order/retrieve/{id}', 'OrderController@retrieve');

        //报关单
        Route::get('/clearance', 'ClearanceController@index');
        Route::post('/clearance/update/{id}', 'ClearanceController@update');
        
        //发票管理
        Route::get('/invoice', 'InvoiceController@index');
        Route::get('/invoice/{id}', 'InvoiceController@show');

        //收汇登记
        Route::get('/finance/receipt', 'ReceiptController@index');
        Route::get('/finance/receipt/number/{id}', 'ReceiptController@number');
        Route::get('/finance/receipt/order/{id}/{flag}', 'ReceiptController@order');
        Route::post('/finance/receipt/binding/{id}', 'ReceiptController@binding');
        Route::get('/finance/receipt/unbinding/{id}', 'ReceiptController@unbinding');
        
        //个人信息
        Route::resource('personal', 'PersonalController');
        Route::post('/personal/upload_img', 'PersonalController@upload_img');
        
        //数据维护
        Route::get('father' , 'DataController@index');
        Route::get('father/{id}' , 'DataController@father');
        
        //上传
        Route::post('/upload', 'UploadController@upload');

        //消息提醒
        Route::get('/notice', 'NotificationController@noticeIndex');
        Route::post('/notice_readed', 'NotificationController@noticeReaded');
    });

});
