@extends('member.layouts.app')
@section('content')
<link rel="stylesheet" href="{{ URL::asset('/css/bootstrap-table.min.css') }}" type="text/css"/>
<section class="vbox">
    <section class="scrollable padder">
        <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
            <li><i class="fa fa-home"></i> 产品管理</li>
        </ul>
        <section class="panel panel-default">
            <div class="panel-body panel-heading">
                @include('member.common.status', ['link'=>'member/product'])
                <div class="pull-left">
                    <!-- <form class="form-inline queryForm gcForm m-b-sm"> -->
                    <div class="form-inline form-horizontal queryForm gcForm">
                        <div class="form-group" style="margin-left: 25px">
                            <input type="text" class="form-control" name="keyword" id="searchName" placeholder="申请人/客户/产品名称/英文名/HSCODE/业务员">
                        </div>
                        <!-- <button type="submit" class="btn btn-normal m-l-md">查询</button> -->
                        <button class="btn btn-normal m-l-md search">查询</button>
                    </div>
                    <!-- </form> -->
                </div>
                <!-- <div class="pull-right">
                    <button href="#" class="btn btn-sm btn-info btn-rounded" onclick="updateLog()" data-outwidth="980px">修改日志</button>
                </div> -->
            </div>
                <!-- <div data-gctable
                     data-url="{{ request()->fullUrl() }}"
                     data-label="申请人,产品名称,产品英文名称,HSCode,规格,退税率,货号,法定单位"
                     data-query=".queryForm"
                     data-operate="2|查看|primary|member/product?outer=prodetail&tp=check|1000px,2|修改||member/product?outer=prodetail&tp=update&df=y|1000px,2|审核||member/product?outer=prodetail&tp=examine|1000px"
                     data-operateFilter="c,u,e"
                     data-field="created_user_name,name,en_name,hscode,standard,rate,number,unit"
                     data-colWidth="70,100,100,100,80,100,70,70,180,60,60"
                ></div> -->
            <table id="EditListTable"></table>
        </section>
    </section>
</section>
<script src="{{ URL::asset('/js/bootstrap-table.min.js') }}"></script>
<script src="{{ URL::asset('/js/bootstrap-table-zh-CN.min.js') }}"></script>
<script>
    function updateLog(){
        var pendingRequests = {};
        jQuery.ajaxPrefilter(function( options, originalOptions, jqXHR ) {
            var key = options.url;
            if (!pendingRequests[key]) {
                pendingRequests[key] = jqXHR;
            }else{
                //jqXHR.abort(); //放弃后触发的提交
                pendingRequests[key].abort(); // 放弃先触发的提交
            }
            var complete = options.complete;
            options.complete = function(jqXHR, textStatus) {
                pendingRequests[key] = null;
                if (jQuery.isFunction(complete)) {
                complete.apply(this, arguments);
                }
            };
        });
        $.get('/member/productlog/choose', function (str) {
            if(str.code == 403){
                layer.msg(str.msg)
                return false
            }
            var index = layer.open({
                type: 1,
                title: '修改日志',
                area: '980px',
                offset: '100px',
                content: str,
                yes: function (i) {
                    layer.close(i);
                },
                end: function () {
                }
            });
            ele = $('#layui-layer' + index + ' [data-gctable]');
            tableShow(ele);
        })
    }

    function actionFormatter(value, row, index) {
        result = ''
        if(row.status == 2 || row.status == 3 || row.status == 5){
            result += "<a href='javascript:;' class='btn btn-sm btn-primary' onclick=\"getHtml("+value+",'read',"+row.status+")\" title='查看'>查看</a>";
        }
        if(row.status == 1 || row.status == 4) {
            result += "<a href='javascript:;' class='btn btn-sm btn-normal' onclick=\"getHtml("+value+",'update')\" title='修改'>修改</a>";
        }
        if(row.status == 3) {
            result += "<a href='javascript:;' class='btn btn-sm btn-twitter' onclick=\"getHtml("+value+",'update_done')\" title='微调'>微调</a>";
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
            field: 'created_user_name',
            title: '申请人'
        }, {
            field: 'customer_name',
            title: '客户名'
        }, {
            field: 'name',
            title: '产品名称'
        }, {
            field: 'en_name',
            title: '产品英文名称'
        },  {
            field: 'hscode',
            title: 'HSCode'
        },  {
            field: 'standard',
            title: '规格',
            width: 300
        },  {
            field: 'tax_refund_rate',
            title: '退税率'
        },  {
            field: 'number',
            title: '货号'
        },  {
            field: 'measure_unit',
            title: '申报单位'
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

    //弹框
    function getHtml(id,val,status) {
        var url = ''
            url = '/member/product/' + val + '/' + id
            outerTitle = '新增'
            btn = ['确定']
            // width = (document.body.clientWidth) * 0.8 + 'px'
            height = (document.body.clientHeight) * 0.6 +'px'
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
        if(val == 'update_done'){
            outerTitle = '微调'
        }
        $.ajax({
            url: url,
            data:{},
            content_type: 'application/x-www-form-urlencoded;charset:utf-8',
            success: function (str) {
                if(str.code == 403){
                    layer.msg(str.msg)
                    return false
                }
                layer.open({
                    type: 1,
                    title: outerTitle,
                    area: ['1100px',height],
                    offset: '50px',
                    btn: btn,
                    maxmin: true,
                    content: str,
                    yes: function () {
                        var status = 2
                        if(val == 'approve'){
                            status =  $(".prodetail form .result").val()
                        }
                        if(val == 'update_done'){
                            status =  3
                        }
                        $('.prodetail .status').val(status);
                        if(val == 'save'){
                            url = '/member/product'
                        }
                        if (val == 'read') {
                            layer.closeAll()
                        } else {
                            var data = []
                            if(val == 'save' || val == 'update' || val == 'approve'){
                                var flag = validated($(".prodetail form"))
                                if(flag){
                                    data = $('.prodetail form').serialize()
                                }
                            } else if(val == 'update_done') {
                                url = '/member/product/update_done/' + id
                                data = $('.prodetail form').serialize()
                            }
                            console.log(data,'data');
                            $.ajax({
                                url: url,
                                type: 'post',
                                dataType:'json',
                                data:data,
                                success: function (res) {
                                    if(res.code == 200){
                                        refresh()
                                        layer.closeAll()
                                    }
                                    layer.msg(res.msg)
                                }
                            })
                        }
                    },
                    btn2: function () {
                        $.ajax({
                            url: '/member/product/retrieve/'+ id,
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
