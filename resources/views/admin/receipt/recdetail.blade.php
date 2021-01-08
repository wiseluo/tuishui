<!--收汇管理弹窗 :详情，新增-->
<style>
    .row{margin: 0;}
    .form-horizontal .control-label{text-align: right !important;}
    .border_size{line-height: 40px;border-bottom: 1px dashed #999;margin-left: 30px;
        font-size: 14px;
        font-weight: 500;}
</style>
<div class="recdetail m-t">
    <form class="form-horizontal customer" style="overflow: hidden;">
        <input type="hidden" name="status" class="status">
        <div class="row">
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">付款方</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control choose_Cus" value="{{ isset($receipt->customer) ? $receipt->customer->name : '' }}"  placeholder="点击选择付款方" name="customer_name" data-parsley-required data-parsley-trigger="change" {{ $disabled }} readonly/>
                    <input type="hidden" id="customer_id" name="customer_id" value="{{ isset($receipt) ? $receipt->customer_id : '' }}" {{ $disabled }}>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">收款账号</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control account_con"  placeholder="点击选择收款账号" value="{{ isset($receipt) ? $receipt->account : '' }}" name="account" data-parsley-required data-parsley-trigger="change" {{ $disabled }} readonly/>
                    <input type="hidden" name="account_id" value="{{ isset($receipt) ? $receipt->account_id : '' }}" {{ $disabled }}>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label ">收款公司</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control receiptcorp_con" value="{{ isset($receipt) ? $receipt->receiptcorp : '' }}" name="receiptcorp" readonly {{ $disabled }} />
                </div>
            </div>
        </div>
        </div>
        <div class="row">
            <div class="col-sm-4 m-b">
                <div class="form-group">
                    <label for="" class="col-sm-4 control-label">开户银行</label>
                    <div class="col-sm-8">
                        <input type="text" name="bank" value="{{ isset($receipt) ? $receipt->bank : '' }}" {{ $disabled }} readonly {{ $disabled }} class="form-control" />
                    </div>
                </div>
             </div>
             <div class="col-sm-4 m-b">
                <div class="form-group">
                    <label for="" class="col-sm-4 control-label">国家<i class="fa fa-fw fa-question-circle-o" data-type="country"></i></label>
                    <div class="col-sm-8">
                        <input type="text" class="country_openCon form-control" name="country" value="{{ isset($receipt->country->country_na) ? $receipt->country->country_na : '' }}"
                            placeholder="付款银行所在国家" {{ $disabled }} readonly {{ $disabled }} class="form-control" />
                        <input type="hidden" class="country_openCon" name="country_id" value="{{ isset($receipt->country_id) ? $receipt->country_id : '' }}" class="form-control" />
                    </div>
                </div>
            </div>
            @include('admin.common.dataselect', ['label'=>'币种', 'var'=>'currency_id', 'obj'=> isset($receipt) ? $receipt : '' ])
        </div>
        <div class="row">
            <div class="col-sm-4 m-b">
                <div class="form-group">
                    <label for="" class="col-sm-4 control-label necessary_front">预收金额</label>
                    <div class="col-sm-8">
                        <input type="text" name="advance_amount" value="{{ isset($receipt) ? $receipt->advance_amount : '' }}" {{ $disabled }} data-parsley-number-message="请输入纯数字或最多两位小数" data-parsley-number  class="form-control" data-parsley-number data-parsley-required data-parsley-trigger="change"/>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 m-b">
                <div class="form-group">
                    <label for="" class="col-sm-4 control-label necessary_front">业务类型<i class="fa fa-fw fa-question-circle-o" data-type="business"></i></label>
                    <div class="col-sm-8">
                        <select type="text" name="business_type" {{ $disabled }} class="form-control" data-parsley-required data-parsley-trigger="change">
                            <option value="1" {{ (isset($receipt) && $receipt->business_type == 1) ? 'selected="selected"' : '' }}>出口业务</option>
                            <option value="2" {{ (isset($receipt) && $receipt->business_type == 2) ? 'selected="selected"' : '' }}>进口业务</option>
                            <option value="3" {{ (isset($receipt) && $receipt->business_type == 3) ? 'selected="selected"' : '' }}>货运业务</option>
                            <option value="4" {{ (isset($receipt) && $receipt->business_type == 4) ? 'selected="selected"' : '' }}>代收代付</option>
                            <option value="5" {{ (isset($receipt) && $receipt->business_type == 5) ? 'selected="selected"' : '' }}>平台服务</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 m-b">
                <div class="form-group">
                    <label for="" class="col-sm-4 control-label necessary_front">申请人</label>
                    <div class="col-sm-8">
                        @if(request()->type === 'update')
                        <input type="text" name="apply_uname" value="{{ isset($receipt) ? $receipt->apply_uname : '' }}" class="form-control" data-parsley-required data-parsley-trigger="change" disabled/>
                        @else
                        <input type="text" name="apply_uname" value="{{ isset($receipt) ? $receipt->apply_uname : '' }}" class="form-control apply_uname" data-parsley-required data-parsley-trigger="change" readonly {{ $disabled }}/>
                        @endif
                        <input type="hidden" name="apply_uid" value="{{ isset($receipt) ? $receipt->apply_uid : '' }}" class="apply_uid" {{ $disabled }}>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4 m-b">
                <div class="form-group">
                    <label for="" class="col-sm-4 control-label necessary_front">预计收款日期</label>
                    <div class="col-sm-8">
                        <input type="text" name="expected_received_at" onClick='laydate()' value="{{ isset($receipt) ? $receipt->expected_received_at : '' }}" {{ $disabled }} class="form-control" data-parsley-required data-parsley-trigger="change" readonly />
                    </div>
                </div>
            </div>
            <div class="col-sm-4 m-b receipt_dom">
                <div class="form-group">
                    <label for="" class="col-sm-4 control-label necessary_front">结汇方式<i class="fa fa-fw fa-question-circle-o" data-type="exchange"></i></label>
                    <div class="col-sm-8">
                        <select type="text" name="exchange_type" {{ $disabled }} class="form-control" data-parsley-trigger="change">
                            <option value="1" {{ (isset($receipt) && $receipt->exchange_type == 1) ? 'selected="selected"' : '' }}>到账结汇</option>
                            <option value="2" {{ (isset($receipt) && $receipt->exchange_type == 2) ? 'selected="selected"' : '' }}>通知结汇</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8 m-b">
                <div class="form-group">
                    <label class="col-sm-2 control-label">备注</label>
                    <div class="col-sm-10">
                        <textarea type="text" value="{{ isset($receipt) ? $receipt->remark : ''}}" name="remark"  {{ $disabled }} class="form-control"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4 m-b" style="margin-bottom: 14px;">
                <div class="form-group">
                    <label for="proPic" class="col-sm-4 control-label necessary_front">流水单</label>
                    <div class="col-sm-5">
                        <input type="button" id="proPic" class="text-center form-control btn-s btn-primary" value="添加流水单" data-upload="image" data-input="[name=picture]" />
                        <input type="hidden" name="picture" value="{{ isset($receipt) ? $receipt->picture : '' }}" data-parsley-required data-parsley-trigger="change">
                    </div>
                </div>
            </div>
        </div>

        {{--以下两项查看页面可见--}}
        @if (isset($receipt) && $receipt->status >= 3)
        <div class="row">
            <div class="border_size col-sm-12 m-b">
                实际收汇
            </div>
            <div class="col-sm-4 m-b">
                <div class="form-group">
                    <label class="col-sm-4 control-label">收款时间</label>
                    <div class="col-sm-8">
                        <input type="text" value="{{ isset($receipt) ? $receipt->received_at : '' }}" class="form-control" disabled/>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 m-b">
                <div class="form-group">
                <label for="" class="col-sm-4 control-label">付款人</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($receipt) ? $receipt->payer : '' }}" class="form-control" disabled/>
                </div>
                </div>
            </div>
            <div class="col-sm-4 m-b">
                <div class="form-group">
                    <label class="col-sm-4 control-label">实收款金额</label>
                    <div class="col-sm-8">
                        <input type="text" value="{{ isset($receipt) ? $receipt->amount : '' }}" class="form-control" disabled/>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 m-b">
                <div class="form-group">
                    <label for="" class="col-sm-4 control-label">汇率</label>
                    <div class="col-sm-8">
                        <input type="text" value="{{ isset($receipt) ? $receipt->rate : '' }}" class="form-control" disabled/>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 m-b">
                <div class="form-group">
                    <label for="" class="col-sm-4 control-label">人民币</label>
                    <div class="col-sm-8">
                        <input type="text" value="{{ isset($receipt) ? $receipt->rmb : '' }}" class="form-control" disabled/>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        @endif
    </form>
</div>
<script>
    $(document).off('click','.choose_Cus')
    $(document).on('click','.choose_Cus',function () {
        var ele,
            outerTitle = '选择客户'
            api = '/admin/customer/customer_choose';
        $.ajax({
            url: api,
            type: 'get',
            data: {
                action:'html'
            },
            success: function (str) {
                var index = layer.open({
                    type: 1,
                    title: outerTitle,
                    area: '800px',
                    content: str,
                    btn: ['确定'],
                    yes: function (i) {
                        let id = ele.find('.bg-active').data('id')
                            cusname = ele.find('.bg-active .customer_name').text()
                        $('.choose_Cus').val(cusname)
                        $('#customer_id').val(id)
                        layer.close(i)
                    }
                })
                ele = $('#layui-layer' + index + '')
            }
        })
    })
    // /curl/erp_enterprise_account'
    $(document).off('click','.account_con')
    $(document).on('click','.account_con',function (){
        var ele,th=$(this),
            outerTitle = '选择收款账号'
            api = '/admin/curl/erp_enterprise_account';
        var cHtml=`<div class="choosePro panel-body">
            <div class="form-group" style="margin:0 0 25px 20px;">
                <div class=" " style="width: 200px;">
                    <input style="width: 200px;float: left" type="text" class="form-control chooseCustomer_name" name="keyword" placeholder="输入收款账号名称">
                </div>
                <button class="btn btn-success m-l-md so_btn">查询</button>
            </div>
            <div class="table-responsive panel" style="max-height: 450px">
                <div class="tab" style="margin-bottom:5px">
                    <button class="btn btn-success type_btn" data-type="0">公司</button>
                    <button class="btn type_btn" data-type="1">个人</button>
                </div>
                <table class="table table-hover b-t b-light" style="table-layout: fixed;">
                    <thead>
                    <tr class="info">
                        <th width="33%">收款账号</th>
                        <th width="33%">收款公司</th>
                        <th width="33%">开户银行</th>
                    </tr>
                    </thead>
                    <tbody class="bnoteTbody">

                    </tbody>
                </table>
            </div>
            {{--分页--}}
            <div class="pagePicker">
                <div id="enterpriseOrder"></div>
            </div>
        </div>`
    var index = layer.open({
            type: 1,
            title: outerTitle,
            area: '800px',
            offset: '50px',
            content: cHtml,
            btn: ['确定'],
            yes: function (i) {
                let id = ele.find('.bg-active').data('id'),
                    cusname = ele.find('.bg-active .ea_account').text(),
                    ea_accountname=ele.find('.bg-active .ea_accountname').text(),
                    ea_bankaccount =ele.find('.bg-active .ea_bankaccount').text();
                th.val(cusname)
                th.next().val(id);
                //收款公司  开户银行
                $('[name="receiptcorp"]').val(ea_accountname);
                $('[name="bank"]').val(ea_bankaccount);
                layer.close(i)
            },
            success: function(layero, index){
                var isparsonal = 0
                layero.on('click', '.bnoteTbody tr', function () {
                   $(this).addClass('bg-active').siblings().removeClass('bg-active');
                })
                layero.on('dblclick', '.bnoteTbody tr', function () {
                    $(this).addClass('bg-active').siblings().removeClass('bg-active');
                    $('.layui-layer-btn0:eq(1)').trigger('click');
                })
			    layero.on('click', '.so_btn', function () {
			        GetEnterprise(1)
			    })
                layero.on('click', '.type_btn', function () {
                    $('.type_btn').removeClass('btn-success')
                    $(this).addClass('btn-success')
                    isparsonal = $(this).data('type')
			        GetEnterprise(1)
			    })
                GetEnterprise(1)
                dataObj.page_enterprise = 1
                function GetEnterprise(page){
                    var keyword =layero.find('.chooseCustomer_name').val()
                    var html = '',con=layero.find('.bnoteTbody');
                    id = $('.choose_customer_id').val()
                    $.get('/admin/curl/erp_enterprise_account',{keyword:keyword,page:page,isparsonal:isparsonal},function (res) {
                        var jsonObj = $.parseJSON(res)
					    var total = jsonObj.total;
					    con.html("");
                        $.each(jsonObj.data,function (a,b) {
                            html += '<tr data-id="' + b.ea_id + '">' +
                                    '<td class="ea_account" >' + b.ea_account + '</td>' +
                                    '<td class="ea_accountname">' + b.ea_accountname + '</td>' +
                                    '<td class="ea_bankaccount" >' + b.ea_bankaccount + '</td>' +
                                '</tr>';
                        })
                        con.html(html);
                        //调用分页
                        Creatpage('enterpriseOrder',total, GetEnterprise)
                    })
                }
            }
        })
        ele = $('#layui-layer' + index + '')
    })
    function cny(){
       var rmb = $("[name='money']").val()*$("[name='rate']").val();
       if(isNaN(rmb)){
           $("[name='cny']").val(0);
           return false;
       }else{
       $("[name='cny']").val(rmb.toFixed(2));}
   }

    $("[name='money'],[name='rate']").change(cny);

    $('.apply_uname').off('click').on('click',function () {  //添加订单信息
        var ele,
            $this = $(this),
            outerWidth = '600px'
            outerTitle = $this.parent().prev().text();
        var index = layer.open({
            type: 1,
            shadeClose: true,
            title: outerTitle,
            area: outerWidth,
            offset: '100px',
            // btn: ['确定'],
            content: `<div class="orderBox table-responsive panel" style="overflow-x:auto;margin:15px;">
                <table class="table table-hover b-t b-light infoList" style="table-layout: fixed;">
                    <thead>
                        <tr>
                          <th>申请人名称</th>
                        </tr>
                    </thead>
                    <tbody class="nameList">
                    </tbody>
                    </table>
                </div>`,
            success: function(){
                nameList()
            }
            // yes: function (i) {
            //     // $('#remitteeName').val(ele.find('.bg-active td').eq(1).html());
            //     // $('.sk_account').val(ele.find('.bg-active td').eq(3).html());
            //     // $('.sk_bank').val(ele.find('.bg-active td').eq(4).html());
            //     layer.close(i);
            // },
            // end: function () {
            // }
        });
    })

    function nameList(page){
        let api = '/admin/curl/erp_tax_user_list'
        $.ajax({
            type: 'get',
            url: api,
            data: {
                page: page,
            },
            dataType: 'json',
            success: function(res){
                var html = ''
                if(res.code == 200){
                   // let total = res.data.total
                    $.each(res.data,function (i,v) {
                        html += `<tr class="name_choose" data-name="${v.u_truename}" data-id="${v.u_id}">
                            <td>${v.u_truename}</td>
                        </tr>`
                   })
                   $('.nameList').html(html)
                   // //调用分页
                   // Creatpage('page',total, nameList)
                } else {
                    layer.msg(res.msg)
                }
            }
        })
    }

    $(document).off('click','.name_choose')
    $(document).on('click','.name_choose',function(){
        let u_truename = $(this).data('name')
            u_id = $(this).data('id')
        $('.apply_uname').val(u_truename)
        $('.apply_uid').val(u_id)
        layer.close(layer.index)
    })
    // 国家
    $(document).off('click','.country_openCon')
    $(document).on('click','.country_openCon',function () {
        //选择国家
        var th=$(this),
            outerTitle = '选择国家'
            api = '/admin/country/list';
        $.ajax({
            url: api,
            type: 'get',
            data:{
                action: 'html'
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
                    offset: 'top',
                    content: str,
                    btn: ['确定'],
                    yes: function (i,layero) {
                        let id = layero.find('.bg-active').data('id')
                            cusname = layero.find('.bg-active .customer_name').text()
                        if(th.attr('name') == 'destination_country'){
                            $('input[name=destination_country]').val(cusname)
                            $('input[name=destination_country]').next().val(id)
                        } else {
                            th.val(cusname)
                            th.next().val(id)
                        }
                        layer.close(i)
                    }
                })
            }
        })
    })

    //判断结汇方式是否显示
    $('#currency_id').on('change',function(){
        money_type = $(this).val()
        if(money_type == 354){
            $('.receipt_dom').hide().find('select').prop('disabled',true)
        } else {
            $('.receipt_dom').show().find('select').prop('disabled',false)
        }
    })
    //提示信息
    $('.fa-question-circle-o').on('click',function(){
        let html = `<div style="padding: 20px">
            <p>这里的国家是指付款银行所在的国家，方便财务查询收款</p>
        </div>`
        console.log($(this).data('type'));
        if($(this).data('type') == 'business'){
            html = `<div style="padding: 20px">
                <p>出口业务：是指所有的贸易出口业务</p>
                <p>进口业务：是指所有的贸易进口业务</p>
                <p>货运业务：是指所有的物流、仓储等相关的业务</p>
                <p>代收代付：是指外贸综合服务相关的业务</p>
                <p>平台服务：是指平台为客户提供服务，收取的服务费，不计入客户资金池</p>
            </div>`
        }
        if($(this).data('type') == 'exchange'){
            html = `<div style="padding: 20px">
                <p>到账结汇：是查款人查到收款后，对外币及时结汇，且将单笔收款全部兑换成人民币</p>
                <p>进通知结汇：是查款人查到收款后，由业务人员提交结汇后再结汇</p>
            </div>`
        }
        layer.open({
            type: 1,
            title: '提示',
            area: '600px',
            offset: '50px',
            btn: ['确定'],
            maxmin: true,
            content: html,
            yes: function (index) {
                layer.close(index)
            }
        })
    })
</script>
