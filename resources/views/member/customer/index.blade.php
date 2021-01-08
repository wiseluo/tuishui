@extends('member.layouts.app')
@section('content')
<link rel="stylesheet" href="{{ URL::asset('/css/bootstrap-table.min.css') }}" type="text/css"/>
<section class="vbox">
    <section class="scrollable padder">
        <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
            <li><i class="fa fa-home"></i> 客户管理</li>
        </ul>
        <section class="panel panel-default">
            <div class="panel-body panel-heading">
                @include('member.common.status', ['link'=>'member/customer'])
                <div class="pull-left">
                    <!-- <form class="form-inline form-horizontal queryForm gcForm"> -->
                        <div class="form-inline form-horizontal queryForm gcForm">
                            <div class="form-group" style="margin-left: 25px">
                                {{--<label for="customerName" class="col-lg-4 control-label">客户名称</label>--}}
                                <div class="col-lg-7 m-r-md">
                                    <input type="text" class="form-control" id="keyword" name="keyword" placeholder="输入客户名称|业务员|录入员">
                                </div>
                            </div>
                            <!-- <button type="submit" class="btn btn-normal">查询</button> -->
                            <button type="button" class="btn btn-normal search">查询</button>
                        </div>
                    <!-- </form> -->
                </div>
                <div class="pull-right">
                    <button href="#" class="btn btn-sm btn-info btn-rounded" onclick="updateLog()" data-outwidth="980px">修改日志</button>
                    <!-- <a href="#" class="btn btn-s-md btn-danger m-r-xs pull-right" data-id="0" data-type="2" data-api="member/customer?outer=cusdetail&tp=call" data-outwidth="1400px">新增客户</a>
                    <input type="hidden" value=" {{session('center_token')}} " id="AsessionA"> -->
                </div>
            </div>
                <!-- <div class="btn_con" data-gctable
                     data-url=" {{ request()->fullUrl() }}"
                     data-label="客户名称,创建时间,联系人,地址,联系电话,业务员,创建人"
                     data-query=".queryForm"
                     data-check="true"
                     data-operate="2|查看|primary|member/customer?outer=cusdetail&tp=check|1400px,2|修改||member/customer?outer=cusdetail&tp=update&df=n|1400px,2|审核||member/customer?outer=cusdetail&tp=examine|1400px"
                     data-operateFilter="c,z,e"
                     data-field="name,created_at,linkman,address,telephone,salesman,created_user_name"
                     data-colWidth="100,80,100,100,80,70,50"
                ></div> -->
            <table id="EditListTable"></table>
        </section>
    </section>
</section>
<script src="{{ URL::asset('/js/bootstrap-table.min.js') }}"></script>
<script src="{{ URL::asset('/js/bootstrap-table-zh-CN.min.js') }}"></script>
<script>
$(function(){
    $('.btn_con').on('click','.layui-layer-btn0',function(){
        $('[data-gctable] input[type=checkbox]:checked').each(function(){
            $(this).attr("checked",false);
        });
    })
    $('.allotBatch').click(function(){
        var ids=[];
        $('[data-gctable] tbody tr input[type=checkbox]:checked').each(function(){
            ids.push($(this).closest('tr').data('id'))
        });
        $(this).data('id',ids);
        if (ids.length == 0){
            layer.msg('至少勾选一项');
            return false;
        }
    })
})

function actionFormatter(value, row, index) {
    result = ''
    if(row.status == 2 || row.status == 3 || row.status == 5){
        result += "<a href='javascript:;' class='btn btn-sm btn-primary' onclick=\"getHtml("+value+",'read',"+row.status+")\" title='查看'>查看</a>";
    }
    if(row.status == 1 || row.status == 4) {
        result += "<a href='javascript:;' class='btn btn-sm btn-normal' onclick=\"getHtml("+value+",'update')\" title='修改'>修改</a>";
    }
    if (row.status == 2) {
        result += "<a href='javascript:;' class='btn btn-sm btn-default' onclick=\"getHtml("+value+",'approve')\" title='审核'>审核</a>";
    }
    if(row.status == 3) {
        result += "<a href='javascript:;' class='btn btn-sm btn-twitter' onclick=\"getHtml("+value+",'update_done')\" title='微调'>微调</a>";
    }
    return result;
}
$('#EditListTable').bootstrapTable({
    url: '{{ request()->fullUrl() }}',
    cache: false, //是否需要缓存
    method: 'get',   //请求方式（*）
    pagination: true, //是否显示分页（*）
    sidePagination: "server",   //分页方式：client客户端分页，server服务端分页（*）
    pageSize: 10,   //每页的记录行数（*）
    pageNumber: 1,
    pageList: [10],  //可供选择的每页的行数（*）
    paginationFirstText: "首页",
    paginationPreText: "上一页",
    paginationNextText: "下一页",
    paginationLastText: "末页",
    buttonsClass: 'btn',
    // showColumns: false, //是否显示所有的列（选择显示的列）
    //得到查询的参数
    queryParams : function (params) {
        //这里的键的名字和控制器的变量名必须一直，这边改动，控制器也需要改成一样的
        var pageNumber = $("#EditListTable").bootstrapTable("getOptions").pageNumber
        if(pageNumber == 'undefined'){
            pageNumber = params.pageNumber;
        }
        var temp = {
            keyword: $('#keyword').val(),
            page: pageNumber,
            pageSize: 10
        };
        console.log(temp,'temp');
        return temp;
    },
    // responseHandler: function (res) { //有二级数据时重定向
    //     return res.attributes.name
    // },
    columns: [{
        field: 'name',
        title: '客户名称'
    }, {
        field: 'number',
        title: '客户编号'
    }, {
        field: 'created_at',
        title: '创建时间'
    },  {
        field: 'linkman',
        title: '联系人'
    },  {
        field: 'address',
        title: '地址',
        width: 250
    },  {
        field: 'telephone',
        title: '联系电话'
    },  {
        field: 'salesman',
        title: '业务员'
    },  {
        field: 'created_user_name',
        title: '创建人'
    },  {
        field: 'id',
        title: '操作',
        width: 180,
        align: 'center',
        valign: 'middle',
        formatter: actionFormatter
    },],
    onLoadSuccess: function () {
    },
    onLoadError: function () {
        // showTips("数据加载失败！");
    },
})

function refresh() {
    //刷新表格数据
    $('#EditListTable').bootstrapTable('refresh');
    //$table.bootstrapTable('refresh'.{url:""});//刷新时调用接口防止表格无限销毁重铸时出现英文
}
$('.search').on('click',function () {
    refresh()
})

//弹框
function getHtml(id,val,status) {
    var url = ''
        url = '/member/customer/' + val + '/' + id
        outerTitle = '新增'
        btn = ['确定']
        height = (document.body.clientHeight) * 0.6 +'px'
    if(val == 'read'){
        outerTitle = '查看'
        if (status == 2) {
            btn = ['确定','取回']
        }
    }
    if(val == 'update'){
        outerTitle = '修改'
    }
    if(val == 'approve'){
        outerTitle = '审核'
    }
    if(val == 'update_done'){
        outerTitle = '微调'
    }
    $.ajax({
        url: url,
        data:{},
        content_type: 'application/x-www-form-urlencoded;charset:utf-8',
        success: function (str) {
            if(str.code == 403){
                layer.msg(str.msg)
                return false
            }
            layer.open({
                type: 1,
                title: outerTitle,
                area: ['1400px',height],
                offset: '50px',
                btn: btn,
                maxmin: true,
                content: str,
                yes: function () {
                    var status = 2
                    if(val == 'approve'){
                        status =  $(".cusdetail form .result").val()
                    }
                    if(val == 'update_done'){
                        status =  3
                    }
                    $('.cusdetail .status').val(status);
                    if(val == 'save'){
                        url = '/member/customer'
                    }
                    if (val == 'read') {
                        layer.closeAll()
                    } else {
                        var data = []
                            flag = true
                        if(val == 'save' || val == 'update' || val == 'approve' || val == 'update_done'){
                            flag = validated($(".cusdetail form"))
                            if(flag){
                                data = $('.cusdetail form').serialize()
                            }
                        } else if(val == 'update_done') {
                            url = '/member/customer/update_done/' + id
                        }
                        console.log(data,'data');
                        console.log(flag);
                        if(flag){
                            $.ajax({
                                url: url,
                                type: 'post',
                                dataType:'json',
                                data:data,
                                success: function (res) {
                                    if(res.code == 200){
                                        refresh()
                                        layer.closeAll()
                                    }
                                    layer.msg(res.msg)
                                }
                            })
                        }
                    }
                },
                btn2: function () {
                    $.ajax({
                        url: '/member/customer/retrieve/'+ id,
                        type: 'post',
                        dataType:'json',
                        data:{},
                        success: function (res) {
                            if(res.code == 200){
                                refresh()
                                layer.closeAll()
                            }
                            layer.msg(res.msg)
                        }
                    })
                }
            })
        }
    })
}

function updateLog(){
    var pendingRequests = {};
    jQuery.ajaxPrefilter(function( options, originalOptions, jqXHR ) {
        var key = options.url;
        if (!pendingRequests[key]) {
            pendingRequests[key] = jqXHR;
        }else{
            //jqXHR.abort(); //放弃后触发的提交
            pendingRequests[key].abort(); // 放弃先触发的提交
        }
        var complete = options.complete;
        options.complete = function(jqXHR, textStatus) {
            pendingRequests[key] = null;
            if (jQuery.isFunction(complete)) {
            complete.apply(this, arguments);
            }
        };
    });
    $.get('/member/customerlog/choose', function (str) {
        var index = layer.open({
            type: 1,
            title: '修改日志',
            area: '980px',
            offset: '100px',
            content: str,
            yes: function (i) {
                layer.close(i);
            },
            end: function () {
            }
        });
        ele = $('#layui-layer' + index + ' [data-gctable]');
        tableShow(ele);
    })
}
</script>
@endsection
