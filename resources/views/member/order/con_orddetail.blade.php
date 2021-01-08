<style>
    .otbody .parsley-errors-list{
        position:relative;
    }
    .panel .table td, .panel .table th{
        padding: 6px 10px;
    }
    .col-sm-4.m-t-xs{
        height: 50px;
    }
</style>
<!--订单查看、修改、添加-->
<div class="con_orddetail">
    <form class="form-horizontal" style="overflow: hidden; border-top:1px solid #cccccc">
        <input type="hidden" name="status">
        <input type="hidden" value="1" name="logistics">
        {{--1.报关方式--}}
        <div class="m-t-xs m-l-sm" style="line-height: 19px;">
            <b class="badge bg-danger order_order">1</b>
            <label class="h5 text-danger m-l-xs">报关方式</label>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="bnote_code" class="col-sm-4 control-label" >业务编号</label>
                <div class="col-sm-8">
                    <input type="text" id="bnote_num" name="bnote_num" value="{{ isset($order) ? $order->bnote_num  : ''}}" class="form-control" placeholder="选择业务编号"   data-outwidth="800px" {{ $disabled }} readonly/>
                    <input type="hidden" id="bnote_id" value="">
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label class="col-sm-4 control-label necessary_front" >客户</label>
                <div class="col-sm-8">
                    <input type="text" id="cusName" value="{{ isset($order) ? $order->customer->name : (isset($customer) ?  $customer->name : '')}}" class="form-control" placeholder="选择客户" data-select="customer" data-target="[name=customer_id]" data-outwidth="800px" data-parsley-required data-parsley-trigger="change" {{ $disabled }} readonly/>
                    <input type="hidden" name="customer_id" value="{{ isset($order) ? $order->customer_id : (isset($customer) ?  $customer->id : '') }}">
                </div>
            </div>
        </div>
        @include('member.common.dataselect', ['label'=>'报关方式', 'var'=>'declare_mode', 'obj'=> isset($order) ? $order : ''])
        @include('member.common.dataselect', ['label'=>'经营单位', 'var'=>'sales_unit', 'obj'=> isset($order) ? $order : ''])
        @include('member.common.dataselect', ['label'=>'业务类型', 'var'=>'business', 'obj'=> isset($order) ? $order : ''])
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="tdnumber" class="col-sm-4 control-label">货柜提单号</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($order) ? $order->tdnumber : ''}}" id="tdnumber" name="tdnumber" class="form-control" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-8 m-t-xs contract-con">
            <div class="form-group">
                <label class="col-sm-2 necessary_front control-label">合同号</label>
                @if(in_array(request()->route('type'), ['read', 'approve']))
                <div class="col-sm-4">
                    <input type="text" value="{{ isset($order) ? $order->number : '' }}" data-parsley-type="number" id="number" readonly class="form-control"/>
                </div>
                @else
                <div class="col-sm-2" style="width: 100px;">
                    <input type="text" value="{{ isset($order) ? $order->contract : '' }}" name="contract" data-parsley-required data-parsley-required-message="请先去业务管理设置合同类型"  data-parsley-trigger="change"  {{ $disabled }} class="form-control form-con" />
                </div>
                <label class="control-label" style="float:left">-</label>
                    @if (in_array(request()->route('type'), ['update']))
                        <div class="col-sm-4" style="width: 160px">
                            <input type="text" value="{{ isset($order) ? $order->order_number : '' }}" data-parsley-type="number" id="number" readonly class="form-control"/>
                        </div>
                    @else
                    <div class="col-sm-4" style="width: 160px">
                        <input type="text" value="{{ isset($order) ? $order->number : '' }}" data-parsley-type="number"  data-parsley-required data-parsley-trigger="change" data-parsley-required-message="请填写订单号" id="number" name="number"  class="form-control"/>
                    </div>
                    @endif
                @endif
            </div>
        </div>
        <div class="clearfix"></div>
        {{--2.产品及开票人信息--}}
        <div class="m-t-xs m-l-sm" style="line-height: 19px;">
            <b class="badge bg-danger">2</b>
            <label class=" h5 text-danger m-l-xs">产品及开票人信息</label>
        </div>
        @include('member.common.dataselect', ['label'=>'报关币种', 'var'=>'currency', 'obj'=> isset($order) ? $order : ''])
        @include('member.common.dataselect', ['label'=>'价格条款', 'var'=>'price_clause', 'obj'=> isset($order) ? $order : ''])
        @include('member.common.dataselect', ['label'=>'包装方式', 'var'=>'package', 'obj'=> isset($order) ? $order : ''])
        @include('member.common.dataselect', ['label'=>'装柜方式', 'var'=>'loading_mode', 'obj'=> isset($order) ? $order : ''])
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="trader" class="col-sm-4 necessary_front  control-label">贸易商</label>
                <div class="col-sm-8">
                    <input type="text" id="trader" value="{{ isset($order) ? (isset($order->trader) ? $order->trader->name : '') : ''}}" class="form-control" placeholder="选择贸易商" data-select="tradercon" data-target="[name=trader_id]" data-outwidth="800px" data-parsley-required data-parsley-trigger="change" {{ $disabled }} readonly/>
                    <input type="hidden" name="trader_id" value="{{ isset($order) ? (isset($order->trader) ? $order->trader->id : '') : ''}}">
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="borders_country" class="col-sm-4 necessary_front  control-label">境内货源地</label>
                <div class="col-sm-8">
                    <input type="text" readonly value="{{ isset($order) ? $order->district_name_str : ''}}" id="district" class="form-control"  placeholder="选择境内货源地" data-select="district" data-target="[name=district_code]" data-outwidth="800px" data-parsley-required data-parsley-trigger="change" {{ $disabled }}/>
                    <input type="hidden" id="district_code" name="district_code" value="{{ isset($order) ? $order->district_code  : ''}}">
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label class="col-sm-4 necessary_front control-label">出货产品清单</label>
                <div class="col-sm-8">
                    <a href="javascript:;" class="btn show_tbody btn-s-md btn-success"  data-before="customer_id" data-follow="drawer" data-content=".otbody" data-outwidth="800px" {{ $disabled }}>选择需出货的产品</a>
                </div>
            </div>
        </div>
        <div class="col-sm-12 m-t-xs">
            <div class="form-group">
                <div class="table-responsive panel" style="overflow-x:auto;margin:0 15px;">
                    <table class="table table-hover b-t b-light"id="ice" style="table-layout: fixed;min-width: 1050px;">
                        <thead>
                        <tr class="info">
                            <th width="8%">品名</th>
                            <th width="8%">开票人</th>
                            <th width="8%">产品数量</th>
                            <th width="8%">单位</th>
                            <th width="8%">单价(USD)</th>
                            <th width="8%">货值</th>
                            <th width="8%">法定数量</th>
<!--                        <th width="9%">法定单位</th>-->
                            <th width="8%">开票金额(RMB)</th>
                            <th width="8%">毛重(KG)</th>
                            <th width="8%">净重(KG)</th>
                            <th width="8%">体积(CBM)</th>
                            <th width="9%">件数(CTNS)</th>
                            <th width="14%">定金单号</th>
                            <th width="10%">采购员</th>
                            <th width="6%">合并</th>
                            <th width="9%">操作</th>
                        </tr>
                        </thead>
                        <tbody class="otbody" >
                        {{--新增不显示，修改&查看&审核显示--}}
                        @if (isset($order) && null !== $order->drawerProducts)
                            @foreach($order->drawerProducts as $key => $drawerProducts)
                                <tr data-id="{{ $drawerProducts->id }}">
                                    <input type="hidden" name="pro[{{ $key }}][drawer_product_id]" value="{{ $drawerProducts->id }}">
                                    <td data-id="{{ $drawerProducts->product_id }}" data-order="product" data-outwidth="850px">{{ $drawerProducts->product->name }}</td>
                                    <td data-id="{{ $drawerProducts->drawer->company }}">{{ $drawerProducts->drawer->company }}</td>
                                    <td>
                                        <input value="{{ $drawerProducts->pivot->number }}" type="text" name="pro[{{ $key }}][number]" class="text-center number" {{ $disabled }}>
                                    </td>
                                    <td data-id="{{ $drawerProducts->pivot->unit }}">
                                        <input value="{{ $drawerProducts->pivot->unit }}" type="text" name="pro[{{ $key }}][unit]" class="text-center unit" {{ $disabled }}>
                                    </td>
                                    <td>
                                        <input type="text" value="{{ $drawerProducts->pivot->single_price }}" name="pro[{{ $key }}][single_price]" class="single_price text-center" readonly {{ $disabled }}>
                                    </td>
                                    <td>
                                        <input type="text" value="{{ $drawerProducts->pivot->total_price }}" name="pro[{{ $key }}][total_price]" data-parsley-type="number" data-parsley-trigger="change" class="total_price text-center" {{ $disabled }}>
                                    </td>
                                    <td>
                                        <input type="text" value="{{ $drawerProducts->pivot->default_num }}" name="pro[{{ $key }}][default_num]" data-parsley-type="number" data-parsley-trigger="change" class="default_number text-center" {{ $disabled }}>
                                    </td>
                                    <td>
                                        <input type="text" value="{{ $drawerProducts->pivot->value }}" name="pro[{{ $key }}][value]" data-parsley-type="number" data-parsley-trigger="change" class="default_price text-center" {{ $disabled }}>
                                    </td>
                                    <td>
                                        <input type="text" value="{{ $drawerProducts->pivot->total_weight }}" name="pro[{{ $key }}][total_weight]" data-parsley-type="number" data-parsley-trigger="change" class="total_weight text-center"  {{ $disabled }}>
                                    </td>
                                    <td>
                                        <input type="text" value="{{ $drawerProducts->pivot->net_weight }}" name="pro[{{ $key }}][net_weight]" data-parsley-type="number" data-parsley-trigger="change" class="net_weight text-center" {{ $disabled }}>
                                    </td>
                                    <td>
                                        <input type="text" value="{{ $drawerProducts->pivot->volume }}" name="pro[{{ $key }}][volume]" data-parsley-type="number" data-parsley-trigger="change" class="volume text-center" {{ $disabled }}>
                                    </td>
                                    <td>
                                        <input type="text" value="{{ $drawerProducts->pivot->pack_number }}" name="pro[{{ $key }}][pack_number]" data-parsley-type="number" data-parsley-trigger="change" class="text-center pack_number" {{ $disabled }}>
                                    </td>
                                    <td>
                                        <input type="text"  data-before="customer_id" id="payNum" readonly value="{{ !empty($pay[$key]['pivot']['pay_number'])? $pay[$key]['pivot']['pay_number'] : '' }}" name="pro[{{ $key }}][pay_number]" data-followed="payment" data-parsley-trigger="change" class="pay_number text-center" {{ $disabled }}>
                                    </td>
                                    <td style="display:none">
                                        <input type="hidden"  readonly  data-parsley-type="number" value="{{ !empty($pay[$key]['pivot']['pay_id'])? $pay[$key]['pivot']['pay_id'] : '' }}"  name="pro[{{ $key }}][pay_id]"  class="text-center pay-id" >
                                    </td>
                                    <td>
                                        <input type="text"  id="buyerId" readonly value="{{ $drawerProducts->pivot->buyer }}" name="pro[{{ $key }}][buyer]" data-choose="choosebuyer"  class="buyer text-center" {{ $disabled }}>
                                    </td>
                                    <td style="display:none">
                                        <input type="hidden"  readonly  data-parsley-type="number" value="{{ $drawerProducts->pivot->u_name }}"  name="pro[{{ $key }}][u_name]"  class="text-center u_name" >
                                    </td>
                                    <td>
                                        <select name="pro[{{ $key }}][merge]"  value="{{ $drawerProducts->pivot->merge }}" {{ $disabled }} id="">
                                            <option value="0" {{($drawerProducts->pivot->merge == 0) ? 'selected="selected"' : ''}}>否</option>
                                            <option value="1" {{($drawerProducts->pivot->merge == 1) ? 'selected="selected"' : ''}}>是</option>
                                        </select>
                                    </td>
                                    @if (request()->route('type') == 'update' || request()->route('type') == 'save')
                                    <td class="text-center text-danger deleteBox">删除</td>
                                    @else
                                    <td></td>
                                    @endif
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                        <tfoot>
                        <tr class="danger">
                            <td colspan="3">
                                <label>产品数量</label>
                                <input type="text" readonly name="total_num" class="sum_number">
                            </td>
                            <td colspan="3">
                                <label>总货值</label>
                                {{--总开票金额--}}
                                <input type="text" readonly name="total_value" class="sum_price">
                            </td>
                            <td colspan="3">
                                <label>总毛重</label>
                                <input type="text" readonly name="total_weight" class="sum_weight">
                            </td>
                            <td colspan="3">
                                <label>总净重</label>
                                <input type="text" readonly name="total_net_weight" class="sum_net_weight">
                            </td>
                            <td colspan="2">
                                    <label>总体积</label>
                                    <input type="text" readonly name="total_volume" class="sum_total_volume">
                                    <input type="hidden" readonly name="total_packnum" class="sum_pack_number">
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="package_number " class="col-sm-4 necessary_front control-label">整体包装件数</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($order) ? $order->package_number : ''}}" id="package_number" name="package_number" class="form-control" data-parsley-required data-parsley-trigger="change" {{ $disabled }}/>
                </div>
            </div>
        </div>
        @include('member.common.dataselect', ['label'=>'整体包装方式', 'var'=>'order_package', 'obj'=> isset($order) ? $order : '', 'col'=>'4'])
        <div class="clearfix"></div>
        <!--3.报关信息-->
        <div class="m-t-xs m-l-sm" style="line-height: 19px;">
            <b class="badge bg-danger">3</b>
            <label class=" h5 text-danger m-l-xs">报关信息</label>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="shipping_at" class="col-sm-4 control-label">预计装柜日期</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($order) ? $order->shipping_at : ''}}" name="shipping_at" id="shipping_at" class="form-control" readonly/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="packing_at" class="col-sm-4 control-label">装柜日期</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($order) ? $order->packing_at : ''}}" name="packing_at" id="packing_at" class="form-control" readonly/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="customs_at" class="col-sm-4 control-label">报关日期</label>
                <div class="col-sm-8">
                    <input type="text" onclick="laydate()" value="{{ isset($order) ? $order->customs_at : ''}}" name="customs_at" id="customs_at" class="form-control" readonly/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="sailing_at" class="col-sm-4 control-label">开船日期</label>
                <div class="col-sm-8">
                    <input type="text"  data-parsley-required data-parsley-trigger="change"  value="{{ isset($order) ? $order->sailing_at : ''}}" name="sailing_at" id="sailing_at" class="form-control" readonly/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="shipment_port" class="col-sm-4 control-label">起运港</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($order) ? $shipment_port[$order->shipment_port] : ''}}" id="start_port"  class="form-control"   readonly/>
                    <input type="hidden" data-parsley-required data-parsley-trigger="change" id="shipment_port" name="shipment_port" class="form-control" value="{{ isset($order) ? $order->shipment_port : ''}}"  readonly/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="aim_country" class="col-sm-4 control-label">最终目的国</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($order) ? $aim_country[$order->aim_country] : ''}}" id="end_country" class="form-control"   readonly/>
                    <input type="hidden" data-parsley-required data-parsley-trigger="change" id="aim_country" name="aim_country" class="form-control" value="{{ isset($order) ? $order->aim_country : ''}}"  readonly/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="unloading_port" class="col-sm-4 control-label">抵运港</label>
                <div class="col-sm-8">
                    <input type="text" id="end_port" class="form-control" value="{{isset($order) ? $order->ports->port_c_cod : ''}}" readonly>
                    <input type="hidden" data-parsley-required data-parsley-trigger="change" id="unloading_port" class="form-control" name="unloading_port" value="{{isset($order) ? $order->unloading_port : ''}}" readonly>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="white_card" class="col-sm-4 control-label">白卡号</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($order) ? $order->white_card : ''}}" id="white_card" name="white_card" class="form-control"  readonly/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="plate_number" class="col-sm-4 control-label">车牌号</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($order) ? $order->plate_number : ''}}" id="plate_number" name="plate_number" class="form-control"  readonly/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="box_number" class="col-sm-4 control-label">货柜箱号</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($order) ? $order->box_number : ''}}" id="box_number" name="box_number" class="form-control"  readonly/>
                    <select id="unit_array" class="form-control" type="hidden" style="display: none">
                        @foreach($unit as $key=>$value)
                            <option value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="box_type" class="col-sm-4 control-label">货柜箱型</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($order) ? $order->box_type : ''}}" id="box_type" name="box_type" class="form-control"  readonly/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="clearance_mode" class="col-sm-4 control-label">报关形式</label>
                <div class="col-sm-8">
                    <input type="text" id="clearance_mode" value="{{ isset($order) ? $order->clearance_mode : ''}}" name="clearance_mode" class="form-control" readonly  readonly/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="clearance_port" class="col-sm-4 control-label">报关口岸</label>
                <div class="col-sm-8">
                    <input type="hidden" id="clearance_port_i"  data-parsley-required data-parsley-trigger="change" name="clearance_port" value="{{ isset($order) ? $order->clearance_port : ''}}" id="clearance_port" name="clearance_port" class="form-control"   readonly/>
                    <select id="clearance_port" class="form-control" disabled>
                        @foreach($clearance_port as $key=>$value)
                            <option value="{{$key}}" @if (isset($order) && $key == $order->clearance_port)selected="selected"@endif>{{$value}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="broker_name" class="col-sm-4 control-label">报关行名称</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($order) ? $order->broker_name : ''}}" id="broker_name" name="broker_name" class="form-control"  readonly/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="broker_number" class="col-sm-4 control-label">报关行代码(10位)</label>
                <div class="col-sm-8">
                    <input type="text"  value="{{ isset($order) ? $order->broker_number : ''}}" id="broker_number" name="broker_number" class="form-control"  readonly/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="is_special" class="col-sm-4 control-label">特殊关系确认</label>
                <div class="col-sm-8">
                    <select id="is_special" class="form-control"value="{{isset($order) ? $order->is_special : ''}}" disabled>
                        <option value="0" selected>否</option>
                        <option value="1">是</option>
                    </select>
                    <input type="hidden" class="form-control" value="0" name="is_special"  readonly>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="ship_name" class="col-sm-4 control-label">船名/航次</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($order) ? $order->ship_name : ''}}" id="ship_name" name="ship_name" class="form-control"   readonly/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="transport" class="col-sm-4 control-label">运输方式</label>
                <div class="col-sm-8">
                    <input id="transport_i" name="transport" type="hidden" value="{{isset($order)? $order->transport:''}}">
                    <select id="transport" name="transport" class="form-control" readonly disabled>
                        @foreach($transport as $key=>$value)
                            <option value="{{$key}}"@if (isset($order) && $key == $order->transport)selected="selected"@endif>{{$value}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="is_pay_special" class="col-sm-4 control-label">支付特许权使用费确认</label>
                <div class="col-sm-8">
                    <select id="is_pay_special" class="form-control" value="{{isset($order) ? $order->is_pay_special:''}}" disabled>
                        <option value="0" selected>否</option>
                        <option value="1">是</option>
                    </select>
                    <input type="hidden" class="form-control" value="0" name="is_pay_special"  readonly>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="operator" class="col-sm-4 control-label ">操作人</label>
                <div class="col-sm-8">
                    <input type="text" id="operator" value="{{ isset($order) ? $order->operator : '' }}"  name="operator" class="form-control"  readonly/>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        @include('member.common.approve', ['obj'=> isset($order) ? $order : ''])
    </form>
</div>
<script>
    $(function(){
     
    //   $('#start_port').change()  
      $(document).on('keyup','#number',function () {
        var re=/[^0-9-]+/;//只允许输入数字
        if(re.test(this.value)){
          this.value=this.value.substr(0,this.value.length-1);//将最后输入的字符去除
        }
      })
      $(document).on('blur','.unit',function(){
        var unitArr = new Array();
        $.each($("#unit_array option"),function(){
          var txt = $(this).text();
          unitArr.push(txt)
        })
        var a = '';
        $.each(unitArr,function(i,v){
          if($('.unit').val() == v){
            a ++;
          }
        })
        if(a<1){
          layer.msg("当前单位在库中不存在,请重新输入",{time:2000})
          $(this).val('');
        }
      })
        $('.layui-layer-content').css("height","590px");
//      添加货柜
      var indexBox = $('.boxBody>tr').length;
      $('.addBox').click(function(){
        $('.boxBody').append(
          '<tr><td><input type="text" name="box['+ indexBox +'][number]" class="text-center"></td><td><input type="text" name="box['+ indexBox +'][type]" class="text-center"></td><td><input type="text" data-parsley-maxlength="20"  data-parsley-trigger="change"	  name="box['+ indexBox +'][tdnumber]" class="text-center"></td><td class="text-center text-danger deleteBox">删除</td></tr>');
        indexBox++;
      });
        //  控制方向切换焦点
      var currentLine=0;
      var currentCol=0;
       var allinput= $('#ice').find('input[type="text"]').not("[readonly]").not('[data-units]')
      $('#ice').on('keydown',allinput,function(e){

        e=window.event||e;
        switch(e.keyCode){
          case 37: //左键
            currentCol--;
            changeItem();
            break;
          case 38: //向上键
            currentLine--;
            changeItem();
            break;
          case 39: //右键
            currentCol++;
            changeItem();
            break;
          case 40: //向下键
            currentLine++;
            changeItem();
            break;
          default:
            break;
        }
      });

      //方向键调用
      function changeItem(){
        if(document.all)
          var it=document.getElementById("ice").children[0];
        else
          var it=document.getElementById("ice");

        if(currentLine<0){
          currentLine=it.rows.length-1;
        }
        if(currentLine==it.rows.length){
        currentLine=0;
        }
        var objtab=document.all.ice;
        var allf=objtab.rows[currentLine];
        var objrow=$(allf).find('input[type="text"]').not("[readonly]");
        if(currentCol<0){
            currentCol=objrow.length-1;
        }else if(currentCol==objrow.length){
            currentCol=0;
        }
        if(typeof(objrow[currentCol])=='undefined'){
            return false;
        }
       objrow[currentCol].select();
      }
//      删除货柜
      $('.boxBody').on('click','.deleteBox',function(){
        $(this).closest('tr').remove();
      });
        $(document).on('dblclick', '[data-gctable] .gcBody tr', function () {
            changeItem();
        });
//      删除所选择的出货产品
      $('.otbody').on('click', '.deleteBox', function () {
        $(this).closest('tr').remove();
        $('.number,.total_weight,.net_weight').keyup();
        if ($('.otbody>tr').length == 0) {
          $('.sum_number,.sum_price,.sum_weight,.sum_net_weight').prop('value','');
        }
      });

    //  代理计算
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
          //计算总货值
          sum('.total_price','.sum_price',4);

          //计算总数量
          sum('.number','.sum_number',2);
        })
        // .on('keyup','.single_price',function () {
        //    //计算货值
        //    var tp = $(this).closest('tr').find('.single_price').val(),
        //      n = $(this).closest('tr').find('.number').val();
        //    if(tp && n){
        //      $(this).closest('tr').find('.total_price').val(accMul(tp,n));
        //    } sum('.total_price','.sum_price',4);

        // })
        //计算总毛重
        .on('keyup','.total_weight',function(){
          sum('.total_weight','.sum_weight',3);
        })
        //计算总净重
        .on('keyup','.net_weight',function(){
          sum('.net_weight','.sum_net_weight',3);
        })
         //计算总体积
        .on('keyup','.volume',function(){
          sum('.volume','.sum_total_volume',3);
        })
        //计算总件数
        .on('keyup','.pack_number',function(){
          sum('.pack_number','.sum_pack_number')  
        });
      $('.single_price,.number,.total_weight,.net_weight,.volume,.pack_number').trigger('keyup');
    });
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
                                    $('.contract-con').find('.form-con').val('');
                                }else{
                                    $('.contract-con').find('.form-con').val(str);
                                }
                            })
            }
       })
    $(document).on('click', '#payNum[data-select]', function (){
       var a =  $('[name=customer_id]').val(),

       checkurl = '/member/order/deposit/'+a;
        $.ajax({
            url:checkurl,
            success:function(){}
      })
    })
</script>