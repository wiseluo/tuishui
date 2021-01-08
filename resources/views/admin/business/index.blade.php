@extends('admin.layouts.app')

@section('content')
    <section class="vbox">
        <section class="scrollable padder">
            <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="/admin"><i class="fa fa-home"></i> 业务管理</a></li>
            </ul>
            <section class="panel panel-default">
                <div class="panel-body">
                    <div class="pull-left">
                        <form class="form-inline form-horizontal queryForm gcForm m-b-sm">
                            <div class="form-group">
                                <label for="keyword" class="col-lg-3 control-label">关键词</label>
                                <div class="col-lg-7 m-r-md">
                                    <input type="text" class="form-control" id="keyword" name="keyword" placeholder="输入业务类型,合同类型">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-normal m-l-md">查询</button>
                        </form>
                    </div>
                    <div class="pull-right">
                        <a href="#" class="btn btn-primary m-r-xs pull-right" data-id="0" data-type="2" data-api="admin/business?outer=busdetail&tp=call" data-outwidth="600px">新增项目</a>
                    </div>
                    <div data-gctable
                         data-url="/admin/business"
                         data-label="序号,公司,业务类型,合同类型"
                         data-query=".queryForm"
                         data-operate="2|修改||admin/business?outer=busdetail&tp=update|1400px,3|删除|danger|admin/business?tp=del"
                         data-operateFilter=""
                         data-field="serial,sales_unit.name,business.name,contract"
                         data-colWidth="50,120,120,120"
                    ></div>
                </div>
            </section>
        </section>
    </section>
@endsection