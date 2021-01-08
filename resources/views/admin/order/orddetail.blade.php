<style>
    .col-sm-4.m-t-xs{
        height: 50px;
    }
    .pro_sec ul,
    .pro_sec li {
        margin: 0;
        padding: 0;
        list-style: none;
    }
    .pro_sec li label {
        width: auto;
    }

    .am-panel-title input {
        border: none;
        background: none;
        color: #0e90d2;
        margin-bottom: 3px;
    }
    .pro_sec input,
    .pro_sec select {
        border: none;
        height: 30px;
        line-height: 30px;
        padding-left: 5px;
    }

    .pro_sec input {
        border-bottom: 1px solid #ccc;
    }

    .pro_sec li {
        width: 33%;
        float: left;
        padding: 2px 0 2px 10px;
        border-right: 1px solid #cccccc;
        border-bottom: 1px solid #cccccc;
        height: 35px;
    }

    .pro_sec .last_li {
        width: 34%;
        border-right: none;
    }

    .pro_sec {
        font-size: 12px;
    }

    .pro_sec ul li .am-btn {
        font-size: 12px;
    }
    .pro_sec li select {
        width: 150px;
        height: 30px;
        display: inline-block;
        text-indent: 0.5rem;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 12px;
    }
</style>
<link rel="stylesheet" href="{{ URL::asset('/css/amazeui.min.css') }}" type="text/css"/>
<!--订单查看、修改、添加-->
<div class="orddetail">
    <form class="form-horizontal cusform" id="cusform" style="overflow: hidden; border-top:1px solid #cccccc">
        <input type="hidden" name="status" class="status">
        <input type="hidden" value="0" name="logistics">
        {{--1.报关方式--}}
        <div class="m-t-xs m-l-sm" style="line-height: 19px;">
            <b class="badge bg-danger order_order">1</b>
            <label class="h5 text-danger m-l-xs">报关方式</label>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="bnote_code" class="col-sm-4 control-label" >业务编号</label>
                <div class="col-sm-8">
                    <input type="text" id="bnote_num" name="bnote_num" value="{{ isset($order) ? $order->bnote_num : ''}}" class="form-control"  data-outwidth="800px" placeholder="选择业务编号" @if($logistics == 0)disabled @endif {{ $disabled }} readonly/>
                    <input type="hidden" id="bnote_id" value="">
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label class="col-sm-4 control-label necessary_front" >客户</label>
                <div class="col-sm-8">
                    <input type="text" id="cusName" value="{{ isset($order->customer) ? $order->customer->name:'' }}" class="form-control choose_Cus" placeholder="选择客户" data-parsley-required data-parsley-trigger="change" {{ $disabled }} readonly/>
                    <input type="hidden" id="customer_id" name="customer_id" value="{{ isset($order) ? $order->customer_id : '' }}">
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label class="col-sm-4 control-label necessary_front" >经营单位</label>
                <div class="col-sm-8">
                    <input type="text" id="companyName" value="{{ isset($order->company)? $order->company->name:''}}" class="form-control choose_company" placeholder="选择经营单位" data-parsley-required data-parsley-trigger="change" {{ $disabled }} readonly/>
                    <input type="hidden" name="company_id" value="{{ isset($order) ? $order->company_id : '' }}">
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
          <div class="form-group">
            <label for="exchange_method" class="col-sm-4 control-label">收汇方式</label>
            <div class="col-sm-8">
                <select id="exchange_method" class="form-control" data-parsley-required data-parsley-trigger="change" name="exchange_method" {{ $disabled }}>
                    <option value="0" {{ (isset($order) && $order->exchange_method==0) ? 'selected="selected"' : '' }}>汇款(如T/T)</option>
                    <option value="1" {{ (isset($order) && $order->exchange_method==1) ? 'selected="selected"' : '' }}>托收(D/P，D/A)</option>
                    <option value="2" {{ (isset($order) && $order->exchange_method==2) ? 'selected="selected"' : '' }}>信用证(L/C)</option>
                    <option value="3" {{ (isset($order) && $order->exchange_method==3) ? 'selected="selected"' : '' }}>赊销(OA)</option>
                </select>
            </div>
          </div>
        </div>
        <div class="col-sm-4 m-b lc_no_div" style="display: none">
          <div class="form-group">
            <label for="lc_no" class="col-sm-4 control-label">信用证号</label>
            <div class="col-sm-8">
                <input data-parsley-trigger="change" type="text" value="{{ isset($order) ? $order->lc_no : ''}}" id="lc_no" name="lc_no" class="form-control" {{ $disabled }}/>
            </div>
          </div>
        </div>
        @include('admin.common.dataselect', ['label'=>'离境口岸', 'var'=>'shipment_port', 'obj'=> isset($order) ? $order : '', 'col'=>'4'])
        @include('admin.common.dataselect', ['label'=>'报关方式', 'var'=>'declare_mode', 'obj'=> isset($order) ? $order : ''])
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="broker_name" class="col-sm-4 control-label">报关行名称</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($order) ? $order->broker_name : ''}}" id="broker_name" name="broker_name" class="form-control" {{ $disabled }}/>
                </div>
            </div>
        </div>
        @include('admin.common.dataselect', ['label'=>'业务类型', 'var'=>'business', 'obj'=> isset($order) ? $order : ''])
        <div class="col-sm-8 contract-con m-b">
            <div class="form-group">
                <label class="col-sm-2 necessary_front control-label">合同号</label>
                <div class="col-sm-4">
                    <input type="text" value="{{ isset($order) ? $order->ordnumber : '' }}" id="ordnumber" name="ordnumber" class="form-control" data-parsley-required data-parsley-trigger="change" {{ $disabled }}/>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>
        {{--2.产品及开票人信息--}}
        <div class="m-t-xs m-l-sm" style="line-height: 19px;">
            <b class="badge bg-danger">2</b>
            <label class=" h5 text-danger m-l-xs">产品及开票人信息</label>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="tdnumber" class="col-sm-4 control-label">货柜提单号</label>
                <div class="col-sm-8">
                    <input data-parsley-trigger="change" type="text" value="{{ isset($order) ? $order->tdnumber : ''}}" id="tdnumber" name="tdnumber" class="form-control" {{ $disabled }}/>
                </div>
            </div>
        </div>
        @include('admin.common.dataselect', ['label'=>'报关币种', 'var'=>'currency', 'obj'=> isset($order) ? $order : ''])
        @include('admin.common.dataselect', ['label'=>'价格条款', 'var'=>'price_clause', 'obj'=> isset($order) ? $order : ''])
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="transport_fee" class="col-sm-4 control-label">运费</label>
                <div class="col-sm-8">
                    <input data-parsley-trigger="change" type="text" value="{{ isset($order) ? $order->transport_fee : ''}}" id="transport_fee" name="transport_fee" class="form-control" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="insurance_fee" class="col-sm-4 control-label">保险费</label>
                <div class="col-sm-8">
                    <input data-parsley-trigger="change" type="text" value="{{ isset($order) ? $order->insurance_fee : ''}}" id="insurance_fee" name="insurance_fee" class="form-control" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="miscellaneous_fee" class="col-sm-4 control-label">杂费</label>
                <div class="col-sm-8">
                    <input data-parsley-trigger="change" type="text" value="{{ isset($order) ? $order->miscellaneous_fee : ''}}" id="miscellaneous_fee" name="miscellaneous_fee" class="form-control" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="shipping_mark" class="col-sm-4 control-label">唛头</label>
                <div class="col-sm-8">
                    <input data-parsley-trigger="change" type="text" value="{{ isset($order) ? $order->shipping_mark : ''}}" id="shipping_mark" name="shipping_mark" class="form-control" data-parsley-trigger="change" {{ $disabled }}/>
                </div>
            </div>
        </div>
        @include('admin.common.dataselect', ['label'=>'包装方式', 'var'=>'package', 'obj'=> isset($order) ? $order : ''])
        @include('admin.common.dataselect', ['label'=>'装柜方式', 'var'=>'loading_mode', 'obj'=> isset($order) ? $order : ''])
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label class="col-sm-4 necessary_front  control-label">贸易商</label>
                <div class="col-sm-8">
                    <input type="text" id="trader" value="{{ isset($order->trader) ? $order->trader->name : ''}}" class="form-control choose_trader" placeholder="选择贸易商" data-parsley-required data-parsley-trigger="change" {{ $disabled }} readonly/>
                    <input type="hidden" name="trader_id" value="{{ isset($order->trader) ? $order->trader->id : ''}}">
                </div>
            </div>
        </div>
    </form>
    <div class="clearfix"></div>
    <div class="col-sm-4 m-b">
        <div class="form-group">
            <label class="col-sm-4 necessary_front control-label">出货产品清单</label>
            <div class="col-sm-8">
                <a href="javascript:;" class="btn show_tbody btn-s-md btn-success"  data-before="customer_id" {{ $disabled }}>选择需出货的产品</a>
            </div>
        </div>
    </div>
    <form class="pro_form">
        <div class="col-sm-12 m-b product_list_div">
            <div class="form-group">
                <div class="table-responsive panel" style="overflow-x:auto;margin:0 15px;">
                    <div class="otbody">
                    {{--新增不显示，修改&查看&审核显示--}}
                    @if (isset($order) && null !== $order->drawerProducts)
                        @foreach($order->drawerProducts as $key => $drawerProducts)
                            <section class="am-panel am-panel-default">
                                <header class="am-panel-hd">
                                    <h5 class="am-panel-title">产品名称<input class="name" id="name" value="{{isset($drawerProducts->product->name) ? $drawerProducts->product->name : ''}}({{isset($drawerProducts->drawer->company) ? $drawerProducts->drawer->company : ''}})" style="width:55%" readonly></h5>
                                    <input type="hidden" name="company" value="{{isset($drawerProducts->drawer->company) ? $drawerProducts->drawer->company : ''}}">
                                    <input type="hidden" class="drawer_product_order_id" value="{{isset($drawerProducts->pivot->id) ? $drawerProducts->pivot->id : ''}}">
                                </header>
                                <div class="pro_sec">
                                  <ul class="clearfix" style="min-width: 1000px;">
                                      <li>
                                        <div>
                                          <label>产品分类：</label>
                                          <input value="{{isset($drawerProducts->product->brand) ? $drawerProducts->product->brand->classify_str : ''}}" disabled>
                                          <input type="hidden" name="drawer_product_id" id="drawer_product_id" value="{{$drawerProducts->id}}">
                                        </div>
                                      </li>
                                      <li>
                                        <div>
                                          <label>品牌类型：</label>
                                          <input id="type_str" value="{{isset($drawerProducts->product->brand) ? $drawerProducts->product->brand->type_str : ''}}" disabled>
                                        </div>
                                      </li>
                                      <li class="last_li">
                                        <div>
                                          <label><small class="red">*</small>英文品名：</label>
                                          <input id="en_name" value="{{isset($drawerProducts->product->en_name) ? $drawerProducts->product->en_name : ''}}" disabled>
                                        </div>
                                      </li>
                                      <li>
                                        <div>
                                          <label><small class="red">*</small>境内货源地：</label>
                                          <input placeholder="请填写境内货源地" data-parsley-required data-parsley-trigger="change" class="domestic_source_con" name="domestic_source" id="domestic_source" value="{{$drawerProducts->pivot->domestic_source}}" style="width:70%" readonly {{$disabled}}>
                                          <input type="hidden" name="domestic_source_id" value="{{$drawerProducts->pivot->domestic_source_id}}">
                                        </div>
                                      </li>
                                      <li>
                                        <div>
                                          <label>产地：</label>
                                          <input placeholder="请填写产地" name="production_place" id="production_place" value="{{$drawerProducts->pivot->production_place}}" {{$disabled}}>
                                        </div>
                                      </li>
                                      <li  class="last_li">
                                        <div>
                                          <label><small class="red">*</small>原产国(地区)：</label>
                                          <input placeholder="请填写原产国" data-parsley-required data-parsley-trigger="change" name="origin_country" id="origin_country" class="country_openCon" value="{{$drawerProducts->pivot->origin_country}}" readonly {{$disabled}}>
                                          <input type="hidden" name="origin_country_id" value="{{$drawerProducts->pivot->origin_country_id}}">
                                        </div>
                                      </li>
                                      <li>
                                        <div>
                                          <label><small class="red">*</small>最终目的国(地区)： </label>
                                          <input placeholder="请填写最终目的国" data-parsley-required data-parsley-trigger="change" name="destination_country" id="destination_country" class="country_openCon" value="{{$drawerProducts->pivot->destination_country}}" readonly {{$disabled}}>
                                          <input type="hidden" name="destination_country_id" value="{{$drawerProducts->pivot->destination_country_id}}">
                                        </div>
                                      </li>
                                      <li>
                                        <div>
                                          <label><small class="red">*</small>货物属性：</label>
                                          <select class="removeClass" name="goods_attribute" data-parsley-required data-parsley-trigger="change" id="goods_attribute" {{$disabled}}>
                                            <option value="0" @if ($drawerProducts->pivot->goods_attribute == 0)selected="selected"@endif>自产</option>
                                            <option value="1" @if ($drawerProducts->pivot->goods_attribute == 1)selected="selected"@endif>委托加工</option>
                                            <option value="2" @if ($drawerProducts->pivot->goods_attribute == 2)selected="selected"@endif>外购</option>
                                          </select>
                                        </div>
                                      </li>
                                      <li class="last_li">
                                        <div>
                                          <label><small class="red">*</small>HSCode：</label>
                                          <input placeholder="请填写HSCode" value="{{isset($drawerProducts->product->hscode) ? $drawerProducts->product->hscode : ''}}" disabled>
                                        </div>
                                      </li>
                                      <li>
                                        <div>
                                          <label>CIQ编码：</label>
                                          <input name="ciq_code" id="ciq_code" value="{{$drawerProducts->pivot->ciq_code}}" {{$disabled}}>
                                        </div>
                                      </li>
                                      <li>
                                        <div>
                                          <label>退税率：</label>
                                            <input name="tax_refund_rate" id="tax_refund_rate" data-parsley-required data-parsley-trigger="change" value="{{isset($drawerProducts->pivot->tax_refund_rate) ? $drawerProducts->pivot->tax_refund_rate : ''}}" disabled>
                                        </div>
                                      </li>
                                      <li class="last_li">
                                        <div>
                                          <label>税率：</label>
                                            <input name="tax_rate" id="tax_rate" data-parsley-required data-parsley-trigger="change" value="{{isset($drawerProducts->pivot->tax_rate) ? $drawerProducts->pivot->tax_rate : ''}}" disabled>
                                        </div>
                                      </li>
                                      <li>
                                        <div>
                                          <label>种类：</label>
                                          <input placeholder="请填写种类" name="species" id="species" value="{{$drawerProducts->pivot->species}}" {{$disabled}}>
                                        </div>
                                      </li>
                                      <li>
                                        <div>
                                          <label>表面材质：</label>
                                          <input placeholder="请填写表面材质" name="surface_material" id="surface_material" value="{{$drawerProducts->pivot->surface_material}}" {{$disabled}}>
                                        </div>
                                      </li>
                                      <li class="last_li">
                                        <div>
                                          <label>款号：</label>
                                          <input placeholder="请填写款号" name="section_number" id="section_number" value="{{$drawerProducts->pivot->section_number}}" {{$disabled}}>
                                        </div>
                                      </li>
                                      <li class="last_li" style="width:100%">
                                        <div>
                                          <label>出口货物在最终目的国（地区）是否享受优惠关税：</label>
                                          <select class="removeClass" name="enjoy_offer" id="enjoy_offer" {{$disabled}}>
                                            <option value="0" @if ($drawerProducts->pivot->enjoy_offer == 0)selected="selected"@endif>享受</option>
                                            <option value="1" @if ($drawerProducts->pivot->enjoy_offer == 1)selected="selected"@endif>不享受</option>
                                            <option value="2" @if ($drawerProducts->pivot->enjoy_offer == 2)selected="selected"@endif>不能确定</option>
                                          </select>
                                        </div>
                                      </li>
                                      <li>
                                        <div>
                                          <label><small class="red">*</small>最大包装件数：</label>
                                          <input placeholder="请填写最大包装件数" name="pack_number" data-parsley-required data-parsley-trigger="change" id="pack_number" class="pack_number" value="{{isset($drawerProducts->pivot->pack_number) ? $drawerProducts->pivot->pack_number : ''}}" {{$disabled}}>
                                        </div>
                                      </li>
                                      <li>
                                        <div>
                                          <label> <small class="red">*</small>净重(KG)：</label>
                                          <input placeholder="请填写净重(KG)" data-parsley-required data-parsley-trigger="change" name="net_weight" id="net_weight" class="net_weight" value="{{isset($drawerProducts->pivot->net_weight) ? $drawerProducts->pivot->net_weight : ''}}" {{$disabled}}>
                                        </div>
                                      </li>
                                      <li class="last_li">
                                        <div>
                                          <label> <small class="red">*</small>毛重(KG)：</label>
                                          <input placeholder="请填写总毛重(KG)" data-parsley-required data-parsley-trigger="change" name="total_weight" id="weight" class="weight" value="{{isset($drawerProducts->pivot->total_weight) ? $drawerProducts->pivot->total_weight : ''}}" {{$disabled}}>
                                        </div>
                                      </li>
                                      <li>
                                        <div>
                                          <label> <small class="red">*</small>体积(CBM)：</label>
                                          <input placeholder="请填写体积(CBM)" data-parsley-required data-parsley-trigger="change" name="volume" id="volume" class="volume" value="{{isset($drawerProducts->pivot->volume) ? $drawerProducts->pivot->volume : ''}}" {{$disabled}}>
                                        </div>
                                      </li>
                                      <li>
                                        <div>
                                          <label> <small class="red">*</small>产品数量：</label>
                                          <input placeholder="请填写产品数量" data-parsley-required data-parsley-trigger="change" name="number" id="number" class="number" value="{{isset($drawerProducts->pivot->number) ? $drawerProducts->pivot->number : ''}}" {{$disabled}}>
                                        </div>
                                      </li>
                                      <li class="last_li">
                                          <div>
                                              <label> <small class="red">*</small>报关金额：</label>
                                              <input placeholder="请填写报关金额" data-parsley-required data-parsley-trigger="change" name="total_price" id="total_price" class="total_price" value="{{isset($drawerProducts->pivot->total_price) ? $drawerProducts->pivot->total_price : ''}}" {{$disabled}}>
                                          </div>
                                      </li>
                                      <li>
                                        <div>
                                          <label>单价：</label>
                                          <input class="single_price" name="single_price" id="single_price" value="{{isset($drawerProducts->pivot->single_price) ? $drawerProducts->pivot->single_price : ''}}" disabled>
                                        </div>
                                      </li>
                                      <li>
                                        <div>
                                          <label>单位：</label>
                                          <input placeholder="请填写单位" name="unit" id="unit" value="{{isset($drawerProducts->pivot->unit) ? $drawerProducts->pivot->unit : ''}}" {{$disabled}}>
                                        </div>
                                      </li>

                                      <li class="last_li">
                                        <div>
                                          <label><small class="red">*</small>开票金额(RMB)：</label>
                                          <input placeholder="开票金额(RMB)" data-parsley-required data-parsley-trigger="change" name="value" id="value" class="value" value="{{isset($drawerProducts->pivot->value) ? $drawerProducts->pivot->value : ''}}">
                                        </div>
                                      </li>
                                      <li>
                                        <div>
                                          <label><small class="red">*</small>申报单位：</label>
                                          <input type="text" placeholder="请填写申报单位" data-parsley-required data-parsley-trigger="change" class="unit_openCon" name="measure_unit_cn" id="measure_unit_cn" value="{{isset($drawerProducts->pivot->measure_unit_cn) ? $drawerProducts->pivot->measure_unit_cn : ''}}"  readonly {{$disabled}} />
                                          <input type="hidden" name="measure_unit" value="{{isset($drawerProducts->pivot->measure_unit) ? $drawerProducts->pivot->measure_unit : ''}}" {{$disabled}}>
                                        </div>
                                      </li>
                                      <li>
                                        <div style="min-width:400px">
                                          <label><small class="red">*</small>法定数量和单位：</label>
                                          <input placeholder="请填写数量" data-parsley-required name="default_num" id="default_num" value="{{$drawerProducts->pivot->default_num}}" style="width:100px" {{$disabled}}>
                                          <input id="default_unit" class="unit_openCon" data-parsley-required name="default_unit" placeholder="请选择法定单位" value="{{isset($drawerProducts->pivot->default_unit) ? $drawerProducts->pivot->default_unit : ''}}" style="width:100px" readonly {{$disabled}}>
                                          <input type="hidden" name="default_unit_id" value="{{isset($drawerProducts->pivot->default_unit_id) ? $drawerProducts->pivot->default_unit_id : ''}}" {{$disabled}}>
                                        </div>
                                      </li>
                                      <li class="last_li">
                                        <div>
                                          <label>合并分组：</label>
                                          <input class="merge" name="merge" value="{{isset($drawerProducts->pivot->merge) ? $drawerProducts->pivot->merge : ''}}" onkeyup="value=value.replace(/[^\d]/g,'')" onblur="value=value.replace(/[^\d]/g,'')" {{$disabled}}>
                                        </div>
                                      </li>
                                      <li class="last_li" style="width:100%">
                                        <div>
                                          <label>申报要素：</label>
                                          <input class="standard" name="standard" value="{{isset($drawerProducts->product->standard) ? $drawerProducts->product->standard : ''}}" style="width:38%" readonly {{$disabled}}/>
                                        </div>
                                      </li>
                                      <li style="width:100%;border-bottom: none">
                                        <div style="float: right">
                                        <a type="button" class="am-btn am-btn-default am-btn-xs kpr_del" {{$disabled}}>删除</a>
                                        </div>
                                      </li>
                                  </ul>
                              </div>
                            </section>
                        @endforeach
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label class="col-sm-4 necessary_front control-label">产品总数量</label>
                <div class="col-sm-8">
                    <input type="text" value="{{isset($order) ? $order->total_num : ''}}" id="total_num" class="form-control total_num" placeholder="根据产品信息计算得出" readonly/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label class="col-sm-4 necessary_front control-label">总体积</label>
                <div class="col-sm-8">
                    <input type="text" value="{{isset($order) ? $order->total_volume : ''}}" class="form-control total_volume" placeholder="根据产品信息计算得出" readonly/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label class="col-sm-4 necessary_front control-label">总货值</label>
                <div class="col-sm-8">
                    <input type="text" value="{{isset($order) ? $order->total_value : ''}}" class="form-control total_value" placeholder="根据产品信息计算得出" readonly/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label class="col-sm-4 necessary_front control-label">总毛重</label>
                <div class="col-sm-8">
                    <input type="text" value="{{isset($order) ? $order->total_weight : ''}}" class="form-control total_weight" placeholder="根据产品信息计算得出" readonly/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label class="col-sm-4 necessary_front control-label">总净重</label>
                <div class="col-sm-8">
                    <input type="text" value="{{isset($order) ? $order->total_net_weight : ''}}" class="form-control total_net_weight" placeholder="根据产品信息计算得出" readonly/>
                </div>
            </div>
        </div>
    <form class="three_form">
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label class="col-sm-4 necessary_front control-label">运输包装件数</label>
                <div class="col-sm-8">
                    <input type="text" value="{{isset($order) ? $order->total_packnum : ''}}" class="form-control total_packnum" placeholder="根据产品最大包装件数计算得出" readonly/>
                </div>
            </div>
        </div>
        @include('admin.common.dataselect', ['label'=>'整体包装方式', 'var'=>'order_package', 'obj'=> isset($order) ? $order : '', 'col'=>'4'])

        <div class="clearfix"></div>
        {{--3.报关信息--}}
        <div class="m-t-xs m-l-sm" style="line-height: 19px;">
            <b class="badge bg-danger">3</b>
            <label class=" h5 text-danger m-l-xs">报关信息</label>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="shipping_at" class="col-sm-4 control-label">预计出货日期</label>
                <div class="col-sm-8">
                    <input type="text" onclick="laydate()"  value="{{ isset($order) ? $order->shipping_at : ''}}" name="shipping_at" id="shipping_at" class="form-control" readonly {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="packing_at" class="col-sm-4 control-label">装箱时间</label>
                <div class="col-sm-8">
                    <input type="text" onclick="laydate()"  value="{{ isset($order) ? $order->packing_at : ''}}" name="packing_at" id="packing_at" class="form-control" readonly {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="customs_at" class="col-sm-4 control-label necessary_front">报关日期</label>
                <div class="col-sm-8">
                    <input type="text" onclick="laydate()"  value="{{ isset($order) ? $order->customs_at : ''}}" name="customs_at" id="customs_at" class="form-control" data-parsley-required data-parsley-trigger="change" readonly {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="sailing_at" class="col-sm-4 control-label necessary_front">开船日期</label>
                <div class="col-sm-8">
                    <input type="text" onclick="laydate()" data-parsley-required data-parsley-trigger="change" value="{{ isset($order) ? $order->sailing_at : ''}}" name="sailing_at" id="sailing_at" class="form-control" readonly {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="trade_country-group">
                <label for="trade_country" class="col-sm-4 control-label">贸易国(地区)</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($order->tradecountry) ? $order->tradecountry->country_na : ''}}" class="form-control country_openCon" data-parsley-required data-parsley-trigger="change" readonly {{ $disabled }} />
                    <input type="hidden" class="trade_country_id" name="trade_country" value="{{ isset($order->trade_country) ? $order->trade_country : ''}}" />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="aim_country" class="col-sm-4 control-label">运抵国(地区)</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($order->country->country_na) ? $order->country->country_na : ''}}" class="form-control country_openCon" data-parsley-required data-parsley-trigger="change" readonly {{ $disabled }} />
                    <input type="hidden" class="aim_country" name="aim_country" value="{{ isset($order->aim_country) ? $order->aim_country : ''}}" />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="unloading_port" class="col-sm-4 control-label necessary_front">指运港</label>
                <div class="col-sm-8">
                    <input id="unloading_port_name" class="form-control" value="{{ isset($order->unloadingport->port_c_cod) ? $order->unloadingport->port_c_cod : ''}}" onclick="dataLayer(14,'指运港',$(this))" data-parsley-required="" data-parsley-trigger="change" readonly {{ $disabled }}>
                    <input type="hidden" id="unloading_port" name="unloading_port" value="{{ isset($order) ? $order->unloading_port : ''}}">
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="white_card" class="col-sm-4 control-label">白卡号</label>
                <div class="col-sm-8">
                    <input data-parsley-trigger="change" type="text" value="{{ isset($order) ? $order->white_card : ''}}" id="white_card" name="white_card" class="form-control" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="plate_number" class="col-sm-4 control-label">车牌号</label>
                <div class="col-sm-8">
                    <input data-parsley-trigger="change" type="text" value="{{ isset($order) ? $order->plate_number : ''}}" id="plate_number" name="plate_number" class="form-control" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="box_number" class="col-sm-4 control-label necessary_front">货柜箱号</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($order) ? $order->box_number : ''}}" id="box_number" name="box_number" class="form-control" data-parsley-required="" data-parsley-trigger="change" {{ $disabled }}/>
                    <select id="unit_array" class="form-control" type="hidden" style="display: none">
                        @foreach($unit as $key=>$value)
                            <option value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="ship_name" class="col-sm-4 control-label necessary_front">船名</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($order) ? $order->ship_name : ''}}" id="ship_name" name="ship_name" class="form-control" data-parsley-required="" data-parsley-trigger="change" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="box_type" class="col-sm-4 control-label">货柜箱型</label>
                <div class="col-sm-8">
                    <input data-parsley-trigger="change" type="text" value="{{ isset($order) ? $order->box_type : ''}}" id="box_type" name="box_type" class="form-control" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="clearance_mode" class="col-sm-4 control-label">报关形式</label>
                <div class="col-sm-8">
                    <input type="text" id="clearance_mode" value="一般贸易" class="form-control" readonly/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="broker_number" class="col-sm-4 control-label">报关行代码(10位)</label>
                <div class="col-sm-8">
                    <input type="text"  value="{{ isset($order) ? $order->broker_number : ''}}" id="broker_number" name="broker_number" class="form-control" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="is_special" class="col-sm-4 control-label">特殊关系确认</label>
                <div class="col-sm-8">
                    {{ Form::select('is_special', ['否','是'], isset($order) ? $order->is_special : '', [
                        'id' => 'is_special',
                        'class' => 'form-control',
                        'data-parsley-required',
                        'data-parsley-trigger' => 'change',
                        'disabled'=> $disabled,
                    ]) }}
                </div>
            </div>
        </div>
        @include('admin.common.dataselect', ['label'=>'运输方式', 'var'=>'transport', 'obj'=> isset($order) ? $order : '', 'col'=>'4'])
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="is_pay_special" class="col-sm-4 control-label">支付特许权使用费确认</label>
                <div class="col-sm-8">
                    {{ Form::select('is_pay_special', ['否','是'], isset($order) ? $order->is_pay_special : '', [
                        'id' => 'is_pay_special',
                        'class' => 'form-control',
                        'data-parsley-required',
                        'data-parsley-trigger' => 'change',
                        'disabled'=> $disabled,
                    ]) }}
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b">
            <div class="form-group">
                <label for="operator" class="col-sm-4 control-label ">操作人</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($order) ? $order->operator : '' }}" id="" name="operator" class="form-control" {{ $disabled }}/>
                </div>
            </div>
        </div>
        @if (isset($order) && ($order->status == 3 || $order->status == 5))
        @include('admin.common.dataselect', ['label'=>'出境关别', 'var'=>'clearance_port', 'obj'=> isset($order) ? $order : '', 'col'=>'4', 'disabled'=> false])
        <!-- 报关单号 -->
        <div class="col-sm-4 m-b-xs">
            <div class="form-group">
                <label for="customs_number" class="col-sm-4  necessary_front control-label">报关单号</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($order) ? $order->customs_number : ''}}" id="customs_number" name="customs_number" class="form-control" data-parsley-required="true" data-parsley-trigger="change" />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-b-xs">
            <div class="form-group">
                <label for="export_date" class="col-sm-4  necessary_front control-label">出口日期</label>
                <div class="col-sm-8">
                    <input type="text" onclick="laydate()" value="{{ isset($order) ? $order->export_date : ''}}" id="export_date" name="export_date" class="form-control" data-parsley-required data-parsley-trigger="change" placeholder="请选择日期" readonly/>
                </div>
            </div>
        </div>
        @endif
    </form>
        <div class="clearfix"></div>
    <form class="approve_form">
        @include('admin.common.approve', ['obj'=> isset($order) ? $order : ''])
    </form>
</div>
<script>
    $(function(){
        // $(document).on('keyup','#number',function () {
        //     var re=/[^0-9-]+/;//只允许输入数字
        //     if(re.test(this.value)){
        //       this.value=this.value.substr(0,this.value.length-1);//将最后输入的字符去除
        //     }
        // })
        $('#clearance_port').select2()
        $('#shipment_port').select2()
        $('#transport').select2()
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
        // $('.layui-layer-content').css("height","590px");
        $(document).on('change','#exchange_method',function () {
            var exchange_method = $('#exchange_method').val()
            if(exchange_method == 2){
                $('.lc_no_div').show()
            } else {
                $('.lc_no_div').hide()
            }
        })
        $(document).off('click','.choose_Cus')
        $(document).on('click','.choose_Cus',function () {  //选择客户
            var outerTitle = '选择客户'
                api = '/admin/customer/customer_choose';
            $.ajax({
                url: api,
                type: 'get',
                data: {
                    action:'html'
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
                            $('.choose_Cus').val(cusname)
                            $('#customer_id').val(id)
                            layer.close(i)
                        }
                    })
                }
            })
        })

        $('.choose_company').on('click',function(){  //选择公司
            var th=$(this),
                outerTitle = '选择公司'
                api = '/admin/company/company_choose';
            $.ajax({
                url: api,
                type: 'get',
                data: {
                    action:'html'
                },
                success: function (str) {
                    if(str.code == 403){
                        layer.msg(str.msg)
                        return false
                    }
                    layer.open({
                        type: 1,
                        title: outerTitle,
                        area: '600px',
                        offset: 'top',
                        content: str,
                        btn: ['确定'],
                        yes: function (i,layero) {
                            let id = layero.find('.bg-active').data('id')
                                name = layero.find('.bg-active').data('name')
                            th.val(name)
                            th.parent().find('input[type=hidden]').val(id)
                            layer.close(i)
                        }
                    })
                }
            })
        })
        $('.choose_trader').on('click',function(){ //选择贸易商
            var th=$(this),
                outerTitle = '选择贸易商'
                api = '/admin/trader/trader_choose';
            $.ajax({
                url: api,
                type: 'get',
                data: {
                    action:'html'
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
                                name = layero.find('.bg-active').data('name')
                            th.val(name)
                            th.parent().find('input[type=hidden]').val(id)
                            layer.close(i)
                        },
						success: function(layero,i){
							$(document).off('dblclick', '.bnoteTbody tr')
							$(document).on('dblclick', '.bnoteTbody tr', function () {
							    $(this).addClass('bg-active').siblings().removeClass('bg-active');
								layero.find('.layui-layer-btn0').trigger('click');
							    // layer.close(i)
							})
						}
                    })
                }
            })
        })

        $(document).off('click','.domestic_source_con')
        $(document).on('click','.domestic_source_con',function () {  //选择境内货源地
            var th=$(this),
                outerTitle = '选择境内货源地'
                api = '/admin/district/list';
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
                                name = layero.find('.bg-active').data('name')
                            th.val(name)
                            th.parent().find('input[type=hidden]').val(id)
                            layer.close(i)
                        },
						success: function(layero,i){
							$(document).off('dblclick', '.bnoteTbody tr')
							$(document).on('dblclick', '.bnoteTbody tr', function () {
							    $(this).addClass('bg-active').siblings().removeClass('bg-active');
								layero.find('.layui-layer-btn0').trigger('click');
							    // layer.close(i)
							})
						}
                    });
                }
            })
        })
        // 国家 单位
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
                                $('input[name=destination_country]').parent().find('input[type=hidden]').val(id)
                            } else {
                                th.val(cusname)
                                th.parent().find('input[type=hidden]').val(id)
                            }
                            layer.close(i)
                        },
						success: function(layero,i){
							$(document).off('dblclick', '.bnoteTbody tr')
							$(document).on('dblclick', '.bnoteTbody tr', function () {
							    $(this).addClass('bg-active').siblings().removeClass('bg-active');
								layero.find('.layui-layer-btn0').trigger('click');
							    // layer.close(i)
							})
						}
                    })
                }
            })
        })
        $(document).off('click','.unit_openCon')
        $(document).on('click','.unit_openCon',function () {
            //选择单位
            var th=$(this),
                outerTitle = '选择单位'
                api = '/admin/unit/list';
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
                            th.val(cusname)
                            th.parent().find('input[type=hidden]').val(id)
                            layer.close(i)
                        },
						success: function(layero,i){
							$(document).off('dblclick', '.bnoteTbody tr')
							$(document).on('dblclick', '.bnoteTbody tr', function () {
							    $(this).addClass('bg-active').siblings().removeClass('bg-active');
								layero.find('.layui-layer-btn0').trigger('click');
							    // layer.close(i)
							})
						}
                    })
                }
            })
        })

        $(document).off('click','.show_tbody')
        $(document).on('click','.show_tbody',function () { //点击产品弹框
            var ele,
                outerTitle = $(this).parent().prev().text()
                customer_id = $('[name=customer_id]').val()
                follow_id = $(this).data().before
            if (!customer_id) {
                layer.msg('请先选择客户！')
                return false;
            } else {
                var api = '/admin/drawer_products/'+ customer_id;
            }
            $.ajax({
                url: api,
                type: 'get',
                data: {
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
                        offset: 'top',
                        content: str,
                        btn: ['确定'],
                        yes: function (i,layero) {
                            let pro_tr = layero.find('.bnoteTbody input:checked').closest('tr')
                                pro_arr = []  //选中产品数组
                                count = 0  //循环次数判断
                            $.each(pro_tr,function(j,item){
                                pro_arr.push({drawer_product_id:$(this).data('id'),product_id:$(this).data('productid')})
                            })
                            // drawer_product_id = pro_tr.data('id')
                            // product_id = pro_tr.data('productid')
                            $.each(pro_arr,function(j,item){
                                $.ajax({
                                    url: '/admin/drawer_products/detail/' + item.drawer_product_id,
                                    type: 'get',
                                    dataType: 'json',
                                    success: function (res) {
                                        console.log(res);
                                        if(res.code == 200){
                                            data = res.data
                                            let obj = {
                                                drawer_product_id: item.drawer_product_id,
                                                domestic_source_id:data.domestic_source_id||'',
                                                domestic_source:data.domestic_source||'',
                                                product_id: item.product_id,
                                                name : data.name,
                                                company: data.company,
                                                origin_country_id:data.origin_country_id||'',
                                                origin_country:data.origin_country||'',
                                                default_unit_id:data.default_unit_id||'',
                                                default_unit:data.default_unit||'',
                                                pro_type : data.brand_classify_str,
                                                type_str : data.brand_type_str,
                                                en_name : data.en_name,
                                                hscode : data.hscode,
                                                tax_refund_rate : data.tax_refund_rate,
                                                tax_rate : data.tax_rate,
                                                standard: data.standard,
                                                measure_unit_cn : data.measure_unit_cn,
                                                measure_unit : data.measure_unit,
                                                source : data.source,
                                                unit : data.unit
                                            }
                                            let val = $('.product_list_div .otbody').find("#destination_country").val()
                                            if (val == undefined || val == null) {
                                                val = ''
                                            }
                                            // console.log(val);
                                            getDiv(obj,val)
                                          //  getMeasureUnit('/admin/data/father/15','.measure_unit_select',1)
                                            count++
                                            $('.product_list_div .otbody').append(html)
                                           // getMeasureUnit('/admin/country/list','.country_openCon',2)
                                            if(count == pro_arr.length) {
                                                layer.msg('产品添加完成')
                                                layer.close(i)
                                            }
                                        }
                                    }
                                })
                            })
                            // layer.close(i)
                        }
                    })
                }
            })
        })
        //删除当前产品
        $(document).on('click','.kpr_del',function () {
            $(this).parents('section').remove()
        })

        $(document).on('keyup', '.pro_sec input.number', function () {
            // 报关金额 = 数量 * 单价
            var a = $(this).val() * 1
            var total_price = $(this).parents('ul').find('input.total_price').val() * 1
            var c = (total_price / a).toFixed(4)
            $(this).parents('ul').find('input.single_price').val(c)
            // 产品数量 = 数量相加
            sum('number', '.total_num', 4)
            sum('total_price', '.total_value', 4)
          }).on('keyup', '.pro_sec input.single_price', function () {
            // 报关金额 = 单价 * 数量
            var a = $(this).val() * 1
            var number = $(this).parents('ul').find('input.number').val() * 1
            var c = (a.toFixed(4) * number.toFixed(4)).toFixed(4)
            $(this).parents('ul').find('input.total_price').val(c)
            sum('total_price', '.total_value', 4)
          }).on('keyup', '.pro_sec input.total_price', function () {
            // 单价 = 报关金额 / 数量
            var a = $(this).val() * 1
            var number = $(this).parents('ul').find('input.number').val() * 1
            var c = (a.toFixed(4) / number.toFixed(4)).toFixed(4)
            sum('total_price', '.total_value', 4)
            $(this).parents('ul').find('input.single_price').val(c)
          }).on('keyup', '.pro_sec input.net_weight', function () {
            sum('net_weight', '.total_net_weight', 4)
          }).on('keyup', '.pro_sec input.volume', function () {
            sum('volume', '.total_volume', 4)
          }).on('keyup', '.pro_sec input.weight', function () {
            sum('weight', '.total_weight', 4)
          }).on('keyup', '.pro_sec input.pack_number', function () {
            sum('pack_number', '.total_packnum', 4)
          }).on('click', '.kpr_del', function () {
              $(this).parents('section').remove()
              $('#pack_number,#net_weight,#total_weight,#volume,#number,#total_price').trigger('keyup')
              sum('number', '.total_num', 4)
              sum('total_price', '.total_value', 4)
              sum('net_weight', '.total_net_weight', 4)
              sum('volume', '.total_volume', 4)
              sum('weight', '.total_weight', 4)
              sum('pack_number', '.total_packnum', 4)
        })
        //产品div
        function getDiv(obj,val) {
            let description_name = ''
                description_id = ''
            if($('.otbody section').length){
                description_name = $('.otbody section').eq(0).find('#destination_country').val()
                description_id = $('.otbody section').eq(0).find('[name=destination_country_id]').val()
            }
            html = `<section class="am-panel am-panel-default">
                        <header class="am-panel-hd">
                            <h5 class="am-panel-title">产品名称<input class="name" id="name" value="${obj.name}(${obj.company})" style="width:55%" readonly></h5>
                            <input type="hidden" name="company" value="${obj.company}">
                            <input type="hidden" class="drawer_product_order_id" value="{{isset($drawerProducts->pivot->id) ? $drawerProducts->pivot->id : ''}}">
                        </header>
                        <div class="pro_sec">
                          <ul class="clearfix" style="min-width: 1000px;">
                              <li>
                                <div>
                                  <label>产品分类：</label>
                                  <input value="${obj.pro_type}" disabled>
                                  <input type="hidden" name="drawer_product_id" id="drawer_product_id" value="${obj.drawer_product_id}">
                                </div>
                              </li>
                              <li>
                                <div>
                                  <label>品牌类型：</label>
                                  <input id="name" value="${obj.type_str}" disabled>
                                </div>
                              </li>
                              <li class="last_li">
                                <div>
                                  <label><small class="red">*</small>英文品名：</label>
                                  <input id="en_name" value="${obj.en_name}" disabled>
                                </div>
                              </li>
                              <li>
                                <div>
                                    <label><small class="red">*</small>境内货源地：</label>
                                    <input type="text" placeholder="请填写境内货源地" data-parsley-required data-parsley-trigger="change" class="domestic_source_con" name="domestic_source" value="${obj.domestic_source}" style="width:70%" readonly {{$disabled}} />
                                    <input type="hidden"  name="domestic_source_id" value="${obj.domestic_source_id}">
                                </div>
                              </li>
                              <li>
                                <div>
                                  <label>产地：</label>
                                  <input placeholder="请填写产地" name="production_place" id="production_place" value="">
                                </div>
                              </li>
                              <li  class="last_li">
                                <div>
                                  <label><small class="red">*</small>原产国(地区)：</label>
                                   <input type="text" placeholder="请填写原产国(地区)" data-parsley-required data-parsley-trigger="change" class="country_openCon" name="origin_country" id="origin_country" value="`+(obj.origin_country == '' ? '中国' : obj.origin_country)+`" readonly {{$disabled}} />
                                   <input type="hidden" name="origin_country_id" value="`+(obj.origin_country_id == '' ? '774' : obj.origin_country_id)+`">
                                </div>
                              </li>
                              <li>
                                <div>
                                  <label><small class="red">*</small>最终目的国(地区)： </label>
                                   <input type="text" placeholder="请填写最终目的国" data-parsley-required data-parsley-trigger="change"  class="country_openCon" name="destination_country" id="destination_country" value="${description_name}" readonly {{$disabled}} />
                                   <input type="hidden" name="destination_country_id" value="${description_id}">
                                </div>
                              </li>
                              <li>
                                <div>
                                  <label><small class="red">*</small>货物属性：</label>
                                  <select class="removeClass" name="goods_attribute" data-parsley-required data-parsley-trigger="change" id="goods_attribute">
                                    <option value="0">自产</option>
                                    <option value="1">委托加工</option>
                                    <option value="2">外购</option>
                                  </select>
                                </div>
                              </li>
                              <li class="last_li">
                                <div>
                                  <label><small class="red">*</small>HSCode：</label>
                                  <input placeholder="请填写HSCode" value="${obj.hscode}" disabled>
                                </div>
                              </li>
                              <li>
                                <div>
                                  <label>CIQ编码：</label>
                                  <input name="ciq_code" id="ciq_code" value="">
                                </div>
                              </li>
                              <li>
                                <div>
                                  <label>退税率：</label>
                                    <input name="tax_refund_rate" id="tax_refund_rate" data-parsley-required data-parsley-trigger="change" value="${obj.tax_refund_rate}" disabled>
                                </div>
                              </li>
                              <li class="last_li">
                                <div>
                                  <label>税率：</label>
                                    <input name="tax_rate" id="tax_rate" data-parsley-required data-parsley-trigger="change" value="${obj.tax_rate}" disabled>
                                </div>
                              </li>
                              <li>
                                <div>
                                  <label>种类：</label>
                                  <input placeholder="请填写种类" name="species" id="species" value="">
                                </div>
                              </li>
                              <li>
                                <div>
                                  <label>表面材质：</label>
                                  <input placeholder="请填写表面材质" name="surface_material" id="surface_material" value="">
                                </div>
                              </li>
                              <li class="last_li">
                                <div>
                                  <label>款号：</label>
                                  <input placeholder="请填写款号" name="section_number" id="section_number" value="">
                                </div>
                              </li>
                              <li class="last_li" style="width:100%">
                                <div>
                                  <label>出口货物在最终目的国（地区）是否享受优惠关税：</label>
                                  <select class="removeClass" name="enjoy_offer" id="enjoy_offer">
                                    <option value="0">享受</option>
                                    <option value="1">不享受</option>
                                    <option value="2">不能确定</option>
                                  </select>
                                </div>
                              </li>
                              <li>
                                <div>
                                  <label><small class="red">*</small>最大包装件数：</label>
                                  <input placeholder="请填写最大包装件数" name="pack_number" data-parsley-required data-parsley-trigger="change" id="pack_number" class="pack_number" value="">
                                </div>
                              </li>
                              <li>
                                <div>
                                  <label> <small class="red">*</small>净重(KG)：</label>
                                  <input placeholder="请填写净重(KG)" data-parsley-required data-parsley-trigger="change" name="net_weight" id="net_weight" class="net_weight" value="">
                                </div>
                              </li>
                              <li class="last_li">
                                <div>
                                  <label> <small class="red">*</small>毛重(KG)：</label>
                                  <input placeholder="请填写总毛重(KG)" data-parsley-required data-parsley-trigger="change" name="total_weight" id="weight" class="weight" value="">
                                </div>
                              </li>
                              <li>
                                <div>
                                  <label> <small class="red">*</small>体积(CBM)：</label>
                                  <input placeholder="请填写体积(CBM)" data-parsley-required data-parsley-trigger="change" name="volume" id="volume" class="volume" value="">
                                </div>
                              </li>
                              <li>
                                <div>
                                  <label> <small class="red">*</small>产品数量：</label>
                                  <input placeholder="请填写产品数量" data-parsley-required data-parsley-trigger="change" name="number" id="number" class="number" value="">
                                </div>
                              </li>
                              <li class="last_li">
                                  <div>
                                      <label> <small class="red">*</small>报关金额：</label>
                                      <input placeholder="请填写报关金额" data-parsley-required data-parsley-trigger="change" name="total_price" id="total_price" class="total_price" value="">
                                  </div>
                              </li>
                              <li>
                                <div>
                                  <label>单价：</label>
                                  <input class="single_price" name="single_price" id="single_price" value="" disabled>
                                </div>
                              </li>
                              <li>
                                <div>
                                  <label>单位：</label>
                                  <input placeholder="请填写单位" name="unit" id="unit" value="">
                                </div>
                              </li>

                              <li class="last_li">
                                <div>
                                  <label><small class="red">*</small>开票金额(RMB)：</label>
                                  <input placeholder="开票金额(RMB)" data-parsley-required data-parsley-trigger="change" name="value" id="value" class="value" value="">
                                </div>
                              </li>
                              <li>
                                <div>
                                  <label><small class="red">*</small>申报单位：</label>
                                   <input type="text" placeholder="请选择申报单位" data-parsley-required data-parsley-trigger="change"  class="unit_openCon" name="measure_unit_cn" id="measure_unit_cn" value="${obj.measure_unit_cn}" readonly />
                                   <input type="hidden" name="measure_unit" value="${obj.measure_unit}">
                                </div>
                              </li>
                              <li>
                                <div style="min-width:400px">
                                  <label><small class="red">*</small>法定数量和单位：</label>
                                   <input type="text" placeholder="请填写数量" data-parsley-required data-parsley-trigger="change" name="default_num" id="default_num" value="" style="width:100px" />
                                   <input type="text" placeholder="请选择单位" data-parsley-required data-parsley-trigger="change" class="unit_openCon" name="default_unit" id="default_unit" value="${obj.default_unit}" style="width:100px" readonly />
                                   <input type="hidden" name="default_unit_id" value="${obj.default_unit_id}">
                                </div>
                              </li>
                              <li class="last_li">
                                <div>
                                  <label>合并分组：</label>
                                  <input class="merge" name="merge" value="0" onkeyup="value=value.replace(/[^\\d]/g,'')" onblur="value=value.replace(/[^\\d]/g,'')" />
                                </div>
                              </li>
                              <li class="last_li" style="width:100%">
                                <div>
                                  <label>申报要素：</label>
                                  <input class="standard" name="standard" value="${obj.standard}" style="width:38%" readonly/>
                                </div>
                              </li>
                              <li style="width:100%;border-bottom: none">
                                <div style="float: right">
                                <a type="button" class="am-btn am-btn-default am-btn-xs kpr_del">删除</a>
                                </div>
                              </li>
                          </ul>
                      </div>
                    </section>`
        }

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
    });

    function sum(num, aim, dot) { //计算公式
        var total = 0
        $('.pro_sec input.' + num ).map(function (index, item) {
            item.value == '' ? (a = 0) : (a = item.value * 1)
            total = total + a
        })
        total = total.toFixed(dot)
        $(aim).val(total)
    }
    // 修改当前的单位和业务 ajax请求
    $('#sales_unit,#business').change(function(){
        var sales_unit= $('#sales_unit').val();
        var business=$('#business').val();
        if(sales_unit!=""||business!=""){
            var Url="/admin/"+"business"+"/"+"contract?"+"business"+"="+ business +"&sales_unit"+"="+sales_unit;
            $.get(Url, {}, function (str) {
                if(str==""){
                    $('.contract-con').find('.form-con').val('');
                }else{
                    $('.contract-con').find('.form-con').val(str);
                }
            })
        }
    })
    function dataLayer(id,name,th) {
        let title = '选择' + name
            $this = $(this)
            country = $('.aim_country').val()
        if(!country){
            layer.msg('请先选择运抵国')
            return false
        }
        let html = `<div class="choosePro panel-body">
            <div class="form-group" style="margin-left: 25px">
            </div>
            <div class="table-responsive panel">
                <table class="table table-hover b-t b-light" style="table-layout: fixed;">
                    <thead>
                    <tr class="info">
                        <th width="100%">${name}</th>
                    </tr>
                    </thead>
                    <tbody class="dataTbody">

                    </tbody>
                </table>
            </div>
        </div>`
        layer.open({
            type: 1,
            title: title,
            area:['500px','500px'],
            btn:['确定'],
            content: html,
            yes: function(index,layero){
                let id = layero.find('.bg-active').data('id')
                    name = layero.find('.bg-active').data('name')
                th.val(name)
                th.parent().find('input[type=hidden]').val(id)
                layer.close(index)
            },
            success: function (layero,index) {
                dataList(th,id)
                $(document).off('click', '.dataTbody tr')
                $(document).on('click', '.dataTbody tr', function () {
                    $(this).addClass('bg-active').siblings().removeClass('bg-active');
                })
                $(document).off('dblclick', '.dataTbody tr')
                $(document).on('dblclick','.dataTbody tr',function(){
                    $(this).addClass('bg-active').siblings().removeClass('bg-active');
                    let id = layero.find('.bg-active').data('id')
                        name = layero.find('.bg-active').data('name')
                    th.val(name)
                    th.parent().find('input[type=hidden]').val(id)
                    layer.close(index)
                    // $('.layui-layer-btn0').trigger('click');
                })
            }
        })
    }
    function dataList(th,id){
        var html = ''
            url =  ''
        if(id == 14){
            let aim = $('.aim_country').val()
            url = '/admin/order/harbor/' + aim
        } else {
            url =  '/admin/data/father/' + id
        }
        $.ajax({
            url: url,
            method: 'get',
            data:{},
            dataType: 'json',
            success: function (res) {
                $('.dataTbody').html("");
                if(id == 14){
                    $.each(res.ports,function (a,b) {
                        html += `<tr class="data_tr" data-name="${b.port_c_cod}" data-id="${b.id}">
                        <td class="">${b.port_c_cod}</td>
                        </tr>`;
                    })
                } else {
                    $.each(res,function (a,b) {
                        html += `<tr class="data_tr" data-name="${b}" data-id="${a}">
                        <td class="">${b}</td>
                        </tr>`;
                    })
                }
                $('.dataTbody').html(html);
            }
        })
    }
    // $('.aim_country_id').change(function(){
    //     var aim = $(this).val();
    //     if(aim = $(this).val() == ''){
    //         return false
    //     }
    //     console.log(aim);
    //     var Url = '/admin/order/harbor/';
    //     $.get(Url+aim, {}, function (str) {
    //         var unloading_port_id = $('#unloading_port_id').val();
    //         var th_opactive = '';
    //         $.each(str.ports,function(i,n){
    //             if(n.id == unloading_port_id){
    //               th_opactive+= "<option value='"+ n.id+"' selected='selected'>"+ n.port_c_cod +"</option>"
    //             }else{
    //               th_opactive+= "<option value='"+ n.id+"'>"+ n.port_c_cod +"</option>"
    //             }
    //         })
    //         $('#unloading_port').html('').append(th_opactive).select2();
    //     })
    // });
    // $('.aim_country_id').trigger('change');
</script>
