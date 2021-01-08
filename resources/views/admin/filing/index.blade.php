@extends('admin.layouts.app')
@section('content')
<link rel="stylesheet" href="{{ URL::asset('/css/bootstrap-table.min.css') }}" type="text/css"/>
<section class="vbox">
    <section class="scrollable padder">
        <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
            <li><i class="fa fa-home"></i> 申报管理</li>
        </ul>
        <section class="panel panel-default">
            <header class="panel-body panel-heading text-right bg-light">
                <ul class="nav nav-tabs pull-left">
                    <li><a href="/admin/filing/2">已退税</a></li>
                    @if($outsideUser == 0)
                    <li><a href="/admin/filing/letter">已申报</a></li>
                    @endif
                </ul>
                @if($outsideUser == 0)
                <span class="btn btn-sm btn-primary filingExport" data-type="2">导出</span>
                <span class="btn btn-sm btn-primary filingYearExport" data-type="2">年导出</span>
                @endif
            <!--<span class="btn btn-sm btn-rounded btn-info" data-id="0" data-type="2" data-api="admin/filing?outer=fildetail&tp=call" data-outwidth="850px">申报登记</span>-->
            </header>
            <div class="panel-body">
                <!-- <form class="form-inline queryForm gcForm m-b-sm"> -->
                <div class="form-inline queryForm gcForm m-b-sm">
                    <div class="form-group">
                        <input type="text" class="form-control m-r-md" id="keyword" name="keyword" placeholder="开票工厂/客户/发票号/报关单号/申报批次/退回日期/申报日期/退税款金额">
                    </div>
                    <button type="submit" class="btn btn-normal m-l-md">查询</button>
                </div>
                <table id="EditListTable"></table>
                <!-- </form>
                    <div data-gctable
                         data-url=" {{ request()->fullUrl() }}"
                         data-label="序号,状态,申报日期,申报批次,退税款金额,票数,退回日期,录入人员"
                         data-query=".queryForm"
                         data-operate="2|查看|primary|admin/filing?outer=fildetail&tp=check|1400px"
                         data-operateFilter="t"
                         data-field="serial,status_str,applied_at,batch,amount,invoice_quantity,returned_at,created_user_name"
                         data-colWidth="30,60,70,60,60,60,70,70">
                    </div> -->
            </div>
        </section>
    </section>
</section>
<script src="{{ URL::asset('/js/bootstrap-table.min.js') }}"></script>
<script src="{{ URL::asset('/js/bootstrap-table-zh-CN.min.js') }}"></script>
<script>
$(function(){
    $('.filingExport').click(function(){
        window.location.href="/admin/filing/export?keyword="+ $("#keyword").val();
    });
    $('.filingYearExport').click(function(){
        window.location.href="/admin/filing/year_export";
    });
});
function actionFormatter(value, row, index) {
    result = ''
    result += "<a href='javascript:;' class='btn btn-sm btn-primary' onclick=\"getHtml("+value+",'read')\" title='查看'>查看</a>";
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
        field: 'id',
        title: '序号'
    }, {
        field: 'status_str',
        title: '状态'
    }, {
        field: 'applied_at',
        title: '申报日期'
    },  {
        field: 'batch',
        title: '申报批次'
    },  {
        field: 'amount',
        title: '退税款金额'
    }, {
        field: 'invoice_quantity',
        title: '票数'
    }, {
        field: 'returned_at',
        title: '退回日期'
    }, {
        field: 'entry_person',
        title: '录入人员'
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

//弹框
function getHtml(id,val) {
    var url = ''
        outerTitle = ''
        url = '/admin/filing/' + val + '/' + id
        width = (document.body.clientWidth) * 0.8 + 'px'
        height = (document.body.clientHeight) * 0.5 +'px'
    if(val == 'read'){
        outerTitle = '查看'
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
                area: [width,height],
                offset: '50px',
                btn: ['确定'],
                maxmin: true,
                content: str,
                yes: function () {
                    layer.closeAll()
                }
            })
        }
    })
}
</script>
@endsection
