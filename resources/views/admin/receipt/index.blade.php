@extends('admin.layouts.app')
@section('content')
<link rel="stylesheet" href="{{ URL::asset('/css/bootstrap-table.min.css') }}" type="text/css"/>
<section class="vbox">
    <section class="scrollable padder">
        <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
            <li><a href="/"><i class="fa fa-home"></i>收汇管理</a></li>
        </ul>
        <section class="panel panel-default">
            <div class="panel-body">
                <div class="pull-left">
                    <!-- <form class="form-inline form-horizontal queryForm gcForm m-b-sm"> -->
                    <div class="form-inline queryForm gcForm m-b-sm">
                        <input type="text" class="form-control" name="keyword" id="keyword" placeholder="客户名称|汇款人|业务员|单证员">
                        <!-- <button type="submit" class="btn btn-normal m-l-md">查询</button> -->
                        <button class="btn btn-normal m-l-md search">查询</button>
                    </div>
                    <!-- </form> -->
                </div>
                <a href="#" class="btn btn-primary m-r-xs pull-right" onclick="getHtml(0,'save')">新增收汇</a>
                <!-- <div data-gctable
                     data-url="receipt"
                     data-label="序号,客户名称,收汇日期,收汇单号,关联状态,收汇金额,币种,汇率,人民币金额,汇款人,录入人员,录入日期"
                     data-query=".queryForm"
                     data-check="true"
                     data-operate="2|查看|primary|admin/finance/receipt?outer=recdetail&tp=check|1400px,3|删除||admin/finance/receipt?tp=del,1|关联订单||finance/receipt?outer=recdetail_number"
                     data-operateFilter=""
                     data-field="serial,customer.name,created_at,identifier,status_str,money,currency_obj.name,rate,rmb,remitter,created_user_name,created_at"
                     data-colWidth="40,100,80,100,80,80,80,80,70,70,80,80"
                ></div> -->
                <!-- <div class="" style="overflow:scroll; width: 100%"> -->
                    <table id="EditListTable"></table>
                <!-- </div> -->
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
        if(row.status == 3 && row.exchange_type == 2) {
            result += "<a href='javascript:;' class='btn btn-sm btn-default' onclick=\"over("+value+")\" title='结汇'>结汇</a>";
        }
        if (row.status == 3 || row.status == 5) {
            result += "<a href='javascript:;' class='btn btn-sm btn-default' onclick=\"jump("+value+",'number')\" title='关联订单'>关联订单</a>";
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
            title: '序号',
            width: 70
        }, {
            field: 'customer_name',
            title: '客户名称',
						width: 130
        }, {
            field: 'received_at',
            title: '收汇日期',
            width: 110
        }, {
            field: 'receipt_number',
            title: '收汇单号'
        }, {
            field: 'relate_str',
            title: '关联状态'
        }, {
            field: 'amount',
            title: '收汇金额',
            width: 120
        }, {
            field: 'currency_name',
            title: '币种',
            width: 76
        }, {
            field: 'rate',
            title: '汇率',
            width: 80
        }, {
            field: 'rmb',
            title: '人民币金额',
            width: 120
        }, {
            field: 'payer',
            title: '付款人'
        }, {
            field: 'apply_uname',
            title: '申请人',
            width: 70
        }, {
            field: 'created_at',
            title: '录入日期',
            cellStyle: formatTableUnit,
            formatter: paramsMatter,
            // visible:false,
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
            showTips("数据加载失败！");
        },
    })
    function paramsMatter(value,row,index) {
        var span=document.createElement('span');
        span.setAttribute('title',value);
        span.innerHTML = value;
        return span.outerHTML;
    }

    function formatTableUnit(value, row, index) {
        return {
            css: {
                "white-space": 'nowrap',
                "text-overflow": 'ellipsis',
                "overflow": 'hidden',
                "max-width": "110px"
            }
        }
    }
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
        var width = (document.body.clientWidth) * 0.85 + 'px'
            outerTitle = ''
            url = '/admin/finance/receipt/' + val + '/' + id
        if(val == 'save'){
            outerTitle = '添加'
        }
        if(val == 'read'){
            outerTitle = '查看'
        }
        if(val == 'update'){
            outerTitle = '修改'
        }
        // if(val == 'number'){
        //     outerTitle = '关联订单'
        //     url = '/admin/finance/receipt/' + val + '/' + id
        // }
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
                    area: width ,
                    offset: '50px',
                    btn: ['确定'],
                    maxmin: true,
                    content: str,
                    yes: function () {
                        var status = 2
                        $('.recdetail .status').val(status);
                        if(val == 'save'){
                            url = '/admin/finance/receipt'
                        }
                        if (val == 'read') {
                            layer.closeAll()
                        } else {
                            var flag = validated($(".recdetail form"))
                                data = $('.recdetail form').serialize()
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
                    },
                    success: function (layero,index) {
                        let money_type = $('#currency_id').val()
                        if(money_type == 354){
                            layero.find('.receipt_dom').hide()
                        } else {
                            layero.find('.receipt_dom').show()
                        }
                        if(val == 'read'){
                            layero.find('.necessary_front').removeClass('necessary_front')
                            layero.find('.receipt_dom').find('select').prop('disabled',true)
                        } else {
                            layero.find('.receipt_dom').find('select').prop('disabled',false)
                        }
                    }
                })
            }
        })
    }
    function jump(id){
        window.open('/admin/finance/receipt/number/'+id)
    }
    function over(id){
        layer.open({
            title: '结汇',
            content: `<div>
                <label>汇率</label>
                <input class="over_rate" />
            </div>`,
            yes: function(index){
                let rate = $('.over_rate').val()
                $.ajax({
                    type: 'post',
                    url: '/admin/finance/receipt/exchange/' + id,
                    data:{
                        rate: rate
                    },
                    success: function(res){
                        if(res.code == 200) {
                            refresh()
                            layer.closeAll()
                        } else {
                            layer.msg(res.msg)
                        }
                    }
                })
            }
        })
    }
</script>
@endsection
