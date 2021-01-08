@inject('menuPresenter','App\Presenters\Admin\MenuPresenter')
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
                        @if(in_array('customer', $permArr))
                        <li name="customer">
                            <a href="/admin/customer/0">
                                <i class="fa fa-dashboard icon">
                                    <b class="bg-danger"></b>
                                </i>
                                <span>客户管理</span>
                                <span class="tips customer_count hide"></span>
                            </a>
                        </li>
                        @endif
                        @if(in_array('link', $permArr))
                        <li name="link">
                            <a href="/admin/link">
                                <i class="fa fa-globe icon">
                                    <b class="bg-danger"></b>
                                </i>
                                <span>联系人管理</span>
                            </a>
                        </li>
                        @endif
                        @if(in_array('brand', $permArr))
                        <li name="brand">
                            <a href="/admin/brand/0">
                                <i class="fa fa-globe icon">
                                    <b class="bg-danger"></b>
                                </i>
                                <span>品牌管理</span>
                                <span class="tips brand_count hide"></span>
                            </a>
                        </li>
                        @endif
                        @if(in_array('product', $permArr))
                        <li name="product">
                            <a href="/admin/product/0">
                                <i class="fa fa-list-alt icon">
                                    <b class="bg-danger"></b>
                                </i>
                                <span>产品管理</span>
                                <span class="tips product_count hide"></span>
                            </a>
                        </li>
                        @endif
                        @if(in_array('drawer', $permArr))
                        <li name="drawer">
                            <a href="/admin/drawer/0">
                                <i class="fa fa-users icon">
                                    <b class="bg-danger"></b>
                                </i>
                                <span>开票人管理</span>
                                <span class="tips drawer_count hide"></span>
                            </a>
                        </li>
                        @endif
                        @if(in_array('trader', $permArr))
                        <li name="trader">
                            <a href="/admin/trader">
                                <i class="fa fa-suitcase icon">
                                    <b class="bg-danger"></b>
                                </i>
                                <span>贸易商管理</span>
                            </a>
                        </li>
                        @endif
                        @if(in_array('order', $permArr))
                        <li name="order">
                            <a href="/admin/order/0">
                                <i class="fa fa-file-text-o icon">
                                    <b class="bg-danger"></b>
                                </i>
                                <span>订单管理</span>
                                <span class="tips order_count hide"></span>
                            </a>
                        </li>
                        @endif
                        @if(in_array('clearance', $permArr))
                        <li name="clearance">
                            <a href="/admin/clearance">
                                <i class="fa fa-columns icon">
                                    <b class="bg-danger"></b>
                                </i>
                                <span>报关管理</span>
                            </a>
                        </li>
                        @endif
                        @if(in_array('invoice', $permArr))
                        <li name="invoice">
                            <a href="/admin/invoice/3">
                                <i class="fa fa-credit-card icon">
                                    <b class="bg-danger"></b>
                                </i>
                                <span>发票管理</span>
                            </a>
                        </li>
                        @endif
                        @if(in_array('filing', $permArr))
                        <li name="filing">
                            <a href="/admin/filing/2">
                                <i class="fa fa-leaf icon">
                                    <b class="bg-danger"></b>
                                </i>
                                <span>申报管理</span>
                            </a>
                        </li>
                        @endif
                        @if(in_array('finance/transport', $permArr) || in_array('finance/pay', $permArr) || in_array('finance/receipt', $permArr) || in_array('finance/remittee', $permArr))
                        <li name="finance">
                            <a href="javascript:;">
                                <i class="fa fa-money icon">
                                    <b class="bg-danger"></b>
                                </i>
                                <span class="pull-right">
                                  <i class="fa fa-angle-down text"></i>
                                  <i class="fa fa-angle-up text-active"></i>
                                </span>
                                <span>财务管理</span>
                            </a>
                            <ul class="nav lt">
                                @if(in_array('finance/transport', $permArr))
                                <li >
                                    <a href="/admin/finance/transport/3">
                                        <i class="fa fa-angle-right"></i>
                                        <span>运费登记</span>
                                    </a>
                                </li>
                                @endif
                                @if(in_array('finance/pay', $permArr))
                                <li >
                                    <a href="/admin/finance/pay/0">
                                        <i class="fa fa-angle-right"></i>
                                        <span>付款管理</span>
                                    </a>
                                </li>
                                @endif
                                @if(in_array('finance/receipt', $permArr))
                                <li >
                                    <a href="/admin/finance/receipt">
                                        <i class="fa fa-angle-right"></i>
                                        <span>收汇登记</span>
                                    </a>
                                </li>
                                @endif
                                @if(in_array('finance/remittee', $permArr))
                                <li >
                                    <a href="/admin/finance/remittee">
                                        <i class="fa fa-angle-right"></i>
                                        <span>收款方管理</span>
                                    </a>
                                </li>
                                @endif
                                @if(in_array('finance/settlement', $permArr))
                                <li >
                                    <a href="/admin/finance/settlement/0">
                                        <i class="fa fa-angle-right"></i>
                                        <span>退税结算</span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif
                        @if(in_array('report', $permArr))
                        <li name="reportForms">
                            <a href="/admin/report">
                                <i class="fa fa-signal icon">
                                    <b class="bg-danger"></b>
                                </i>
                                <span>报表管理</span>
                            </a>
                        </li>
                        @endif
                        @if(in_array('business', $permArr))
                        <li name="busine">
                            <a href="/admin/business">
                                <i class="fa fa-tasks icon">
                                    <b class="bg-danger"></b>
                                </i>
                                <span>业务管理</span>
                            </a>
                        </li>
                        @endif
                        @if(in_array('data_manage', $permArr))
                        <li name="data_manage">
                            <a href="/admin/data_manage">
                                <i class="fa fa-film icon">
                                    <b class="bg-danger"></b>
                                </i>
                                <span>数据维护</span>
                            </a>
                        </li>
                        @endif
                        @if(in_array('billing', $permArr))
                        <li name="billing">
                            <a href="/admin/billing">
                                <i class="fa fa-ticket icon">
                                    <b class="bg-danger"></b>
                                </i>
                                <span>开票资料管理</span>
                            </a>
                        </li>
                        @endif
                        @if(in_array('company', $permArr))
                        <li name="company">
                            <a href="/admin/company">
                                <i class="fa fa-building-o icon">
                                    <b class="bg-danger"></b>
                                </i>
                                <span>公司管理</span>
                            </a>
                        </li>
                        @endif
                        @if(in_array('purchase', $permArr))
                        <li name="purchase">
                            <a href="/admin/purchase/1">
                                <i class="fa fa-file icon">
                                    <b class="bg-danger"></b>
                                </i>
                                <span>采购合同管理</span>
                            </a>
                        </li>
                        @endif
                        @if(in_array('system/permission', $permArr) || in_array('system/role', $permArr) || in_array('system/account', $permArr))
                        <li name="system">
                            <a href="javascript:;">
                                <i class="fa fa-gear icon">
                                    <b class="bg-danger"></b>
                                </i>
                                <span class="pull-right">
                                  <i class="fa fa-angle-down text"></i>
                                  <i class="fa fa-angle-up text-active"></i>
                                </span>
                                <span>系统管理</span>
                            </a>
                            <ul class="nav lt">
                                @if(in_array('system/permission', $permArr))
                                <li >
                                    <a href="/admin/system/permission">
                                        <i class="fa fa-angle-right"></i>
                                        <span>权限管理</span>
                                    </a>
                                </li>
                                @endif
                                @if(in_array('system/role', $permArr))
                                <li >
                                    <a href="/admin/system/role">
                                        <i class="fa fa-angle-right"></i>
                                        <span>角色管理</span>
                                    </a>
                                </li>
                                @endif
                                @if(in_array('system/account', $permArr))
                                <li >
                                    <a href="/admin/system/account">
                                        <i class="fa fa-angle-right"></i>
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
    </script>
</aside>
