@extends('admin.layouts.app')

@section('content')
<style>
 table{
    border-right: 1px solid #ccc;
    border-top: 1px solid #ccc;
 }
 table th, table td{
    border: 1px solid #ccc;
    border-right: none;
    border-top: none;
    text-align: center;
    padding: 6px;
    word-break: break-all;
 }
</style>
<section class="vbox">
    <section class="scrollable padder">
        <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
            <li><i class="fa fa-home"></i> 申报管理</li>
        </ul>
        <section class="panel panel-default">
            <header class="panel-heading text-right bg-light">
                <ul class="nav nav-tabs pull-left">
                    <li><a href="/admin/filing/2">已退税</a></li>
                    <li><a href="/admin/filing/letter">已申报</a></li>
                </ul>
            <!--<span class="btn btn-sm btn-rounded btn-info" data-id="0" data-type="2" data-api="admin/filing?outer=fildetail&tp=call" data-outwidth="850px">申报登记</span>-->
            </header>
            <div class="panel-body table-responsive panel" style="overflow-x:auto;width:100%;text-align: center">
                <table class="table table-hover b-t b-light listTable" style="table-layout: fixed;">
                    <thead>
                        <tr>
                            <th width="80">申报日期</th>
                            <th width="80">申报批次</th>
                            <th width="80">退税款金额</th>
                            <th width="80">票数</th>
                            <th width="80">退回日期</th>
                            <th width="80">录入人员</th>
                            <th width="80">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <div class="purpage page_turn" id="page"></div>
            </div>
        </section>
        <div class="addHtml hide"></div>
    </section>
</section>
<script>
    $(function(){
        list()
        function list() {
            $.ajax({
                type: 'get',
                url: '/admin/filing/letter',
                data:{
                    type: 1
                },
                dataType: 'json',
                success: function(res){
                    console.log(res)
                    let html = ''
                    if(res.code == '200'){
                        $.each(res.data.data,function(i,item) {
                            html += `<tr>
                                    <td>${item.applied_at}</td>
                                    <td>${item.batch}</td>
                                    <td>${item.amount}</td>
                                    <td>${item.invoice_quantity}</td>
                                    <td>${item.returned_at}</td>
                                    <td>${item.created_username}</td>
                                    <td><div class="btn-group"><button type="button" data-url="/admin/filing/letter/${item.id}" class="check btn btn-sm btn-primary">查看</button></div></td>
                                </tr>`
                        })
                        $('.listTable tbody').html(html)
                        layui.use('laypage', function(){
                            var laypage = layui.laypage;
                            //执行一个laypage实例
                            laypage.render({
                                elem: 'page', //注意，这里的 test1 是 ID，不用加 # 号
                                count: res.data.total, //数据总数，从服务端得到
                                limit:15,   //每页条数设置
                                jump: function(obj, first){
                                      //obj包含了当前分页的所有参数，比如：
                                      console.log(obj.curr); //得到当前页，以便向服务端请求对应页的数据。
                                      console.log(obj.limit); //得到每页显示的条数
                                      page=obj.curr;  //改变当前页码
                                      limit=obj.limit;
                                      //首次不执行
                                      if(!first){
                                          list(obj.curr)  //加载数据
                                      }
                                  }
                            });
                        });
                    }else{
                        layer.msg('暂无数据')
                    }
                }
            })
        }

        var width = "1200px"
            height = (window.screen.height *0.6 + 60) + "px"
        $(document).on('click','.check',function () {
            url = $(this).attr('data-url')
            $.ajax({
                type: 'get',
                dataType: "html",
                url: url,
                success: function(res){
                    if(res.code == 403){
                        layer.msg(res.msg)
                        return false
                    }
                    $('.addHtml').html(res)
                }
            }).then(()=>{
                layer.open({
                    type: 1,
                    shade: false,
                    title: '申报查看',
                    area: [width,height],
                    content: $('.addHtml'),
                    success: function () {
                        $('.addHtml').removeClass('hide')
                        $.ajax({
                            type: 'get',
                            url: url,
                            dataType: 'json',
                            data:{
                                type: 1
                            },
                            success: function(res){
                                if(res.code == 403){
                                    layer.msg(res.msg)
                                    return false
                                }
                                let inHtml = ''
                                    workHtml = ''
                                    result = res.data
                                    console.log(result,'11')
                                    invoice = result.invoice
                                    state =  result.df_state
                                    refused =  result.df_refused
                                    workFlowList = result.workflow
                                $('#applied_at').val(result.applied_at)
                                $('#batch').val(result.batch)
                                $('.declared_amount').val(result.declared_amount)
                                $('.amount').val(result.amount)
                                $('.invoice_quantity').val(result.invoice_quantity)
                                $('#endDate').val(result.returned_at)
                                $('.created_at').val(result.created_at)
                                $('#register').val(result.created_username)
                                $('#letter').val(result.letter)
                                getInvoiceList(result.id)
                                $.each(workFlowList,function(i,item){
                                    if(i < state || (refused != 1 && state == 0)){
                                        workHtml += `<span class="flow_chart flow_chart_con ap_pass">${item[1].name}</span>
                                        <span class="flow_chart glyphicon glyphicon-arrow-right">&nbsp;</span>`
                                    } else if(i == state){
                                        workHtml += `<span class="flow_chart flow_chart_con ap_current">${item[1].name}</span>
                                        <span class="flow_chart glyphicon glyphicon-arrow-right">&nbsp;</span>`
                                    } else {
                                        workHtml += `<span class="flow_chart flow_chart_con">${item[1].name}</span>
                                        <span class="flow_chart glyphicon glyphicon-arrow-right">&nbsp;</span>`
                                    }
                                })
                                $('.workFlowList').html(workHtml)
                            }
                        })
                    },
                    cancel:function(){
                        $('.addHtml').addClass('hide')
                    }
                })
            })

            function getInvoiceList(id){  //发票信息列表
                  $.ajax({
                      type: "get",
                      url: "/admin/filing/letter-invoice-list/"+id,
                      dataType: 'json',
                      success: function (res) {
                          if(res.code == 403){
                              layer.msg(res.msg)
                              return false
                          }
                          let invoiceList = res.data
                              html = ''
                          $.each(invoiceList,function(i,item){
                              html += `<tr>
                                  <td>${item.ordnumber}</td>
                                  <td>${item.invoice_number}</td>
                                  <td>${item.product_invoice_quantity_sum}</td>
                                  <td>${item.billed_at}</td>
                                  <td>${item.invoice_amount}</td>
                              </tr>`
                          })
                          $('.infoList tbody').html(html)
                      }
                  })
            }
        })
    });
</script>
@endsection
