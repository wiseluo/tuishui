@extends('admin.layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ URL::asset('/css/bootstrap-table.min.css') }}" type="text/css"/>
    <section class="vbox">
        <section class="scrollable padder">
            <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><i class="fa fa-home"></i> 付款管理</li>
            </ul>
            <section class="panel panel-default">
                <header class="panel-heading text-right bg-light">
                    @include('admin.common.status', ['link'=>'admin/finance/pay'])
                    <a href="javascript:;" class='btn btn-sm btn-primary' onclick="getHtml(0,'save')" title='添加'>添加</a>
                    <span class="btn btn-sm btn-primary payExport" data-type="2">导出</span>
                </header>
                <div class="panel-body rem-Ite">
                    <!-- <form class="form-inline gcForm m-b-sm"> -->
                    <div class="form-inline gcForm m-b-sm">
                        <div class="form-group">
                            <input type="text" name="keyword" class="keyword form-control m-r-md" placeholder="订单编号|收款单位|客户|业务员">
                            <select name="pay_type" class="pay_type form-control m-r-md">
                                <option value="">请选择款项类型</option>
                                <option value="0">定金</option>
                                <option value="1">货款</option>
                                <option value="2">税款</option>
                                <option value="3">运费</option>
                                <option value="4">其它</option>
                            </select>
                        </div>
                        <!-- <button type="submit" class="btn btn-normal m-l-md">查询</button> -->
                        <button class="btn btn-normal m-l-md search">查询</button>
                    </div>
                    <!-- </form> -->
                    <!-- <div data-gctable
                         data-url="{{ request()->fullUrl() }}"
                         data-label="序号,申请单号,申请日期,款项类型,款项内容,收款单位,金额"
                         data-query=".queryForm"
                         data-operate="2|查看|primary|admin/finance/pay?outer=paydetail&tp=check|900px,2|修改||admin/finance/pay?outer=paydetail&tp=update|900px,2|审核||admin/finance/pay?outer=paydetail&tp=examine|900px,2|付款关联||admin/finance/pay?outer=payrelate&tp=relate|900px"
                         data-operateFilter="c,u,e,g"
                         data-field="serial,pay_number,applied_at,type_str,content,remittee.name,money"
                         data-colWidth="70,100,100,100,100,80,120,80">
                    </div> -->
                    <table id="EditListTable"></table>
                </div>
            </section>
        </section>
        <script src="{{ URL::asset('/js/bootstrap-table.min.js') }}"></script>
        <script src="{{ URL::asset('/js/bootstrap-table-zh-CN.min.js') }}"></script>
        <script>
            $('.payExport').click(function(){
                window.location.href="/admin/finance/pay/export";
            });
            function actionFormatter(value, row, index) {
                    result = ''
                    if(row.status == 2 || row.status == 3 || row.status == 5){
                        result += "<a href='javascript:;' class='btn btn-sm btn-primary' onclick=\"getHtml("+value+",'read')\" title='查看'>查看</a>";
                    }
                    if(row.status == 1 || row.status == 4) {
                        result += "<a href='javascript:;' class='btn btn-sm btn-warning' onclick=\"getHtml("+value+",'update')\" title='修改'>修改</a>";
                    }
                    // if (row.status == 2) {
                    //     result += "<a href='javascript:;' class='btn btn-sm btn-default' onclick=\"getHtml("+value+",'approve')\" title='审核'>审核</a>";
                    // }
                    if (row.status == 3 && row.type == 0) {
                        result += "<a href='javascript:;' class='btn btn-sm btn-normal' onclick=\"getHtml("+value+",'relate_order')\" title='关联订单'>关联订单</a>" +
                        "<a href='javascript:;' class='btn btn-sm btn-info' onclick=\"getHtml("+value+",'relate_receipt')\" title='关联收汇'>关联收汇</a>";
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
                        pay_type: $('.pay_type').val(),
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
                    field: 'pay_number',
                    title: '申请单号'
                }, {
                    field: 'applied_at',
                    title: '申请日期'
                },  {
                    field: 'type_str',
                    title: '款项类型'
                },  {
                    field: 'content',
                    title: '款项内容'
                },  {
                    field: 'remittee_name',
                    title: '收款单位'
                },  {
                    field: 'money',
                    title: '金额'
                }, {
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
                    showTips("数据加载失败！");
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
                    url = '/admin/finance/pay/' + val + '/' + id
                if(val == 'save'){
                    outerTitle = '添加'
                }
                if(val == 'read'){
                    outerTitle = '查看'
                }
                if(val == 'update'){
                    outerTitle = '修改'
                }
                // if(val == 'approve'){
                //     outerTitle = '审核'
                // }
                if(val == 'relate_order'){
                    outerTitle = '关联订单'
                }
                if(val == 'relate_receipt'){
                    outerTitle = '关联收汇'
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
                            area: '900px',
                            offset: '50px',
                            btn: ['确定'],
                            maxmin: true,
                            content: str,
                            yes: function () {
                                var status = 2
                                if(val == 'approve'){
                                    status =  $(".paydetail form .result").val()
                                }
                                $('.paydetail .status').val(status);
                                if(val == 'save'){
                                    url = '/admin/finance/pay'
                                }
                                if (val == 'read') {
                                    layer.closeAll()
                                 } else {
                                    var flag = validated($(".paydetail form"))
                                    data = $('.paydetail form').serialize()
                                    if(flag) {
                                        $.ajax({
                                            url: url,
                                            data: data,
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
                                    }
                                }
                            }
                        })
                    }
                })
            }
        </script>
    </section>
@endsection
