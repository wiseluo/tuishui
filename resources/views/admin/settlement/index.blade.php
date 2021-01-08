@extends('admin.layouts.app')
@section('content')
<link rel="stylesheet" href="{{ URL::asset('/css/bootstrap-table.min.css') }}" type="text/css"/>
<section class="vbox">
    <section class="scrollable padder tsjs">
        <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
            <li><i class="fa fa-home"></i>退税结算</li>
        </ul>
        <section class="panel panel-default">
            <header class="panel-body panel-heading text-right bg-light">
                @include('admin.common.status', ['link'=>'admin/finance/settlement'])
            </header>
            <div class="panel-body settle">
                <!-- <form class="form-inline queryForm gcForm m-b-sm"> -->
                <div class="form-inline queryForm gcForm m-b-sm">
                    <div class="form-group">
                        <input type="text" name="keyword" class="form-control m-r-md orderKeyword" placeholder="订单号/开票人工厂名称/客户名称/报关单号/单证员/业务员">
                        <label for="apply_start_date">订单日期：</label>
                        <input type="text" id="apply_start_date" class="form-control" name="apply_start_date" readonly/>
                        <label for="apply_end_date">-</label>
                        <input type="text" id="apply_end_date" class="form-control" name="apply_end_date" readonly/>
                    </div>
                    <!-- <button type="submit" class="btn btn-normal m-l-md">查询</button> -->
                    <button class="btn btn-normal m-l-md search">查询</button>
                </div>
                <!-- </form> -->
                <!-- <div data-gctable
                     data-url="{{ request()->fullUrl() }}"
                     data-label="序号,订单日期,订单号,客户名称,应退税款,佣金,应付税款,结算日期,状态,审批意见"
                     data-query=".queryForm"
                     data-operate="2|详情|primary|admin/finance/settlement?outer=seldetail&tp=check|1400px,2|审核||admin/finance/settlement?outer=seldetail&tp=examine|1400px,3|结算||admin/finance/settlement?outer=settlementdetail&tp=settle"
                     data-operateFilter="c,e,s"
                     data-field="id,order.created_at,order.ordnumber,order.customer.name,refund_tax_sum,commission_sum,payable_refund_tax_sum,settle_at,status_str,opinion"
                     data-colWidth="40,100,100,100,80,90,80,80,80,120,80,110">
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

        function actionFormatter(value, row, index) {
            result = ''
            if(row.status == 2 || row.status == 3 || row.status == 5){
                result += "<a href='javascript:;' class='btn btn-sm btn-primary' onclick=\"getHtml("+value+",'read')\" title='详情'>详情</a>";
            }
            if(row.status == 2) {
                result += "<a href='javascript:;' class='btn btn-sm btn-normal' onclick=\"getHtml("+value+",'approve')\" title='审核'>审核</a>";
            }
            if (row.status == 1 || row.status == 4) {
                result += "<a href='javascript:;' class='btn btn-sm btn-default' onclick=\"toOver("+value+")\" title='结算'>结算</a>";
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
                    keyword: $('.orderKeyword').val(),
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
                field: 'id',
                title: '序号'
            }, {
                field: 'order_created_at',
                title: '订单日期'
            }, {
                field: 'ordnumber',
                title: '订单号'
            },  {
                field: 'customer_name',
                title: '客户名称'
            },  {
                field: 'refund_tax_sum',
                title: '应退税款'
            },  {
                field: 'commission_sum',
                title: '佣金'
            },  {
                field: 'payable_refund_tax_sum',
                title: '应付税款'
            },  {
                field: 'settle_at',
                title: '结算日期'
            },  {
                field: 'status_str',
                title: '状态'
            },  {
                field: 'opinion',
                title: '审批意见'
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
    });
    //弹框
    function getHtml(id,val) {
        var url = ''
        outerTitle = ''
        url = '/admin/finance/settlement/' + val + '/' + id
        if(val == 'read'){
            outerTitle = '查看'
        }
        if(val == 'approve'){
            outerTitle = '审核'
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
                    area: '1200px',
                    offset: '50px',
                    btn: ['确定'],
                    maxmin: true,
                    content: str,
                    yes: function () {
                        var status = 2
                        if(val == 'approve'){
                            status =  $(".seldetail form .result").val()
                        }
                        $('.seldetail .status').val(status);
                        if (val == 'read') {
                            layer.closeAll()
                        } else {
                            var flag = validated($(".seldetail form"))
                            data = $('.seldetail form').serialize()
                            if(flag) {
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
                    }
                })
            }
        })
    }
    //结算
    function toOver(id){
        var url =  '/admin/finance/settlement' +'/settle' + '/' + id;
        $.get(url,{},function(str){
            if(str.code == 403){
                layer.msg(str.msg)
                return false
            }
            layer.open({
                type: 1,
                title: '退税结算',
                area: '1200px',
                offset: '50px',
                btn: ['确定'],
                maxmin: true,
                content: str,
                yes:function (i) {
                    var data = $(".settlementdetail form").serialize();
                    var postUrl ='/admin/finance/settlement/settlesave/'+$('.settlement_id').val();
                    console.log(postUrl)
                    $.post(postUrl,data,function (res) {
                        console.log(res);
                        if(res.code == 200){
                            layer.msg('提交成功', {time: 1000}, function () {
                                layer.close(i);
                                getData($('[data-gctable]'));
                            });
                        }else{
                            layer.msg(res.msg);
                        }
                    })
                }
            })
        })
    }
</script>
@endsection
