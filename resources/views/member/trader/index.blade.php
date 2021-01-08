@extends('member.layouts.app')
@section('content')
<link rel="stylesheet" href="{{ URL::asset('/css/bootstrap-table.min.css') }}" type="text/css"/>
<section class="vbox">
    <section class="scrollable padder">
        <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
            <li><i class="fa fa-home"></i> 境外贸易商管理</li>
        </ul>
        <section class="panel panel-default">
            <div class="panel-body">
                <form class="form-inline queryForm gcForm m-b-sm">
                    <div class="form-group">
                        <input type="text" class="form-control" name="keyword" id="keyword" placeholder="贸易商名称|国家|业务员|客户">
                    </div>
                    <button type="submit" class="btn btn-success m-l-md">查询</button>
                </form>
                <div data-gctable
                     data-url="{{ request()->fullUrl() }}"
                     data-label="名称,国家,地址,邮箱,手机,网址"
                     data-query=".queryForm"
                     data-operate=""
                     data-operateFilter=""
                     data-field="name,country_str_en,address,email,cellphone,url"
                     data-colWidth="100,100,100,100,100,100,90"
                ></div>
            </div>
        </section>
    </section>
</section>
@endsection
