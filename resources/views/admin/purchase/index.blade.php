@extends('admin.layouts.app')
@section('content')
<section class="vbox">
    <section class="scrollable padder">
        <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
            <li><i class="fa fa-home"></i> 采购合同管理</li>
        </ul>
        <section class="panel panel-default">
            <header class="panel-heading text-right bg-light">
                <ul class="nav nav-tabs pull-left">
                    <li><a href="/admin/purchase/1" >待审核</a></li>
                    <li><a href="/admin/purchase/2" >可退税</a></li>
                    <li><a href="/admin/purchase/3" >审批拒绝</a></li>
                </ul>
                <form class="form-inline queryForm gcForm">
                    <div class="form-group">
                        <input type="text" class="form-control" name="keyword" id="searchName" placeholder="输入合同号/客户名/采购员">
                    </div>
                    <button type="submit" class="btn btn-normal m-l-md">查询</button>
                </form>
            </header>
            <div class="panel-body">
                <div data-gctable
                     data-url="{{ request()->fullUrl() }}"
                     data-label="采购合同号,客户,供方名称,采购员,业务员"
                     data-query=".queryForm"
                     data-operate="2|查看|primary|admin/purchase?outer=purdetail&tp=check|1400px,2|审核||admin/purchase?outer=purdetail&tp=examine|1400px"
                     data-operateFilter="cz,ce"
                     data-field="purchase_code,cus_name,supplier,buyer,salesman"
                     data-colWidth="70,100,100,100,80"
                ></div>
            </div>
        </section>
    </section>
</section>
@endsection
