@extends('admin.layouts.app')
@section('content')
<link rel="stylesheet" href="{{ URL::asset('/css/bootstrap-table.min.css') }}" type="text/css"/>
<section class="vbox">
    <section class="scrollable padder">
        <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
            <li><i class="fa fa-home"></i> 联系人管理</li>
        </ul>
        <section class="panel panel-default">
            <header class="panel-heading text-right bg-light">
                <!-- <span class="btn btn-sm btn-primary" data-id="0" data-type="2" data-api="admin/drawer?outer=dradetail&tp=add" data-outwidth="1000px">添加开票人</span> -->
                <a href="javascript:;" class='btn btn-sm btn-primary' onclick="getHtml(0,'save')" title='添加开票人'>添加联系人</a>
                <!-- <span class="btn btn-sm btn-danger drawerExport" data-type="2">导出</span> -->
            </header>
            <div class="panel-body">
                <!-- <form class="form-inline queryForm gcForm m-b-sm"> -->
                <div class="form-inline queryForm gcForm m-b-sm">
                    <div class="form-group">
                        <input type="text" class="form-control" name="keyword" id="searchName" placeholder="联系人名称">
                    </div>
                    <button class="btn btn-normal m-l-md search">查询</button>
                </div>
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
        result += "<a href='javascript:;' class='btn btn-sm btn-normal' onclick=\"getHtml("+value+",'update')\" title='修改'>修改</a>";
        result += "<a href='javascript:;' class='btn btn-sm btn-primary' onclick=\"del("+value+",'delete')\" title='删除'>删除</a>";
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
        field: 'name',
        title: '联系人名称'
    }, {
        field: 'phone',
        title: '手机号'
    }, {
        field: 'created_user_name',
        title: '创建者'
    }, {
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
function del(id,val) {
    url = '/admin/link/' + val + '/' + id
    var str = "<p>是否删除联系人？</p>";
    layer.confirm(str, {btn: ['确定', '取消'], title: "提示"}, function () {
        $.ajax({
            url: url,
            type: 'post',
            success: function (res) {
                if(res.code == 200){
                    refresh()
                    layer.closeAll()
                    layer.msg(res.msg)
                } else {
                    layer.msg(res.msg)
                }
            }
        })
    })
}
//弹框
function getHtml(id,val) {
    var url = ''
        outerTitle = ''
        url = '/admin/link/' + val + '/' + id
    if(val == 'save'){
        outerTitle = '添加'
    }
    if(val == 'update'){
        outerTitle = '修改'
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
                area: '820px',
                offset: '50px',
                btn: ['确定'],
                maxmin: true,
                content: str,
                yes: function () {
                    if(val == 'save'){
                        url = '/admin/link'
                    }
                    data = $('.linkdetail form').serialize()
                    $.ajax({
                        url: url,
                        type: 'post',
                        data: data,
                        success: function (res) {
                            if(res.code == 200){
                                refresh()
                                layer.closeAll()
                                layer.msg(res.msg)
                            } else {
                                layer.msg(res.msg)
                            }
                        }
                    })
                }
            })
        }
    })
}
</script>
@endsection
