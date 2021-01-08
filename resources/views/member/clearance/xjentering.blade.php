

        <section class="panel fildetail ">
            <form class="form-horizontal"  style="overflow: hidden;">
                {{--1.报关方式--}}
                <div class="m-t-xs m-l-sm" style="line-height: 19px;">
                    <b class="badge bg-danger">1</b>
                    <label class="h5 text-danger m-l-xs">报关方式</label>
                </div>
                <div class="col-sm-4 m-t-xs">
                    <div class="form-group">
                        <label for="cusName" class="col-sm-4 control-label necessary_front" >客户</label>
                        <div class="col-sm-8">
                            <input type="text" id="cusName" value="{{ isset($order) ? $order->customer->name : (isset($customer) ?  $customer->name : '')}}" class="form-control" placeholder="选择客户"  readonly/>
                        </div>
                    </div>
                </div>
                @include('member.common.dataselect', ['label'=>'报关口岸', 'var'=>'clearance_port', 'obj'=> isset($order) ? $order : ''])
                @include('member.common.dataselect', ['label'=>'起运港', 'var'=>'shipment_port', 'obj'=> isset($order) ? $order : ''])
                @include('member.common.dataselect', ['label'=>'报关方式', 'var'=>'declare_mode', 'obj'=> isset($order) ? $order : ''])
                @include('member.common.dataselect', ['label'=>'经营单位', 'var'=>'sales_unit', 'obj'=> isset($order) ? $order : ''])
                @include('member.common.dataselect', ['label'=>'业务类型', 'var'=>'business', 'obj'=> isset($order) ? $order : ''])
                <div class="col-sm-4 m-t-xs">
                    <div class="form-group">
                        <label for="cusName" class="col-sm-4 control-label necessary_front" >合同号</label>
                        <div class="col-sm-8">
                            <input type="text"  value="{{ isset($order) ? $order->contract : '' }}" class="form-control"   readonly/>
                        </div>
                    </div>
                </div>
                  <div class="clearfix"></div>

                {{--2.出货产品清单--}}
                <div class="m-t-xs m-l-sm" style="line-height: 19px;">
                    <b class="badge bg-danger">2</b>
                    <label class="h5 text-danger m-l-xs">出货产品清单</label>
                </div>
                <div class="col-sm-12 m-t-xs">
                    <div class="form-group">
                        <div class="table-responsive panel" style="overflow-x:auto;margin:0 15px;">
                            <table class="table table-hover b-t b-light"id="ice" style="table-layout: fixed;min-width: 1050px;">
                                <thead>
                                <tr class="info">
                                    <th width="8%">品名</th>
                                    <th width="12%">开票人</th>
                                    <th width="9%">产品数量</th>
                                    <th width="7%">单位</th>
                                    <th width="10%">单价(USD)</th>
                                    <th width="9%">货值</th>
                                    <th width="9%">法定数量</th>
                                    <th width="12%">开票金额(RMB)</th>
                                    <th width="9%">毛重(KG)</th>
                                    <th width="9%">净重(KG)</th>
                                    <th width="10%">体积(CBM)</th>
                                    <th width="9%">操作</th>
                                </tr>
                                </thead>
                                <tbody class="otbody" >
                                @if (isset($order) && null !== $order->drawerProducts)
                                    @foreach($order->drawerProducts as $key => $drawerProducts)
                                        <tr data-id="{{ $drawerProducts->id }}">
                                            <input type="hidden" value="{{ $drawerProducts->id }}">
                                            <td data-id="{{ $drawerProducts->product_id }}" data-order="product" data-outwidth="850px">{{ $drawerProducts->product->name }}</td>
                                            <td data-id="{{ $drawerProducts->drawer->company }}">{{ $drawerProducts->drawer->company }}</td>
                                            <td>
                                                <input value="{{ $drawerProducts->pivot->number }}" type="text" name="pro[{{ $key }}][unit]" class="text-center number" {{ $disabled }}>
                                            </td>
                                            <td>
                                                <input value="{{ $drawerProducts->pivot->unit }}" type="text" name="pro[{{ $key }}][unit]" class="text-center" {{ $disabled }}>
                                            </td>
                                            <td>
                                                <input type="text" value="{{ $drawerProducts->pivot->single_price }}" name="pro[{{ $key }}][single_price]"  data-parsley-type="number" data-parsley-trigger="change" class="single_price text-center" {{ $disabled }}>
                                            </td>
                                            <td>
                                                <input type="text" value="{{ $drawerProducts->pivot->total_price }}" name="pro[{{ $key }}][total_price]" class="total_price text-center" readonly>
                                            </td>
                                            <td>
                                                <input type="text" value="{{ $drawerProducts->pivot->default_num }}" name="pro[{{ $key }}][default_num]"  data-parsley-trigger="change" class="default_number text-center" {{ $disabled }}>
                                            </td>
                                            <td>
                                                <input type="text" value="{{ $drawerProducts->pivot->value }}" name="pro[{{ $key }}][value]" data-parsley-type="number" data-parsley-trigger="change" class="default_price text-center" {{ $disabled }}>
                                            </td>
                                            <td>
                                                <input type="text" value="{{ $drawerProducts->pivot->total_weight }}" name="pro[{{ $key }}][total_weight]" data-parsley-type="number" data-parsley-trigger="change" class="total_weight text-center" {{ $disabled }}>
                                            </td>
                                            <td>
                                                <input type="text" value="{{ $drawerProducts->pivot->net_weight }}" name="pro[{{ $key }}][net_weight]" data-parsley-type="number" data-parsley-trigger="change" class="net_weight text-center" {{ $disabled }}>
                                            </td>
                                            <td>
                                                <input type="text" value="{{ $drawerProducts->pivot->volume }}" name="pro[{{ $key }}][volume]" data-parsley-type="number" data-parsley-trigger="change" class="text-center" {{ $disabled }}>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                                <tfoot>
                                <tr class="danger">
                                    <td colspan="3">
                                        <label>产品数量</label>
                                        <input type="text" readonly class="sum_number">
                                    </td>
                                    <td colspan="3">
                                        <label>总货值</label>
                                        {{--总开票金额--}}
                                        <input type="text" readonly class="sum_price">
                                    </td>
                                    <td colspan="3">
                                        <label>总毛重</label>
                                        <input type="text" readonly class="sum_weight">
                                    </td>
                                    <td colspan="3">
                                        <label>总净重</label>
                                        <input type="text" readonly class="sum_net_weight">
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- 报关单号 -->
                <div class="col-sm-4 m-t-xs">
                    <div class="form-group">
                        <label for="customs_number" class="col-sm-4  necessary_front control-label">报关单号</label>
                        <div class="col-sm-8">
                            <input type="text" value="{{ isset($order) ? $order->customs_number : ''}}" id="customs_number" name="customs_number" class="form-control" data-parsley-required="" data-parsley-trigger="change" />
                        </div>
                    </div>
                </div>
            </form>

    <script>
        $(function () {
          if($('#customs_number').val() !==""){
            $('.layui-layer-btn0').css('display','none')
          }
        })
        var customs_number = $("#customs_number").val();

        if( '' != customs_number ){
            $('#customs_number').attr("disabled",true);
        }
        //      代理计算
                //输入产品数量时，法定数量跟随修改

                $('.otbody').on('keydown','.number',function(){
                    $(this).closest('tr').find('.default_number').val($(this).val());
                })

                $('.otbody').on('keyup','.number',function(event){
                    $(this).closest('tr').find('.default_number').val($(this).val());
                })
                  .on('keyup','.total_price,.number',function () {
                    //计算单价（货物/数量）
                    var tp = $(this).closest('tr').find('.total_price').val(),
                      n = $(this).closest('tr').find('.number').val();
                    if(tp && n){
                      $(this).closest('tr').find('.single_price').val(accDiv(tp,n,4));
                    }
                        //计算总数量
                        sum('.number','.sum_number',2);

                        //计算总货值
                        sum('.total_price','.sum_price',4);

                    })
                    //计算总毛重
                    .on('keyup','.total_weight',function(){
                        sum('.total_weight','.sum_weight',2);
                    })
                    //计算总净重
                    .on('keyup','.net_weight',function(){
                        sum('.net_weight','.sum_net_weight',2);
                    });
                $('.single_price,.number,.total_weight,.net_weight').trigger('keyup');

            function sum(s,t,num){
                var total = 0;
                $(s).each(function (i,v) {
                    total =accAdd(total,v.value,num);
                });
                $(t).val(total);
            }
            // 修改当前的单位和业务 ajax请求
            $('#sales_unit,#business').change(function(){
                var sales_unit= $('#sales_unit').val();
                var business=$('#business').val();
                if(sales_unit!=""||business!=""){
                    var Url="/member/"+"business"+"/"+"contract?"+"business"+"="+ business +"&sales_unit"+"="+sales_unit;
                    $.get(Url, {}, function (str) {
                        if(str==""){
                            $('.contract-con').find('.form-control').val('');
                        }else{
                            $('.contract-con').find('.form-control').val(str);
                        }
                    })
                }
            })
    </script>
</section>

