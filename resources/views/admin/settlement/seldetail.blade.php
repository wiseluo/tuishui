<!--退税结算弹窗 :详情，审核-->
<div class="seldetail">
    <form class="form-horizontal" style="overflow: hidden;">
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <input type="hidden" name="status" class="status" value="">
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
                    <input type="text" readonly value="{{ isset($settlement) ? $settlement->order->ordnumber : '' }}" class="form-control"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label">订单日期</label>
                <div class="col-sm-8">
                    <input type="text" readonly value="{{ isset($settlement) ? $settlement->order->created_at : '' }}" class="form-control">
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label">报关金额</label>
                <div class="col-sm-8">
                    <input type="text" readonly value="{{ isset($settlement) ? $settlement->total_value : '' }}" class="form-control"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label">报关币种</label>
                <div class="col-sm-8">
                    <input type="text" readonly value="{{ isset($settlement->currencyData) ? $settlement->currencyData->key : '' }}" class="form-control"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label">已收票金额</label>
                <div class="col-sm-8">
                    <input type="text" readonly value="{{ isset($settlement) ? $settlement->invoice_sum : '' }}" class="form-control"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label">已付定金金额</label>
                <div class="col-sm-8">
                    <input type="text" readonly value="{{ isset($settlement) ? $settlement->paid_deposit_sum : '' }}" class="form-control"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label">已付货款</label>
                <div class="col-sm-8">
                    <input readonly type="text" value="{{ isset($settlement) ? $settlement->paid_payment_sum : '' }}" class="form-control"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label">应退税款</label>
                <div class="col-sm-8">
                    <input type="text" readonly value="{{ isset($settlement) ? $settlement->refund_tax_sum : '' }}" class="form-control"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label">佣金</label>
                <div class="col-sm-8">
                    <input readonly type="text" value="{{ isset($settlement) ? $settlement->commission_sum : '' }}" class="form-control"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label">应付税款</label>
                <div class="col-sm-8">
                    <input type="text" readonly value="{{ isset($settlement) ? $settlement->payable_refund_tax_sum : '' }}" class="form-control"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label">结算日期</label>
                <div class="col-sm-8">
                    <input type="text" readonly value="{{ isset($settlement) ? $settlement->settle_at : '' }}" class="form-control">
                </div>
            </div>
        </div>
        <!-- <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">结算人员</label>
                <div class="col-sm-8">
                    <input type="text" readonly value="" class="form-control" data-parsley-required data-parsley-trigger="change"/>
                </div>
            </div>
        </div> -->
        <div class="col-sm-12 m-t-xs" style="margin-top: 30px">
            <span>付款信息</span>
            <div class="form-group">
                <div class="table-responsive panel" style="overflow-x:auto;margin:0 15px;">
                    <table class="table table-hover b-t b-light" style="table-layout: fixed;min-width: 800px;">
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
                        @if (isset($settlement) && null !== $settlement->settlepayment)
                            @foreach($settlement->settlepayment as $key => $payment)
                                <tr data-id="{{ $payment->id }}">
                                    <td>{{ $loop->index+1 }}</td>
                                    <td>{{ $payment->company }}</td>
                                    <td>{{ $payment->invoice_amount }}</td>
                                    <td>{{ $payment->received_invoice }}</td>
                                    <td>{{ $payment->paid_deposit}}</td>
                                    <td>{{ $payment->paid_payment }}</td>
                                    <td>{{ $payment->refund_tax}}</td>
                                    <td>{{ $payment->commission}}</td>
                                    <td>{{ $payment->payable_refund_tax}}</td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        @include('admin.common.approve', ['obj'=> isset($settlement) ? $settlement : ''])
    </form>

</div>
