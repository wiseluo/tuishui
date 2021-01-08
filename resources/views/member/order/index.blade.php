@extends('member.layouts.app')
@section('content')
<link rel="stylesheet" href="{{ URL::asset('/css/bootstrap-table.min.css') }}" type="text/css"/>
<section class="vbox">
    <section class="scrollable padder dingdan">
        <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
            <li><i class="fa fa-home"></i> 订单管理</li>
        </ul>
        <section class="panel panel-default">
            <header class="panel-heading text-right bg-light"  style="height:47px">
                @include('member.common.status', ['link'=>'member/order'])
                <button href="#" class="btn btn-sm btn-info btn-rounded" onclick="updateLog()" data-outwidth="980px">修改日志</button>
                <!-- <span class="btn btn-sm btn-rounded btn-info layui-layer-max" data-id="0" data-type="2" data-api="member/order?outer=orddetail&tp=add" data-outwidth="100">添加订单</span> -->
            </header>
            <div class="panel-body">
                <!-- <form class="form-inline queryForm gcForm m-b-sm"> -->
                <div class="form-inline queryForm gcForm m-b-sm">
                    <div class="form-group">
                        <input type="text" name="keyword" class="form-control m-r-md" id="keyword" placeholder="订单号/开票人工厂名称/客户名称/报关单号/单证员/业务员">
                        <label for="apply_start_date">下单日期：</label>
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
                     data-label="订单号,客户,报关状态,报关详情,订单状态,下单时间"
                     data-query=".queryForm"
                     data-operate="2|查看|primary|member/order?outer=orddetail&tp=check|100%,2|修改||member/order?outer=orddetail&tp=update|1300px,2|审核||member/order?outer=orddetail&tp=examine|1300px,3|删除|info|member/order?tp=del,3|结案||member/order?tp=over"
                     data-operateFilter="c,u,e,d,f"
                     data-field="ordnumber,customer.name,clearance_status,declare_mode_data.name,status_str,created_at"
                     data-colWidth="70,150,100,150,80,100,150">
                </div> -->
                <table id="EditListTable"></table>
            </div>
        </section>
    </section>
</section>
<script src="{{ URL::asset('/js/bootstrap-table.min.js') }}"></script>
<script src="{{ URL::asset('/js/bootstrap-table-zh-CN.min.js') }}"></script>
<script>
    // $(function(){
        // 查询日期设置
        setDateRange();
    // });
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
        $.get('/member/orderlog/choose', function (str) {
            var index = layer.open({
                type: 1,
                title: '修改日志',
                area: '1600px',
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
        }).error(function (XMLHttpRequest, textStatus, errorThrown, data) {
            ajaxerr(XMLHttpRequest);
        });
    }

    function actionFormatter(value, row, index) {
        result = ''
        if(row.status == 2 || row.status == 3 || row.status == 5){
            result += "<a href='javascript:;' class='btn btn-sm btn-primary' onclick=\"getHtml("+value+",'read',"+row.status+")\" title='查看'>查看</a>";
        }
        if(row.status == 1 || row.status == 4) {
            result += "<a href='javascript:;' class='btn btn-sm btn-warning' onclick=\"getHtml("+value+",'update')\" title='修改'>修改</a>";
        }
        if(row.status == 1){
            result += "<a href='javascript:;' class='btn btn-sm btn-danger' onclick=\"del_order("+value+")\" title='删除'>删除</a>";
        }
        if (row.status == 2) {
            result += "<a href='javascript:;' class='btn btn-sm btn-default' onclick=\"getHtml("+value+",'approve')\" title='审核'>审核</a>";
        }
        result += "<a href='javascript:;' class='btn btn-sm btn-info' onclick=\"downLoad("+value+")\" title='下载'>下载</a>"
        // if (row.status == 3) {
        //     result += "<a href='javascript:;' class='btn btn-sm btn-info' onclick=\"toOver("+value+")\" title='结案'>结案</a>";
        // }
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
            field: 'ordnumber',
            title: '订单号'
        }, {
            field: 'customer_name',
            title: '客户'
        }, {
            field: 'clearance_status',
            title: '报关状态'
        },  {
            field: 'declare_mode_name',
            title: '报关详情'
        },  {
            field: 'invoice_complete_str',
            title: '发票状态'
        },  {
            field: 'status_str',
            title: '订单状态'
        },  {
            field: 'created_at',
            title: '下单时间'
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
    //是否为公司物流判断
    $('.addPro').on('click',function () {
        var logistics=0
        layer.open({
          title:'订单方式选择',
          content:'<div class="dd_choose">'+
                  '<input name="com" type="radio" value="company">公司物流导入</input>'+
                  '<input style="margin-left: 10px;" name="com" type="radio" value="no_company">非公司物流导入</input>'+
                  '</div>',
          yes:function(i){
                outerTitle = '添加订单'
            if($('.dd_choose input:radio:checked').val() == 'company'){
                 outerDiv = 'orddetail'
                 logistics = '1'
                 outerTitle = outerTitle + '(公司物流)'
            }else {
               outerDiv = 'orddetail'
               logistics = '0'
               outerTitle = outerTitle + '(非公司物流)'
            }
            console.log(111)
            getHtml(0,'save',0,logistics,outerTitle)
            layer.close(i)
          },
          end: function () {
          }
        })
    })
    //弹框
    function getHtml(id,val,status,logistics,outerTitle) {
        var url = ''
            url = '/member/order/' + val + '/' + id
            btn = ['确定']
            width = (document.body.clientWidth) * 0.85 + 'px'
            height = (document.body.clientHeight) * 0.6 +'px'
        if(val == 'read'){
            outerTitle = '查看'
            if (status == 2 || status == 3) {
                btn = ['确定','取回']
            }
        }
        if(val == 'update'){
            outerTitle = '修改'
        }
        if(val == 'approve'){
            outerTitle = '审核'
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
                        var status = 2
                        if(val == 'approve'){
                            status =  $(".orddetail form .result").val()
                        }
                        $('.orddetail .status').val(status);
                        if(val == 'save'){
                            url = '/member/order'
                        }
                        if (val == 'read') {
                            layer.closeAll()
                        } else {
                            if(val == 'save' || val == 'update'){
                                var flag = validated($(".orddetail form"))
                                console.log(flag);
                                var data = []
                                    data2= []
                                    data3 = []
                                    data2 = $(".orddetail .pro_form section").serializeObjArr()
                                    data2.map(function(item,i){
                                        let net_weight = +item.net_weight
                                            total_weight = +item.total_weight
                                            dif = total_weight - net_weight
                                        if(dif < 0){
                                            flag = false
                                            layer.msg('第'+(i+1)+'个产品净重大于毛重')
                                            return false
                                        }
                                    })
                                if(flag){
                                    data = $(".orddetail form.cusform").serializeObject()
                                    // data2 = $(".orddetail .pro_form section").serializeObjArr()
                                    data3 = $(".orddetail .three_form").serializeObject()
                                    data = $.extend(data, data3)
                                    data.pro = data2
                                } else {
                                    return false
                                }
                            } else {
                                data = $(".orddetail form.approve_form").serializeObject()
                                data.status = status
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
                            url: '/member/order/retrieve/'+ id,
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
    //下载报关预录单
    function downLoad(val){
        window.location.href = '/member/download/customs_order/'+val
    }
    //删除草稿
    function del_order(id) {
        layer.confirm('确定要删除草稿订单吗？', {
            btn: ['确定', '取消']
        }, function () {
            $.ajax({
                type: 'POST',
                url: "/member/order/delete/" + id,
                success: function (res) {
                    layer.msg(res.msg)
                    refresh()
                }
            })
        })
    }
</script>
@endsection
