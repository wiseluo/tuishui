@extends('admin.layouts.app')

@section('content')
<section class="vbox">
    <section class="scrollable padder">
        <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
            <li><i class="fa fa-home"></i> 开票资料管理</li>
        </ul>
        <section class="panel panel-default">
            <div class="panel-body">
                <form class="form-inline queryForm gcForm m-b-sm">
                    <div class="form-group">
                        <input type="text" class="form-control" name="keyword" id="keyword" placeholder="输入经营单位名称">
                    </div>
                    <button type="submit" class="btn btn-normal m-l-md">查询</button>
                </form>
                <div data-gctable
                     data-url="{{ request()->fullUrl() }}"
                     data-label="经营单位名称,开票名称,纳税人识别号,电话,开户行,海关编码"
                     data-query=".queryForm"
                     data-operate="2|查看|primary|admin/billing?outer=billingdetail&tp=check|1400px,2|修改||admin/billing?outer=billingdetail&tp=update|1400px,3|删除|danger|admin/billing?tp=del"
                     data-operateFilter=""
                     data-field="name,billing.name,billing.identification_num,billing.telephone,billing.bankname,billing.customs_code"
                     data-colWidth="100,100,100,100,100,100"
                ></div>
            </div>
        </section>
    </section>
</section>
@endsection