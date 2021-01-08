@extends('admin.layouts.app')
@section('content')
<link rel="stylesheet" href="{{ URL::asset('/css/bootstrap-table.min.css') }}" type="text/css"/>
<section class="vbox">
    <section class="scrollable padder">
        <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
            <li><i class="fa fa-home"></i> 产品管理</li>
        </ul>
        <section class="panel panel-default">
            <header class="panel-heading text-right bg-light">
                @include('admin.common.status', ['link'=>'admin/product'])
                <span class="btn btn-sm btn-primary" onclick="getHtml(0,'save')">添加产品</span>
                <span class="btn btn-sm btn-danger productExport">导出</span>
            </header>
            <div class="panel-body">
                <!-- <form class="form-inline queryForm gcForm m-b-sm"> -->
                <div class="form-inline queryForm gcForm m-b-sm">
                    <div class="form-group">
                        <input type="text" class="form-control" name="keyword" id="searchName" placeholder="申请人/客户/产品名称/英文名/HSCODE/业务员">
                    </div>
                    <!-- <button type="submit" class="btn btn-normal m-l-md">查询</button> -->
                    <button class="btn btn-normal m-l-md search">查询</button>
                    <input type="hidden" class="outsideUser" value="{{$outsideUser}}">
                </div>
                <!-- </form> -->
                <!-- <div data-gctable
                     data-url="{{ request()->fullUrl() }}"
                     data-label="申请人,产品名称,产品英文名称,HSCode,规格,退税率,货号,法定单位"
                     data-query=".queryForm"
                     data-operate="2|查看|primary|admin/product?outer=prodetail&tp=check|1000px,2|修改||admin/product?outer=prodetail&tp=update&df=y|1000px,2|审核||admin/product?outer=prodetail&tp=examine|1000px,3|删除|danger|admin/product?tp=del"
                     data-operateFilter="c,u,e,d"
                     data-field="created_user_name,name,en_name,hscode,standard,tax_refund_rate,number,unit"
                     data-colWidth="90,100,100,100,80,100,70,70,180,60,60"
                ></div> -->
                <table id="EditListTable"></table>
            </div>
            <div class="drawer_box" style="display:none">
                <button class="btn btn-primary add_drawer" style="float:right;margin-right: 10px;margin-bottom:10px">新增开票人</button>
                <div class="table-responsive panel m-t">
                    <table class="table table-hover b-t b-light" style="table-layout: fixed;">
                        <thead>
                            <tr class="info">
                                <th width="40%">开票人名称</th>
                                <th width="40%">经营范围</th>
                                <th width="20%">操作</th>
                            </tr>
                        </thead>
                        <tbody class="drawer_list">

                        </tbody>
                    </table>
                </div>
                {{--分页--}}
                <div class="pagePicker" style="text-align: center">
                    <div id="drawer_page"></div>
                </div>
            </div>
        </section>
    </section>
</section>
<script src="{{ URL::asset('/js/bootstrap-table.min.js') }}"></script>
<script src="{{ URL::asset('/js/bootstrap-table-zh-CN.min.js') }}"></script>
<script>
    $('.productExport').click(function(){
        window.location.href="/admin/product/export?keyword="+ $("#searchName").val();
    });
    function actionFormatter(value, row, index) {
        result = ''
        outsideUser = $('.outsideUser').val()
        if(row.status == 2 || row.status == 3 || row.status == 5){
            result += "<a href='javascript:;' class='btn btn-sm btn-primary' onclick=\"getHtml("+value+",'read',"+row.status+")\" title='查看'>查看</a>";
        }
        if(row.status == 1 || row.status == 4) {
            result += "<a href='javascript:;' class='btn btn-sm btn-normal' onclick=\"getHtml("+value+",'update')\" title='修改'>修改</a>";
        }
        if(row.status == 3) {
            result += "<a href='javascript:;' class='btn btn-sm btn-twitter' onclick=\"getHtml("+value+",'update_done')\" title='微调'>微调</a>";
            result += "<a href='javascript:;' class='btn btn-sm btn-normal' onclick=\"relation("+value+","+row.customer_id+")\" title='关联'>关联</a>";
        }
        // if(row.status == 3 && outsideUser == 1){
        //     result += "<a href='javascript:;' class='btn btn-sm btn-normal' onclick=\"relation("+value+","+row.customer_id+")\" title='关联'>关联</a>";
        // }
        if (row.status == 2) {
            result += "<a href='javascript:;' class='btn btn-sm btn-default' onclick=\"getHtml("+value+",'approve')\" title='审核'>审核</a>";
        }
        result += "<a href='javascript:;' class='btn btn-sm btn-default' onclick=\"getHtml("+value+",'copy')\" title='复制'>复制</a>";
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
            field: 'drawer_company',
            title: '开票人'
        },
        //{
            //field: 'created_user_name',
            //title: '申请人'
        //},
        {
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

    //弹框
    function getHtml(id,val,status) {
        var url = ''
            url = '/admin/product/' + val + '/' + id
        if(val == 'copy'){
            url = '/admin/product/update/' + id
        }
            outerTitle = '新增'
            btn = ['确定']
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
                    area: ['1000px',height],
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
                        if(val == 'save' || val == 'copy'){
                            url = '/admin/product'
                        }
                        if (val == 'read') {
                            layer.closeAll()
                        } else {
                            var data = []
                            if(val == 'save' || val == 'update' || val == 'approve' || val == 'copy'){
                                var flag = validated($(".prodetail form"))
                                if(flag){
                                    data = $('.prodetail form').serialize()
                                } else {
                                    return false
                                }
                            } else if(val == 'update_done') {
                                url = '/admin/product/update_done/' + id
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
                            url: '/admin/product/retrieve/'+ id,
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

    //关联开票人
    let product_id = ''
    function relation(id,customer_id){
        layer.open({
            type: 1,
            title: '关联开票人',
            area: ['800px','600px'],
            btn: ['确定','取消'],
            content: $('.drawer_box'),
            success: function(layero,index){
                product_id = id
                getRelationInfo(1)
                $('.add_drawer').off('click')
                $('.add_drawer').on('click',function(){
                    var outerTitle = '选择开票人'
                        api = '/admin/drawer/drawer_choose';
                    $.ajax({
                        url: api,
                        type: 'get',
                        data: {
                            action:'html',
                            customer_id: customer_id
                        },
                        success: function (str) {
                            if(str.code == 403){
                                layer.msg(str.msg)
                                return false
                            }
                            layer.open({
                                type: 1,
                                title: outerTitle,
                                area: '800px',
                                content: str,
                                offset: 'top',
                                btn: ['确定'],
                                yes: function (i,layero) {
                                    let drawer_id = layero.find('.bg-active').data('id')
                                        cusname = layero.find('.bg-active').data('name')
                                        $.ajax({
                                            url: '/admin/product/relate_drawer',
                                            type: 'post',
                                            data: {
                                                product_id: product_id,
                                                drawer_id: drawer_id
                                            },
                                            success: function(res){
                                                if(res.code == 200){
                                                    getRelationInfo(1)
                                                    // refresh()
                                                    layer.close(i)
                                                }
                                                layer.msg(res.msg)
                                            }
                                        })
                                    layer.close(i)
                                }
                            })
                        }
                    })
                })
            },
            yes: function(index,layero){
                // refresh()
                layer.closeAll()
            },
            btn2: function () {
                // refresh()
            },
            cancel: function(){

            }
        })
    }

    //获取关联信息  开票人列表
    dataObj.page_enterprise = 1
    function getRelationInfo(page){
        $.ajax({
            url: '/admin/product/related_drawer',
            type: 'get',
            data: {
                product_id: product_id,
                page:page
            },
            success: function(res){
                if(res.code == 200){
                    total = res.data.total
                    let html = ''
                    $('.bnoteTbody').html("");
                    if(total != 0){
                        $.each(res.data.data,function (a,b) {
                            html += `<tr>
                                <td>${b.company}</td>
                                <td>`+(b.busines_scope == null ? '' : b.busines_scope)+`</td>
                                <td><span class="btn btn-sm btn-twitter relieve_btn" data-id="${b.id}">取消关联</span></td>
                            </tr>`;
                        })
                        $('.drawer_list').html(html);
                        //调用分页
                    } else {
                        $('.drawer_list').html('');
                    }
                    Creatpage('drawer_page',total, getRelationInfo)
                    $('.relieve_btn').on('click',function(){
                        let drawer_id = $(this).data('id')
                        layer.confirm('确定要取消关联吗？',{title:'提示',btn:['确定','取消']},function () {
                            $.ajax({
                                url: '/admin/product/del_drawer',
                                type: 'post',
                                data: {
                                    product_id: product_id,
                                    drawer_id: drawer_id
                                },
                                success: function(res){
                                    if(res.code == 200){
                                        layer.msg(res.msg)
                                        getRelationInfo(1)
                                        // refresh()
                                    } else {
                                        layer.msg(res.msg)
                                    }
                                }
                            })
                        })
                    })
                }
            }
        })
    }
</script>
@endsection
