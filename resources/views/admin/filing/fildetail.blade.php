<!--申报查看、新增-->
<div class="fildetail">
    <form class="form-horizontal" style="overflow: hidden;">
        <input type="hidden" name="status"class="status">
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="apply_at" class="col-sm-4 control-label necessary_front">申报日期</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($filing) ? $filing->applied_at : '' }}" id="applied_at" class="form-control" name="applied_at" readonly data-parsley-required data-parsley-trigger="change"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="apply_number" class="col-sm-4 control-label necessary_front">申报批次</label>
                <div class="col-sm-8" style="position: static">
                    <input type="text" value="{{isset($filing) ? $filing->batch : '' }}" id="batch" name="batch" class="form-control" readonly data-parsley-required data-parsley-trigger="change"/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">应退税款</label>
                <div class="col-sm-8">
                    <input type="text" name="declared_amount" class="form-control" data-parsley-number-message="请输入最多两位小数" data-parsley-number data-parsley-required value="{{ isset($filing) ? $filing->declared_amount : '' }}" {{ $disabled }} readonly/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">已退税款</label>
                <div class="col-sm-8">
                    <input type="text" name="amount" class="form-control" data-parsley-number-message="请输入最多两位小数" data-parsley-number   data-parsley-required value="{{ isset($filing) ? $filing->amount : '' }}" {{ $disabled }} readonly/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">票数</label>
                <div class="col-sm-8">
                    <input type="text" name="invoice_quantity" class="form-control" data-parsley-type="integer" data-parsley-required data-parsley-trigger="change" value="{{ isset($filing) ? $filing->invoice_quantity : '' }}" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">退回日期</label>
                <div class="col-sm-8">
                    <input type="text" name="returned_at" id="endDate" readonly class="form-control" data-parsley-required data-parsley-trigger="change" value="{{ isset($filing) ? $filing->returned_at : '' }}" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label ">录入日期</label>
                <div class="col-sm-8">
                    <input type="text" readonly value="{{ isset($filing) ? $filing->created_at : \Carbon\Carbon::now()->format('Y-m-d') }}" class="form-control" data-parsley-required data-parsley-trigger="change" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="register" class="col-sm-4 control-label">录入人员</label>
                <div class="col-sm-8">
                    <input type="text" id="register" value="{{ isset($filing) ? $filing->entry_person : '' }}" class="form-control" readonly/>
                </div>
            </div>
        </div>
        <div class="col-sm-12 m-t-sm">
            <div class="form-group">
                <label class="col-sm-1 control-label" style="padding:7px 0;">函调</label>
                <div class="col-sm-11 letter">
                    <textarea id="letter" class="form-control letter1" rows="3" disabled>{{ isset($filing) ? $filing->letter : '' }}</textarea>
                </div>
            </div>
        </div>
        <div class="col-sm-12 m-t-sm">
            <div class="form-group">
                <label class="h5 text-danger col-sm-12 control-label">发票信息</label>
            </div>
        </div>
        <div class="col-sm-12 m-t-sm">
            <div class="form-group">
                <div class="table-responsive panel" style="overflow-x:auto;margin:0 15px;">
                    <table class="table table-hover b-t b-light" style="table-layout: fixed;">
                        <thead>
                        <tr class="info">
                            <th width="15%">订单号</th>
                            <th width="120">发票号</th>
                            <th width="100">开票时间</th>
                            <th width="120">开票金额</th>
                            <th width="120">退税款</th>
                            <th width="110">收票时间</th>
                        </tr>
                        </thead>
                        <tbody class="ftbody">
                        @if(isset($filing))
                            @forelse($filing->invoice as $item)
                                <tr data-id="{{ $item->id }}">
                                    <td>{{ $item->order->ordnumber }}</td>
                                    <td>{{ $item->number }}</td>
                                    <td>{{ $item->billed_at }}</td>
                                    <td>{{ $item->invoice_amount }}</td>
                                    <td>{{ $item->tax_amount_sum }}</td>
                                    <td>{{ $item->received_at }}</td>
                                </tr>
                            @empty
                                <tr class="zwsj">
                                    <td colspan="6">暂无数据</td>
                                </tr>
                            @endforelse
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>
</div>
