@inject('menuPresenter','App\Presenters\Member\MenuPresenter')
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
                            <a href="/member/customer/0">
                                <i class="fa fa-dashboard icon">
                                    <b class="bg-danger"></b>
                                </i>
                                <span>客户管理</span>
                                <span class="tips customer_count hide"></span>
                            </a>
                        </li>
                        @endif
                        @if(in_array('brand', $permArr))
                        <li name="brand">
                            <a href="/member/brand/0">
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
                            <a href="/member/product/0">
                                <i class="fa fa-list-alt icon">
                                    <b class="bg-info"></b>
                                </i>
                                <span>产品管理</span>
                                <span class="tips product_count hide"></span>
                            </a>
                        </li>
                        @endif
                        @if(in_array('drawer', $permArr))
                        <li name="drawer">
                            <a href="/member/drawer/0">
                                <i class="fa fa-users icon">
                                    <b class="bg-success"></b>
                                </i>
                                <span>开票人管理</span>
                                <span class="tips drawer_count hide"></span>
                            </a>
                        </li>
                        @endif
                        @if(in_array('order', $permArr))
                        <li name="order">
                            <a href="/member/order/0">
                                <i class="fa fa-file-text-o icon">
                                    <b class="bg-warning"></b>
                                </i>
                                <span>订单管理</span>
                                <span class="tips order_count hide"></span>
                            </a>
                        </li>
                        @endif
                        @if(in_array('clearance', $permArr))
                        <li name="clearance">
                            <a href="/member/clearance">
                                <i class="fa fa-columns icon">
                                    <b class="bg-primary"></b>
                                </i>
                                <span>报关管理</span>
                            </a>
                        </li>
                        @endif
                        @if(in_array('invoice', $permArr))
                        <li name="invoice">
                            <a href="/member/invoice/3">
                                <i class="fa fa-credit-card icon">
                                    <b class="bg-danger"></b>
                                </i>
                                <span>发票管理</span>
                            </a>
                        </li>
                        @endif
                        @if(in_array('filing', $permArr))
                        <li name="filing">
                            <a href="/member/filing/2">
                                <i class="fa fa-leaf icon">
                                    <b class="bg-info"></b>
                                </i>
                                <span>申报管理</span>
                            </a>
                        </li>
                        @endif
                        @if(in_array('finance/transport', $permArr) || in_array('finance/pay', $permArr) || in_array('finance/receipt', $permArr) || in_array('finance/remittee', $permArr))
                        <li name="finance">
                            <a href="javascript:;">
                                <i class="fa fa-money icon">
                                    <b class="bg-warning"></b>
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
                                    <a href="/member/finance/transport/3">
                                        <i class="fa fa-angle-right"></i>
                                        <span>运费登记</span>
                                    </a>
                                </li>
                                @endif
                                @if(in_array('finance/pay', $permArr))
                                <li >
                                    <a href="/member/finance/pay/0">
                                        <i class="fa fa-angle-right"></i>
                                        <span>付款管理</span>
                                    </a>
                                </li>
                                @endif
                                @if(in_array('finance/receipt', $permArr))
                                <li >
                                    <a href="/member/finance/receipt" >
                                        <i class="fa fa-angle-right"></i>
                                        <span>收汇登记</span>
                                    </a>
                                </li>
                                @endif
                                @if(in_array('finance/remittee', $permArr))
                                <li >
                                    <a href="/member/finance/remittee" >
                                        <i class="fa fa-angle-right"></i>
                                        <span>收款方管理</span>
                                    </a>
                                </li>
                                @endif
                                @if(in_array('finance/settlement', $permArr))
                                <li >
                                    <a href="/member/finance/settlement/0" >
                                        <i class="fa fa-angle-right"></i>
                                        <span>退税结算</span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif
                        @if(in_array('personalcenter/personal', $permArr))
                        <li name="personalcenter">
                            <a href="javascript:;">
                                <i class="fa fa-gear icon">
                                    <b class="bg-danger"></b>
                                </i>
                                <span class="pull-right">
                                  <i class="fa fa-angle-down text"></i>
                                  <i class="fa fa-angle-up text-active"></i>
                                </span>
                                <span>客户中心</span>
                                <span class="tips personal_count hide"></span>
                            </a>
                            <ul class="nav lt">
                                @if(in_array('personalcenter/personal', $permArr))
                                <li >
                                    <a href="/member/personalcenter/personal/0">
                                        <i class="fa fa-angle-right"></i>
                                        <span>客户信息</span>
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

        <footer class="footer lt hidden-xs b-t b-dark">
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
                url: '/member/pending_review_number',
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
