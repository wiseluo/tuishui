@extends('admin.layouts.app')

@section('content')
<script src="{{ URL::asset('/js/layui/layui.js') }}"></script>
<link rel="stylesheet" href="{{ URL::asset('/js/layui/css/layui.css') }}" type="text/css"/>
<style>
    .form-group .form-control{
        min-width: 45%;
        width: auto;
        max-width: 65%;
        padding:6px 10px;
        height:auto;
        max-height:50px;
    }
    .col-sm-4.control-label{
        padding-top: 8px;
    }
    .gehead{
        padding:5px 20px 10px 20px;
        font-size:14px;
    }
    .d_margin{
        margin-bottom: 10px ;
        padding:10px 0;
        background: #f6f6f6;
        min-width:200px;
    }
    .form-control span{
        overflow:hidden;
    }
    .d_margin .form-group{
        margin-bottom: 5px;
    }

    #page1,#page2{
        display:none;
    }
    #page1 .btn,#page2 .btn{
         font-weight: normal;
         border-radius: 5px;font-size:12px;
    }
    #page1.active,#page2.active{
    display:block;
    }
    .nav-tabs li.active a{
     background:#F6F6F6;
    }
    #page1 tr td .form-control,
    #page2 tr td .form-control
    {
       margin: 0 auto;
    }
    #test1,#test2{
        text-align:center;
    }

    .nav-con{
        margin:20px ;
    }
     p[class^="gcPage"] {
      line-height: 32px;
      text-align: center;
      font-size: 12px;
      margin: 0;
    }
     p[class^="gcPage"] span {
      margin: 0 1px;
      border: 1px solid #cacaca;
      padding: 1px 5px;
      display: inline-block;
      border-radius: 3px;
      color: #333;
      min-width: 26px;
      line-height: 22px;
      font-size: 12px;
      cursor: pointer;
    }
     p[class^="gcPage"] span.disabled,
     p[class^="gcPage"] span.me {
      cursor: not-allowed;
    }
   > p[class^="gcPage"] span.me,
     p[class^="gcPage"] span:hover {
      color: #23527c;
      background-color: #eee;
      border-color: #ddd;
    }
     p[class^="gcPage"] input {
      line-height: 22px;
      border: 1px solid #c3c2c2;
      border-radius: 3px;
      vertical-align: baseline;
      text-align: center;
      outline: 0;
      transition: border-color 0.2s cubic-bezier(0.645, 0.045, 0.355, 1);
    }
     p[class^="gcPage"] input:focus {
      border-color: #20a0ff;
    }
     p[class^="gcPage"] select {
      width: 66px;
      cursor: pointer;
      margin: 4px 6px 0;
      height: 25px;
      padding: 0 6px;
      border: 1px solid #ccc;
      border-radius: 3px;
      box-sizing: content-box;
      outline: 0;
    }
     p[class^="gcPage"] select option {
      padding: 2px 4px;
    }
     p[class^="gcPage"] select:focus {
      border-color: #20a0ff;
    }
    @media (max-width: 768px) {
      p[class^="gcPage"] select {
        display: none;
      }
    }
</style>
<section class="vbox">
    <section class="scrollable padder">
        <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
            <li><a href="javascript:void(0)"><i class="fa fa-home"></i>关联订单</a></li>
        </ul>
        <section class="panel panel-default">
            <div class="panel-body">
                <!-- 当前|订单|详情 -->
                <div class=" gehead">收汇单 <span>{{ $receipt->identifier }}</span> 详情  </div>
                <div class="clearfix">
                    <div class="pull-left col-md-12 d_margin">
                        <div class="pull-left col-md-6">
                            <form class="form-horizontal">
                                <div class="form-group">
                                    <label for="" class="col-sm-4 control-label h5">本次收汇金额:</label>
                                    <div class="col-sm-8 form-control " readonly>
                                        <span> {{ $receipt->currency->name }} {{$receipt->currency->key}}</span>
                                        <span class="h5"> {{ $receipt->amount }} </span>
                                    </div>
                                </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-4 control-label"></label>
                                        <div class="col-sm-8 form-control h6" readonly>
                                                   ( 约 CNY <span style="font-size:12px;"> {{ $receipt->rmb }} </span> )
                                        </div>
                                  </div>
                                  <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">汇率:</label>
                                    <div class="col-sm-8 form-control " readonly>
                                              <span> {{ $receipt->rate }} </span>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">已关联金额:</label>
                                    <div class="col-sm-8 form-control " readonly>
                                        {{ $receipt->receipted_amount }}
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">未关联金额 :</label>
                                    <div class="col-sm-8 form-control " readonly>
                                       <span> {{ $receipt->amount - $receipt->receipted_amount }} </span>
                                    </div>
                                  </div>
                            </form>
                        </div>
                        <div class="pull-left col-md-6">
                            <form class="form-horizontal">
                                  <div class="form-group">
                                        <label for="" class="col-sm-4 control-label">汇款银行:</label>
                                        <div class="col-sm-8 form-control " readonly>
                                            <span class="  "> {{ $receipt->bank }} </span>
                                        </div>
                                  </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-4 control-label">付款人:</label>
                                        <div class="col-sm-8 form-control " readonly>
                                            <span class="  "> {{ $receipt->payer }} </span>
                                        </div>
                                  </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-4 control-label">收汇时间:</label>
                                        <div class="col-sm-8 form-control " readonly>
                                            <span class="  "> {{ $receipt->received_at }} </span>
                                        </div>
                                  </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-4 control-label">客 &nbsp; 户:</label>
                                        <div class="col-sm-8 form-control " readonly>
                                            <span class="  "> {{ $receipt->customer->name }} </span>
                                        </div>
                                  </div>
                                   {{-- <div class="form-group">
                                        <label for="" class="col-sm-4 control-label"> 备&nbsp;注:</label>
                                        <div class="col-sm-8 form-control " readonly>
                                            <span class="  "> 2017-12-6 18:20:50 </span>
                                        </div>
                                  </div>--}}
                            </form>
                        </div>
                    </div>
                </div>

                <div class="nav-con  col-sm-12" >
                    <ul class="nav nav-tabs">
                        <li role="presentation" class="active"><a href="#page1">待关联订单</a></li>
                        <li role="presentation"><a href="#page2">已关联订单</a></li>
                    </ul>
                </div>
                <div class="page1" >
                    <div class="col-sm-12 m-t-sm active page_con" id="page1">
                        <div class="form-group ">
                            <div class="table-responsive panel" style="overflow-x:auto;margin:0 15px;">
                                <table class="table table-bordered table-hover" >
                                    <thead>
                                        <tr>
                                            <th>订单号</th>
                                            <th>下单日期</th>
                                            <th>报关金额</th>
                                            <th>币种</th>
                                            <th>待关联金额</th>
                                            <th>录入关联金额</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody class="page1_con">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="page">
                            <ul id="gcPage1"></ul>
                        </div>
                    </div>
                </div>
                <div class="page2"  >
                    <div class="col-sm-12 m-t-sm page_con" id="page2">
                        <div class="form-group">
                            <div class="table-responsive panel" style="overflow-x:auto;margin:0 15px;">
                                <table class="table table-bordered table-hover ">
                                    <thead>
                                        <tr>
                                            <th>订单号</th>
                                            <th>关联日期</th>
                                            <th>报关金额</th>
                                            <th>币种</th>
                                            <th>已关联该笔订单的金额</th>
                                            <th>订单资金状况</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody class="page2_con">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- <div class="page">
                            <ul id="gcPage2"></ul>
                        </div> -->
                    </div>
                </div>
            </div>
        </section>
    </section>

<script>
    var id = '{{ request()->route('id') }}';
    var Time=false;
    $(document).ready(function(){
        $('.nav-tabs li').eq(0).trigger('click');
    })
    //判断数据
    function GetData($this){
        if($($this).find('tbody tr').length <=0){
        $($this).find('tbody').html("<tr style='text-align:center;'><td colspan='6'>暂无数据</td></tr>")
        }
    }
    // 总提交
    $('.nav-tabs li').click(function(){
        $('.nav-tabs li,.page_con').removeClass('active');
        var page=  $(this).addClass('active').find('a').attr('href');
        if(page=="#page1"){
            if(Time==true){
                layer.msg('请不要重复提交')
            }else{
                Time =true;
                var Url ='/admin/finance/receipt/order/'+id+'/'+0;
                get_order_list(1)
                function get_order_list(page){
                    $.get(Url, {page:page}, function (str) {
                        var tparent=$('.page1_con');
                        tparent.find('tr').remove();
                        var th='';
                        var Da=str.data.data;
                        if(Da==''){
                            GetData(page);
                        }
                        for(var i=0;i<Da.length;i++){
                            th += "<td>"+Da[i].ordnumber +"</td><td>"+Da[i].created_at+"</td>" +
                            "<td>"+ Da[i].total_value+"</td>"+
                            "<td>"+ Da[i].currency_data.value+"</td>"+
                            "<td data-amount='"+ (Da[i].total_value - Da[i].receipted_amount) +"'>"+ (Da[i].total_value - Da[i].receipted_amount)+" </td>" +
                            "<td><input type='text' class='form-control' name='money' > </td>" +
                            "<td><button data-order_id="+"'"+ Da[i].id+"'"+
                            "class='btn btn-primary page1-btn' > 关联订单 </button> </td>";
                            tparent.append("<tr>"+th+"</tr>" );
                            //调用分页
                            th='';
                        }
                        total = str.data.total
                        Creatpage('gcPage1',total, get_order_list)
                        Time =false;
                        //验证输入
                        $('#page1 .form-control').keyup(function(){
                            $(this).val(
                                $(this).val()
                                .replace(/[^\d.]/g,'')
                                .replace(/^\./g,"")
                                .replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3')
                                .replace(/\.{2,}/g,".")
                                .replace(".","$#$")
                                .replace(/\./g,"")
                                .replace("$#$",".")
                            );
                            var $val=parseFloat($(this).val());
                            var $bg_con=$(this).parent().prev();
                            var $bg=parseFloat($bg_con.attr('data-amount'));
                            var $wbg_con=$(this).parent().prev();
                            if($(this).val() == ''){
                                $wbg_con.html($bg);
                                return false;
                            }
                            var chaz = $bg - $val;
                            if(chaz<0){
                                $(this).val($bg);
                                $wbg_con.html(0);
                                return false;
                            }else{
                                $wbg_con.html(chaz.toFixed(2));
                            }
                        })
                        //关联操作
                        $('.page1-btn').click(function(){
                            var this_input=$(this).parent().prev().find('input.form-control').val();
                            var order_id=$(this).attr('data-order_id');
                            var $this=$(this)
                            if(this_input==''){
                                layer.msg('请先录入关联金额')
                                return false;
                            }else{
                                //关联操作 id 金额 order_id
                                var page1uar="/admin/finance/receipt/binding/"+id+"?order_id="+order_id+"&money="+this_input;
                                $.get(page1uar, {}, function (res) {
                                    if(res.code == 200){
                                        $this.parents('tr').remove();
                                        layer.msg(res.msg, {time: 1000}, function () {
                                            GetData('#page1');
                                        });
                                    }else{
                                        layer.msg(res.msg, {time: 2000}, function () {

                                        });
                                    }
                                })
                            }
                        })
                    })
                }
            }
        }else{
            // 已关联
            if(Time==true){
                layer.msg('请不要重复提交')
            }else{
                Time = true
                var Url ='/admin/finance/receipt/order/'+id+'/'+1;
                $.get(Url, {}, function (str) {
                    var tparent=$('.page2_con');
                    tparent.find('tr').remove();
                    var th='';
                    var Da=str.data;
                    if(Da.orders.length<='0'){
                        GetData('#page2');
                        Time = false;
                        return false;
                    }
                    for(var j=0;j<Da.orders.length;j++){
                        // console.log(Da[i].orders[j].status)
                        th += "<td>"+Da.orders[j].ordnumber +"</td><td>"+Da.orders[j].created_at+"</td>" +
                            "<td>"+ Da.orders[j].total_value+" </td>" +
                            "<td>"+ Da.orders[j].currency_data.value+"</td>"+
                            "<td>"+ Da.orders[j].receipted_amount +"</td>";
                            console.log(Da.orders[j].receipted_amount)
                            console.log(Da.orders[j].total_value)
                        var receipted_amount =Math.floor( Da.orders[j].receipted_amount * 100)/100;
                        var total_value =Math.floor( Da.orders[j].total_value * 100)/100;
                        if(receipted_amount == total_value){
                            th +=  "<td>已收齐</td>"
                        }else{
                            th += "<td>未收齐</td>"
                        }
                        if(Da.orders[j].status=='5'){
                            th += "<td><button data-pivot='"+Da.orders[j].pivot.id +"' data-order_id="+"'"+ Da.orders[j].id+"'"+
                                "class='btn btn-danger page2-btn'> 取消关联 </button></td>";
                                //"<button class='btn btn-default page2-cancel' > 取消结案 </button></td>" ;
                        }else{
                            th += "<td><button data-pivot='"+Da.orders[j].pivot.id +"' data-order_id="+"'"+ Da.orders[j].id+"'"+
                                "class='btn btn-danger page2-btn'> 取消关联 </button></td>";
                                //"<button class='btn btn-default page2-settle' > 结案 </button></td>" ;
                        }
                        tparent.append("<tr>"+th+"</tr>" );
                        th='';
                    }
                    // $('.gcPage2').html(str.page);
                    Time = false;
                    //取消关联
                    $(document).off('click','.page2-btn')
                    $(document).on('click','.page2-btn',function(){
                        var $this =$(this);
                        var order_id=$(this).attr('data-order_id');
                        var pivot_id=$(this).data('pivot');
                        //   /finance/receipt/unbinding/12?order_id=1
                        var page2uar="/admin/finance/receipt/unbinding/"+id+"?order_id="+order_id;
                        $.get(page2uar, {}, function (str) {
                            if(str=='1'){

                            }
                            layer.msg('已取消关联', {time: 1000}, function () {
                                $this.parents('tr').remove();
                                GetData('#page2');
                            });
                        })
                    })
                    //结案
                    $(document).on('click','.page2-settle')
                    $(document).on('click','.page2-settle',function(){
                        var order_id=$(this).prev().attr('data-order_id');
                        var $this =$(this);
                        var status=5;
                        // /order/update/29
                        var page2settle="/admin/order/update/"+order_id;
                        $.post(page2settle, {status}, function (str) {
                            if(str=='1'){
                                layer.msg('已结案', {time: 1000}, function () {
                                    $($this).parent().html("<button data-order_id="+"'"+order_id+"'"+
                                        "class='btn btn-danger page2-btn ' disabled='disabled' > 取消关联 </button>"+
                                        "<button class='btn btn-default page2-cancel' > 取消结案 </button>")
                                    GetData('#page2');
                                });
                            }
                        })
                    })
                    //取消结案
                    //  $('.page2-cancel').click(function(){
                    $(document).on('click','.page2-cancel')
                    $(document).on('click','.page2-cancel',function(){
                        var order_id=$(this).prev().attr('data-order_id');
                        var $this =$(this);
                        var status=3;
                        // /order/update/29
                        var page2cancel="/admin/order/update/"+order_id;
                        $.post(page2cancel, {status}, function (str) {
                            if(str=='1'){
                                layer.msg('已取消结案', {time: 1000}, function () {
                                    $($this).parent().html("<button data-order_id="+"'"+ order_id+"'"+
                                                      "class='btn btn-danger page2-btn '  > 取消关联 </button>"+
                                                      "<button class='btn btn-default page2-settle' > 结案 </button>");
                                    GetData('#page2');
                                });
                            }
                        })
                    })
                })
            }
        }
        $(page).addClass('active');
    })
</script>
</section>
@endsection
