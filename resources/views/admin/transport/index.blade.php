@extends('admin.layouts.app')
@section('content')
<link rel="stylesheet" href="{{ URL::asset('/css/bootstrap-table.min.css') }}" type="text/css"/>
<section class="vbox">
    <section class="scrollable padder">
        <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
            <li><i class="fa fa-home"></i> 运费登记</li>
        </ul>
        <section class="panel panel-default">
            <header class="panel-heading text-right bg-light" style="height: 46px">
                <!-- @include('admin.common.status', ['link'=>'admin/finance/transport'])
                <span class="btn btn-sm btn-primary" onclick="getHtml(0,'save')" title='添加'>添加</span> -->
                <ul class="nav nav-tabs pull-left">
                    <li><a href="/admin/finance/transport/3">已登记</a></li>
                </ul>
            </header>
            <div class="panel-body">
                <!-- <form class="form-inline queryForm gcForm m-b-sm"> -->
                <div class="form-inline queryForm gcForm m-b-sm">
                    <div class="form-group">
                        <input type="text" name="keyword" class="keyword form-control m-r-md" placeholder="订单号|开票名称">
                    </div>
                    <!-- <button type="submit" class="btn btn-normal m-l-md">查询</button> -->
                    <button class="btn btn-normal m-l-md search">查询</button>
                </div>
                <!-- </form> -->
                <!-- <div data-gctable
                     data-url="{{ request()->fullUrl() }}"
                     data-label="序号,申请日期,订单号,金额"
                     data-query=".queryForm"
                     data-operate="2|查看|primary|admin/finance/transport?outer=tradetail&tp=check|1400px,2|修改||admin/finance/transport?outer=tradetail&tp=update|1400px,2|审核||admin/finance/transport?outer=tradetail&tp=examine|1400px"
                     data-operateFilter="c,u,e"
                     data-field="serial,applied_at,order.ordnumber,money"
                     data-colWidth="70,100,150,100,80,80,80">
                </div> -->
                <table id="EditListTable"></table>
            </div>
        </section>
    </section>
</section>
<script src="{{ URL::asset('/js/bootstrap-table.min.js') }}"></script>
<script src="{{ URL::asset('/js/bootstrap-table-zh-CN.min.js') }}"></script>
<script>
    function actionFormatter(value, row, index) {
        result = ''
        if(row.status != 1 && row.status != 4){
            result += "<a href='javascript:;' class='btn btn-sm btn-primary' onclick=\"getHtml("+value+",'read')\" title='查看'>查看</a>";
        }
        if(row.status == 1 || row.status == 4) {
            result += "<a href='javascript:;' class='btn btn-sm btn-warning' onclick=\"getHtml("+value+",'update')\" title='修改'>修改</a>";
        }
        if (row.status == 2) {
            result += "<a href='javascript:;' class='btn btn-sm btn-default' onclick=\"getHtml("+value+",'approve')\" title='审核'>审核</a>";
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
                keyword: $('.keyword').val(),
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
            field: 'applied_at',
            title: '申请日期'
        }, {
            field: 'billed_at',
            title: '开票日期'
        }, {
            field: 'name',
            title: '开票名称'
        }, {
            field: 'company_name',
            title: '开票公司'
        }, {
            field: 'money',
            title: '金额'
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
            layer.msg("数据加载失败！");
        },
    })

    function refresh() {
        /**
         * 刷新表格数据
         */
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
            url = '/admin/finance/transport/' + val + '/' + id
        if(val == 'save'){
            outerTitle = '添加'
        }
        if(val == 'read'){
            outerTitle = '查看'
        }
        if(val == 'update'){
            outerTitle = '修改'
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
                    area: '1000px',
                    offset: '50px',
                    btn: ['确定'],
                    maxmin: true,
                    content: str,
                    yes: function () {
                        var status = 2
                        if(val == 'approve'){
                            status =  $(".tradetail form .result").val()
                        }
                        $('.tradetail .status').val(status);
                        if(val == 'save'){
                            url = '/admin/finance/transport'
                        }
                        if (val == 'read') {
                            layer.closeAll()
                        } else {
                            var flag = validated($(".tradetail form"))
                                data = $('.tradetail form').serialize()
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
</script>
@endsection
