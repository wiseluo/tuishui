<!--客户弹窗 :详情，新增-->
<style>
    #customer_tab {
        width: 100%;
        margin-top:10px;
        border-collapse: collapse;
    }
    #customer_tab th,#customer_tab td{
        border: 1px solid #ccc;
        border-right: none;
        border-top: none;
        text-align: center;
        padding: 6px;
        word-break: break-all;
    }
    #customer_tab tr.orange{
        background-color: #FFC;
    }
    div.customer_div {
        padding-top:10px;
        width: 100%;
        padding-right: 17px;
        border: 1px solid #ccc;
        background-color: #f5f5f5;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        margin: 10px 0;
        overflow-x: auto;
        overflow-y: hidden;
    }

    .cusdetail .row{margin: 0;}
    .form-horizontal .control-label{text-align: right !important;}
</style>
<div class="cusdetail m-t" style="height:450px">
    <form class="form-horizontal">
        <input type="hidden" name="status" class="status">
        <div class="row">
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="cusType" class="col-sm-4 control-label necessary_front">客户类型</label>
                <div class="col-sm-8">
                    <select name="custype" id="cusType" class="form-control" onchange="chooseType()" {{ $disabled }}>
                        <option value="1" {{ (isset($customer) && $customer->custype==1) ? 'selected="selected"' : '' }}>个人客户</option>
                        <option value="2" {{ (isset($customer) && $customer->custype==2) ? 'selected="selected"' : '' }}>企业客户</option>
                    </select>
                </div>
            </div>
        </div>
       <div class="col-sm-4 m-b">
           <div class="form-group">
                <label for="cusName" class="col-sm-4 control-label necessary_front">客户名称</label>
                <div class="col-sm-8">
                    <input readonly type="text" id="cusName" name="name" value="{{ isset($customer) ? $customer->name : ''}}" {{ $disabled }} class="form-control" data-parsley-required data-parsley-trigger="change"/>
                    <input type="hidden" id="ucenterid" name="ucenterid" value="{{ isset($customer) ? $customer->ucenterid : ''}}" {{ $disabled }}>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="cusNumber" class="col-sm-4 control-label necessary_front">客户编号</label>
                <div class="col-sm-8">
                    @if(in_array(request()->route('type'), ['update']))
                        <input readonly type="text" id="cusNumber" name="number" value="{{ isset($customer) ? $customer->number : '' }}" class="form-control" data-parsley-required data-parsley-zw  data-parsley-zw-message="您输入的值不可包含中文" data-parsley-trigger="change"/>
                    @else
                        <input readonly type="text" id="cusNumber" name="number" value="{{ isset($customer) ? $customer->number : '' }}" {{ $disabled }} class="form-control" data-parsley-required data-parsley-zw  data-parsley-zw-message="您输入的值不可包含中文" data-parsley-trigger="change"/>
                    @endif
                </div>
            </div>
        </div>
        <!-- <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="customer_user" class="col-sm-4 control-label necessary_front">客户账号</label>
                <div class="col-sm-8">
                    <input type="text" id="customer_user" value="{{ isset($customer) ? $customer->account_user : ''}}" class="form-control" placeholder="选择账号"   data-outwidth="80px" readonly/>
                        <input type="hidden" id="customer_user_id" name="user_id" value="{{ isset($customer) ? $customer->user_id : ''}}" class="form-control" data-parsley-required data-parsley-trigger="change">
                </div>
            </div>
        </div> -->
        </div>
        <div class="row">
        <div class="col-sm-4 m-b" name="company">
            <div class="form-group">
                <label for="uscCode" class="col-sm-4 control-label necessary_front">社会统一信用代码</label>
                <div class="col-sm-8">
                    <input type="text" id="uscCode" name="usc_code" value="{{ isset($customer) ? $customer->usc_code : ''}}" {{ $disabled }} class="form-control" data-parsley-required data-parsley-trigger="change"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b" name="company">
            <div class="form-group">
                <label for="regcapital" class="col-sm-4 control-label necessary_front">注册资本</label>
                <div class="col-sm-8">
                    <input type="text" id="regcapital" name="reg_capital" value="{{ isset($customer) ? $customer->reg_capital : ''}}" {{ $disabled }} class="form-control" data-parsley-required data-parsley-trigger="change"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b" name="company">
            <div class="form-group">
                <label for="buiDate" class="col-sm-4 control-label necessary_front">成立日期</label>
                <div class="col-sm-8">
                    <input type="text" readonly onclick="laydate()" id="buiDate" name="set_date" value="{{ isset($customer) ? $customer->set_date : ''}}" {{ $disabled }} class="form-control" data-parsley-required data-parsley-trigger="change"/>
                </div>
            </div>
        </div>
        </div>
        <div class="row">
        <div class="col-sm-4 m-b" name="company">
            <div class="form-group">
                <label for="leaName" class="col-sm-4 control-label necessary_front">法人姓名</label>
                <div class="col-sm-8">
                    <input type="text" id="leaName" name="legal_name" value="{{ isset($customer) ? $customer->legal_name : ''}}" {{ $disabled }} class="form-control" data-parsley-required data-parsley-trigger="change"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b" name="company">
            <div class="form-group">
                <label for="leaNum" class="col-sm-4 control-label necessary_front">法人身份证号</label>
                <div class="col-sm-8">
                    <input type="text" id="leaNum" name="legal_num" value="{{ isset($customer) ? $customer->legal_num : ''}}" {{ $disabled }} class="form-control" data-parsley-required data-parsley-trigger="change" />
                </div>
            </div>
        </div>
        </div>
        <div class="row">
         <div class="col-sm-4 m-b" name="personal">
                <div class="form-group">
                    <label class="col-sm-4 control-label">证件类型</label>
                    <div class="col-sm-8">
                        <input type="radio" name="card_type" checked value="1" style="line-height: 32px" {{ $updateDoneDisabled }}><span style="line-height: 32px">身份证</span>
                        <input type="radio" name="card_type" value="2" style="line-height: 32px;margin-left: 20px" {{ $updateDoneDisabled }}><span style="line-height: 32px">护照</span>
                    </div>
                </div>
            </div>
        <div class="col-sm-4 m-b" name="personal">
            <div class="form-group">
                <label for="cardNum" class="col-sm-4 control-label necessary_front">证件号码</label>
                <div class="col-sm-8">
                    <input type="text" id="cardNum" name="card_num" value="{{ isset($customer) ? $customer->card_num : ''}}" {{ $updateDoneDisabled }} class="form-control" data-parsley-required data-parsley-trigger="change" />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label class="col-sm-4 control-label necessary_front">计费基数</label>
                <div class="col-sm-8">
                    <select class="form-control" name="bill_base" id="billBase" {{ $updateDoneDisabled }}>
                        <option value="1" {{ (isset($customer) && $customer->bill_base==1) ? 'selected="selected"' : '' }}>含税开票金额</option>
                        <option value="2" {{ (isset($customer) && $customer->bill_base==2) ? 'selected="selected"' : '' }}>报关金额</option>
                        <option value="3" {{ (isset($customer) && $customer->bill_base==3) ? 'selected="selected"' : '' }}>退税金额</option>
                        <option value="4" {{ (isset($customer) && $customer->bill_base==4) ? 'selected="selected"' : '' }}>不含税开票金额</option>
                    </select>
                </div>
            </div>
        </div>
        </div>
        <div class="row">
         <div class="col-sm-4 m-b">
                <div class="form-group">
                    <label for="serviceRate" class="col-sm-4 control-label necessary_front">服务费率(%)</label>
                    <div class="col-sm-8">
                        <input type="text" data-parsley-type="number" data-parsley-required data-parsley-trigger="change" id="serviceRate" name="service_rate" value="{{ isset($customer) ? $customer->service_rate : ''}}" {{ $updateDoneDisabled }} class="form-control" data-parsley-required data-parsley-trigger="change" />
                    </div>
                </div>
            </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="lineCredit" class="col-sm-4 control-label cuscus">授信额度</label>
                <div class="col-sm-8">
                    <input type="text" id="lineCredit" name="line_credit"  value="{{ isset($customer) ? $customer->line_credit : ''}}" {{ $disabled }} class="form-control"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="linkman" class="col-sm-4 control-label necessary_front">联系人</label>
                <div class="col-sm-8">
                    <input type="text" id="linkman" name="linkman"  value="{{ isset($customer) ? $customer->linkman : ''}}" {{ $updateDoneDisabled }} class="form-control" data-parsley-required data-parsley-trigger="change"/>
                </div>
            </div>
        </div>
        </div>
        <div class="row">
            <div class="col-sm-4 m-b">
                <div class="form-group">
                    <label for="linkPhone" class="col-sm-4 control-label necessary_front">联系电话</label>
                    <div class="col-sm-8">
                        <input type="text" id="linkPhone" name="telephone" value="{{ isset($customer) ? $customer->telephone : ''}}" {{ $updateDoneDisabled }} class="form-control" data-parsley-required data-parsley-type="integer" data-parsley-minlength="8"  data-parsley-trigger="change"/>
                    </div>
                </div>
            </div>
        @include('admin.common.dataselect', ['label'=>'业务员', 'var'=>'u_name', 'obj'=> isset($customer) ? $customer : ''])
        <input type="hidden" name="salesman" id="salesman" value="{{ isset($customer) ? $customer->salesman : ''}}">
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="cusclassify" class="col-sm-4 control-label ">客户分类</label>
                <div class="col-sm-8">
                    <select class="form-control" name="cusclassify" id="cusclassify" {{ $disabled }}>
                        <option value="Z" {{ (isset($customer) && $customer->cusclassify=='Z') ? 'selected="selected"' : '' }}>Z类</option>
                        <option value="F" {{ (isset($customer) && $customer->cusclassify=='F') ? 'selected="selected"' : '' }}>F类</option>
                        <option value="S" {{ (isset($customer) && $customer->cusclassify=='S') ? 'selected="selected"' : '' }}>S类</option>
                    </select>
                </div>
            </div>
        </div>
        </div>
        <div class="row">
        <div class="col-sm-4 m-b">
                <div class="form-group">
                    <label for="email" class="col-sm-4 control-label ">邮箱</label>
                    <div class="col-sm-8">
                        <input type="text" id="email" name="email" value="{{ isset($customer) ? $customer->email : ''}}" {{ $disabled }} class="form-control"  data-parsley-type="integer" data-parsley-minlength="8"  />
                    </div>
                </div>
            </div>
        @if(in_array(request()->route('type'), ['read', 'approve']))
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="balance" class="col-sm-4 control-label">账户余额(RMB)</label>
                <div class="col-sm-8">
                    <input readonly type="text" id="balance" value="{{ isset($customer) ? $customer->balance : ''}}" class="form-control"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="tax_balance" class="col-sm-4 control-label">账户退税余额(RMB)</label>
                <div class="col-sm-8">
                    <input readonly type="text" id="tax_balance" value="{{ isset($customer) ? $customer->tax_balance : ''}}" class="form-control"/>
                </div>
            </div>
        </div>
        @endif
        </div>
        <div class="row">
            <div class="col-sm-4 m-b" name="personal">
                <div class="form-group">
                    <label for="cardname" class="col-sm-4 control-label">户名</label>
                    <div class="col-sm-8">
                        <input type="text" id="cardname" name="card_name" value="{{ isset($customer) ? $customer->card_name : ''}}" {{ $disabled }} class="form-control"/>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="depositBank" class="col-sm-4 control-label">开户银行</label>
                <div class="col-sm-8">
                    <input type="text" id="depositBank" name="deposit_bank" value="{{ isset($customer) ? $customer->deposit_bank : ''}}" {{ $disabled }} class="form-control"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="bankAccount" class="col-sm-4 control-label">银行账号</label>
                <div class="col-sm-8">
                    <input type="text" id="bankAccount" name="bank_account" value="{{ isset($customer) ? $customer->bank_account : ''}}" {{ $disabled }} class="form-control"/>
                </div>
            </div>
        </div>
            <div class="col-sm-4 m-b" name="company">
                <div class="form-group">
                    <label for="receiver" class="col-sm-4 control-label">收款人</label>
                    <div class="col-sm-8">
                        <input type="text" id="receiver" name="receiver" value="{{ isset($customer) ? $customer->receiver : ''}}" {{ $disabled }} class="form-control"/>
                    </div>
                </div>
            </div>
        </div>
        {{--查看时显示，新增不显示--}}
        @if (isset($customer))
        <div class="row">
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label class="col-sm-4 control-label">录入人员</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($customer) ? $customer->created_user_name : ''}}" disabled class="form-control"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label class="col-sm-4 control-label">录入时间</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($customer) ? $customer->created_at : ''}}" disabled class="form-control"/>
                </div>
            </div>
        </div>
        </div>
        @endif
{{--     <div class="col-sm-12 m-t">--}}
{{--            <label for="linkAddress" class="col-sm-1 control-label" >地址</label>--}}
{{--            <div class="col-sm-2">--}}
{{--            {{Form::select('province_code',isset($addr)?$addr:'', null, [--}}
{{--               'id' => 'province',--}}
{{--               'class' => 'form-control',--}}
{{--               'data-parsley-required',--}}
{{--               'data-parsley-trigger' => 'change',--}}
{{--               'disabled' => $disabled,--}}
{{--            ])}}--}}
{{--            </div>--}}
{{--            <div class="col-sm-2">--}}
{{--                <select id="city" class="form-control"  data-parsley-trigger="change" name="city_code"   {{ $disabled }} >--}}
{{--                    <option value="">请选择市</option>--}}
{{--                </select>--}}
{{--            </div>--}}
{{--            <div class="col-sm-2">--}}
{{--                <select id="district" class="form-control"  data-parsley-trigger="change"  name="district_code"  {{ $disabled }}>--}}
{{--                    <option value="">请选择区县</option>--}}
{{--                </select>--}}
{{--            </div>--}}
{{--            <div class="col-sm-5">--}}
{{--                <input type="text" id="linkAddress" name="address" value="{{ isset($customer) ? $customer->address : ''}}"  {{ $updateDoneDisabled }} class="form-control add-ress" data-parsley-required data-parsley-trigger="change"/>--}}
{{--            </div>--}}
{{--        </div>--}}
        <div class="col-sm-12 m-t">
            <div class="form-group">
                <label for="linkAddress" class="col-sm-1 control-label necessary_front" style="margin-left: 33px">地址</label>
                <div class="col-sm-11">
                    <input type="text" id="linkAddress" name="address" value="{{ isset($customer) ? $customer->address : ''}}"  {{ $updateDoneDisabled }} class="form-control add-ress" data-parsley-required data-parsley-trigger="change"/>
                </div>
            </div>
        </div>
        <div class="row">
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label class="col-sm-4 control-label necessary_front">证照</label>
                <div class="col-sm-8">
                    <a href="#" class="btn btn-s-md btn-primary " data-upload="image" data-input="[name=picture_lic]" >证照上传</a>
                    <input type="hidden" name="picture_lic" value="{{ isset($customer) ? $customer->picture_lic : ''}}" data-parsley-required data-parsley-trigger="change"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label class="col-sm-4 control-label" >合同</label>
                <div class="col-sm-8 ">
                    <a href="#" class="btn btn-s-md btn-primary" data-upload="image" data-input="[name=picture_pact]">合同上传</a>
                    <input type="hidden" name="picture_pact" value="{{ isset($customer) ? $customer->picture_pact : ''}}">
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label class="col-sm-4 control-label" >其它</label>
                <div class="col-sm-8 ">
                    <a href="#" class="btn btn-s-md btn-primary" data-upload="image" data-input="[name=picture_other]">其它上传</a>
                    <input type="hidden" name="picture_other" value="{{ isset($customer) ? $customer->picture_other : ''}}">
                </div>
            </div>
        </div>
        </div>
        <div class="clearfix"></div>
        @include('admin.common.approve', ['obj'=> isset($customer) ? $customer : ''])
    </form>
</div>
<script>
    function chooseCustomer(page){
        $.get('/admin/curl/erp_customer_list',{
            name:$('#searchCusName').val(),
            page:page
        }).done(function (res) {
            res = JSON.parse(res)
            var html = ''
            if(res.code == 403){
                layer.msg(str.msg)
                return false
            }
            res.data.map(function(i,k){
                html += `<tr class="bg-white" data-id="${i.cus_id}">
                    <td>${i.cus_full_name}</td>
                    <td>${i.cus_short_name}</td>
                    <td>${i.cus_order_id}</td>
                </tr>`
            })
            $('#customer_tab tbody').html(html)
            var element = $('#page')
                totalpage = Math.ceil(res.total/10)
            element.bootstrapPaginator({
                bootstrapMajorVersion: 3, //bootstrap版本
                currentPage: res.current_page, //当前页面
                numberOfPages: 7, //一页显示几个按钮（在ul里面生成5个li）
                totalPages: totalpage != 0 ? totalpage : 1, //总页数
                itemTexts: function (type, page, current) {
                    switch (type) {
                        case "first":
                        return "首页";
                        case "prev":
                        return "上一页";
                        case "next":
                        return "下一页";
                        case "last":
                        return "末页";
                        case "page":
                        return page;
                    }
                },
                onPageClicked: function (event, originalEvent, type, page) {
                    console.log(page)
                    chooseCustomer(page)
                }
            })
        })
    }
    function chooseType(){
        if($('#cusType').val()=='2'){
            $('[name="personal"]').css('display','none');
            $('[name="company"]').css('display','block');
            $('[name="personal"]').find('input').removeAttr('data-parsley-required')
            $('[name="company"]').find('input').attr('data-parsley-required','true')
            $('[for="linkAddress"]').html('公司地址')
        }else{
            $('[name="personal"]').css('display','block');
            $('[name="company"]').css('display','none');
            $('[name="company"]').find('input').removeAttr('data-parsley-required')
            $('[name="personal"]').find('input').attr('data-parsley-required','true')
            $('[for="linkAddress"]').html('地址')
        }
    }
    $(function(){
        chooseType();
        if ($('.chooseGoodsBox').length == 0) {
            $(document.body).append('<div class="chooseGoodsBox" >' +
                '<table>' +
                '<thead>' +
                '<tr>' +
                '<th width="20%">用户ID</th>' +
                '<th width="25%">客户名称</th>' +
                '<th width="35%">联系人名称</th>' +
                '<th>联系电话</th>' +
                '</tr>' +
                '</thead>' +
                '<tbody class="goodsList">' +
                '</tbody>' +
                '</table>' +
                '</div>'
            )
        }

        if($('#u_name').val()=='000001'){
            $('#u_name').val('');
        }
        $('#u_name').removeAttr('data-parsley-required');
        $('[for="u_name"]').removeClass('necessary_front');
        $(document).ready(function(){
            $('#u_name').select2().change(function(){
                $('#salesman').val($(this).find("option:selected").text());
                console.log($(this).find("option:selected").text())
            });
            $('#u_name').trigger('change');
        })
        $('#cusName').off()
        $('#cusName').on('click',function(){
            layer.open({
                type:1,
                area:['700px','600px'],
                btn:['确定','取消'],
                content:`<div class="customer_div"><input type="text" class="form-control m-l-sm" style="width:50%;display:inline-block;"  id="searchCusName" placeholder="输入客户名">
                    <button onclick="chooseCustomer(1)" class="btn btn_search btn-success m-l-md">查询</button>
                    <table id="customer_tab">
                    <thead>
                        <tr class="bg-white">
                            <th>客户全称</th>
                            <th>客户简称</th>
                            <th>客户编号</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    </table> <div class="purpage page_turn">
                  <ul id="page" style="text-align:center"></ul>
                </div></div>`,
                success:function(){
                    chooseCustomer()
                },
                btn1:function(index){
                    var id = $('#customer_tab tr.orange').data('id')
                    var cusNumber = $('#customer_tab tr.orange td:eq(2)').text()
                    var cusName = $('#customer_tab tr.orange td:eq(0)').text()
                    $('#cusName').val(cusName)
                    $('#cusNumber').val(cusNumber)
                    $.get('/admin/curl/erp_customer/'+id,function(res){
                    res = JSON.parse(res)
                    // console.log(res.cus_charger)
                    $('#u_name option').each(function (key, item) {
                        if($.trim($(item).text()) === res.cus_charger) {
                            $('#u_name').val($(item).val()).change()
                            return true;
                        }
                    })
                    $('#ucenterid').val(res.ucenterid)
                    $('#linkman').val(res.cus_short_name)
                    $('#linkPhone').val(res.cus_mobile_phone)
                    $('#email').val(res.cus_email)
                    $('#linkAddress').val(res.cus_addr)
                    })
                    layer.close(index)
                }
            })
        })
        $(document).on('click','#customer_tab tbody tr',function(){
            $(this).addClass('orange').siblings().removeClass('orange')
        }).on('dblclick','#customer_tab tbody tr',function(){
            //$('.layui-layer-btn0').trigger('click')
        })

        $(document).on('click','.cusdetail .layui-layer-btn0',function(){
            if($('#cusType').val()=='2'){
                if($('#uscCode').val()==""){
                    layer.msg('请填写社会统一信用代码');
                    return false;
                }else if($('#regcapital').val()==""){
                    layer.msg('请填写注册资本');
                    return false;
                }else if($('#buiDate').val()==""){
                    layer.msg('请填写成立日期');
                    return false;
                }else if($('#leaName').val()==""){
                    layer.msg('请填写法人姓名');
                    return false;
                }else if($('#leaNum').val()==""){
                    layer.msg('请填写法人身份证号');
                    return false;
                }else if($('#serviceRate').val()=='' || $('#serviceRate').val() == "0" ){
                    layer.msg('请正确填写服务税率');
                    return false;
                }
            }else{
                if($('#cardNum').val()==""){
                    layer.msg('请填写证件号码');
                    return false;
                }else if($('#serviceRate').val()=='' || $('#serviceRate').val() == "0" ){
                    layer.msg('请正确填写服务税率');
                    return false;
                }
            }
        })
    })
    $(document).ready(function(){
        // if($('#borders_country').prop('disabled') == true){
        //     $('.orddetail').find('select').attr('readonly','readonly');
        //     $('.result').attr('readonly', false);
        // }else{
        $('#trade_country').select2()
        $('#province').select2().change(function(){
            var code=$(this).val();
            var Url='/admin/get_regions/';
            $.ajax({
                type: 'get',
                url: Url+code,
                data: {},
                success: function(res){
                    let html = ''
                    if(res.code == 200){
                        $.each(res.data,function(i,n){
                            html += "<option value='"+ n.region_code+"'>"+ n.region_name +"</option>"
                        })
                        city_code=res.data[0].region_code;
                        $.ajax({
                            type: 'get',
                            url: Url+city_code,
                            data: {},
                            success: function(res){
                                let html = ''
                                if(res.code == 200){
                                    $.each(res.data,function(i,n){
                                        html += "<option value='"+ n.region_code+"'>"+ n.region_name +"</option>"
                                    })
                                }
                                $('#district').html(html).select2();
                            }
                        })
                    }
                    $('#city').html(html).select2();
                }
            })
        });
        $('#city').select2().change(function(){
            var code=$(this).val();
            var Url='/admin/get_regions/';
            $.ajax({
                type: 'get',
                url: Url+code,
                data: {},
                success: function(res){
                    let html = ''
                    if(res.code == 200){
                        $.each(res.data,function(i,n){
                            html += "<option value='"+ n.region_code+"'>"+ n.region_name +"</option>"
                        })
                    }
                    $('#district').html(html).select2();
                }
            })
        });
        $('#aim_country').trigger('change');
    })
</script>
