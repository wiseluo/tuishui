@extends('admin.layouts.app')

@section('content')
<link rel="stylesheet" href="{{ URL::asset('/css/bootstrap-table.min.css') }}" type="text/css"/>
<section class="vbox">
    <section class="scrollable padder">
        <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
            <li><a href="/"><i class="fa fa-home"></i> 报表管理</a></li>
        </ul>
        <section class="panel panel-default">
            <div class="panel-body">
                <div class="pull-left">
                    <!-- <form class="form-inline form-horizontal queryForm gcForm m-b-sm"> -->
                    <div class="form-inline queryForm gcForm m-b-sm">
                        <input type="text" class="form-control" name="keyword" id="keyword" placeholder="订单号/开票人工厂名称/客户名称/报关单号/单证员/业务员">
                        <label for="apply_start_date">订单日期：</label>
                        <input type="text" id="apply_start_date" class="form-control" name="apply_start_date" readonly/>
                        <label for="apply_end_date">-</label>
                        <input type="text" id="apply_end_date" class="form-control" name="apply_end_date" readonly/>
                        <!-- <button type="submit" class="btn btn-normal m-l-md">查询</button> -->
                        <button class="btn btn-normal m-l-md search">查询</button>
                    <!-- </form> -->
                    </div>
                </div>
                <div class="pull-right">
                    <span class="btn btn-sm btn-primary reportExport" data-type="2">退税报表下载</span>
                </div>
                <!-- <div data-gctable
                     data-url="report"
                     data-label="序号,出口日期,合同号,工厂名称,发票号,开票数量,开票金额,客户名,申请批次,退回日期,报关单号"
                     data-query=".queryForm"
                     data-check="true"
                     data-operateFilter=""
                     data-field="serial, order.shipping_at, order.number, drawer.company, number, count_amount, sum,drawer.customer.name, filing.number, order.returned_at, order.customs_number"
                     data-colWidth="30,40,100,80,100,80,80,100,100,70,70,80,80"
                ></div> -->

                <table id="EditListTable"></table>
                <!-- 2|关联订单||finance/receipt?outer=recdetail_number&tp=check|1000px -->
            </div>
        </section>
    </section>
</section>
<script src="{{ URL::asset('/js/bootstrap-table.min.js') }}"></script>
<script src="{{ URL::asset('/js/bootstrap-table-zh-CN.min.js') }}"></script>
<script>
    $(function(){
        // 查询日期设置
        setDateRange();
        $('.reportExport').click(function(){
            var keyword = $('#keyword').val();
            var apply_start_date = $('#apply_start_date').val();
            var apply_end_date = $('#apply_end_date').val();
            window.location.href="/admin/report/export?keyword="+ keyword +"&apply_start_date="+ apply_start_date +"&apply_end_date="+ apply_end_date;
        });

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
                    apply_start_date: $('#apply_start_date').val(),
                    apply_end_date: $('#apply_end_date').val(),
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
                field: 'export_date',
                title: '出口日期'
            }, {
                field: 'ordnumber',
                title: '合同号'
            },  {
                field: 'drawer_company',
                title: '工厂名称'
            },  {
                field: 'invoice_number',
                title: '发票号'
            },  {
                field: 'invoice_quantity',
                title: '开票数量'
            },  {
                field: 'invoice_amount',
                title: '开票金额'
            },  {
                field: 'customer_name',
                title: '客户名'
            }, {
                field: 'returned_at',
                title: '退回日期'
            },  {
                field: 'customs_number',
                title: '报关单号'
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
    });
</script>
@endsection
