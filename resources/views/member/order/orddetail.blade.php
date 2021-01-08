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
    .pro_sec ul,
    .pro_sec li {
      margin: 0;
      padding: 0;
      list-style: none;
      ;
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
    .red {
      color: red;
    }
    .pro_sec input,
    .pro_sec select {
      border: none;
      height: 24px;
      line-height: 24px;
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
        height: auto;
        display: inline-block;
        text-indent: 0.5rem;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 12px;
    }
    span{font-size: 12px}
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
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label class="col-sm-4 control-label necessary_front" >客户</label>
                <div class="col-sm-8">
                    <input type="text" id="cusName" value="{{ isset($order) ? (isset($order->customer)?$order->customer->name:'') : (isset($customer) ?  $customer->name : '')}}" class="form-control" placeholder="选择客户" data-parsley-required data-parsley-trigger="change" disabled/>
                    <input type="hidden" name="customer_id" value="{{ isset($order) ? $order->customer_id : (isset($customer) ?  $customer->id : '') }}" disabled>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label class="col-sm-4 control-label necessary_front" >经营单位</label>
                <div class="col-sm-8">
                    <input type="text" id="companyName" value="{{ isset($order->company)? $order->company->name:''}}" class="form-control" placeholder="选择经营单位" data-parsley-required data-parsley-trigger="change" disabled/>
                    <input type="hidden" name="company_id" value="{{ isset($order) ? $order->company_id : '' }}" disabled>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
          <div class="form-group">
            <label for="exchange_method" class="col-sm-4 control-label">收汇方式</label>
            <div class="col-sm-8">
                <select id="exchange_method" class="form-control" data-parsley-required data-parsley-trigger="change" name="exchange_method" {{ $disabled }}>
                    <option value="0" {{ ($order->exchange_method==0) ? 'selected="selected"' : '' }}>汇款(如T/T)</option>
                    <option value="1" {{ ($order->exchange_method==1) ? 'selected="selected"' : '' }}>托收(D/P，D/A)</option>
                    <option value="2" {{ ($order->exchange_method==2) ? 'selected="selected"' : '' }}>信用证(L/C)</option>
                    <option value="3" {{ ($order->exchange_method==3) ? 'selected="selected"' : '' }}>赊销(OA)</option>
                </select>
            </div>
          </div>
        </div>
        @if(isset($order) && $order->exchange_method == 2)
        <div class="col-sm-4 m-t">
          <div class="form-group">
            <label for="lc_no" class="col-sm-4 control-label">信用证号</label>
            <div class="col-sm-8">
                <input data-parsley-trigger="change" type="text" value="{{ isset($order) ? $order->lc_no : ''}}" id="lc_no" name="lc_no" class="form-control" {{ $disabled }}/>
            </div>
          </div>
        </div>
        @endif
        @include('member.common.dataselect', ['label'=>'报关方式', 'var'=>'declare_mode', 'obj'=> isset($order) ? $order : ''])
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="broker_name" class="col-sm-4 control-label">报关行名称</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($order) ? $order->broker_name : ''}}" id="broker_name" name="broker_name" class="form-control" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="links" class="col-sm-4 necessary_front control-label">本单联系人</label>
                <div class="col-sm-8">
                    <input type="text" id="links" value="{{ isset($order->link->name) ? $order->link->name : '' }}" class="form-control" placeholder="选择联系人" data-select="links" data-target="[name=links_id]" data-outwidth="800px" data-parsley-required data-parsley-trigger="change" disabled/>
                    <input type="hidden" name="link_id" value="{{ isset($order) ? (isset($order->link) ? $order->link->id : '') : ''}}">
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="links_phone" class="col-sm-4 necessary_front control-label">本单联系人电话</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($order->link->phone) ? $order->link->phone : '' }}" id="links_phone"  class="form-control" disabled/>
                </div>
            </div>
        </div>
        <!-- @include('member.common.dataselect', ['label'=>'业务类型', 'var'=>'business', 'obj'=> isset($order) ? $order : '']) -->

        <div class="col-sm-4 m-t contract-con">
            <div class="form-group">
                <label class="col-sm-4 necessary_front control-label">合同号</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($order) ? $order->ordnumber : '' }}" data-parsley-type="number" id="number" disabled class="form-control"/>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>
        {{--2.产品及开票人信息--}}
        <div class="m-t-xs m-l-sm" style="line-height: 19px;">
            <b class="badge bg-danger">2</b>
            <label class=" h5 text-danger m-l-xs">产品及开票人信息</label>
        </div>

        @include('member.common.dataselect', ['label'=>'装柜方式', 'var'=>'loading_mode', 'obj'=> isset($order) ? $order : ''])
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="box_number" class="col-sm-4 control-label">货柜箱号</label>
                <div class="col-sm-8">
                    <input data-parsley-trigger="change" type="text" value="{{ isset($order) ? $order->box_number : ''}}" id="box_number" name="box_number" class="form-control" {{ $disabled }}/>
                    <select id="unit_array" class="form-control" type="hidden" style="display: none">
                        @foreach($unit as $key=>$value)
                            <option value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="tdnumber" class="col-sm-4 control-label">货柜提单号</label>
                <div class="col-sm-8">
                    <input data-parsley-trigger="change" type="text" value="{{ isset($order) ? $order->tdnumber : ''}}" id="tdnumber" name="tdnumber" class="form-control" {{ $disabled }} />
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="ship_name" class="col-sm-4 control-label">船名航次</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($order) ? $order->ship_name : ''}}" id="ship_name" name="ship_name" class="form-control" {{ $disabled }}/>
                </div>
            </div>
        </div>
        @include('member.common.dataselect', ['label'=>'报关币种', 'var'=>'currency', 'obj'=> isset($order) ? $order : ''])
        @include('member.common.dataselect', ['label'=>'价格条款', 'var'=>'price_clause', 'obj'=> isset($order) ? $order : ''])
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="ship_name" class="col-sm-4 control-label">唛头</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($order) ? $order->shipping_mark : ''}}" id="shipping_mark" name="shipping_mark" class="form-control" data-parsley-trigger="change" {{ $disabled }}/>
                </div>
            </div>
        </div>
        @include('member.common.dataselect', ['label'=>'贸易国（地区）', 'var'=>'trade_country', 'obj'=> isset($order) ? $order : '', 'col'=>'4'])
        @include('member.common.dataselect', ['label'=>'指抵国（地区）', 'var'=>'aim_country', 'obj'=> isset($order) ? $order : '', 'col'=>'4'])
        <div class="col-sm-4 m-t">
            <div class="form-group">
                <label for="unloading_port" class="col-sm-4 control-label necessary_front">指运港</label>
                <div class="col-sm-8">
                    <input type="hidden" id="unloading_port_id" value="{{ isset($order) ? $order->unloading_port : ''}}">
                    <select id="unloading_port" class="form-control" data-parsley-required data-parsley-trigger="change" name="unloading_port" data-value="{{isset($order->unloading_port)?$order->unloading_port:''}}"  {{ $disabled }}>
                    </select>
                </div>
            </div>
        </div>
        @include('member.common.dataselect', ['label'=>'运输方式', 'var'=>'transport', 'obj'=> isset($order) ? $order : '', 'col'=>'4'])
        @include('member.common.dataselect', ['label'=>'包装方式', 'var'=>'package', 'obj'=> isset($order) ? $order : ''])
        </form>
        <div class="clearfix"></div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label class="col-sm-4 necessary_front control-label">出货产品清单</label>
                <div class="col-sm-8">
                    <a href="javascript:;" class="btn show_tbody btn-s-md btn-success"  data-before="customer_id" data-follow="drawer" data-content=".otbody" data-outwidth="800px" {{ $disabled }}>选择需出货的产品</a>
                </div>
            </div>
        </div>
        <form class="pro_form">
        <div class="col-sm-12 m-t-xs product_list_div">
            <div class="form-group">
                <div class="table-responsive panel" style="overflow-x:auto;margin:0 15px;">
                    <div class="otbody" >
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
                                      <input type="hidden" name="drawer_product_id" value="{{$drawerProducts->id}}">
                                    </div>
                                  </li>
                                  <li>
                                    <div>
                                      <label>品牌类型：</label>
                                      <input id="name" value="{{isset($drawerProducts->product->brand) ? $drawerProducts->product->brand->type_str : ''}}" disabled>
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
                                      <input placeholder="请填写境内货源地" data-parsley-required data-parsley-trigger="change" name="domestic_source" id="domestic_source" class="domestic_source_con" value="{{$drawerProducts->pivot->domestic_source}}" style="width:70%" readonly {{$disabled}}>
                                      <input type="hidden"  name="domestic_source_id" value="{{$drawerProducts->pivot->domestic_source_id}}">
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
                                      <input placeholder="请填写原产国" data-parsley-required data-parsley-trigger="change" name="origin_country" id="origin_country" class="country_openCon" value="{{$drawerProducts->pivot->origin_country}}" class="country_openCon" readonly {{$disabled}}>
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
                                      <input placeholder="开票金额(RMB)" data-parsley-required data-parsley-trigger="change" name="value" id="value" class="value" value="{{isset($drawerProducts->pivot->value) ? $drawerProducts->pivot->value : ''}}" />
                                    </div>
                                  </li>
                                  <li>
                                    <div>
                                      <label><small class="red">*</small>申报单位：</label>
                                      <input type="text" placeholder="请填写申报单位" data-parsley-required data-parsley-trigger="change" class="unit_openCon" name="measure_unit_cn" id="measure_unit_cn" value="{{isset($drawerProducts->pivot->measure_unit_cn) ? $drawerProducts->pivot->measure_unit_cn : ''}}" readonly {{$disabled}} />
                                      <input type="hidden" name="measure_unit" value="{{isset($drawerProducts->pivot->measure_unit) ? $drawerProducts->pivot->measure_unit : ''}}" {{$disabled}}>
                                    </div>
                                  </li>
                                  <li>
                                    <div style="min-width:350px">
                                      <label><small class="red">*</small>法定数量和单位：</label>
                                      <input placeholder="请填写数量" name="default_num" id="default_num" data-parsley-required value="{{$drawerProducts->pivot->default_num}}" style="width:100px" {{$disabled}}>
                                      <input id="default_unit" class="unit_openCon" name="default_unit" data-parsley-required placeholder="请填写法定单位" value="{{isset($drawerProducts->pivot->default_unit) ? $drawerProducts->pivot->default_unit : ''}}" style="width:100px" readonly {{$disabled}}>
                                      <input type="hidden" name="default_unit_id" value="{{isset($drawerProducts->pivot->default_unit_id) ? $drawerProducts->pivot->default_unit_id : ''}}">
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
                                      <input class="standard" name="standard" value="{{isset($drawerProducts->product->standard) ? $drawerProducts->product->standard : ''}}" style="width:28%" readonly {{$disabled}}/>
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
                    </div>
                @endif
                </div>
            </div>
        </div>
        </form>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="total_net_weight " class="col-sm-4 control-label">总净重</label>
                <div class="col-sm-8">
                <input type="text" readonly name="total_net_weight" class="total_net_weight form-control" value="{{$order->total_net_weight}}" {{ $disabled }}>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="total_weight " class="col-sm-4 control-label">总毛重</label>
                <div class="col-sm-8">
                <input type="text" readonly name="total_weight" class="sum_weight form-control" value="{{$order->total_weight}}" {{ $disabled }}>
                </div>
            </div>
        </div>

        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label class="col-sm-4 control-label">总体积</label>
                <div class="col-sm-8">
                <input type="text" readonly name="total_volume" class="total_volume form-control" value="{{$order->total_volume}}" {{ $disabled }}>
                <input type="hidden" readonly name="total_packnum" class="sum_pack_number form-control" value="{{$order->total_packnum}}" {{ $disabled }}>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="total_num " class="col-sm-4 control-label">产品数量</label>
                <div class="col-sm-8">
                <input type="text" readonly name="total_num" class="total_num form-control" value="{{$order->total_num}}" {{ $disabled }}>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="total_value " class="col-sm-4 control-label">总货值</label>
                <div class="col-sm-8">
                <input type="text" readonly name="total_value" class="total_value form-control" value="{{$order->total_value}}" {{ $disabled }}>
                </div>
            </div>
        </div>
        <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="total_packnum " class="col-sm-4 control-label">运输包装件数</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($order) ? $order->total_packnum : ''}}" id="total_packnum" class="total_packnum form-control" name="total_packnum" {{ $disabled }}/>
                </div>
            </div>
        </div>
        <!-- <div class="col-sm-4 m-t-xs">
            <div class="form-group">
                <label for="other_packages_type " class="col-sm-4 control-label">其他包装种类</label>
                <div class="col-sm-8">
                    <input type="text" value="{{ isset($order) ? $order->other_packages_type : ''}}" id="other_packages_type" name="other_packages_type" class="form-control" {{ $disabled }}/>
                </div>
            </div>
        </div> -->
        <form class="three_form">
            @include('member.common.dataselect', ['label'=>'整体包装方式', 'var'=>'order_package', 'obj'=> isset($order) ? $order : '', 'col'=>'4'])

            <div class="clearfix"></div>
            {{--3.报关信息--}}
            <div class="m-t-xs m-l-sm" style="line-height: 19px;">
                <b class="badge bg-danger">3</b>
                <label class=" h5 text-danger m-l-xs">报关信息</label>
            </div>
            <div class="col-sm-4 m-t-xs">
                <div class="form-group">
                    <label for="shipping_at" class="col-sm-4 control-label">预计出货日期</label>
                    <div class="col-sm-8">
                        <input type="text" onclick="laydate()"  value="{{ isset($order) ? $order->shipping_at : ''}}" name="shipping_at" id="shipping_at" class="form-control" readonly {{ $disabled }}/>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 m-t">
                <div class="form-group">
                    <label for="customs_at" class="col-sm-4 control-label necessary_front">报关日期</label>
                    <div class="col-sm-8">
                        <input type="text" onclick="laydate()"  value="{{ isset($order) ? $order->customs_at : ''}}" name="customs_at" id="customs_at" class="form-control" data-parsley-required data-parsley-trigger="change" readonly {{ $disabled }}/>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 m-t-xs">
                <div class="form-group">
                    <label for="sailing_at" class="col-sm-4 control-label necessary_front">开船日期</label>
                    <div class="col-sm-8">
                        <input type="text" onclick="laydate()"  value="{{ isset($order) ? $order->sailing_at : ''}}" name="sailing_at" id="sailing_at" class="form-control" data-parsley-required data-parsley-trigger="change" readonly {{ $disabled }}/>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 m-t-xs">
                <div class="form-group">
                    <label for="receive" class="col-sm-4 necessary_front control-label">境外收货人</label>
                    <div class="col-sm-8">
                        <input type="text" id="receive" value="{{ isset($order->receive->name) ? $order->receive->name : '' }}" class="form-control" placeholder="选择境外收货人人" data-select="receive" data-target="[name=receive_id]" data-outwidth="800px" data-parsley-required data-parsley-trigger="change" disabled/>
                        <input type="hidden" name="receive_id" value="{{ isset($order->receive->id) ? $order->receive->id : '' }}" {{ $disabled }}>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 m-t-xs">
                <div class="form-group">
                    <label for="links_phone" class="col-sm-4 necessary_front control-label">境外收货人国家</label>
                    <div class="col-sm-8">
                        <input type="text" value="{{ isset($order->receive->country) ? $order->receive->country : '' }}" id="receive_country"  class="form-control" disabled/>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 m-t-xs">
                <div class="form-group">
                    <label for="deposit" class="col-sm-4 necessary_front control-label">货物联系人</label>
                    <div class="col-sm-8">
                        <input type="text" id="deposit" value="{{ isset($order->deposit->name) ? $order->deposit->name : '' }}" class="form-control" placeholder="选择货物联系人" data-select="deposit" data-target="[name=receives_id]" data-outwidth="800px" data-parsley-required data-parsley-trigger="change" disabled/>
                        <input type="hidden" name="deposit_id" value="{{ isset($order->deposit->id) ? $order->deposit->id : '' }}" {{ $disabled }}>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 m-t-xs">
                <div class="form-group">
                    <label for="links_phone" class="col-sm-4 necessary_front control-label">货物存放地址</label>
                    <div class="col-sm-8">
                        <input type="text" value="{{ isset($order->deposit->address) ? $order->deposit->address : '' }}" id="deposit_address"  class="form-control" disabled/>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 m-t-xs">
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

            <div class="col-sm-4 m-t-xs">
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
            <div class="col-sm-4 m-t-xs">
                <div class="form-group">
                    <label for="borders_country" class="col-sm-4 control-label ">操作人</label>
                    <div class="col-sm-8">
                        <input type="text" value="{{ isset($order) ? $order->operator : '' }}" id="" name="operator" class="form-control" {{ $disabled }}/>
                    </div>
                </div>
            </div>
            @if (isset($order) && $order->status == 3 || $order->status == 5)
            @include('member.common.dataselect', ['label'=>'出境关别', 'var'=>'clearance_port', 'obj'=> isset($order) ? $order : '', 'col'=>'4', 'disabled'=> false])
            <!-- 报关单号 -->
            <div class="col-sm-4 m-t-xs">
                <div class="form-group">
                    <label for="customs_number" class="col-sm-4  necessary_front control-label">报关单号</label>
                    <div class="col-sm-8">
                        <input type="text" value="{{ isset($order) ? $order->customs_number : ''}}" id="customs_number" name="customs_number" class="form-control" data-parsley-required data-parsley-trigger="change" />
                    </div>
                </div>
            </div>
            <div class="col-sm-4 m-t-xs">
                <div class="form-group">
                    <label for="export_date" class="col-sm-4  necessary_front control-label">出口日期</label>
                    <div class="col-sm-8">
                        <input type="text" onclick="laydate()" value="{{ isset($order) ? $order->export_date : ''}}" id="export_date" name="export_date" class="form-control" data-parsley-required data-parsley-trigger="change" placeholder="请选择日期" readonly/>
                    </div>
                </div>
            </div>
            @endif
            <div class="col-sm-2">
                <a href="#" class="btn btn-s-md btn-primary" data-upload="image" data-input="[name=attach_upload]">附件</a>
                <input type="hidden" name="attach_upload" value="{{ isset($order) ? $order->attach_upload : ''}}" data-parsley-trigger="change">
            </div>
        </form>
        <div class="clearfix"></div>
    <form class="approve_form">
        @include('member.common.approve', ['obj'=> isset($order) ? $order : ''])
    </form>
</div>
<script>
    function sum(num, aim, dot) {
      var total = 0
      $('.pro_sec input.' + num ).map(function (index, item) {
        item.value == '' ? (a = 0) : (a = item.value * 1)
        total = total + a
      })
      total = total.toFixed(dot)
      $(aim).val(total)
    }
    $(function(){
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
        $(document).on('click','.choose_Cus',function () {
            var ele,
                outerTitle = '选择客户'
                api = '/member/customer/customer_choose';
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
                    var index = layer.open({
                        type: 1,
                        title: outerTitle,
                        area: '800px',
                        offset: 'top',
                        content: str,
                        btn: ['确定'],
                        yes: function (i) {
                            let id = ele.find('.bg-active').data('id')
                                cusname = ele.find('.bg-active .customer_name').text()
                            $('.choose_Cus').val(cusname)
                            $('#customer_id').val(id)
                            layer.close(i)
                        }
                    })
                    ele = $('#layui-layer' + index + '')
                }
            })
        })

        $(document).off('click','.domestic_source_con')
        $(document).on('click','.domestic_source_con',function () {  //选择境内货源地
            var ele,th=$(this),
                outerTitle = '选择境内货源地'
            api = '/member/district/list';
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
                    var index = layer.open({
                        type: 1,
                        title: outerTitle,
                        area: '800px',
                        offset: 'top',
                        content: str,
                        btn: ['确定'],
                        yes: function (i) {
                            let id = ele.find('.bg-active').data('id')
                                cusname = ele.find('.bg-active .customer_name').text()
                            th.val(cusname)
                            th.parent().find('input[type="hidden"]').val(id)
                            layer.close(i)
                        }
                    });
                    ele = $('#layui-layer' + index + '')
                }
            })
        })

        $(document).off('click','.country_openCon')
        $(document).on('click','.country_openCon',function () {
            //选择国家
            var ele,th=$(this),
                outerTitle = '选择国家'
            api = '/member/country/list';
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
                    var index = layer.open({
                        type: 1,
                        title: outerTitle,
                        area: '800px',
                        offset: 'top',
                        content: str,
                        btn: ['确定'],
                        yes: function (i) {
                            let id = ele.find('.bg-active').data('id')
                            cusname = ele.find('.bg-active .customer_name').text()
                            th.val(cusname)
                            th.parent().find('input[type="hidden"]').val(id)
                            layer.close(i)
                        }
                    })
                    ele = $('#layui-layer' + index + '')
                }
            })
        })

        $(document).off('click','.unit_openCon')
        $(document).on('click','.unit_openCon',function () {
            //选择单位
            var ele,th=$(this),
                outerTitle = '选择单位'
            api = '/member/unit/list';
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
                    var index = layer.open({
                        type: 1,
                        title: outerTitle,
                        area: '800px',
                        offset: 'top',
                        content: str,
                        btn: ['确定'],
                        yes: function (i) {
                            let id = ele.find('.bg-active').data('id')
                            cusname = ele.find('.bg-active .customer_name').text()
                            th.val(cusname)
                            th.parent().find('input[type="hidden"]').val(id)
                            layer.close(i)
                        }
                    })
                    ele = $('#layui-layer' + index + '')
                }
            })
        })

        $(document).off('click','.show_tbody')
        $(document).on('click','.show_tbody',function () {
            var ele,
                outerTitle = $(this).parent().prev().text()
                customer_id = $('[name=customer_id]').val()
                follow_id = $(this).data().before
            if (!customer_id) {
                layer.msg('请先选择客户！')
                return false;
            } else {
                var api = '/member/drawer_products/'+ customer_id;
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
                    var index = layer.open({
                        type: 1,
                        title: outerTitle,
                        area: '800px',
                        content: str,
                        btn: ['确定'],
                        yes: function (i) {
                            let pro_tr = ele.find('.bnoteTbody input:checked').closest('tr')
                                pro_arr = []  //选中产品数组
                                count = 0  //循环次数判断
                            $.each(pro_tr,function(j,item){
                                pro_arr.push({drawer_product_id:$(this).data('id'),product_id:$(this).data('productid')})
                            })
                            $.each(pro_arr,function(j,item){
                                $.ajax({
                                    url: '/member/drawer_products/detail/' + item.drawer_product_id,
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
                                                destination_country_id:data.destination_country_id||'',
                                                destination_country:data.destination_country||'',
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
                                            // getMeasureUnit()
                                            count++
                                            $('.product_list_div .otbody').append(html)
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
                    ele = $('#layui-layer' + index + '')
                }
            })
        })

        //删除当前产品
        $(document).on('click','.kpr_del',function () {
            $(this).parents('section').remove()
        })

        $(document).on('keyup', '.pro_sec input.number', function () {
            // 货值 = 数量 * 单价
            var a = $(this).val() * 1
            var total_price = $(this).parents('ul').find('input.total_price').val() * 1
            var c = (total_price / a).toFixed(4)
            $(this).parents('ul').find('input.single_price').val(c)
            // 产品数量 = 数量相加
            sum('number', '.total_num', 4)
            sum('total_price', '.total_value', 4)
        }).on('keyup', '.pro_sec input.single_price', function () {
            // 货值 = 单价 * 数量
            var a = $(this).val() * 1
            var number = $(this).parents('ul').find('input.number').val() * 1
            var c = (a.toFixed(4) * number.toFixed(4)).toFixed(4)
            $(this).parents('ul').find('input.total_price').val(c)
            sum('total_price', '.total_value', 4)
        }).on('keyup', '.pro_sec input.total_price', function () {
            // 单价 = 货值 / 数量
            var a = $(this).val() * 1
            var number = $(this).parents('ul').find('input.number').val() * 1
            var c = (a.toFixed(4) / number.toFixed(4)).toFixed(4)
            sum('total_price', '.total_value', 4)
            $(this).parents('ul').find('input.single_price').val(c)
        }).on('keyup', '.pro_sec input.net_weight', function () {
            sum('net_weight', '.total_net_weight', 4)
        }).on('keyup', '.pro_sec input.volume', function () {
            sum('volume', '.total_volume', 4)
        }).on('keyup', '.pro_sec input.total_weight', function () {
            sum('total_weight', '.sum_weight', 4)
        }).on('keyup','.pro_sec input.pack_number',function(){
            sum('pack_number','.total_packnum',4)
        }).on('click', '.kpr_del', function () {
          $(this).parents('section').remove()
          $('#net_weight,#total_weight,#volume,#number,#total_price').trigger('keyup')
        })
    //产品div
    function getDiv(obj,val) {
        html = `<section class="am-panel am-panel-default">
                    <header class="am-panel-hd">
                        <h5 class="am-panel-title">产品名称<input class="name" id="name" value="${obj.name}(${obj.company})" style="width:55%" readonly></h5>
                        <input type="hidden" name="company" value="${obj.company}">
                    </header>
                    <div class="pro_sec">
                      <ul class="clearfix" style="min-width: 1000px;">
                          <li>
                            <div>
                              <label>产品分类：</label>
                              <input value="${obj.pro_type}" disabled>
                              <input type="hidden" name="drawer_product_id" value="${obj.drawer_product_id}">
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
                              <input placeholder="请填写境内货源地" data-parsley-required data-parsley-trigger="change" name="domestic_source" id="domestic_source" class="domestic_source_con" value="${obj.source}" style="width:70%" />
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
                              <input placeholder="请填写原产国" data-parsley-required data-parsley-trigger="change" name="origin_country" id="origin_country" class="country_openCon" value="中国">
                              <input type="hidden" name="origin_country_id" value="${obj.origin_country_id}">
                            </div>
                          </li>
                          <li>
                            <div>
                              <label><small class="red">*</small>最终目的国(地区)： </label>
                              <input placeholder="请填写最终目的国" data-parsley-required data-parsley-trigger="change" name="destination_country" id="destination_country" class="country_openCon" value="${val}">
                              <input type="hidden" name="destination_country_id" value="${obj.destination_country_id}">
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
                              <input placeholder="开票金额(RMB)" data-parsley-required data-parsley-trigger="change" name="value" id="value" class="value" value="" />
                            </div>
                          </li>
                          <li>
                            <div>
                              <label><small class="red">*</small>申报单位：</label>
                              <input type="text" placeholder="请填写申报单位" data-parsley-required data-parsley-trigger="change"  class="unit_openCon" name="measure_unit_cn" id="measure_unit_cn" value="${obj.measure_unit_cn}" readonly />
                              <input type="hidden" name="measure_unit" value="${obj.measure_unit}">
                            </div>
                          </li>
                          <li>
                            <div>
                              <label>法定数量和单位：</label>
                              <input placeholder="请填写数量" data-parsley-required name="default_num" id="default_num" value="" style="width:100px">
                              <input id="default_unit" class="unit_openCon" data-parsley-required name="default_unit" placeholder="请填写法定单位" value="${obj.unit}" style="width:100px" readonly>
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
                              <input class="standard" name="standard" value="${obj.standard}" style="width:28%" readonly/>
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

    $(document).on('keyup','#number',function () {
        var re=/[^0-9-]+/;//只允许输入数字
        if(re.test(this.value)){
          this.value=this.value.substr(0,this.value.length-1);//将最后输入的字符去除
        }
      })
      $(document).on('blur','.measure_unit',function(){
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
            if(document.all){
                var it=document.getElementById("ice").children[0];
            } else {
                var it=document.getElementById("ice");
            }
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

    //默认执行一次选择 以免 空值
    $(document).ready(function(){
        $('#trade_country').select2()
        $('#clearance_port').select2()
        $('#aim_country').select2().change(function(){
            var aim=$(this).val();
            var Url='/member/order/harbor/';
            $.get(Url+aim, {}, function (str) {
                var unloading_port_id = $('#unloading_port_id').val();
                var th_opactive = '';
                $.each(str.ports,function(i,n){
                    if(n.id == unloading_port_id){
                      th_opactive+= "<option value='"+ n.id+"' selected='selected'>"+ n.port_c_cod +"</option>"
                    }else{
                      th_opactive+= "<option value='"+ n.id+"'>"+ n.port_c_cod +"</option>"
                    }
                })
                $('#unloading_port').html('').append(th_opactive).select2();
            })
        });
        $('#aim_country').trigger('change');
    })

</script>
