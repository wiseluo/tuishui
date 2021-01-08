<!--运费登记弹窗 :详情，修改，新增-->
<div class="tradetail">
    <form class="form-horizontal" style="overflow: hidden;">
        <input type="hidden" name="status" class="status">
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">申请日期</label>
                <div class="col-sm-8">
                    <input type="text" id="strDate" value="{{ isset($transport) ? $transport->applied_at : \Carbon\Carbon::now()->format('Y-m-d') }}" readonly name="applied_at" class="form-control" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label">开票公司</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($transport->company->name) ? $transport->company->name : '' }}" class="form-control" readonly/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">发票号码</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($transport->number) ? $transport->number : '' }}" name="number" class="form-control" data-parsley-minlength="18" data-parsley-maxlength="18"  data-parsley-required data-parsley-trigger="change" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">开票名称</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($transport->name) ? $transport->name : '' }}" name="name" class="form-control" data-parsley-required data-parsley-trigger="change" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">开票日期</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($transport) ? $transport->billed_at : '' }}" readonly name="billed_at" class="form-control" data-parsley-required data-parsley-trigger="change" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="" class="col-sm-4 control-label necessary_front">金额</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($transport) ? $transport->money : '' }}" name="money" class="form-control" data-parsley-number-message="只能输入整数或者最多两位小数" data-parsley-number data-parsley-required data-parsley-trigger="change" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="proPic" class="col-sm-4 control-label">图片</label>
                <div class="col-sm-5">
                    <input type="button" id="proPic" class="text-center form-control btn-s btn-primary supImg" value="添加图片" data-upload="image"  data-input="[name=picture]"/>
                    <input type="hidden" value="{{ isset($transport) ? $transport->picture : '' }}" name="picture">
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label class="col-sm-4 control-label">订单信息</label>
                <div class="col-sm-5">
                    <input type="button" class="text-center form-control btn-s btn-primary addOder" value="添加订单"/>
                </div>
            </div>
        </div>
        <div class="panel-body table-responsive panel" style="overflow-x:auto;width:100%;text-align: center">
            <table class="table table-hover b-t b-light order_listTable" style="table-layout: fixed;">
                <thead>
                    <tr>
                        <th width="80">订单号</th>
                        <th width="80">金额</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($transport->order))
                        @foreach($transport->order as $key => $order)
                        <tr class="j_order_list_item">
                            <td>{{$order->ordnumber}}</td>
                            <td>{{$order->pivot->money}}</td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <div class="clearfix"></div>
    </form>
</div>
