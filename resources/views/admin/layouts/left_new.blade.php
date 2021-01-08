@inject('menuPresenter','App\Presenters\Admin\MenuPresenter')
<style media="screen">
    .logo{
        margin-top: -8px !important;
        margin-left: -6px !important;
        width: 30px !important;
        height: 30px;
    }
    .choosed{
        background-color: #f0ad4e
    }
    .customer_logo{
        background: url('/images/center/left_logo.png') -12px -15px;
    }
    .busine_logo{
        background: url('/images/center/left_logo.png') -45px -15px;
    }
    .finance_logo{
        background: url('/images/center/left_logo.png') -82px -15px;
    }
    .reportForms_logo{
        background: url('/images/center/left_logo.png') -120px -15px;
    }
    .system_logo{
        background: url('/images/center/left_logo.png') -156px -15px;
    }

    .cus_logo{
        background: url('/images/center/left_logo.png') -12px -40px;
    }
    .link_logo{
        background: url('/images/center/left_logo.png') -38px -41px;
    }
    .trader_logo{
        background: url('/images/center/left_logo.png') -65px -42px;
    }
    .remittee_logo{
        background: url('/images/center/left_logo.png') -90px -42px;
    }
    .drawer_logo{
        background: url('/images/center/left_logo.png') -118px -41px;
    }
    .company_logo{
        background: url('/images/center/left_logo.png') -142px -41px;
    }

    .brand_logo{
        background: url('/images/center/left_logo.png') -12px -64px;
    }
    .product_logo{
        background: url('/images/center/left_logo.png') -38px -64px;
    }
    .order_logo{
        background: url('/images/center/left_logo.png') -65px -64px;
    }
    .clearance_logo{
        background: url('/images/center/left_logo.png') -90px -64px;
    }
    .data_logo{
        background: url('/images/center/left_logo.png') -116px -64px;
    }
    .bus_logo{
        background: url('/images/center/left_logo.png') -142px -64px;
    }
    .billing_logo{
        background: url('/images/center/left_logo.png') -168px -64px;
    }

    .filing_logo{
        background: url('/images/center/left_logo.png') -12px -88px;
    }
    .invoice_logo{
        background: url('/images/center/left_logo.png') -38px -88px;
    }
    .transport_logo{
        background: url('/images/center/left_logo.png') -63px -88px;
    }
    .pay_logo{
        background: url('/images/center/left_logo.png') -90px -88px;
    }
    .receipt_logo{
        background: url('/images/center/left_logo.png') -116px -88px;
    }
    .settlement_logo{
        background: url('/images/center/left_logo.png') -142px -88px;
    }

    .report_logo{
        background: url('/images/center/left_logo.png') -12px -110px;
    }

    .purchase_logo{
        background: url('/images/center/left_logo.png') -12px -136px;
    }
    .permission_logo{
        background: url('/images/center/left_logo.png') -36px -136px;
    }
    .role_logo{
        background: url('/images/center/left_logo.png') -60px -136px;
    }
    .account_logo{
        background: url('/images/center/left_logo.png') -88px -136px;
    }
</style>
<aside class="bg-infor lter aside-md hidden-print" id="nav" style="background:#fff;">
    <section class="vbox">
        <section class="w-f scrollable">
            <div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0"
                 data-size="5px" data-color="#333333">
                <!-- 左侧导航栏 -->
                <nav class="nav-primary hidden-xs">
                    <ul class="nav">
                        @php
                            $permArr = $menuPresenter->menuList();
                        @endphp
                        @if(in_array('customer', $permArr) || in_array('link', $permArr) || in_array('trader', $permArr) || in_array('finance/remittee', $permArr) || in_array('drawer', $permArr) || in_array('company', $permArr))
                        <li class="menu_one customer_base" name="customer_base">
                            <a href="javascript:;">
                                <i class="customer_logo logo icon"></i>
                                <span class="pull-right">
                                  <i class="fa fa-angle-down text"></i>
                                  <i class="fa fa-angle-up text-active"></i>
                                </span>
                                <span style="font-size: 16px">客户关系管理</span>
                            </a>
                            <ul class="nav lt">
                                @if(in_array('customer', $permArr))
                                <li name="customer" class="customer">
                                    <a data-href='/admin/customer/0'>
                                        <i class="cus_logo logo icon"></i>
                                        <span>客户管理</span>
                                        <span class="tips customer_count hide"></span>
                                    </a>
                                </li>
                                @endif
                                @if(in_array('link', $permArr))
                                <li name="link" class="link">
                                    <a data-href="/admin/link">
                                        <i class="link_logo logo icon"></i>
                                        <span>联系人管理</span>
                                    </a>
                                </li>
                                @endif
                                @if(in_array('trader', $permArr))
                                <li name="trader" class="trader">
                                    <a data-href="/admin/trader">
                                        <i class="trader_logo logo icon"></i>
                                        <span>贸易商管理</span>
                                    </a>
                                </li>
                                @endif
                                @if(in_array('finance/remittee', $permArr))
                                <li name="remittee"  class="remittee">
                                    <a data-href="/admin/finance/remittee">
                                        <i class="remittee_logo logo icon"></i>
                                        <span>收款方管理</span>
                                    </a>
                                </li>
                                @endif
                                @if(in_array('drawer', $permArr))
                                <li name="drawer" class="drawer">
                                    <a data-href="/admin/drawer/0">
                                        <i class="drawer_logo logo icon"></i>
                                        <span>开票人管理</span>
                                        <span class="tips drawer_count hide"></span>
                                    </a>
                                </li>
                                @endif
                                @if(in_array('company', $permArr))
                                <li name="company" class="company">
                                    <a data-href="/admin/company">
                                        <i class="company_logo logo icon"></i>
                                        <span>公司管理</span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif

                        @if(in_array('brand', $permArr) || in_array('product', $permArr) || in_array('order', $permArr) || in_array('clearance', $permArr) || in_array('data_manage', $permArr) || in_array('business', $permArr) || in_array('billing', $permArr))
                        <li class="menu_one busine" name="busine">
                            <a data-href="javascript:;">
                                <i class="busine_logo logo icon"></i>
                                <span class="pull-right">
                                  <i class="fa fa-angle-down text"></i>
                                  <i class="fa fa-angle-up text-active"></i>
                                </span>
                                <span style="font-size: 16px">业务资料管理</span>
                            </a>
                            <ul class="nav lt">
                                @if(in_array('brand', $permArr))
                                <li name="brand" class="brand">
                                    <a data-href="/admin/brand/0">
                                        <i class="brand_logo logo icon"></i>
                                        <span>品牌管理</span>
                                        <span class="tips brand_count hide"></span>
                                    </a>
                                </li>
                                @endif
                                @if(in_array('product', $permArr))
                                <li name="product" class="product">
                                    <a data-href="/admin/product/0">
                                        <i class="product_logo logo icon"></i>
                                        <span>产品管理</span>
                                        <span class="tips product_count hide"></span>
                                    </a>
                                </li>
                                @endif
                                @if(in_array('order', $permArr))
                                <li name="order" class="order">
                                    <a data-href="/admin/order/0">
                                        <i class="order_logo logo icon"></i>
                                        <span>订单管理</span>
                                        <span class="tips order_count hide"></span>
                                    </a>
                                </li>
                                @endif
                                @if(in_array('clearance', $permArr))
                                <li name="clearance" class="clearance">
                                    <a data-href="/admin/clearance">
                                        <i class="clearance_logo logo icon"></i>
                                        <span>报关管理</span>
                                    </a>
                                </li>
                                @endif
                                @if(in_array('data_manage', $permArr))
                                <li name="data_manage" class="data_manage">
                                    <a data-href="/admin/data_manage">
                                        <i class="data_logo logo icon"></i>
                                        <span>数据维护</span>
                                    </a>
                                </li>
                                @endif
                                @if(in_array('business', $permArr))
                                <li name="business" class="business">
                                    <a data-href="/admin/business">
                                        <i class="bus_logo logo icon"></i>
                                        <span>业务类型管理</span>
                                    </a>
                                </li>
                                @endif
                                @if(in_array('purchase', $permArr))
                                <li name="purchase" class="purchase">
                                    <a data-href="/admin/purchase/1">
                                        <i class="purchase_logo logo icon"></i>
                                        <span>采购合同管理</span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif

                        @if(in_array('filing', $permArr) || in_array('invoice', $permArr) || in_array('finance/transport', $permArr) || in_array('finance/pay', $permArr) || in_array('finance/receipt', $permArr) || in_array('finance/settlement', $permArr))
                        <li class="menu_one finance" name="finance">
                            <a href="javascript:;">
                                <i class="finance_logo logo icon"></i>
                                <span class="pull-right">
                                  <i class="fa fa-angle-down text"></i>
                                  <i class="fa fa-angle-up text-active"></i>
                                </span>
                                <span style="font-size: 16px">财务信息管理</span>
                            </a>
                            <ul class="nav lt">
                                @if(in_array('filing', $permArr))
                                <li name="filing" class="filing">
                                    <a data-href="/admin/filing/2">
                                        <i class="filing_logo logo icon"></i>
                                        <span>申报管理</span>
                                    </a>
                                </li>
                                @endif
                                @if(in_array('invoice', $permArr))
                                <li name="invoice" class="invoice">
                                    <a data-href="/admin/invoice/3">
                                        <i class="invoice_logo logo icon"></i>
                                        <span>发票管理</span>
                                    </a>
                                </li>
                                @endif
                                @if(in_array('finance/transport', $permArr))
                                <li name="transport" class="transport">
                                    <a data-href="/admin/finance/transport/3">
                                        <i class="transport_logo logo icon"></i>
                                        <span>运费登记</span>
                                    </a>
                                </li>
                                @endif
                                @if(in_array('finance/pay', $permArr))
                                <li name="pay" class="pay">
                                    <a data-href="/admin/finance/pay/0">
                                        <i class="pay_logo logo icon"></i>
                                        <span>付款管理</span>
                                    </a>
                                </li>
                                @endif
                                @if(in_array('finance/receipt', $permArr))
                                <li name="receipt" class="receipt">
                                    <a data-href="/admin/finance/receipt">
                                        <i class="receipt_logo logo icon"></i>
                                        <span>收汇登记</span>
                                    </a>
                                </li>
                                @endif
                                @if(in_array('finance/settlement', $permArr))
                                <li name="settlement" class="settlement">
                                    <a data-href="/admin/finance/settlement/0">
                                        <i class="settlement_logo logo icon"></i>
                                        <span>退税结算</span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif
                        @if(in_array('report', $permArr))
                        <li class="menu_one reportForms" name="reportForms">
                            <a href="javascript:;">
                                <i class="reportForms_logo logo icon"></i>
                                <span class="pull-right">
                                  <i class="fa fa-angle-down text"></i>
                                  <i class="fa fa-angle-up text-active"></i>
                                </span>
                                <span style="font-size: 16px">数据报表管理</span>
                            </a>
                            <ul class="nav lt">
                                @if(in_array('report', $permArr))
                                <li name="report" class="report">
                                    <a data-href="/admin/report">
                                        <i class="report_logo logo icon"></i>
                                        <span>报表管理</span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif

                        @if(in_array('purchase', $permArr) || in_array('system/permission', $permArr) || in_array('system/role', $permArr) || in_array('system/account', $permArr))
                        <li class="menu_one system" name="system">
                            <a href="javascript:;">
                                <i class="system_logo logo icon"></i>
                                <span class="pull-right">
                                  <i class="fa fa-angle-down text"></i>
                                  <i class="fa fa-angle-up text-active"></i>
                                </span>
                                <span style="font-size: 16px">系统管理</span>
                            </a>
                            <ul class="nav lt">
                                @if(in_array('system/permission', $permArr))
                                <li name="permission" class="permission">
                                    <a data-href="/admin/system/permission">
                                        <i class="permission_logo logo icon"></i>
                                        <span>权限管理</span>
                                    </a>
                                </li>
                                @endif
                                @if(in_array('system/role', $permArr))
                                <li name="role" class="role">
                                    <a data-href="/admin/system/role">
                                        <i class="role_logo logo icon"></i>
                                        <span>角色管理</span>
                                    </a>
                                </li>
                                @endif
                                @if(in_array('system/account', $permArr))
                                <li name="account" class="account">
                                    <a data-href="/admin/system/account">
                                        <i class="account_logo logo icon"></i>
                                        <span>账号管理</span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </section>

        <footer class="footer lt hidden-xs b-t">
            <a href="#nav" data-toggle="class:nav-xs" class="pull-right btn btn-sm btn-dark btn-icon">
                <i class="fa fa-angle-left text"></i>
                <i class="fa fa-angle-right text-active"></i>
            </a>
        </footer>
    </section>
    <script>
        auditNum()
        function auditNum(){ //获得审核待办数
            $.ajax({
                type: 'get',
                url: '/admin/pending_review_number',
                success: function(res){
                    if(res.code == 200){
                        $.each(res.data,function(i,item){
                            $('.'+i).html(item)
                        })
                        $.each($('.tips'),function(){
                            if($(this).text() != '' && $(this).text() != 0){
                                $(this).removeClass('hide')
                            }
                        })
                    }
                }
            })
        }
        //设置cookie
        function setCookie(name,value){
            var Days = 30;
            var exp = new Date();
            exp.setTime(exp.getTime() + Days*24*60*60*1000);
            document.cookie = name + "=" + encodeURIComponent(value) + ";expires=" + exp.toGMTString()+";path=/";
            return true
        }
        var Cookiename = ''
        base_menu = 'busine'
        $('.menu_one ul li').on('click',function(){
            var menu_one_name = $(this).parent().parent().attr('name')
                menu_two_name = $(this).attr('name')
            setCookie('Cookiename',menu_one_name)
            setCookie('Cookiechoose',menu_two_name)
            var href = $(this).children().data('href')
            setTimeout(function () {
                window.location.href = href
            }, 500);
        })
        var menu_one = getCookie('Cookiename')
            menu_two = getCookie('Cookiechoose')
        menu_one ? menu_one : base_menu
        console.log(menu_one);
        if(menu_one){
            $('.' + menu_one).addClass('active')
            $('.' + menu_one + ' ul li.' + menu_two).addClass('choosed')
        }
        //读取cookie
        function getCookie(Cookiename){
            var arr,reg=new RegExp("(^| )"+Cookiename+"=([^;]*)(;|$)");
            if(arr=document.cookie.match(reg))
            return unescape(arr[2]);
            else
            return null;
        }
    </script>
</aside>
