@extends('member.layouts.app')

@section('content')
<section class="vbox">
    <section class="scrollable padder">
        <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
            <li><i class="fa fa-home"></i> 通知管理</li>
        </ul>
        <section class="panel panel-default">
            <div class="panel-body">
                <div class="pull-left">
                    <form class="form-inline queryForm gcForm m-b-sm">
                        <div class="form-group">
                            <label for="receiver">接受者：</label>
                            <input type="text" name="keyword" class="form-control m-r-md" id="receiver">
                        </div>
                        <button type="submit" class="btn btn-success m-l-md">查询</button>
                    </form>
                </div>
                <!-- <div class="pull-right">
                    <a href="#" class="btn btn-s-md btn-danger m-r-xs pull-right" data-id="0" data-type="2" data-api="{{ request()->path() }}?outer=notifydetail&tp=call" data-outwidth="600px">新增通知</a>
                </div> -->
                <div data-gctable
                     data-url="{{ request()->fullUrl() }}"
                     data-label="发送者,接受者,内容,通知类型,通知状态"
                     data-query=".queryForm"
                     data-operate="2|查看|primary|member/notification?outer=notifydetail&tp=check|1400px"
                     data-operateFilter=""
                     data-field="senderuser.username,receiveruser.username,content,status_str,readed_str"
                     data-colWidth="100,100,150,80,50,80">
                </div>
            </div>
        </section>
    </section>
</section>
@endsection