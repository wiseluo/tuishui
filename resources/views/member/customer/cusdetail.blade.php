<!--客户弹窗 :详情，新增-->
<div class="cusdetail">
    <form class="form-horizontal" style="overflow: hidden;">
        <input type="hidden" name="status" class="status">
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="cusType" readonly class="col-sm-4 control-label necessary_front">客户类型</label>
                <div class="col-sm-8">
                    <input type="text" readonly id="cusType" value="企业客户" class="form-control" disabled>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="cusName" class="col-sm-4 control-label necessary_front">客户名称</label>
                <div class="col-sm-8">
                    <input type="text" readonly id="cusName" name="name" value="{{ isset($customer) ? $customer->name : ''}}" {{ $disabled }} class="form-control" data-parsley-required data-parsley-trigger="change"/>
                </div>
            </div>
        </div>
        <!-- <div class="col-sm-4 m-t">
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
        </div> -->
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="uscCode" class="col-sm-4 control-label necessary_front">社会统一信用代码</label>
                <div class="col-sm-8">
                    <input type="text" id="uscCode" name="usc_code" value="{{ isset($customer) ? $customer->usc_code : ''}}" {{ $disabled }} class="form-control" data-parsley-required data-parsley-trigger="change"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="regcapital" class="col-sm-4 control-label necessary_front">注册资本</label>
                <div class="col-sm-8">
                    <input type="text" id="regcapital" name="reg_capital" value="{{ isset($customer) ? $customer->reg_capital : ''}}" {{ $disabled }} class="form-control" data-parsley-required data-parsley-trigger="change"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="buiDate" class="col-sm-4 control-label necessary_front">成立日期</label>
                <div class="col-sm-8">
                    <input type="text" readonly onclick="laydate()" id="buiDate" name="set_date" value="{{ isset($customer) ? $customer->set_date : ''}}" {{ $disabled }} class="form-control" data-parsley-required data-parsley-trigger="change"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="leaName" class="col-sm-4 control-label necessary_front">法人姓名</label>
                <div class="col-sm-8">
                    <input type="text" id="leaName" name="legal_name" value="{{ isset($customer) ? $customer->legal_name : ''}}" {{ $disabled }} class="form-control" data-parsley-required data-parsley-trigger="change"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="leaNum" class="col-sm-4 control-label">法人身份证号</label>
                <div class="col-sm-8">
                    <input type="text" id="leaNum" name="legal_num" value="{{ isset($customer) ? $customer->legal_num : ''}}" {{ $disabled }} class="form-control"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
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
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="serviceRate" class="col-sm-4 control-label necessary_front">服务费率(%)</label>
                <div class="col-sm-8">
                    <input type="text" data-parsley-type="number" data-parsley-required data-parsley-trigger="change" id="serviceRate" name="service_rate" value="{{ isset($customer) ? $customer->service_rate : ''}}" {{ $updateDoneDisabled }} class="form-control"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="lineCredit" class="col-sm-4 control-label cuscus">授信额度</label>
                <div class="col-sm-8">
                    <input type="text" id="lineCredit" name="line_credit"  value="{{ isset($customer) ? $customer->line_credit : ''}}" {{ $disabled }} class="form-control"/>
                </div>
            </div>
        </div>

        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="linkman" class="col-sm-4 control-label necessary_front">联系人</label>
                <div class="col-sm-8">
                    <input type="text" id="linkman" name="linkman" value="{{ isset($customer) ? $customer->linkman : ''}}" disabled class="form-control" data-parsley-required data-parsley-trigger="change"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="linkPhone" class="col-sm-4 control-label necessary_front">联系电话</label>
                <div class="col-sm-8">
                    <input type="text" id="linkPhone" name="telephone" value="{{ isset($customer) ? $customer->telephone : ''}}" disabled class="form-control" data-parsley-required data-parsley-type="integer" data-parsley-minlength="8"  data-parsley-trigger="change"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="email" class="col-sm-4 control-label ">邮箱</label>
                <div class="col-sm-8">
                    <input type="text" id="email" name="email" value="{{ isset($customer) ? $customer->email : ''}}" {{ $disabled }} class="form-control"  data-parsley-type="integer" data-parsley-minlength="8"  />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="salesman" class="col-sm-4 control-label ">业务员</label>
                <div class="col-sm-8">
                    <input type="text" id="salesman" name="salesman" value="{{ isset($customer) ? $customer->salesman : ''}}" disabled class="form-control"  data-parsley-type="integer" data-parsley-minlength="8"  />
                    <input type="hidden" name="u_name" id="u_name" value="{{ isset($customer) ? $customer->u_name : ''}}">
                </div>
            </div>
        </div>
        @if(in_array(request()->route('type'), ['read', 'approve']))
        <div class="col-sm-4 m-t">
            <div class="form-group">
                    <label for="balance" class="col-sm-4 control-label">账户余额(RMB)</label>
                    <div class="col-sm-8">
                        <input readonly type="text" id="balance" value="{{ isset($customer) ? $customer->balance : ''}}" class="form-control"/>
                    </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                    <label for="tax_balance" class="col-sm-4 control-label">账户退税余额(RMB)</label>
                    <div class="col-sm-8">
                        <input readonly type="text" id="tax_balance" value="{{ isset($customer) ? $customer->tax_balance : ''}}" class="form-control"/>
                    </div>
            </div>
        </div>
        @endif
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="depositBank" class="col-sm-4 control-label">开户银行</label>
                <div class="col-sm-8">
                    <input type="text" id="depositBank" name="deposit_bank" value="{{ isset($customer) ? $customer->deposit_bank : ''}}" {{ $disabled }} class="form-control"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="bankAccount" class="col-sm-4 control-label">银行账号</label>
                <div class="col-sm-8">
                    <input type="text" id="bankAccount" name="bank_account" value="{{ isset($customer) ? $customer->bank_account : ''}}" {{ $disabled }} class="form-control"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="receiver" class="col-sm-4 control-label">收款人</label>
                <div class="col-sm-8">
                    <input type="text" id="receiver" name="receiver" value="{{ isset($customer) ? $customer->receiver : ''}}" {{ $disabled }} class="form-control"/>
                </div>
            </div>
        </div>
        {{--查看时显示，新增不显示--}}
        @if (isset($customer))
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label class="col-sm-4 control-label">录入人员</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($customer) ? $customer->created_user_name : ''}}" disabled class="form-control"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label class="col-sm-4 control-label">录入时间</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($customer) ? $customer->created_at : ''}}" disabled class="form-control"/>
                </div>
            </div>
        </div>
        @endif
        <div class="col-sm-12 m-t">
            <div class="form-group">
                <label for="linkAddress" class="col-sm-1 control-label necessary_front" style="margin-left: 33px">公司地址</label>
                <div class="col-sm-11">
                    <input type="text" id="linkAddress" name="address" value="{{ isset($customer) ? $customer->address : ''}}"  {{ $updateDoneDisabled }} class="form-control add-ress" data-parsley-required data-parsley-trigger="change"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label class="col-sm-4 control-label necessary_front">证照</label>
                <div class="col-sm-8">
                    <a href="#" class="btn btn-s-md btn-primary " data-upload="image" data-input="[name=picture_lic]" >证照上传</a>
                    <input type="hidden" name="picture_lic" value="{{ isset($customer) ? $customer->picture_lic : ''}}" data-parsley-required data-parsley-trigger="change"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label class="col-sm-4 control-label" >合同</label>
                <div class="col-sm-8 ">
                    <a href="#" class="btn btn-s-md btn-primary" data-upload="image" data-input="[name=picture_pact]">合同上传</a>
                    <input type="hidden" name="picture_pact" value="{{ isset($customer) ? $customer->picture_pact : ''}}">
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label class="col-sm-4 control-label" >其它</label>
                <div class="col-sm-8 ">
                    <a href="#" class="btn btn-s-md btn-primary" data-upload="image" data-input="[name=picture_other]">其它上传</a>
                    <input type="hidden" name="picture_other" value="{{ isset($customer) ? $customer->picture_other : ''}}">
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        @include('member.common.approve', ['obj'=> isset($customer) ? $customer : ''])
    </form>
</div>
