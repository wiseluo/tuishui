@extends('member.layouts.app')

@section('content')
    <section class="vbox">
        <section class="scrollable padder">
            <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><i class="fa fa-home"></i> 账号管理</li>
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
                        <!-- <a href="#" class="btn btn-s-md btn-danger m-r-xs pull-right" data-id="0" data-type="2" data-api="{{ request()->path() }}?outer=accdetail&tp=call" data-outwidth="600px">新增账号</a> -->
                    </div>
                    <div data-gctable
                         data-url="{{ request()->fullUrl() }}"
                         data-label="序号,账号,姓名,邮箱,角色,最后修改"
                         data-query=".queryForm"
                         data-operate="2|修改||{{ request()->path() }}?outer=accdetail&tp=update|1400px,3|删除|info|{{ request()->path() }}?tp=del"
                         data-operateFilter=""
                         data-field="serial,username,name,email,role,updated_at"
                         data-colWidth="100,120,120,120,120,100"
                    ></div>
                </div>
            </section>
        </section>
    </section>
@endsection