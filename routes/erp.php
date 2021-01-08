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
//erp对接接口
Route::group(['namespace'=> 'Erp', 'middleware'=> ['reject.empty.values']], function(){
    Route::post('ywapply/pay_return_temp', 'YwapplyController@payReturnTemp');
    
    Route::group(['middleware'=> 'auth.erp'], function(){
        //退税订单与erp托单接口
        Route::get('getOrdInfo', 'ErpapiController@getOrdInfo');
        Route::post('ordInfo', 'ErpapiController@ordInfo');
        Route::post('portSync', 'ErpapiController@portSync');
        //采购合同接口
        Route::post('purchase-throw-tax', 'PurchaseController@purchaseThrowTax')->name('purchaseThrowTax');

        //获取维护数据
        Route::get('get-data', 'CommonController@getData');
        //获取单位列表
        Route::get('unit/list', 'CommonController@unitList');
        //获取运营公司列表
        Route::get('company-list', 'CommonController@getCompanyList');
        //获取客户列表
        Route::get('customer-list', 'CustomerController@getCustomerList');
        //获取客户金额列表
        Route::get('customerfund-list', 'CustomerfundController@getCustomerfundList');

        /*退税发票与申报接口*/
        Route::get('tax/tax-uninvoice-order-pro-list', 'TaxapiController@taxUninvoiceOrderProList')->name('taxUninvoiceOrderProList');
        Route::get('tax/tax-order-with-pro-detail', 'TaxapiController@taxOrderWithProDetail')->name('taxOrderWithProDetail');
        //erp发票流程结束时发送数据到退税
        Route::post('tax/tax-invoice-complete', 'TaxapiController@taxInvoiceComplete')->name('taxInvoiceComplete');
        //erp申报流程开始时修改退税发票状态为已申报
        Route::post('tax/tax-filing-start', 'TaxapiController@taxFilingStart')->name('taxFilingStart');
        //erp申报流程结束时发送数据到退税
        Route::post('tax/tax-filing-complete', 'TaxapiController@taxFilingComplete')->name('taxFilingComplete');

        //业务报销流程返回接口
        Route::post('ywapply/pay_return', 'YwapplyController@payReturn')->name('ywapplyPayReturn');
        //收款申请流程返回接口
        Route::post('skapply/receipt_return', 'SkapplyController@receiptReturn')->name('skapplyReceiptReturn');
        //结汇接口
        Route::post('skapply/receipt_exchange', 'SkapplyController@receiptExchange')->name('receiptExchange');

        /*erp运费登记接口*/
        Route::get('transport/order-list', 'TransportController@orderList')->name('transportOrderList');
        Route::post('transport/transport-complete', 'TransportController@transportComplete')->name('transportComplete');

        /*实地核查*/
        Route::get('field_audit/drawer_list', 'FieldAuditController@drawerList');

    });
});
