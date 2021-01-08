@extends('admin.layouts.app')
@section('content')
<link rel="stylesheet" href="{{ URL::asset('/css/bootstrap-table.min.css') }}" type="text/css"/>
<section class="vbox">
    <section class="scrollable padder">
        <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
            <li><i class="fa fa-home"></i> 报关管理</li>
        </ul>
        <section class="panel panel-default">
            <header class="panel-heading text-right bg-light">
                <span class="btn btn-sm btn-info btn-rounded clearanceExport" data-type="2">导出</span>
            </header>
            <div class="panel-body">
                <!-- <form class="form-inline queryForm gcForm m-b-sm"> -->
                <div class="form-inline queryForm gcForm m-b-sm">
                    <div class="form-group">
                        <input type="text" class="form-control" name="keyword" id="keyword" placeholder="订单号/开票人工厂/经营单位/报关单号/客户名/贸易国别/业务员">
                    </div>
                    <button class="btn btn-normal m-l-md search">查询</button>
                </div>
                <!-- </form> -->
                <!-- <div data-gctable
                     data-url="{{ request()->fullUrl() }}"
                     data-label="订单号,经营单位,报关详情,报关单号,订单状态"
                     data-query=".queryForm"
                     data-operateFilter=""
                     data-operate="2|查看|primary|admin/clearance?outer=orddetail&tp=check|1300px,2|备案资料||admin/clearance/file?outer=fildetail&tp=checkFile|1000px,3|开票资料||admin/clearance?tp=xiazai,3|下载报关资料||admin/clearance?tp=generator,2|报关录入||admin/clearance/xjentering?outer=fildetail&tp=conTainEr|1200px"
                     data-field="order.ordnumber,order.company.invoice_name,order.declare_mode_data.name,order.customs_number,order.status_str"
                     data-colWidth="70,100,80,80,60"
                ></div> -->
                <table id="EditListTable"></table>
            </div>
        </section>
    </section>
</section>
<script src="{{ URL::asset('/js/bootstrap-table.min.js') }}"></script>
<script src="{{ URL::asset('/js/bootstrap-table-zh-CN.min.js') }}"></script>
<script>
$(function(){
    $('.clearanceExport').click(function(){
        window.location.href="/admin/clearance/export?keyword="+ $("#keyword").val();
    });

    function actionFormatter(value, row, index) {
        let result =  ''
            result = "<a href='javascript:;' class='btn btn-sm btn-primary' onclick=\"getHtml("+value+",'read')\" title='查看'>查看</a>" +
                "<a href='javascript:;' class='btn btn-sm btn-normal' onclick=\"getHtml("+row.id+",'record')\" title='备案资料'>备案资料</a>" +
                "<a href='javascript:;' class='btn btn-sm btn-default' onclick=\"downLoad("+row.id+",'xiazai')\" title='开票资料'>开票资料</a>" +
                "<a href='javascript:;' class='btn btn-sm btn-default' onclick=\"downLoad("+row.id+",'generator')\" title='下载报关资料'>下载报关资料</a>" +
                "<a href='javascript:;' class='btn btn-sm btn-info' onclick=\"getHtml("+value+",'entry',"+row.status+")\" title='报关录入'>报关录入</a>"
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
        // responseHandler: function (res) { //有二级数据时重定向
        //     return res.attributes.name
        // },
        columns: [{
            field: 'ordnumber',
            title: '订单号',
            width: 70,
        }, {
            field: 'company_name',
            title: '经营单位',
            width: 100,
        }, {
            field: 'customer_name',
            title: '客户名称',
            width: 100,
        }, {
            field: 'declare_mode_name',
            title: '报关详情',
            width: 80,
        },  {
            field: 'customs_number',
            title: '报关单号',
            width: 80,
        },  {
            field: 'status_str',
            title: '订单状态',
            width: 60,
        }, {
            field: 'order_id',
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
})
function refresh() {
    //刷新表格数据
    $('#EditListTable').bootstrapTable('refresh');
    //$table.bootstrapTable('refresh'.{url:""});//刷新时调用接口防止表格无限销毁重铸时出现英文
}
$('.search').on('click',function () {
    refresh()
})

    function downLoad(id,value) {
        let url = ''
        if(value == 'xiazai') {
            url = '/admin/download/billing_data/' + id
        } else {
            url = '/admin/download/clearance_material/'+ id
        }
        window.location.href = url
    }

    //弹框
    function getHtml(id,val,status) {
        var url = '/admin/order/' + val + '/' + id
            btn = ['确定']
            width = (document.body.clientWidth) * 0.8 +'px'
            height = (document.body.clientHeight) * 0.6 +'px'
            is_ok = true
        if(val == 'read'){
            outerTitle = '查看'
        }
        if(val == 'record'){
            outerTitle = '备案资料'
            url = '/admin/clearance/file_info/' + id
            width = (document.body.clientWidth) * 0.9 +'px'
            height ='200px'
        }
        if(val == 'entry'){
            outerTitle = '报关录入'
            url = '/admin/order/read/' + id
            if(status == 5){
                btn = false
            }
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
                    area: [width,height],
                    offset: '50px',
                    btn: btn,
                    maxmin: true,
                    content: str,
                    success: function(){
                        if(status == 5 || val == 'read'){
                            $('#value,#customs_number, #export_date, #clearance_port').prop('disabled',true)
                        }
                        is_ok = false
                    },
                    yes: function () {
                        if (val == 'read') {
                            layer.closeAll()
                        }
                        if (val == 'record') {
                            url = '/admin/clearance/file_info/' + id
                            data = $(".fildetail form.form-horizontal").serializeObject()
                            $.ajax({
                                url: url,
                                type: 'post',
                                dataType:'json',
                                data:data,
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
                        if(val == 'entry') {
                            let customs_number = $('#customs_number').val()
                                export_date = $('#export_date').val()
                                clearance_port = $('#clearance_port').val()
                                data = []
                                $.each($(".orddetail .pro_form section"),function(i,item){
                                    data.push({'drawer_product_order_id':$(this).find('.drawer_product_order_id').val(),'value':$(this).find('#value').val()})
                                })
                            $.ajax({
                                url: '/admin/clearance/customes_entry/'+id,
                                type: 'post',
                                dataType:'json',
                                data:{
                                    customs_number: customs_number,
                                    export_date: export_date,
                                    clearance_port: clearance_port,
                                    pro: data
                                },
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
                    },
                    end: function () {
                        is_ok = true
                    }
                })
            }
        })
    }
</script>
@endsection
