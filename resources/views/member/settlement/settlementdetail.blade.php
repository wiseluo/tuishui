<!--退税结算理弹窗 :结算-->
<div class="settlementdetail">
    <form class="form-horizontal" style="overflow: hidden;">
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label">客户名称</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($settlement) ? $settlement->order->customer->name : '' }}" class="form-control" disabled/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-xs contract-con">
            <div class="form-group">
                <label class="col-sm-4  control-label">订单号</label>
                <div class="col-sm-8">
                    <input readonly type="text" value="{{ isset($settlement) ? $settlement->order->ordnumber : '' }}" class="form-control"/>
                    <input type="hidden" value="{{ isset($settlement) ? $settlement->id : '' }}" class="form-control settlement_id"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label">订单日期</label>
                <div class="col-sm-8">
                    <input readonly type="text" value="{{ isset($settlement) ? $settlement->order->created_at : '' }}" class="form-control" readonly="">
                    </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">报关金额</label>
                <div class="col-sm-8">
                    <input readonly type="text" value="{{ isset($settlement) ? $settlement->order->total_value : '' }}" data-parsley-type="number" class="form-control" data-parsley-required data-parsley-trigger="change"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">报关币种</label>
                <div class="col-sm-8">
                    <input readonly type="text" value="{{ isset($settlement->order->currencyData) ? $settlement->order->currencyData->key : '' }}" data-parsley-type="number" class="form-control" data-parsley-required data-parsley-trigger="change"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">已收票金额</label>
                <div class="col-sm-8">
                    <input readonly type="text" value="{{ $invoice_sum }}" data-parsley-type="number" class="form-control" data-parsley-required data-parsley-trigger="change"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">已付定金</label>
                <div class="col-sm-8">
                    <input readonly type="text" value="{{ $paid_deposit_sum }}" data-parsley-type="number" class="form-control" data-parsley-required data-parsley-trigger="change"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">已付货款</label>
                <div class="col-sm-8">
                    <input readonly type="text" value="{{ $paid_payment_sum }}" data-parsley-type="number" class="form-control" data-parsley-required data-parsley-trigger="change"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">应退税款</label>
                <div class="col-sm-8">
                    <input readonly type="text" value="{{ $refund_tax_sum }}" data-parsley-type="number" class="form-control" data-parsley-required data-parsley-trigger="change"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">佣金</label>
                <div class="col-sm-8">
                    <input readonly type="text" value="" class="form-control" data-parsley-type="number" data-parsley-required data-parsley-trigger="change"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">应付税款</label>
                <div class="col-sm-8">
                    <input readonly type="text" value="" class="form-control" data-parsley-type="number" data-parsley-required data-parsley-trigger="change"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">结算日期</label>
                <div class="col-sm-8">
                    <input readonly type="text" onclick="laydate()" id="settle_at" name="settle_at" id="settle_at" class="form-control">
                </div>
            </div>
        </div>
        <!-- <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">结算人员</label>
                <div class="col-sm-8">
                    <input readonly type="text" value="" class="form-control" data-parsley-required data-parsley-trigger="change"/>
                    <input type="hidden" name="user_id" value="">
                </div>
            </div>
        </div> -->
        <div class="col-sm-12 m-t-xs" style="margin-top: 30px">
            <span>付款信息</span>
                <!-- <input  type="button" class="btn btn-s btn-danger" data-before="settlement_id" data-content=".stbody" value="选择开票工厂" id="company" data-check="settle_company" data-target="[name=drawer_id]" class="form-control" style="border-radius: 3px;background-color: #ffd266; position: absolute;right: 50px;top: -15px;width: 100px;
                height: 30px;font-size: 12px"></input> -->
            <div class="form-group">
                <div class="table-responsive panel" style="overflow-x:auto;margin:0 15px;">
                    <table class="table table-hover b-t b-light settle_table"  style="table-layout: fixed;min-width: 800px;">
                        <thead>
                        <tr class="info">
                            <th width="5%">序号</th>
                            <th width="15%">开票工厂</th>
                            <th width="10%">开票金额</th>
                            <th width="10%">已开票金额</th>
                            <th width="10%">已付定金</th>
                            <th width="10%">已付货款</th>
                            <th width="10%">应退税款</th>
                            <th width="10%">佣金</th>
                            <th width="10%">应付退税款</th>
                        </tr>
                        </thead>
                        <tbody class="stbody" >
                        {{--新增不显示，修改&查看&审核显示--}}
                             @foreach($drawers as $key => $v)
                                <tr data-id="{{ $key }}">
                                    <input type="hidden" name="drawer[{{ $loop->index }}][drawer_id]" value="{{ $key }}">
                                    <td>{{ $loop->index+1 }}</td>
                                    <td><input data-parsley-type="integer" class="settle_company form-control" type="text" value="{{ $v['company'] }}" readonly></td>
                                    <td><input data-parsley-type="number" data-parsley-trigger="change" class="invoice_amount form-control" type="text" value="{{ $v['invoice_amount'] }}" readonly></td>
                                    <td><input data-parsley-type="number" data-parsley-trigger="change" class="received_invoice form-control" type="text" value="{{ $v['received_invoice'] }}" readonly></td>
                                    <td><input data-parsley-type="number" data-parsley-trigger="change" class="paid_deposit form-control" type="text" value="{{ $v['paid_deposit'] }}" readonly></td>
                                    <td><input data-parsley-type="number" data-parsley-trigger="change" class="paid_payment form-control" type="text" value="{{ $v['paid_payment'] }}" readonly></td>
                                    <td><input data-parsley-type="number" data-parsley-trigger="change" class="refund_tax form-control" type="text" value="{{ $v['refund_tax'] }}" readonly></td>
                                    <td><input data-parsley-type="number" data-parsley-trigger="change" class="commission form-control" type="text" name="drawer[{{ $loop->index }}][commission]" value="{{ $v['commission'] }}"></td>
                                    <td><input data-parsley-type="number" data-parsley-trigger="change" class="payable_refund_tax form-control" type="text" value="{{ $v['payable_refund_tax'] }}" readonly></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    $(function(){

        //结算日期默认为当前日期
        $('#settle_at').val(laydate.now(0,'YYYY-MM-DD'))
        //拟付退税款金额不能超过应付税款
        $('.drawerposed_deposit').keyup(function(){
            if($(this).val() - $('[name="payable_amount"]').val()>0){
                layer.msg('拟付退税款金额不能超过应付税款,请重新输入');
                $(this).val('');
            }
        })
    })
</script>
