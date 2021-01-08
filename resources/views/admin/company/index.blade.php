@extends('admin.layouts.app')
@section('content')
<link rel="stylesheet" href="{{ URL::asset('/css/bootstrap-table.min.css') }}" type="text/css"/>
<section class="vbox">
    <section class="scrollable padder">
        <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
            <li><a href="/"><i class="fa fa-home"></i> 公司管理</a></li>
        </ul>
        <section class="panel panel-default">
            <header class="panel-heading text-right bg-light">
                <span class="btn btn-sm btn-primary" onclick="getHtml(0,'save')">添加公司</span>
            </header>
            <div class="panel-body">
                <!-- <form class="form-inline queryForm gcForm m-b-sm"> -->
                <div class="form-inline queryForm gcForm m-b-sm">
                    <div class="form-group">
                        <input type="text" class="form-control" id="keyword" placeholder="输入公司名称">
                    </div>
                    <button class="btn btn-normal m-l-md search">查询</button>
                </div>
                <table id="EditListTable"></table>
                <!-- </form>
                <div class="btn_con" data-gctable
                     data-url=" {{ request()->fullUrl() }}"
                     data-label="公司名称,纳税人识别号,电话,海关编码"
                     data-query=".queryForm"
                     data-check="true"
                     data-operate="2|查看|primary|admin/company?outer=companydetail&tp=check|1400px,2|修改||admin/company?outer=companydetail&tp=update&df=n|1400px"
                     data-operateFilter=""
                     data-field="name,tax_id,telephone,customs_code"
                     data-colWidth="100,80,50,100"
                ></div> -->
            </div>
        </section>
    </section>
</section>
<script src="{{ URL::asset('/js/bootstrap-table.min.js') }}"></script>
<script src="{{ URL::asset('/js/bootstrap-table-zh-CN.min.js') }}"></script>
<script>
    // $(function(){
    //    $('.btn_con').on('click','.layui-layer-btn0',function(){
    //         $('[data-gctable] input[type=checkbox]:checked').each(function(){
    //                 $(this).attr("checked",false);
    //        });
    //    })
    //   $('.allotBatch').click(function(){
    //     var ids=[];
    //     $('[data-gctable] tbody tr input[type=checkbox]:checked').each(function(){
    //       ids.push($(this).closest('tr').data('id'))
    //     });
    //     $(this).data('id',ids);
    //     if (ids.length == 0){
    //       layer.msg('至少勾选一项');
    //       return false;
    //     }
    //   })
    // })
    function actionFormatter(value, row, index) {
            result = ''
            result += "<a href='javascript:;' class='btn btn-sm btn-normal' onclick=\"getHtml("+value+",'read')\" title='查看'>查看</a>";
            result += "<a href='javascript:;' class='btn btn-sm btn-warning' onclick=\"getHtml("+value+",'update')\" title='修改'>修改</a>";
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
                keyword: $('.queryForm #keyword').val(),
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
            title: '公司名称'
        }, {
            field: 'tax_id',
            title: '纳税人识别号'
        }, {
            field: 'telephone',
            title: '电话'
        }, {
            field: 'customs_code',
            title: '海关编码'
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
            url = '/admin/company/' + val + '/' + id
            width = (document.body.clientWidth) * 0.85 + 'px'
        if(val == 'save'){
            outerTitle = '添加'
        }
        if(val == 'read'){
            outerTitle = '查看'
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
                    area: width,
                    offset: '50px',
                    btn: ['确定'],
                    maxmin: true,
                    content: str,
                    yes: function () {
                        if(val == 'read'){
                            outerTitle = '查看'
                            layer.closeAll()
                            return false
                        }
                        if(val == 'save'){
                            url = '/admin/company'
                        }
                        data = $('.companydetail form').serialize()
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
