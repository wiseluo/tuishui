@extends('member.layouts.app')

@section('content')
<link rel="stylesheet" href="{{ URL::asset('/css/bootstrap-table.min.css') }}" type="text/css"/>
<section class="vbox">
    <section class="scrollable padder">
        <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
            <li><i class="fa fa-home"></i> 发票管理</li>
        </ul>
        <section class="panel panel-default">
            <header class="panel-heading text-right bg-light" style="height:47px">
                <ul class="nav nav-tabs pull-left">
                    <li><a href="/member/invoice/3">待申报</a></li>
                    <li><a href="/member/invoice/5">已申报</a></li>
                    <li><a href="/member/invoice/6">已退税</a></li>
                    <li><a href="/member/invoice/order_list">订单</a></li>
                </ul>
                <!-- <span class="btn btn-sm btn-info btn-rounded" data-id="0" data-type="2" data-api="member/invoice?outer=invdetail&tp=add" data-outwidth="980px">登记发票</span> -->
            </header>
            <div class="panel-body">
                <!-- <form class="form-inline queryForm gcForm m-b-sm"> -->
                <div class="form-inline queryForm gcForm m-b-sm">
                    <div class="form-group">
                        <input type="text" class="form-control m-r-md" id="keyword" name="keyword" placeholder="订单号|发票号|开票工厂|报关单号">
                        <!-- <label for="apply_start_date">收票日期：</label>
                        <input type="text" id="apply_start_date" class="form-control" name="apply_start_date" readonly/>
                        <label for="apply_end_date">-</label>
                        <input type="text" id="apply_end_date" class="form-control" name="apply_end_date" readonly/> -->
                    </div>
                    <button class="btn btn-normal m-l-md search">查询</button>
                </div>
                <!-- </form> -->
                <!-- <div data-gctable
                     data-url="{{ request()->fullUrl() }}"
                     data-label="序号,订单号,开票工厂,发票号,开票时间,开票金额,收票时间,状态"
                     data-query=".queryForm"
                     data-operate="2|查看|primary|member/invoice?outer=invdetail&tp=check|1400px"
                     data-operateFilter="c"
                     data-field="serial,order.ordnumber,drawer.company,number,billed_at,invoice_amount,received_at,status_str"
                     data-colWidth="70,100,120,100,80,80,80,80">
                </div> -->
                <table id="EditListTable"></table>
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
    });
    function actionFormatter(value, row, index) {
        result = ''
        if(row.status != 1 || row.status != 4){
            result += "<a href='javascript:;' class='btn btn-sm btn-primary' onclick=\"getHtml("+value+",'read',"+row.status+")\" title='查看'>查看</a>";
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
        columns: [{
            field: 'id',
            title: '序号'
        }, {
            field: 'ordnumber',
            title: '订单号'
        }, {
            field: 'drawer_company',
            title: '开票工厂'
        }, {
            field: 'number',
            title: '发票号'
        }, {
            field: 'billed_at',
            title: '开票时间'
        }, {
            field: 'invoice_amount',
            title: '开票金额'
        }, {
            field: 'received_at',
            title: '收票时间'
        }, {
            field: 'refund_tax_amount_sum',
            title: '应退税款'
        }, {
            field: 'refunded_tax_amount_sum',
            title: '已退税款'
        }, {
            field: 'status_str',
            title: '状态'
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
    function getHtml(id,val,status,logistics) {
        var url = ''
            url = '/member/invoice/' + val + '/' + id
            outerTitle = ''
            btn = ['确定']
            width = (document.body.clientWidth) * 0.8 + 'px'
            height = (document.body.clientHeight) * 0.6 +'px'
        if(val == 'read'){
            outerTitle = '查看'
        }

        $.ajax({
            url: url,
            data:{logistics:logistics},
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
                    btn: btn,
                    maxmin: true,
                    content: str,
                    yes: function () {
                        layer.closeAll()
                    },
                })
            }
        })
    }
</script>
@endsection
