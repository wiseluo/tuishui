<?php
Route::get('/', function () {
    return redirect('/admin/login'); //跳转响应（重定向），默认admin
});

//上传excel，直接处理数据库数据用
Route::post('/uploadfile', 'Admin\UploadDealController@uploadFile');
//处理上传的excel
Route::post('/deal_uploadfile', 'Admin\UploadDealController@uploadDeal');

//后台用户路由
Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
    // Authentication Routes...
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('admin.login');
    Route::get('erplogin', 'Auth\LoginController@erplogin');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout')->name('admin.logout');

    Route::group(['middleware' => 'auth.admin:admin'], function () {

        Route::any('curl', function (Curl\Curl $curl, \Illuminate\Http\Request $request) {
            return $curl->{$request->method()}($request->url, $request->except(['url', 's']))->response;
        });

        //添加产品从报关系统获取数据
        Route::get('/custom_sys/product_list', 'CurlController@productList');
        Route::get('/custom_sys/element', 'CurlController@getElement');
		//获取市级和县级地址
	    Route::get('/get_regions/{code}','RegionController@getRegionByParentCode');

        Route::get('/{dialog}/choose', function ($dialog) {
            return view("admin.dialogs.{$dialog}");
        });
        //首页
        Route::get('/', 'HomeController@index');
        //上传
        Route::post('/upload', 'UploadController@upload');
        
        //审核待办数
        Route::get('/pending_review_number', 'CommonController@pendingReviewNumber');
        //获取单位列表
        Route::get('/unit/list', 'CommonController@unitList');
        //获取国家列表
        Route::get('/country/list', 'CommonController@countryList');
        //获取国家列表
        Route::get('/port/list', 'CommonController@portList');
        //获取境内货源地列表
        Route::get('/district/list', 'CommonController@districtList');

        //获取erp中客户信息
        Route::get('/curl/erp_customer_list', 'CurlController@erpCustomerList');
        Route::get('/curl/erp_customer/{id}', 'CurlController@erpCustomer');
        //获取erp中运营管理部的员工列表
        Route::get('/curl/erp_tax_user_list', 'CurlController@erpTaxUserList');
        //获取erp中收款账号列表
        Route::get('/curl/erp_enterprise_account', 'CurlController@erpEnterpriseAccount');

        //报关资料下载
        Route::get('/download/clearance_material/{id}', 'DownloadController@clearanceMaterialExcel');
        //报关预录入单下载
        Route::get('/download/customs_order/{id}', 'DownloadController@customsOrderExcel');
        //开票资料下载
        Route::get('/download/billing_data/{id}', [
            'as' => 'billingDownload', 'uses' => 'DownloadWordController@billingData'
        ]);

        //客户管理
        //普通请求获取主页,ajax请求获取分页数据
        Route::get('/customer/{status}', 'CustomerController@index');
        Route::get('/customer/{type}/{id}', 'CustomerController@read');
        Route::post('/customer', 'CustomerController@save')->name('adminCustomerSave');
        Route::post('/customer/delete/{id}', 'CustomerController@delete');
        Route::post('/customer/update/{id}', 'CustomerController@update')->name('adminCustomerUpdate');
        Route::post('/customer/update_done/{id}', 'CustomerController@updateDone')->name('adminCustomerUpdateDone');
        Route::post('/customer/approve/{id}', 'CustomerController@approve');
        Route::post('/customer/retrieve/{id}', 'CustomerController@retrieve');
        //Route::get('/customer/number/{number}', 'CustomerController@existNumber');
        Route::get('/customer/export', 'CustomerController@customerExport');
        //客户选择列表
        Route::get('/customer/customer_choose', 'CustomerController@customerChoose');

        //客户资金管理
        Route::get('/customerfund', 'CustomerfundController@index');
        Route::get('/customerfund/deatil', 'CustomerfundController@deatil');
        Route::get('/customerfund/statement_list', 'CustomerfundController@statementList');
        
        //品牌
        Route::get('/brand/{status}', 'BrandController@index');
        Route::get('/brand/{type}/{id}', 'BrandController@read');
        Route::post('/brand', 'BrandController@save');
        Route::post('/brand/update/{id}', 'BrandController@update');
        Route::post('/brand/approve/{id}', 'BrandController@approve');
        //选择品牌
        Route::get('/brand/brandlist', 'BrandController@brandlist');

        //联系人
        Route::get('/link', 'LinkController@index');
        Route::get('/link/{type}/{id}', 'LinkController@read');
        Route::post('/link', 'LinkController@save');
        Route::post('/link/update/{id}', 'LinkController@update');
        Route::post('/link/delete/{id}', 'LinkController@delete');
        //选择联系人
        Route::get('/link/linklist', 'LinkController@linkList');

        //产品管理
        Route::get('/product/{status}', 'ProductController@index');
        Route::get('/product/{type}/{id}', 'ProductController@read');
        Route::post('/product', 'ProductController@save')->name('adminProductSave');
        Route::post('/product/delete/{id}', 'ProductController@delete');
        Route::post('/product/update/{id}', 'ProductController@update')->name('adminProductUpdate');
        Route::post('/product/approve/{id}', 'ProductController@approve');
        Route::post('/product/retrieve/{id}', 'ProductController@retrieve');
        Route::post('/product/update_done/{id}', 'ProductController@updateDone')->name('adminProductUpdateDone');
        Route::get('/product/export', 'ProductController@productExport');
        //获取单个产品信息
        Route::get('/product/product_info/{id}', 'ProductController@productInfo');
        //产品选择列表
        Route::get('/product/product_choose', 'ProductController@productChoose');
        //产品关联开票人
        Route::post('/product/relate_drawer', 'ProductController@relateDrawer')->name('adminProductRelateDrawer');
        Route::post('/product/del_drawer', 'ProductController@delDrawer')->name('adminProductDelDrawer');
        //获取产品关联的开票人
        Route::get('/product/related_drawer', 'ProductController@relatedDrawer');

        //开票人管理
        Route::get('/drawer/{status}', 'DrawerController@index');
        Route::get('/drawer/{type}/{id}', 'DrawerController@read');
        Route::post('/drawer', 'DrawerController@save')->name('adminDrawerSave');
        Route::post('/drawer/delete/{id}', 'DrawerController@delete');
        Route::post('/drawer/update/{id}', 'DrawerController@update')->name('adminDrawerUpdate');
        Route::post('/drawer/approve/{id}', 'DrawerController@approve');
        Route::post('/drawer/retrieve/{id}', 'DrawerController@retrieve');
        Route::post('/drawer/update_done/{id}', 'DrawerController@updateDone')->name('adminDrawerUpdateDone');
        //开票人添加产品
        Route::post('/drawer/relate_product/{id}', 'DrawerController@relateProducts')->name('adminDrawerRelateProducts');
        Route::get('/drawer/export', 'DrawerController@drawerExport');

        //开票人选择
        Route::get('/drawer/drawer_choose', 'DrawerController@drawerChoose');
        Route::get('/drawer/drawer_detail/{id}', 'DrawerController@drawerDetail');
        
        //根据客户id选择开票产品
        Route::get('/drawer_products/{id}', 'DrawerController@drawerProducts');
        Route::get('/drawer_products/detail/{id}', 'DrawerController@drawerProductDetail');

        //订单管理
        Route::get('/order/{status}', 'OrderController@index');
        Route::get('/order/{type}/{id}', 'OrderController@read');
        Route::post('/order/draft_save', 'OrderController@draftSave')->name('adminOrderDraftSave');
        Route::post('/order/draft_update/{id}', 'OrderController@draftUpdate')->name('adminOrderDraftUpdate');
        Route::post('/order/save', 'OrderController@save')->name('adminOrderSave');
        Route::post('/order/update/{id}', 'OrderController@update')->name('adminOrderUpdate');
        Route::post('/order/delete/{id}', 'OrderController@delete');
        Route::post('/order/approve/{id}', 'OrderController@approve');
        Route::post('/order/retrieve/{id}', 'OrderController@retrieve');
        Route::get('/order/harbor/{id}', 'OrderController@harbor');
        Route::get('/order/export', 'OrderController@orderExport');
        Route::post('/order/close_case', 'OrderController@closeCase');

        //订单选择列表
        Route::get('/order/order_choose', 'OrderController@orderChoose');

        //选择业务编号
        Route::get('/bnotelist', 'OrderController@bnotelist');
        //获取erp托单信息
        Route::get('/bnoteinfo/{id}', 'OrderController@bnoteinfo');

        /*erp报关单*/
        Route::get('/customs', 'CustomsController@index');
        Route::get('/customs/read/{id}', 'CustomsController@read');
        Route::post('/customs/customs_import', 'CustomsController@customsImport');
        //报关单产品与开票人关联
        Route::get('/order/drawer_product_relate', 'OrderController@drawerProductRelateView');
        Route::post('/order/drawer_product_relate', 'OrderController@drawerProductRelate')->name('drawerProductRelate');
        //由报关单做订单保存
        Route::post('/order/customs_order_save', 'OrderController@customsOrderSave')->name('customsOrderSave');
        //订单字段匹配id
        Route::post('/order/field_match_id', 'OrderController@fieldMatchId')->name('fieldMatchId');
        
        //报关管理
        Route::get('/clearance', 'ClearanceController@index');
        //Route::get('/clearance/{type}/{id}', 'ClearanceController@read');
        //Route::post('/clearance', 'ClearanceController@save');
        Route::post('/clearance/update/{id}', 'ClearanceController@update');
        Route::get('/clearance/{id}', 'ClearanceController@get');
        //Route::get('/clearance/customes_entry/{id}', 'ClearanceController@customsEntryRead');
        Route::post('/clearance/customes_entry/{id}', 'ClearanceController@customsEntryUpdate')->name('adminCustomsEntry');
        Route::get('/clearance/file_info/{id}', 'ClearanceController@fileInfo');
        Route::post('/clearance/file_info/{id}', 'ClearanceController@fileInfo');
        Route::get('/clearance/export', 'ClearanceController@clearanceExport');
        //下载报关资料（注释）
        Route::get('/clearance/dwxlsx/{id}', function ($id) {
            return view('clearance.dwxlsx', ['id'=>$id]);
        });

        //发票管理
        Route::get('/invoice/{status}', 'InvoiceController@index');
        Route::get('/invoice/{type}/{id}', 'InvoiceController@read');
        Route::get('/invoice/order_list', 'InvoiceController@orderList');
        Route::get('/invoice/order_invoice_list/{id}', 'InvoiceController@orderInvoiceList');

        //Route::post('/invoice', 'InvoiceController@save')->name('invoiceSave');
        Route::post('/invoice/delete/{id}', 'InvoiceController@delete');
        Route::post('/invoice/update/{id}', 'InvoiceController@update');
        Route::get('/invoice/export', 'InvoiceController@invoiceExport');


        //申报管理
        Route::get('/filing/{status}', 'FilingController@index');
        Route::get('/filing/{type}/{id}', 'FilingController@read');
        Route::get('/filing/select/{id}', 'FilingController@select');
        //Route::post('/filing', 'FilingController@save')->name('adminFilingSave');
        Route::post('/filing/update/{id}', 'FilingController@update');
        Route::post('/filing/delete/{id}', 'FilingController@delete');
        Route::get('/filing/batch/{batch}', 'FilingController@existBatch');
        Route::get('/filing/letter', 'FilingController@letterList');
        Route::get('/filing/letter/{id}', 'FilingController@letterDetail');
        Route::get('/filing/letter-invoice-list/{id}', 'FilingController@letterInvoiceList');
        Route::get('/filing/export', 'FilingController@filingExport');
        Route::get('/filing/year_export', 'FilingController@filingYearExport');

        //运费管理
        Route::get('/finance/transport/{status}', 'TransportController@index');
        Route::get('/finance/transport/{type}/{id}', 'TransportController@read');
        Route::post('/finance/transport', 'TransportController@save');
        Route::post('/finance/transport/update/{id}', 'TransportController@update');
        Route::post('/finance/transport/approve/{id}', 'TransportController@approve');
        Route::get('/finance/transport/orderchoose', 'TransportController@orderchoose');
        
        //付款管理
        Route::get('/finance/pay/{status}', 'PayController@index');
        Route::get('/finance/pay/{type}/{id}', 'PayController@read');
        Route::post('/finance/pay', 'PayController@save')->name('adminPaySave');
        Route::post('/finance/pay/update/{id}', 'PayController@update')->name('adminPayUpdate');
        Route::post('/finance/pay/approve/{id}', 'PayController@approve');
        Route::get('/finance/payorder/choose', 'PayController@payOrder')->name('adminPayOrderChoose');//付款管理选择订单
        Route::get('/finance/pay/relate_order/{id}', 'PayController@relateOrder');
        Route::post('/finance/pay/relate_order/{id}', 'PayController@relateOrder')->name('adminPayRelateOrder');
        Route::get('/finance/payreceipt/choose', 'PayController@payReceipt')->name('adminPayReceiptChoose');//付款管理选择收汇
        Route::get('/finance/pay/relate_receipt/{id}', 'PayController@relateReceipt');
        Route::post('/finance/pay/relate_receipt/{id}', 'PayController@relateReceipt')->name('adminPayRelateReceipt');
        Route::get('/finance/pay/export', 'PayController@payExport');
        
        //结算管理
        Route::get('/finance/settlement/{status}', 'SettlementController@index');
        Route::get('/finance/settlement/{type}/{id}', 'SettlementController@read');
        Route::get('/finance/settlement/download', 'SettlementController@download');
        Route::get('/finance/settlement/settle/{id}', 'SettlementController@settle');
        Route::post('/finance/settlement/update/{id}', 'SettlementController@update');
        Route::post('/finance/settlement/approve/{id}', 'SettlementController@approve');
        Route::post('/finance/settlement/settlesave/{id}', 'SettlementController@settlesave')->name('adminSettlementSave');
        
        //收汇登记
        Route::get('/finance/receipt', 'ReceiptController@index');
        Route::get('/finance/receipt/{type}/{id}', 'ReceiptController@read');
        Route::post('/finance/receipt', 'ReceiptController@save')->name('adminReceiptSave');
        Route::post('/finance/receipt/update/{id}', 'ReceiptController@update')->name('adminReceiptUpdate');
        Route::post('/finance/receipt/exchange/{id}', 'ReceiptController@exchangeSettlement')->name('adminReceiptExchange');
        Route::post('/finance/receipt/approve/{id}', 'ReceiptController@approve');
        Route::post('/finance/receipt/delete/{id}', 'ReceiptController@delete');
        Route::get('/finance/receipt/number/{id}', 'ReceiptController@number');
        Route::get('/finance/receipt/order/{id}/{flag}', 'ReceiptController@order');
        Route::get('/finance/receipt/binding/{id}', 'ReceiptController@binding')->name('adminBinding');
        Route::get('/finance/receipt/unbinding/{id}', 'ReceiptController@unbinding')->name('adminUnbinding');

        //收款方管理
        Route::get('/finance/remittee', 'RemitteeController@index');
        Route::get('/finance/remittee/{type}/{id}', 'RemitteeController@read');
        Route::post('/finance/remittee', 'RemitteeController@save')->name('adminRemitteeSave');
        Route::post('/finance/remittee/update/{id}', 'RemitteeController@update')->name('adminRemitteeUpdate');
        Route::post('/finance/remittee/approve/{id}', 'RemitteeController@approve');
        Route::post('/finance/remittee/delete/{id}', 'RemitteeController@delete');
        //收款方选择列表
        Route::get('/finance/remittee_choose', 'RemitteeController@remitteeChoose');

        //数据维护
        Route::get('/data_manage', 'DataController@index');
        Route::get('/data/{id}', 'DataController@read');
        Route::get('/data/father/{id}', 'DataController@father');
        Route::post('/data/{id}', 'DataController@save');
        
        // 报表管理
        Route::get('/report', 'ReportController@index');
        Route::get('/report/export', 'ReportController@reportExport');

        //业务管理
        //普通请求获取主页,ajax请求获取分页数据
        Route::get('/business', 'BusinessController@index');
        Route::get('/business/{type}/{id}', 'BusinessController@read');
        Route::post('/business', 'BusinessController@save');
        Route::post('/business/delete/{id}', 'BusinessController@delete');
        Route::post('/business/update/{id}', 'BusinessController@update');
        Route::get('/business/contract', 'BusinessController@contract');

        //贸易商管理
        Route::get('/trader', 'TraderController@index');
        Route::get('/trader/{type}/{id}', 'TraderController@read');
        Route::post('/trader', 'TraderController@save');
        Route::post('/trader/delete/{id}', 'TraderController@delete');
        Route::post('/trader/update/{id}', 'TraderController@update');
        Route::get('/trader/export', 'TraderController@traderExport');
        //贸易商选择列表
        Route::get('/trader/trader_choose', 'TraderController@traderChoose');

        //开票资料管理
        Route::get('/billing', 'BillingController@index');
        Route::get('/billing/{type}/{id}', 'BillingController@read');
        Route::post('/billing/delete/{id}', 'BillingController@delete');
        Route::post('/billing/update/{id}', 'BillingController@update');
        
        //系统管理
        Route::get('/system/role', 'RoleController@index');
        Route::get('/system/role/{type}/{id}', 'RoleController@read');
        Route::post('/system/role', 'RoleController@save');
        Route::post('/system/role/update/{id}', 'RoleController@update');
        Route::post('/system/role/delete/{id}', 'RoleController@delete');

        Route::get('/system/permission', 'PermissionController@index');
        Route::get('/system/permission/{type}/{id}', 'PermissionController@read');
        Route::post('/system/permission', 'PermissionController@save');
        Route::post('/system/permission/update/{id}', 'PermissionController@update');
        Route::post('/system/permission/delete/{id}', 'PermissionController@delete');

        Route::get('/system/account', 'AccountController@index');
        //Route::get('/system/account/{role}', 'AccountController@role');
        Route::get('/system/account/{type}/{id}', 'AccountController@read');
        Route::post('/system/account', 'AccountController@save');
        Route::post('/system/account/update/{id}', 'AccountController@update');
        Route::post('/system/account/delete/{id}', 'AccountController@delete');

        //通知
        Route::get('/notification', 'NotificationController@index');
        Route::get('/notification/{type}/{id}', 'NotificationController@read');
        Route::post('/notification', 'NotificationController@save');

        //公司
        Route::get('/company', 'CompanyController@index');
        Route::get('/company/{type}/{id}', 'CompanyController@read');
        Route::post('/company', 'CompanyController@save');
        Route::post('/company/update/{id}', 'CompanyController@update');
        //公司选择列表
        Route::get('/company/company_choose', 'CompanyController@companyChoose');

        //采购合同
        Route::get('/purchase/{refund}', 'PurchaseController@index');
        Route::get('/purchase/{type}/{id}', 'PurchaseController@read');
        Route::post('/purchase/update/{id}', 'PurchaseController@update');
        Route::post('/purchase/approve/{id}', 'PurchaseController@approve');
    });
});

//Auth::routes();
//Route::get('/home', 'CustomerController@index');

//erp对接接口
// Route::get('/erp/getOrdInfo', 'Admin\ErpapiController@getOrdInfo');
// Route::post('/erp/ordInfo', 'Admin\ErpapiController@ordInfo');
// Route::post('/erp/portSync', 'Admin\ErpapiController@portSync');

// Route::post('/erp/purchase-throw-tax', 'Admin\PurchaseController@purchaseThrowTax')->name('purchaseThrowTax');

// Route::get('/tax/tax-company-list', 'Admin\TaxapiController@taxCompanyList');
// Route::get('/tax/tax-uninvoice-order-pro-list', 'Admin\TaxapiController@taxUninvoiceOrderProList')->name('taxUninvoiceOrderProList');
// Route::get('/tax/tax-order-with-pro-detail', 'Admin\TaxapiController@taxOrderWithProDetail')->name('taxOrderWithProDetail');
// Route::get('/tax/get-data', 'Admin\TaxapiController@getData');
// //erp发票流程结束时发送数据到退税
// Route::post('/tax/tax-invoice-complete', 'Admin\TaxapiController@taxInvoiceComplete')->name('taxInvoiceComplete');
// //erp申报流程开始时修改退税发票状态为已申报
// Route::post('/tax/tax-filing-start', 'Admin\TaxapiController@taxFilingStart')->name('taxFilingStart');
// //erp申报流程结束时发送数据到退税
// Route::post('/tax/tax-filing-complete', 'Admin\TaxapiController@taxFilingComplete')->name('taxFilingComplete');

//sxt商翔通对接接口
// Route::post('/sxt/customerStore', 'Admin\SxtapiController@customerStore');
// Route::post('/sxt/productStore', 'Admin\SxtapiController@productStore');
// Route::post('/sxt/drawerStore', 'Admin\SxtapiController@drawerStore');
// Route::post('/sxt/traderStore', 'Admin\SxtapiController@traderStore');
// Route::post('/sxt/orderStore', 'Admin\SxtapiController@orderStore');
// Route::get('/sxt/getData', 'Admin\SxtapiController@getData');
//Route::get('/sxt/remoteGetImage', 'SxtapiController@remoteGetImg');

//公司用户路由
Route::group(['prefix' => 'member', 'namespace' => 'Member'], function () {
    // Authentication Routes...
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('member.login');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout')->name('member.logout');

    Route::group(['middleware' => 'auth.member:member'], function () {
        
        Route::any('curl', function (Curl\Curl $curl, \Illuminate\Http\Request $request) {
            return $curl->{$request->method()}($request->url, $request->except(['url', 's']))->response;
        });

        //添加产品从报关系统获取数据
        Route::get('/custom_sys/product_list', 'CurlController@productList');
        Route::get('/custom_sys/element', 'CurlController@getElement');
        
        //选择审核通过的订单
        Route::get('/order/choose/{status}', 'DialogController@order');
        
        Route::get('/{dialog}/choose', function ($dialog) {
            return view("member.dialogs.{$dialog}");
        });
        //首页
        Route::get('/', 'HomeController@index');
        //上传
        Route::post('/upload', 'UploadController@upload');
        
        //审核待办数
        Route::get('/pending_review_number', 'CommonController@pendingReviewNumber');
        
        //获取单位列表
        Route::get('/unit/list', 'CommonController@unitList');
        //获取国家列表
        Route::get('/country/list', 'CommonController@countryList');
        //获取境内货源地列表
        Route::get('/district/list', 'CommonController@districtList');
        
        //获取erp中运营管理部的员工列表
        Route::get('/curl/erp_tax_user_list', 'CurlController@erpTaxUserList');
        //获取erp中收款账号列表
        Route::get('/curl/erp_enterprise_account', 'CurlController@erpEnterpriseAccount');

        //报关资料下载
        Route::get('/download/clearance_material/{id}', 'DownloadController@clearanceMaterialExcel');
        //报关预录入单下载
        Route::get('/download/customs_order/{id}', 'DownloadController@customsOrderExcel');
        //开票资料下载
        Route::get('/download/invoice_material/{id}', 'DownloadController@invoiceMaterialExcel');

        //客户管理
        //普通请求获取主页,ajax请求获取分页数据
        Route::get('/customer/{status}', 'CustomerController@index');
        Route::get('/customer/{type}/{id}', 'CustomerController@read');
        //Route::post('/customer', 'CustomerController@save');
        Route::post('/customer/delete/{id}', 'CustomerController@delete');
        Route::post('/customer/update/{id}', 'CustomerController@update')->name('memberCustomerUpdate');
        Route::post('/customer/update_done/{id}', 'CustomerController@updateDone')->name('memberCustomerUpdateDone');
        Route::post('/customer/approve/{id}', 'CustomerController@approve');
        Route::post('/customer/retrieve/{id}', 'CustomerController@retrieve');
        //Route::get('/customer/number/{number}', 'CustomerController@existNumber');
        //客户选择列表
        Route::get('/customer/customer_choose', 'CustomerController@customerChoose');

        //品牌
        Route::get('/brand/{status}', 'BrandController@index');
        Route::get('/brand/{type}/{id}', 'BrandController@read');
        Route::post('/brand', 'BrandController@save');
        Route::post('/brand/update/{id}', 'BrandController@update');
        Route::post('/brand/approve/{id}', 'BrandController@approve');
        //选择品牌
        Route::get('/brand/brandlist', 'BrandController@brandlist');

        //联系人
        //选择联系人
        Route::get('/link/linklist', 'LinkController@linkList');

        //产品管理
        Route::get('/product/{status}', 'ProductController@index');
        Route::get('/product/{type}/{id}', 'ProductController@read');
        //Route::post('/product', 'ProductController@save');
        Route::post('/product/delete/{id}', 'ProductController@delete');
        Route::post('/product/update/{id}', 'ProductController@update')->name('memberProductUpdate');
        Route::post('/product/approve/{id}', 'ProductController@approve');
        Route::post('/product/retrieve/{id}', 'ProductController@retrieve');
        Route::post('/product/update_done/{id}', 'ProductController@updateDone')->name('memberProductUpdateDone');
        //获取单个产品信息
        Route::get('/product/product_info/{id}', 'ProductController@productInfo');
        //产品选择列表
        Route::get('/product/product_choose', 'ProductController@productChoose');
        
        //开票人管理
        Route::get('/drawer/{status}', 'DrawerController@index');
        Route::get('/drawer/{type}/{id}', 'DrawerController@read');
        Route::post('/drawer', 'DrawerController@save');
        Route::post('/drawer/delete/{id}', 'DrawerController@delete');
        Route::post('/drawer/update/{id}', 'DrawerController@update');
        Route::post('/drawer/approve/{id}', 'DrawerController@approve');
        Route::post('/drawer/retrieve/{id}', 'DrawerController@retrieve');
        Route::post('/drawer/update_done/{id}', 'DrawerController@updateDone')->name('memberDrawerUpdateDone');
        Route::get('/drawer/relate_product/{id}', 'DrawerController@relateProducts');
        Route::post('/drawer/relate_product/{id}', 'DrawerController@relateProducts')->name('memberDrawerRelateProducts');//开票人添加产品
        Route::get('/drawer/export', 'DrawerController@drawerExport');
        Route::get('/drawer/name_match', 'DrawerController@nameMatch'); //开票人公司名称匹配
        //根据客户id选择开票产品
        Route::get('/drawer_products/{id}', 'DrawerController@drawerProducts');
        Route::get('/drawer_products/detail/{id}', 'DrawerController@drawerProductDetail');

        //订单管理
        Route::get('/order/{status}', 'OrderController@index');
        Route::get('/order/{type}/{id}', 'OrderController@read');
        Route::post('/order', 'OrderController@save');
        Route::post('/order/delete/{id}', 'OrderController@delete');
        Route::post('/order/update/{id}', 'OrderController@update');
        Route::post('/order/delete/{id}', 'OrderController@delete');
        Route::post('/order/approve/{id}', 'OrderController@approve');
        Route::post('/order/retrieve/{id}', 'OrderController@retrieve');
        Route::get('/order/harbor/{id}', 'OrderController@harbor');
        Route::get('/order/export', 'OrderController@orderExport');
        Route::post('/order/close_case', 'OrderController@closeCase');

        //订单选择列表
        Route::get('/order/order_choose', 'OrderController@orderChoose');
        //选择业务编号
        Route::get('/bnotelist', 'OrderController@bnotelist');
        //获取erp托单信息
        Route::get('/bnoteinfo/{id}', 'OrderController@bnoteinfo');
        
        //报关管理
        Route::get('/clearance', 'ClearanceController@index');
        //Route::get('/clearance/{type}/{id}', 'ClearanceController@read');
        //Route::post('/clearance', 'ClearanceController@save');
        Route::post('/clearance/update/{id}', 'ClearanceController@update');
        Route::get('/clearance/{id}', 'ClearanceController@get');
        Route::get('/clearance/customes_entry/{id}', 'ClearanceController@customsEntryRead');
        Route::post('/clearance/customes_entry/{id}', 'ClearanceController@customsEntryUpdate')->name('memberCustomsEntry');
        Route::get('/clearance/file_info/{id}', 'ClearanceController@fileInfo');
        Route::post('/clearance/file_info/{id}', 'ClearanceController@fileInfo');
        Route::get('/clearance/export', 'ClearanceController@clearanceExport');
        //发送订单到品浙行
        Route::post('/clearance/pzx_order', 'ClearanceController@pzxOrder');
        //发送报关单到品浙行
        Route::post('/clearance/pzx_clearance', 'ClearanceController@pzxClearance');
        //下载报关资料（注释）
        Route::get('/clearance/dwxlsx/{id}', function ($id) {
            return view('clearance.dwxlsx', ['id'=>$id]);
        });

        //发票管理
        Route::get('/invoice/{status}', 'InvoiceController@index');
        Route::get('/invoice/{type}/{id}', 'InvoiceController@read');
        Route::get('/invoice/order_list', 'InvoiceController@orderList');
        Route::get('/invoice/order_invoice_list/{id}', 'InvoiceController@orderInvoiceList');
        
        //Route::post('/invoice', 'InvoiceController@save')->name('invoiceSave');
        Route::post('/invoice/delete/{id}', 'InvoiceController@delete');
        Route::post('/invoice/update/{id}', 'InvoiceController@update');
        Route::get('/invoice_clearances/{drawer_id}', 'InvoiceController@clearances');
        Route::get('/invoice/export', 'InvoiceController@invoiceExport');


        //申报管理
        Route::get('/filing/{status}', 'FilingController@index');
        Route::get('/filing/{type}/{id}', 'FilingController@read');
        Route::get('/filing/select/{id}', 'FilingController@select');
        //Route::post('/filing', 'FilingController@save')->name('adminFilingSave');
        Route::post('/filing/update/{id}', 'FilingController@update');
        Route::post('/filing/delete/{id}', 'FilingController@delete');
        Route::get('/filing/batch/{batch}', 'FilingController@existBatch');
        Route::get('/filing/letter', 'FilingController@letterList');
        Route::get('/filing/letter/{id}', 'FilingController@letterDetail');
        Route::get('/filing/letter-invoice-list/{id}', 'FilingController@letterInvoiceList');
        Route::get('/filing/export', 'FilingController@filingExport');
        Route::get('/filing/year_export', 'FilingController@filingYearExport');

        //运费管理
        Route::get('/finance/transport/{status}', 'TransportController@index');
        Route::get('/finance/transport/{type}/{id}', 'TransportController@read');
        Route::post('/finance/transport', 'TransportController@save');
        Route::post('/finance/transport/update/{id}', 'TransportController@update');
        Route::post('/finance/transport/approve/{id}', 'TransportController@approve');
        Route::get('/finance/transport/orderchoose', 'TransportController@orderchoose');
        
        //付款管理
        Route::get('/finance/pay/{status}', 'PayController@index');
        Route::get('/finance/pay/{type}/{id}', 'PayController@read');
        Route::post('/finance/pay', 'PayController@save')->name('memberPaySave');
        Route::post('/finance/pay/update/{id}', 'PayController@update')->name('memberPayUpdate');
        Route::post('/finance/pay/approve/{id}', 'PayController@approve');
        Route::get('/finance/payorder/choose', 'PayController@payOrder')->name('memberPayOrderChoose');//付款管理选择订单
        Route::get('/finance/pay/relate_order/{id}', 'PayController@relateOrder');
        Route::post('/finance/pay/relate_order/{id}', 'PayController@relateOrder')->name('memberPayRelateOrder');
        Route::get('/finance/payreceipt/choose', 'PayController@payReceipt')->name('memberPayReceiptChoose');//付款管理选择收汇
        Route::get('/finance/pay/relate_receipt/{id}', 'PayController@relateReceipt');
        Route::post('/finance/pay/relate_receipt/{id}', 'PayController@relateReceipt')->name('memberPayRelateReceipt');
        Route::get('/finance/pay/export', 'PayController@payExport');
        
        //结算管理
        Route::get('/finance/settlement/{status}', 'SettlementController@index');
        Route::get('/finance/settlement/{type}/{id}', 'SettlementController@read');
        Route::get('/finance/settlement/settle/{id}', 'SettlementController@settle');
        Route::post('/finance/settlement/update/{id}', 'SettlementController@update');
        Route::post('/finance/settlement/approve/{id}', 'SettlementController@approve');
        Route::post('/finance/settlement/settlesave/{id}', 'SettlementController@settlesave')->name('memberSettlementSave');
        Route::get('/finance/settlement/export', 'SettlementController@settlementExport');
        
        //收汇登记
        Route::get('/finance/receipt', 'ReceiptController@index');
        Route::get('/finance/receipt/{type}/{id}', 'ReceiptController@read');
        Route::post('/finance/receipt', 'ReceiptController@save')->name('memberReceiptSave');
        Route::post('/finance/receipt/update/{id}', 'ReceiptController@update')->name('memberReceiptUpdate');
        Route::post('/finance/receipt/exchange/{id}', 'ReceiptController@exchangeSettlement')->name('memberReceiptExchange');
        Route::post('/finance/receipt/approve/{id}', 'ReceiptController@approve');
        Route::post('/finance/receipt/delete/{id}', 'ReceiptController@delete');
        Route::get('/finance/receipt/number/{id}', 'ReceiptController@number');
        Route::get('/finance/receipt/order/{id}/{flag}', 'ReceiptController@order');
        Route::get('/finance/receipt/binding/{id}', 'ReceiptController@binding')->name('memberBinding');
        Route::get('/finance/receipt/unbinding/{id}', 'ReceiptController@unbinding')->name('memberUnbinding');

        //收款方管理
        Route::get('/finance/remittee', 'RemitteeController@index');
        Route::get('/finance/remittee/{type}/{id}', 'RemitteeController@read');
        Route::post('/finance/remittee', 'RemitteeController@save')->name('memberRemitteeSave');;
        Route::post('/finance/remittee/update/{id}', 'RemitteeController@update')->name('memberRemitteeUpdate');;
        Route::post('/finance/remittee/approve/{id}', 'RemitteeController@approve');
        Route::post('/finance/remittee/delete/{id}', 'RemitteeController@delete');
        //收款方选择列表
        Route::get('/finance/remittee_choose', 'RemitteeController@remitteeChoose');

        //数据维护
        Route::get('/data_manage', 'DataController@index');
        Route::get('/data/{id}', 'DataController@read');
        Route::get('/data/father/{id}', 'DataController@father');
        Route::post('/data/{id}', 'DataController@save');

        // 报表管理
        Route::get('/report', 'ReportController@index');

        //业务管理
        //普通请求获取主页,ajax请求获取分页数据
        Route::get('/business', 'BusinessController@index');
        Route::get('/business/{type}/{id}', 'BusinessController@read');
        Route::post('/business', 'BusinessController@save');
        Route::post('/business/delete/{id}', 'BusinessController@delete');
        Route::post('/business/update/{id}', 'BusinessController@update');
        Route::get('/business/contract', 'BusinessController@contract');

        //贸易商管理
        Route::get('/trader', 'TraderController@index');
        Route::get('/trader/{type}/{id}', 'TraderController@read');
        Route::post('/trader', 'TraderController@save');
        Route::post('/trader/delete/{id}', 'TraderController@delete');
        Route::post('/trader/update/{id}', 'TraderController@update');
        Route::get('/trader/export', 'TraderController@traderExport');

        //开票资料管理
        Route::get('/billing', 'BillingController@index');
        Route::get('/billing/{type}/{id}', 'BillingController@read');
        Route::post('/billing/delete/{id}', 'BillingController@delete');
        Route::post('/billing/update/{id}', 'BillingController@update');
        
        //系统管理
        Route::get('/system/role', 'RoleController@index');
        Route::get('/system/role/{type}/{id}', 'RoleController@read');
        Route::post('/system/role', 'RoleController@save');
        Route::post('/system/role/update/{id}', 'RoleController@update');
        Route::post('/system/role/delete/{id}', 'RoleController@delete');

        Route::get('/system/permission', 'PermissionController@index');
        Route::get('/system/permission/{type}/{id}', 'PermissionController@read');
        Route::post('/system/permission', 'PermissionController@save');
        Route::post('/system/permission/update/{id}', 'PermissionController@update');
        Route::post('/system/permission/delete/{id}', 'PermissionController@delete');

        Route::get('/system/account', 'AccountController@index');
        //Route::get('/system/account/{role}', 'AccountController@role');
        Route::get('/system/account/{type}/{id}', 'AccountController@read');
        Route::post('/system/account', 'AccountController@save');
        Route::post('/system/account/update/{id}', 'AccountController@update');
        Route::post('/system/account/delete/{id}', 'AccountController@delete');

        //通知
        Route::get('/notification', 'NotificationController@index');
        Route::get('/notification/{type}/{id}', 'NotificationController@read');
        Route::post('/notification', 'NotificationController@save');

        //公司
        Route::get('/company', 'CompanyController@index');
        Route::get('/company/{type}/{id}', 'CompanyController@read');
        Route::post('/company', 'CompanyController@save');
        Route::post('/company/update/{id}', 'CompanyController@update');
        //公司选择列表
        Route::get('/company/company_choose', 'CompanyController@companyChoose');

        /*个人中心*/
        //个人信息
        Route::get('/personalcenter/personal/{status}', 'PersonalController@index');
        Route::get('/personalcenter/personal/{type}/{id}', 'PersonalController@read');
        Route::post('/personalcenter/personal', 'PersonalController@save');
        Route::post('/personalcenter/personal/update/{id}', 'PersonalController@update');
        Route::post('/personalcenter/personal/approve/{id}', 'PersonalController@approve');
        Route::post('/personalcenter/personal/password/reset', 'PersonalController@resetPassword')->name('memberPersonalResetPassword');

        //产品日志
        Route::get('/productlog', 'ProductlogController@index');
        //客户日志
        Route::get('/customerlog', 'CustomerlogController@index');
        //订单日志
        Route::get('/orderlog', 'OrderlogController@index');
    });
});