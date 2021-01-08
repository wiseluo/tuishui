@extends('member.layouts.app')

@section('content')
    <section class="vbox">
        <section class="scrollable padder">
            <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="/"><i class="fa fa-home"></i> 权限管理</a></li>
            </ul>
            <section class="panel panel-default">
                <div class="panel-body">
                    <div class="pull-left">
                        <form class="form-inline form-horizontal queryForm gcForm m-b-sm">
                            <div class="form-group">
                                <label for="keyword" class="col-lg-4 control-label">关键词</label>
                                <div class="col-lg-7 m-r-md">
                                    <input type="text" class="form-control" id="keyword" name="keyword" placeholder="输入关键词">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success m-l-md">查询</button>
                        </form>
                    </div>
                    <div class="pull-right">
                        <a href="#" class="btn btn-s-md btn-danger m-r-xs pull-right" data-id="0" data-type="2" data-api="{{ request()->path() }}?outer=perdetail&tp=call" data-outwidth="600px">新增权限</a>
                    </div>
                    <div data-gctable
                         data-url="{{ request()->fullUrl() }}"
                         data-label="序号,权限名称,权限信息,描述"
                         data-query=".queryForm"
                         data-operate="2|修改||member/system/permission?outer=perdetail&tp=update|1400px,3|删除|info|member/system/permission?tp=del"
                         data-operateFilter=""
                         data-field="serial,display_name,name,description"
                         data-colWidth="70,100,100,120,150"
                    ></div>
                </div>
            </section>
        </section>
    </section>
@endsection