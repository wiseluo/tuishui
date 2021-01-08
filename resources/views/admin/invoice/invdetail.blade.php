<style>
    .invBody .parsley-errors-list {
        position: relative;
    }
    #dowebok li{
        width: 100px;
        height: 100px;
        padding: 2px;
        margin: 5px;
        border: 1px dashed #c2c2c2;
        border-radius: 5px;
        display: inline-block;
        float: left;
        position: relative;
        font-size: 14px;
    }
    #dowebok li img{
        max-width: 100%;
        height: auto;
    }
</style>
<!--发票登记-->
<div class="invdetail">
    <form class="form-horizontal" style="overflow: hidden;">
        <input type="hidden" name="status">
        <label class="h5 text-danger m-t-sm col-sm-12">发票信息</label>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="company" class="col-sm-4 necessary_front control-label">开票工厂</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($invoice->drawer->company) ? $invoice->drawer->company : '' }}" id="company" class="form-control" placeholder="选择开票工厂" data-parsley-required data-parsley-trigger="change" readonly {{ $disabled }}/>
                    <input type="hidden" name="drawer_id" value="{{ isset($invoice) ?  $invoice->drawer_id : '' }}">
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label class="col-sm-4 control-label necessary_front">订单号</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="{{ isset($invoice->order->ordnumber) ? $invoice->order->ordnumber : '' }}" placeholder="选择订单号" data-parsley-required data-parsley-trigger="change" readonly {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="identify_number" class="col-sm-4 control-label">纳税人识别号</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($invoice->drawer->tax_id) ? $invoice->drawer->tax_id : '' }}" id="identify_number" class="form-control" readonly/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="invoice_number" class="col-sm-4 control-label necessary_front">发票号码</label>
                <div class="col-sm-8">
                    <input type="text" id="invoice_number" value="{{ isset($invoice->number) ? $invoice->number : '' }}"
                           name="number" class="form-control" data-parsley-required data-parsley-minlength="18"         data-parsley-maxlength="18" data-parsley-trigger="change" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="statime" class="col-sm-4 control-label necessary_front">开票日期</label>
                <div class="col-sm-8">
                    <input type="text" onclick="laydate({max: laydate.now(0,'YYYY-MM-DD')})"
                           value="{{ isset($invoice->billed_at) ? $invoice->billed_at : '' }}" id="statime"
                           class="form-control" name="billed_at" readonly data-parsley-required
                           data-parsley-trigger="change" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="endtime" class="col-sm-4 control-label necessary_front">收票日期</label>
                <div class="col-sm-8">
                    <input type="text" onclick="laydate()" id="endtime"
                           value="{{ isset($invoice->received_at) ? $invoice->received_at : \Carbon\Carbon::now()->format('Y-m-d') }}"
                           class="form-control" name="received_at" readonly data-parsley-required
                           data-parsley-trigger="change" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-sm">
            <div class="form-group">
                <label for="money" class="col-sm-4 control-label">开票金额</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($invoice->invoice_amount) ? $invoice->invoice_amount : 0 }}" name="invoice_amount" id="money" class="form-control" readonly/>
                </div>
            </div>
        </div>
        <div class="col-sm-12 m-t-sm">
            <div class="form-group">
                <label class="h5 text-danger m-t-sm col-sm-12">商品信息</label>
            </div>
        </div>
        <div class="col-sm-12 m-t-sm">
            <div class="form-group">
                <div class="table-responsive panel" style="overflow-x:auto;margin:0 15px;">
                    <table class="table table-hover b-t b-light" style="table-layout: fixed;min-width: 1300px;">
                        <thead>
                        <tr class="info">
                            <th width="5%">序号</th>
                            <th width="8%">商品编码</th>
                            <th width="10%">商品名称</th>
                            <th width="5%">单位</th>
                            <th width="8%">产品数量</th>
                            <th width="6%">税率(%)</th>
                            <th width="7%">单价(未税)</th>
                            <th width="8%">未税金额</th>
                            <th width="6%">发票数量</th>
                            <th width="8%">发票金额</th>
                            <th width="7%">未开票数量</th>
                            <th width="8%">累计发票金额</th>
                            <!-- <th width="70" class="text-center">操作</th> -->
                        </tr>
                        </thead>
                        <tbody class="invBody">
                        @if(isset($invoice))
                            @forelse($invoice->drawerProductOrders as $drawerProductOrder)
                                <tr>
                                    <input type="hidden" value="{{$drawerProductOrder->id}}">
                                    <td>{{ $loop->index }}</td>
                                    <td>{{ $drawerProductOrder->drawerProduct->product->hscode }}</td>
                                    <td>{{ $drawerProductOrder->drawerProduct->product->name }}</td>
                                    <td>{{ $drawerProductOrder->measure_unit_cn }}</td>
                                    <td>{{ $drawerProductOrder->number }}</td>
                                    <td>{{ $drawerProductOrder->pivot->tax_rate }}</td>
                                    <td class="untax_price"> {{ $drawerProductOrder->pivot->single_price }} </td>
                                    <td class="untax_amount">{{ $drawerProductOrder->pivot->product_untaxed_amount }}</td>
                                    <td>{{ $drawerProductOrder->pivot->quantity }}</td>
                                    <td>{{ $drawerProductOrder->pivot->amount }}</td>
                                    <td class="rest_amount">{{ $drawerProductOrder->number - $drawerProductOrder->invoice_quantity }} </td>
                                    <td class="gather_price">{{ $drawerProductOrder->invoice_amount }}</td>
                                </tr>
                            @empty
                                暂无数据
                            @endforelse
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-sm-12 m-t-sm" id="contract" style="display: none;">
            <div class="form-group">
                <label class="h5 text-danger m-t-sm col-sm-12">合同</label>
            </div>
            <div class="form-group">
                <ul id="dowebok">
                </ul>
            </div>
        </div>
        <input type="hidden" id="generator_str" value="{{isset($invoice) ?$invoice->order->clearance->generator:'0'}}  ">
        @include('admin.common.approve', ['obj'=> isset($invoice) ? $invoice : ''])

    </form>
</div>
<script>
  $(function () {
      var images = $('#generator_str').val();
        if(images.length>0 && images != 0){
            var strs =  images.split("|");
            var lis = $.map(strs, function (item) {
                return '<li> <img src="'+item+ '"alt="图片"> </li>'
            }).join('');
            $('#dowebok').html(lis);
            $('#contract').show();
        }

      // 图片查看
      $('#dowebok').viewer();
      var scrollable_w=parseInt($(document).width())-320;
      var scrollable_h=$(document).height();
      $(document).on('click','#dowebok li img',function(){
          $('.viewer-container').css({
              width:scrollable_w+'px',
              height:scrollable_h,
              left:'-250px'
          });
      });
  })
</script>