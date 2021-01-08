@extends('member.layouts.app')
@section('content')
<section class="vbox">
    <section class="scrollable padder">
        <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
            <li><i class="fa fa-home"></i> 个人信息</li>
        </ul>
        <section class="panel panel-default">
            @include('member.common.status', ['link'=>'member/personalcenter/personal'])

            <div class="panel-body">
                <form class="form-inline queryForm gcForm m-b-sm">
                    <div class="form-group">
                        <input type="text" class="form-control" name="keyword" id="searchName" placeholder="输入开票人名称">
                    </div>
                    <button type="submit" class="btn btn-success m-l-md">查询</button>
                </form>
                <div data-gctable
                     data-url="{{ request()->fullUrl() }}"
                     data-label="开票人名称,法人,联系电话,纳税人识别号,一般纳税人认定时间,境内货源地"
                     data-query=".queryForm"
                     data-operate="2|查看|primary|member/personalcenter/personal?outer=personaldetail&tp=check|1450px,2|审核||member/personalcenter/personal?outer=personaldetail&tp=examine|1450px,3|重置密码||member/personalcenter/personal?tp=reset"
                     data-operateFilter="c,e,q"
                     data-field="drawer_name,legal_person,phone,tax_id,tax_at,original_addr"
                     data-colWidth="100,100,50,100,50,100"
                ></div>
            </div>
        </section>
    </section>
</section>
@endsection
