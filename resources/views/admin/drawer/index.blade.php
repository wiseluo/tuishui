@extends('admin.layouts.app')
@section('content')
<link rel="stylesheet" href="{{ URL::asset('/css/bootstrap-table.min.css') }}" type="text/css"/>
<section class="vbox">
    <section class="scrollable padder">
        <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
            <li><i class="fa fa-home"></i> 开票人管理</li>
        </ul>
        <section class="panel panel-default">
            <header class="panel-heading text-right bg-light">
                @include('admin.common.status', ['link'=>'admin/drawer'])
                <!-- <span class="btn btn-sm btn-primary" data-id="0" data-type="2" data-api="admin/drawer?outer=dradetail&tp=add" data-outwidth="1000px">添加开票人</span> -->
                <a href="javascript:;" class='btn btn-sm btn-primary' onclick="getHtml(0,'save')" title='添加开票人'>添加开票人</a>
                <span class="btn btn-sm btn-danger drawerExport" data-type="2">导出</span>
            </header>
            <div class="panel-body">
                <!-- <form class="form-inline queryForm gcForm m-b-sm"> -->
                <div class="form-inline queryForm gcForm m-b-sm">
                    <div class="form-group">
                        <input type="text" class="form-control" name="keyword" id="searchName" placeholder="开票人名称/纳税人识别号/客户名称/单证员/业务员">
                    </div>
                    <!-- <button type="submit" class="btn btn-normal m-l-md">查询</button> -->
                    <button class="btn btn-normal m-l-md search">查询</button>
                </div>
                <!-- </form> -->
                <!-- <div data-gctable
                     data-url="{{ URL::full() }}"
                     data-label="开票人公司名称,纳税人识别号,提交时间,状态,客户名称"
                     data-query=".queryForm"
                     data-operate="2|查看|primary|admin/drawer?outer=dradetail&tp=check|820px,2|修改||admin/drawer?outer=dradetail&tp=update|820px,2|审核||admin/drawer?outer=dradetail&tp=examine|820px,3|删除|danger|admin/drawer?tp=del,3|修改||admin/drawer?outer=dradetail&tp=xiugai|820px"
                     data-operateFilter="c,u,e,d,x"
                     data-field="company,tax_id,created_at,status_str,customer.name"
                     data-colWidth="150,150,100,80,100"
                ></div> -->
                <table id="EditListTable"></table>
            </div>
        </section>
    </section>
</section>
<script src="{{ URL::asset('/js/bootstrap-table.min.js') }}"></script>
<script src="{{ URL::asset('/js/bootstrap-table-zh-CN.min.js') }}"></script>
<script>
$('.drawerExport').click(function(){
    window.location.href="/admin/drawer/export?keyword="+ $("#searchName").val();
});
function actionFormatter(value, row, index) {
        result = ''
        if(row.status == 2 || row.status == 3 || row.status == 5){
            result += "<a href='javascript:;' class='btn btn-sm btn-primary' onclick=\"getHtml("+value+",'read',"+row.status+")\" title='查看'>查看</a>";
        }
        if(row.status == 1 || row.status == 4) {
            result += "<a href='javascript:;' class='btn btn-sm btn-warning' onclick=\"getHtml("+value+",'update')\" title='修改'>修改</a>";
        }
        if (row.status == 2) {
            result += "<a href='javascript:;' class='btn btn-sm btn-default' onclick=\"getHtml("+value+",'approve')\" title='审核'>审核</a>";
        }
        if (row.status == 3) {
            result += "<a href='javascript:;' class='btn btn-sm btn-info' onclick=\"getHtml("+value+",'relate_product')\" title='关联'>关联</a>";
            result += "<a href='javascript:;' class='btn btn-sm btn-twitter' onclick=\"getHtml("+value+",'update_done')\" title='微调'>微调</a>";
        }
        return result;
    }
$('#EditListTable').bootstrapTable({
    url: '{{ URL::full() }}',
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
            keyword: $('#searchName').val(),
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
        field: 'company',
        title: '开票人公司名称'
    }, {
        field: 'tax_id',
        title: '纳税人识别号'
    }, {
        field: 'created_at',
        title: '提交时间'
    },  {
        field: 'status_str',
        title: '状态'
    },  {
        field: 'customer_name',
        title: '客户名称'
    },  {
        field: 'id',
        title: '操作',
        width: 240,
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
        outerTitle = ''
        url = '/admin/drawer/' + val + '/' + id
        btn = ['确定']
        height = (document.body.clientHeight) * 0.6 +'px'
    if(val == 'save'){
        outerTitle = '添加'
    }
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
    if(val == 'relate_product'){
        outerTitle = '关联'
    }
    if(val == 'update_done'){
        outerTitle = '微调'
    }
    $.ajax({
        url: url,
        content_type: 'application/x-www-form-urlencoded;charset:utf-8',
        success: function (str) {
            if(str.code == 403){
                layer.msg(str.msg)
                return false
            }
            layer.open({
                type: 1,
                title: outerTitle,
                area: ['1200px',height],
                offset: '50px',
                btn: btn,
                maxmin: true,
                content: str,
                yes: function () {
                    var status = 2
                    if(val == 'approve'){
                        status =  $(".dradetail form .result").val()
                    }
                    if(val == 'update_done'){
                        status =  3
                    }
                    $('.dradetail .status').val(status);
                    if(val == 'save'){
                        url = '/admin/drawer'
                    }
                    if (val == 'read') {
                        layer.closeAll()
                    } else {
                        if (val == 'update_done') {
                            url = '/admin/drawer/update_done/' + id
                            data = $('.dradetail form').serialize()
                        }
                        if(val == 'relate_product'){
                            var pid = []
                            if ($('.ptbody>tr').length == 0) {
                                layer.msg('请添加开票人产品！');
                                return false;
                            } else {
                                $('.ptbody>tr').each(function () {
                                  pid.push(this.dataset.id);
                                });
                            }
                            data = {'pid':pid}
                        } else {
                            var flag = validated($(".dradetail form"))
                            if(flag){
                                data = $('.dradetail form').serialize()
                            }
                        }
                        if(flag || $('.ptbody>tr').length != 0){
                            console.log(data);
                            $.ajax({
                                url: url,
                                data: data,
                                type: 'post',
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
                        url: '/admin/drawer/retrieve/'+ id,
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
</script>
@endsection
