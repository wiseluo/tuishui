@extends('member.layouts.app')
@section('content')
<link rel="stylesheet" href="{{ URL::asset('/css/bootstrap-table.min.css') }}" type="text/css"/>
<section class="vbox">
    <section class="scrollable padder">
        <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
            <li><i class="fa fa-home"></i> 收款方管理</li>
        </ul>
        <section class="panel panel-default">
            <div class="panel-body">
                <div class="pull-left">
                    <!-- <form class="form-inline queryForm gcForm m-b-sm"> -->
                    <div class="form-inline queryForm gcForm m-b-sm">
                        <input type="text" class="form-control keyword" name="keyword" placeholder="开户名/收款单位/开票人/业务员/客户/单证">
                        <!-- <button type="submit" class="btn btn-normal m-l-md">查询</button> -->
                        <button class="btn btn-normal m-l-md search">查询</button>
                    </div>
                    <!-- </form> -->
                </div>
                <a href="#" class="btn btn-primary m-r-xs pull-right" onclick="getHtml(0,'save')">新增收款方</a>
                <!-- <div data-gctable
                     data-url="remittee"
                     data-label="序号,收款方类型,收款方名称,开户名,收款账号,开户银行,录入人员,录入日期"
                     data-query=".queryForm"
                     data-operate="2|修改|primary|member/finance/remittee?outer=remdetail&tp=update|800px,3|删除||member/finance/remittee?tp=del"
                     data-operateFilter=""
                     data-field="serial,remit_type_str,tag,name,number,bank,created_user_name,created_at"
                     data-colWidth="40,80,100,100,100,100,70,100"
                ></div> -->
                <table id="EditListTable"></table>
            </div>
        </section>
    </section>
</section>
<script src="{{ URL::asset('/js/bootstrap-table.min.js') }}"></script>
<script src="{{ URL::asset('/js/bootstrap-table-zh-CN.min.js') }}"></script>
<script>
    function actionFormatter(value, row, index) {
        let result = ''
        result += "<a href='javascript:;' class='btn btn-sm btn-primary' onclick=\"getHtml("+value+",'update')\" title='修改'>修改</a>";
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
        // responseHandler: function (res) { //有二级数据时重定向
        //     return res.attributes.name
        // },
        columns: [{
            field: 'id',
            title: '序号'
        }, {
            field: 'remit_type_str',
            title: '收款方类型'
        }, {
            field: 'tag',
            title: '收款方名称'
        },  {
            field: 'name',
            title: '开户名'
        },  {
            field: 'number',
            title: '收款账号'
        },  {
            field: 'bank',
            title: '开户银行'
        },  {
            field: 'created_user_name',
            title: '录入人员'
        },  {
            field: 'created_at',
            title: '录入日期'
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
        url = '/member/finance/remittee/' + val + '/' + id
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
                    area: '1200px',
                    offset: '50px',
                    btn: ['确定'],
                    maxmin: true,
                    content: str,
                    success: function(){
                        if(val == 'update'){
                            $('#remit_type, .company').prop('disabled',true)
                        }
                    },
                    yes: function () {
                        var flag = validated($(".remdetail form"))
                            data = $('.remdetail form').serialize()
                        if(val == 'save'){
                            url = '/member/finance/remittee'
                        }
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
                })
            }
        })
    }
</script>
@endsection
