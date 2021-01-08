@extends('admin.layouts.app')
@section('content')
<link rel="stylesheet" href="{{ URL::asset('/css/bootstrap-table.min.css') }}" type="text/css"/>
<section class="vbox">
    <section class="scrollable padder dingdan">
        <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
            <li><i class="fa fa-home"></i> 订单管理</li>
        </ul>
        <section class="panel panel-default">
            <header class="panel-heading text-right bg-light">
                @include('admin.common.status', ['link'=>'admin/order'])
                <span class="btn btn-sm btn-normal layui-layer-max import_btn">导入报关单</span>
                <span class="btn btn-sm btn-primary layui-layer-max addPro">添加订单</span>
                <input type="hidden" class="outsideUser" value="{{$outsideUser}}">
                <!-- <a href='javascript:;' class='btn btn-sm btn-primary' onclick="getHtml(0,'save')" title='添加订单'>添加订单</a> -->
                <span class="btn btn-sm btn-danger orderExport" data-type="2">导出</span>
            </header>
            <div class="panel-body">
                <!-- <form class="form-inline queryForm gcForm m-b-sm"> -->
                <div class="form-inline queryForm gcForm m-b-sm">
                    <div class="form-group">
                        <input type="text" class="form-control m-r-md" id="keyword" placeholder="订单号/开票人工厂名称/客户名称/报关单号/单证员/业务员">
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
                     data-operate="2|查看|primary|admin/order?outer=orddetail&tp=check|100%,2|修改||admin/order?outer=orddetail&tp=update|1300px,2|审核||admin/order?outer=orddetail&tp=examine|1300px,3|删除|info|admin/order?tp=del,3|结案||admin/order?tp=over"
                     data-operateFilter="c,u,e,d,f"
                     data-field="ordnumber,customer.name,clearance_status,declare_mode_data.name,status_str,created_at"
                     data-colWidth="70,150,100,150,80,100,150">
                </div> -->
                <table id="EditListTable"></table>
            </div>
        </section>
    </section>
</section>
<div class="customs_box panel-body" style="display:none">
    <div class="form-group m-b" style="margin-left: 25px">
        <div class=" " style="width: 200px;">
            <input style="width: 200px;float: left" type="text" class="form-control customs_word" placeholder="输入关键字">
        </div>
        <button class="btn btn-success m-l-md cus_search">查询</button>
    </div>
    <div class="table-responsive panel">
        <table class="table table-hover b-t b-light" style="table-layout: fixed;">
            <thead>
            <tr class="info">
                <th width="33%">合同号</th>
                <th width="33%">报关单号</th>
                <th width="33%">经营单位</th>
            </tr>
            </thead>
            <tbody class="customs_list">

            </tbody>
        </table>
    </div>
    {{--分页--}}
    <div class="pagePicker">
        <div id="page"></div>
    </div>
</div>
<script src="{{ URL::asset('/js/bootstrap-table.min.js') }}"></script>
<script src="{{ URL::asset('/js/bootstrap-table-zh-CN.min.js') }}"></script>
<script>
    // 查询日期设置
    setDateRange();
    $('.orderExport').click(function(){ //导出数据
        window.location.href = "/admin/order/export?keyword="+$(".queryForm #keyword").val();
    });
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
                keyword: $('.queryForm #keyword').val(),
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
            field: 'status_str',
            title: '订单状态'
        },  {
            field: 'invoice_complete_str',
            title: '发票状态'
        },  {
            field: 'created_at',
            title: '下单时间'
        },  {
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
        var logistics = 0
            outsideUser = $('.outsideUser').val()
        if(outsideUser == 0){
            layer.open({
                title:'订单方式选择',
                content:'<div class="dd_choose">'+
                '<input name="com" type="radio" value="company">物流导入'+
                '<input style="margin-left: 10px;" name="com" type="radio" value="no_company">日常录单'+
                '<input style="margin-left: 10px;" type="radio" value="repair">后补录单'+
                '</div>',
                yes:function(i){
                    outerTitle = '添加订单'
                    if($('.dd_choose input:radio:checked').val() == 'company'){
                        outerDiv = 'orddetail'
                        logistics = '1'
                        outerTitle = outerTitle + '(公司物流)'
                    } else if($('.dd_choose input:radio:checked').val() == 'no_company') {
                        outerDiv = 'orddetail'
                        logistics = '0'
                        outerTitle = outerTitle + '(非公司物流)'
                    }
                    if($('.dd_choose input:radio:checked').val() != 'repair'){
                        getHtml(0,'save',0,logistics,outerTitle)
                    } else {
                        layer.open({
                            type: 1,
                            title: '选择报关单',
                            area:['650px','550px'],
                            content: $('.customs_box'),
                            yes: function(index,layero){

                            },
                            success: function (layero,index) {
                                layero.find('.cus_search').on('click',function(){
                                    getCustomsList(1)
                                })
                                getCustomsList(1)
                            }
                        })
                    }
                    layer.close(i)
                },
                end: function () {
                }
            })
        } else {
            outerTitle = '添加订单(非公司物流)'
            getHtml(0,'save',0,logistics,outerTitle)
        }
    })
    //弹框
    function getHtml(id,val,status,logistics,outerTitle) {
        var url = '/admin/order/' + val + '/' + id
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
            btn = ['保存草稿','提交审核']
        }
        if(val == 'save'){
            outerTitle = '添加'
            btn = ['保存草稿','提交审核']
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
                    btn1: function () {
                        var status = 2
                        if(val == 'approve'){
                            status =  $(".orddetail form .result").val()
                        }
                        if(val == 'save'){
                            url = '/admin/order/draft_save'
                            status = 1
                        }
                        if(val == 'update'){
                            url = '/admin/order/draft_update/' + id
                            status = 1
                        }
                        $('.orddetail .status').val(status);
                        if (val == 'read') {
                            layer.closeAll()
                        } else {
                            orderData(url,status,val,'draft')
                        }
                    },
                    btn2: function () {
                        let status = 2
                        $('.orddetail .status').val(status);
                        if(val == 'save'){
                            url = '/admin/order/save';
                        }
                        if(val == 'save' || val == 'update'){
                            orderData(url,status,val,'')
                            return false;
                        } else {
                            $.ajax({
                                url: '/admin/order/retrieve/'+ id,
                                type: 'post',
                                dataType:'json',
                                data:{},
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
                })
            }
        })
    }
    //订单参数序列化并提交
    function orderData(url,status,val,type){
        if(val == 'save' || val == 'update'){
            var flag = true
            if(type != 'draft'){
                var flag = validated($(".orddetail form"))
            } else {
                flag = true
            }
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
    //导入报关单
    $('.import_btn').on('click',function(){
        let export_html = `<div class="" style="padding: 15px">
            <div class="form-group m-b">
                <label>①首次导入请下载模板</label>
                <input type="button" class="btn btn-normal down_btn" value="下载报关单导入模板" style="float:right" />
            </div>
            <div style="clear: both"></div>
            <div class="form-group">
                <label>②导入报关单</label>
                <input type="button" class="btn btn-normal import_btn" value="导入报关单" style="float:right" />
                <input type="file" class="import_file hide" />
            </div>
        </div>`
        layer.open({
            type: 1,
            title: '导入报关单',
            area: ['400px','200px'],
            content: export_html,
            btn: ['确定'],
            yes: function (index,layero) {
                layer.close(index)
            },
            success: function (layero,index) {
                //下载模板
                $('.down_btn').click(function(){
                    window.location.href = "/excel_tpl/customs.xls";
                })
                //导入
                $('.import_btn').on('click',function(){
                    $('.import_file').trigger('click')
                })

                $('.import_file').off('change');
                $('.import_file').on('change',function(){
                    let formData = new FormData();
                    formData.append('file', $('.import_file')[0].files[0]);
                    // formData.append("type", 17)
                    if(formData != ''){
                        $.ajax({
                            url: '/admin/customs/customs_import',
                            type: 'post',
                            data: formData,
                            /**
                            *必须false才会自动加上正确的Content-Type
                            */
                            contentType: false,
                            /**
                            * 必须false才会避开jQuery对 formdata 的默认处理
                            * XMLHttpRequest会对 formdata 进行正确的处理
                            */
                            processData: false,
                            dataType: 'json',
                            success: function(res){
                                console.log(res);
                                if(res.code=='200'){
                                    $('.import_file').val('')
                                }
                                layer.confirm(res.msg,function(index){
                                    layer.close(index)
                                })
                            }
                        })
                    }
                })
            }
        })
    })
    //下载报关预录单
    function downLoad(val){
        window.location.href = '/admin/download/customs_order/'+val
    }
    //获取报关单列表
    dataObj.page_enterprise = 1
    function getCustomsList(page){
        var keyword = $('.customs_word').val()
        var html = ''
        $.ajax({
            url: '/admin/customs',
            method: 'get',
            data:{
                keyword: keyword,
                page: page
            },
            dataType: 'json',
            success: function (res) {
                console.log(res.data);
                total = res.data.total
                $('.customs_list').html("");
                $.each(res.data.data,function (a,b) {
                    html += `<tr class="customs_tr" data-id="${b.id}">
                        <td class="container_no">${b.contract_no}</td>
                        <td class="customs_no">${b.customs_no}</td>
                        <td class="">${b.business_unit_name}</td>
                    </tr>`;
                })
                $('.customs_list').html(html);
                //调用分页
                Creatpage('page',total, getCustomsList)

                $('.customs_tr').on('dblclick',function(){
                    let id = $(this).data('id')
                    console.log(id);
                    window.open('/admin/customs/read/' + id)
                })
            }
        })
    }
    //删除草稿
    function del_order(id) {
        layer.confirm('确定要删除草稿订单吗？', {
            btn: ['确定', '取消']
        }, function () {
            $.ajax({
                type: 'POST',
                url: "/admin/order/delete/" + id,
                success: function (res) {
                    layer.msg(res.msg)
                    refresh()
                }
            })
        })
    }
</script>
@endsection
