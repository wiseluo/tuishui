@extends('admin.layouts.app')
@section('content')
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
<link rel="stylesheet" href="{{ URL::asset('/css/amazeui.min.css') }}" type="text/css"/>
<section class="vbox">
    <section class="scrollable padder">
        <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
            <li><i class="fa fa-home"></i>订单修改</li>
        </ul>
        <section class="panel panel-default">
            <div class="panel-body">
                <div class="orddetail">
                    <form class="form-horizontal ord_form" id="ord_form" style="overflow: hidden; border-top:1px solid #cccccc">
                        <div class="m-b-xs m-l-sm" style="line-height: 19px;">
                            <!-- <b class="badge bg-danger order_order">1</b> -->
                            <label class="h5 text-danger m-l-xs">基本信息</label>
                        </div>
                        <div class="col-sm-4 contract-con m-b">
                            <div class="form-group">
                                <label class="col-sm-4 necessary_front control-label">合同号</label>
                                <div class="col-sm-8">
                                    <input type="text" value="{{ isset($customs->contract_no) ? $customs->contract_no : ''}}" id="ordnumber" name="ordnumber" class="form-control" data-parsley-required data-parsley-trigger="change" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 m-b">
                            <div class="form-group">
                                <label class="col-sm-4 control-label necessary_front">客户</label>
                                <div class="col-sm-8">
                                    <input type="text" id="cusName" value="{{ isset($customs->customer_name) ? $customs->customer_name : ''}}" class="form-control choose_Cus" placeholder="选择客户" data-parsley-required data-parsley-trigger="change"  readonly/>
                                    <input type="hidden" id="customer_id" name="customer_id" value="" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 m-b">
                            <div class="form-group">
                                <label class="col-sm-4 control-label necessary_front" >经营单位</label>
                                <div class="col-sm-8">
                                    <input type="text" id="company_name" value="{{ isset($customs->business_unit_name) ? $customs->business_unit_name : ''}}" class="form-control choose_company" placeholder="选择经营单位" data-parsley-required data-parsley-trigger="change"  readonly/>
                                    <input type="hidden" name="company_id" value="" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 m-b">
                          <div class="form-group">
                            <label for="exchange_method" class="col-sm-4 control-label">收汇方式</label>
                            <div class="col-sm-8">
                                <select id="exchange_method" class="form-control" data-parsley-required data-parsley-trigger="change" name="exchange_method">
                                    <option value="0">汇款(如T/T)</option>
                                    <option value="1">托收(D/P，D/A)</option>
                                    <option value="2">信用证(L/C)</option>
                                    <option value="3">赊销(OA)</option>
                                </select>
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-4 m-b lc_no_div" style="display: none">
                          <div class="form-group">
                            <label for="lc_no" class="col-sm-4 control-label">信用证号</label>
                            <div class="col-sm-8">
                                <input type="text" value="{{ isset($customs->lc_no) ? $customs->lc_no : ''}}" id="lc_no" name="lc_no" class="form-control" data-parsley-trigger="change" />
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-4 m-b">
                            <div class="form-group">
                                <label for="bnote_code" class="col-sm-4 control-label">业务编号</label>
                                <div class="col-sm-8">
                                    <input type="text" id="bnote_num" name="bnote_num" value="{{ isset($customs->bnote_num) ? $customs->bnote_num : ''}}" class="form-control"  data-outwidth="800px" placeholder="选择业务编号" readonly/>
                                    <input type="hidden" id="bnote_id" value="" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 m-b">
                            <div class="form-group">
                                <label class="col-sm-4 necessary_front  control-label">贸易商</label>
                                <div class="col-sm-8">
                                    <input type="text" id="trader_name" value="{{ isset($customs->trader_name) ? $customs->trader_name : ''}}" class="form-control choose_trader" placeholder="选择贸易商" data-parsley-required data-parsley-trigger="change" readonly/>
                                    <input type="hidden" name="trader_id" value="" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 m-b">
                            <div class="form-group">
                                <label for="business" class="col-sm-4 control-label necessary_front">业务类型</label>
                                <div class="col-sm-8">
                                    <input type="text" value="" id="business" class="form-control" onclick="dataLayer(11,'业务类型',$(this))" readonly />
                                    <input type="hidden" value="" name="business" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 m-b">
                            <div class="form-group">
                                <label for="declare_mode" class="col-sm-4 control-label necessary_front">报关方式</label>
                                <div class="col-sm-8">
                                    <input type="text" value="" id="declare_mode_name" class="form-control" onclick="dataLayer(4,'报关方式',$(this))" readonly />
                                    <input type="hidden" value="" name="declare_mode" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 m-b">
                            <div class="form-group">
                                <label for="broker_name" class="col-sm-4 control-label">报关行名称</label>
                                <div class="col-sm-8">
                                    <input type="text" value="{{ isset($customs->declare_unit_name) ? $customs->declare_unit_name : ''}}" id="broker_name" name="broker_name" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 m-b">
                            <div class="form-group">
                                <label for="broker_number" class="col-sm-4 control-label">报关行代码(10位)</label>
                                <div class="col-sm-8">
                                    <input type="text" value="{{ isset($customs->declare_unit_code) ? $customs->declare_unit_code : ''}}" id="broker_number" name="broker_number" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 m-b">
                            <div class="form-group">
                                <label for="price_clause" class="col-sm-4 control-label necessary_front">价格条款</label>
                                <div class="col-sm-8">
                                    <input type="text" value="{{ isset($customs->transaction_mode) ? $customs->transaction_mode : ''}}" id="price_clause_name" class="form-control" onclick="dataLayer(6,'价格条款',$(this))" readonly />
                                    <input type="hidden" value="" name="price_clause" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 m-b">
                            <div class="form-group">
                                <label for="order_package" class="col-sm-4 control-label necessary_front">整体包装方式</label>
                                <div class="col-sm-8">
                                    <input type="text" value="" id="order_package" class="form-control" onclick="dataLayer(9,'整体包装方式',$(this))" readonly />
                                    <input type="hidden" value="" name="order_package" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        {{--2.报关信息--}}
                        <div class="m-t-xs m-l-sm" style="line-height: 19px;">
                            <!-- <b class="badge bg-danger">2</b> -->
                            <label class=" h5 text-danger m-l-xs">报关信息</label>
                        </div>
                        <!-- 报关单号 -->
                        <div class="col-sm-4 m-b">
                            <div class="form-group">
                                <label for="customs_number" class="col-sm-4 control-label">报关单号</label>
                                <div class="col-sm-8">
                                    <input type="text" value="{{ isset($customs->customs_no) ? $customs->customs_no : ''}}" id="customs_number" name="customs_number" class="form-control" data-parsley-required="true" data-parsley-trigger="change" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 m-b">
                            <div class="form-group">
                                <label for="customs_at" class="col-sm-4 control-label necessary_front">报关日期</label>
                                <div class="col-sm-8">
                                    <input type="text" onclick="laydate()" value="{{ isset($customs->declare_date) ? $customs->declare_date : ''}}" name="customs_at" id="customs_at" class="form-control" data-parsley-required data-parsley-trigger="change" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 m-b">
                            <div class="form-group">
                                <label for="currency" class="col-sm-4 control-label necessary_front">报关币种</label>
                                <div class="col-sm-8">
                                    <input type="text" value="" id="currency_name" class="form-control" onclick="dataLayer(5,'报关币种',$(this))" data-parsley-required data-parsley-trigger="change" readonly />
                                    <input type="hidden" value="" name="currency" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 m-b">
                            <div class="form-group">
                                <label for="shipment_port" class="col-sm-4 control-label necessary_front">离境口岸</label>
                                <div class="col-sm-8">
                                    <input type="text" value="" id="shipment_port_name" class="form-control choose_port" data-parsley-required data-parsley-trigger="change" readonly />
                                    <input type="hidden" name="shipment_port" value="" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 m-b">
                            <div class="form-group">
                                <label class="col-sm-4 control-label necessary_front">贸易国(地区)</label>
                                <div class="col-sm-8">
                                    <input type="text" value="" id="trade_country" class="form-control country_openCon" data-parsley-required data-parsley-trigger="change" readonly />
                                    <input type="hidden" name="trade_country" value="" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 m-b">
                            <div class="form-group">
                                <label class="col-sm-4 control-label necessary_front">运抵国(地区)</label>
                                <div class="col-sm-8">
                                    <input type="text" value="{{ isset($customs->arrival_country) ? $customs->arrival_country : ''}}" id="aim_country_name" class="form-control country_openCon" data-parsley-required data-parsley-trigger="change" readonly />
                                    <input type="hidden" class="aim_country" name="aim_country" value="" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 m-b">
                            <div class="form-group">
                                <label for="unloading_port" class="col-sm-4 control-label necessary_front">指运港</label>
                                <div class="col-sm-8">
                                    <input id="unloading_port_name" class="form-control" value="{{ isset($customs->arrival_port) ? $customs->arrival_port : ''}}" onclick="dataLayer(14,'指运港',$(this))" data-parsley-required="" data-parsley-trigger="change" readonly />
                                    <input type="hidden" id="unloading_port" name="unloading_port" value="">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 m-b">
                            <div class="form-group">
                                <label for="clearance_mode" class="col-sm-4 control-label">报关形式</label>
                                <div class="col-sm-8">
                                    <input type="text" value="一般贸易" class="form-control" name="clearance_mode" onclick="dataLayer(10,'报关形式',$(this))" readonly/>
                                    <!-- <input type="hidden" id="clearance_mode" value=""> -->
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 m-b">
                            <div class="form-group">
                                <label for="package" class="col-sm-4 control-label necessary_front">包装方式</label>
                                <div class="col-sm-8">
                                    <input type="text" id="package_name" value="{{ isset($customs->package_type) ? $customs->package_type : ''}}" onclick="dataLayer(7,'包装方式',$(this))" class="form-control" readonly/>
                                    <input type="hidden" id="package" name="package" value="">
                                </div>
                            </div>
                        </div>
                    </form>
                    {{--3.商品信息--}}
                    <div class="clearfix"></div>
                    <div class="m-b-xs m-l-sm" style="line-height: 19px;">
                        <!-- <b class="badge bg-danger">3</b> -->
                        <label class="h5 text-danger m-l-xs">商品信息</label>
                    </div>
                    <div class="col-sm-12 m-b-xs">
                        <div class="form-group">
                            <div class="table-responsive panel" style="overflow-x:auto;margin:0 15px;">
                                <table class="table table-hover b-t b-light" id="ice" style="table-layout: fixed;min-width: 1050px;">
                                    <thead>
                                    <tr class="info">
                                        <th width="6%">合并分组</th>
                                        <th width="8%">Hscode</th>
                                        <th width="8%">产品名称</th>
                                        <th width="15%">申报要素</th>
                                        <th width="6%">申报数量</th>
                                        <th width="6%">申报单位</th>
                                        <th width="12%">操作</th>
                                    </tr>
                                    </thead>
                                    <form class="pro_table_form" onsubmit="return false;">
                                        <tbody class="otbody">
                                            @if (isset($customs->product) && null !== $customs->product)
                                            @foreach($customs->product as $key => $product)
                                            <tr data-id="{{ $product->id }}">
                                                <td>
                                                    <input type="hidden" name="pro[{{ $key }}][single_price]" data-name="single_price" value="{{ $product->declare_price }}" />
                                                    <input type="hidden" name="pro[{{ $key }}][total_price]" data-name="total_price" class="total_price" value="{{ $product->total_price }}" />
                                                    <input type="hidden" name="pro[{{ $key }}][destination_country]" data-name="destination_country" class="destination_country" value="{{ $product->marketing_country }}" />
                                                    <input type="hidden" name="pro[{{ $key }}][destination_country_id]" data-name="destination_country_id" class="pro_match" value="" />
                                                    <input type="hidden" name="pro[{{ $key }}][default_num]" data-name="default_num" value="{{ $product->legal_quantity }}" />
                                                    <input type="hidden" name="pro[{{ $key }}][default_unit]" data-name="default_unit" class="default_unit_name" value="{{ $product->legal_unit }}" />
                                                    <input type="hidden" name="pro[{{ $key }}][default_unit_id]" data-name="default_unit_id" class="pro_match" value="" />
                                                    <input type="hidden" name="pro[{{ $key }}][company]" data-name="company" class="drawer_name" value="{{ $product->drawer_name }}" />
                                                    <input type="hidden" name="pro[{{ $key }}][drawer_id]" data-name="drawer_id" class="pro_match" value="" />
                                                    <input type="hidden" name="pro[{{ $key }}][en_name]" data-name="en_name" value="{{ $product->ename }}" />
                                                    <input type="hidden" name="pro[{{ $key }}][domestic_source]" data-name="domestic_source" class="domestic_source" value="{{ $product->domestic_source }}" />
                                                    <input type="hidden" name="pro[{{ $key }}][domestic_source_id]" data-name="domestic_source_id" class="pro_match" value="" />
                                                    <input type="hidden" name="pro[{{ $key }}][origin_country]" data-name="origin_country" value="" />
                                                    <input type="hidden" name="pro[{{ $key }}][origin_country_id]" data-name="origin_country_id" value="" />
                                                    <input type="hidden" name="pro[{{ $key }}][goods_attribute]" data-name="goods_attribute" value="" />
                                                    <input type="hidden" name="pro[{{ $key }}][unit]" data-name="unit" value="" />
                                                    <input type="hidden" name="pro[{{ $key }}][net_weight]" data-name="net_weight" class="net_weight" value="{{ $product->net_weight }}" />
                                                    <input type="hidden" name="pro[{{ $key }}][total_weight]" data-name="total_weight" class="weight" value="{{ $product->gross_weight }}" />
                                                    <input type="hidden" name="pro[{{ $key }}][volume]" data-name="volume" class="volume" value="{{ $product->volume }}" />
                                                    <input type="hidden" name="pro[{{ $key }}][pack_number]" data-name="pack_number" class="pack_number" value="{{ $product->pack_number }}" />
                                                    <input type="hidden" name="pro[{{ $key }}][tax_refund_rate]" data-name="tax_refund_rate" value="{{ $product->tax_refund_rate }}" />
                                                    <input type="hidden" name="pro[{{ $key }}][tax_rate]" data-name="tax_rate" value="{{ $product->tax_rate }}" />
                                                    <input type="hidden" name="pro[{{ $key }}][value]" data-name="value" class="value" value="{{ $product->invoice_amount }}" />
                                                    <input type="hidden" name="pro[{{ $key }}][enjoy_offer]" data-name="enjoy_offer" value="" />
                                                    <input type="hidden" name="pro[{{ $key }}][pro_classification]" data-name="pro_classification" value="" />
                                                    <input type="hidden" name="pro[{{ $key }}][pro_classification_id]" data-name="pro_classification_id" value="" />
                                                    <input type="hidden" name="pro[{{ $key }}][type_str]" data-name="type_str" value="" />
                                                    <input type="hidden" name="pro[{{ $key }}][production_place]" data-name="production_place" value="" />
                                                    <input type="hidden" name="pro[{{ $key }}][ciq_code]" data-name="ciq_code" value="" />
                                                    <input type="hidden" name="pro[{{ $key }}][species]" data-name="species" value="" />
                                                    <input type="hidden" name="pro[{{ $key }}][surface_material]" data-name="surface_material" value="" />
                                                    <input type="hidden" name="pro[{{ $key }}][section_number]" data-name="section_number" value="" />
                                                    <input type="hidden" name="pro[{{ $key }}][drawer_product_id]" data-name="drawer_product_id" value="">
                                                    <input type="text" name="pro[{{ $key }}][merge]" data-name="merge" value="" class="text-center merge" data-parsley-type="number" readonly />
                                                </td>
                                                <td>
                                                    <input type="text" name="pro[{{ $key }}][hscode]" data-name="hscode" value="{{ $product->hscode }}" class="text-center hscode" readonly />
                                                </td>
                                                <td>
                                                    <input type="text" name="pro[{{ $key }}][name]" data-name="name" value="{{ $product->name }}" class="text-center name" readonly />
                                                </td>
                                                <td>
                                                    <input type="text" name="pro[{{ $key }}][standard]" data-name="standard" value="{{ $product->standard }}" class="standard text-center" readonly />
                                                </td>
                                                <td>
                                                    <input type="text" name="pro[{{ $key }}][number]" data-name="number" value="{{ $product->declare_number }}" class="number text-center" readonly />
                                                </td>
                                                <td>
                                                    <input type="text" name="pro[{{ $key }}][measure_unit_cn]" data-name="measure_unit_cn" value="{{ $product->declare_unit }}" class="measure_unit_cn text-center" readonly />
                                                    <input type="hidden" name="pro[{{ $key }}][measure_unit]" data-name="measure_unit" class="pro_match" value="">
                                                </td>
                                                <td class="text-center"><button class="btn btn-normal m-l-md relation_btn">关联开票人</button></td>
                                            </tr>
                                            @endforeach
                                            @endif
                                        </tbody>
                                    </form>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 m-b-xs">
                        <div class="form-group">
                            <label class="col-sm-4 necessary_front control-label">产品总数量</label>
                            <div class="col-sm-8">
                                <input type="text" value="{{ isset($customs->total_num) ? $customs->total_num : ''}}" id="total_num" class="form-control total_num" placeholder="根据产品信息计算得出" readonly/>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 m-b-xs">
                        <div class="form-group">
                            <label class="col-sm-4 necessary_front control-label">总体积</label>
                            <div class="col-sm-8">
                                <input type="text" value="{{ isset($customs->total_volume) ? $customs->total_volume : ''}}" class="form-control total_volume" placeholder="根据产品信息计算得出" readonly/>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 m-b-xs">
                        <div class="form-group">
                            <label class="col-sm-4 necessary_front control-label">总货值</label>
                            <div class="col-sm-8">
                                <input type="text" value="{{ isset($customs->total_value) ? $customs->total_value : ''}}" class="form-control total_value" placeholder="根据产品信息计算得出" readonly/>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 m-b-xs">
                        <div class="form-group">
                            <label class="col-sm-4 necessary_front control-label">总毛重</label>
                            <div class="col-sm-8">
                                <input type="text" value="{{ isset($customs->total_weight) ? $customs->total_weight : ''}}" class="form-control total_weight" placeholder="根据产品信息计算得出" readonly/>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 m-b-xs">
                        <div class="form-group">
                            <label class="col-sm-4 necessary_front control-label">总净重</label>
                            <div class="col-sm-8">
                                <input type="text" value="{{ isset($customs->total_net_weight) ? $customs->total_net_weight : ''}}" class="form-control total_net_weight" placeholder="根据产品信息计算得出" readonly/>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 m-b-xs">
                        <div class="form-group">
                            <label class="col-sm-4 necessary_front control-label">运输包装件数</label>
                            <div class="col-sm-8">
                                <input type="text" value="{{ isset($customs->total_packnum) ? $customs->total_packnum : ''}}" class="form-control total_packnum" placeholder="根据产品最大包装件数计算得出" readonly/>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <form class="form_3">
                        <div class="m-b-xs m-l-sm" style="line-height: 19px;">
                            <!-- <b class="badge bg-danger">4</b> -->
                            <label class="h5 text-danger m-l-xs">提运信息</label>
                        </div>
                        <div class="col-sm-4 m-b">
                            <div class="form-group">
                                <label for="shipping_at" class="col-sm-4 control-label">预计出货日期</label>
                                <div class="col-sm-8">
                                    <input type="text" onclick="laydate()" value="{{ isset($customs->shipping_at) ? $customs->shipping_at : ''}}" name="shipping_at" id="shipping_at" class="form-control" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 m-b">
                            <div class="form-group">
                                <label for="packing_at" class="col-sm-4 control-label">装箱时间</label>
                                <div class="col-sm-8">
                                    <input type="text" onclick="laydate()" value="{{ isset($customs->packing_at) ? $customs->packing_at : ''}}" name="packing_at" id="packing_at" class="form-control" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 m-b">
                            <div class="form-group">
                                <label for="sailing_at" class="col-sm-4 control-label necessary_front">开船日期</label>
                                <div class="col-sm-8">
                                    <input type="text" onclick="laydate()" value="{{ isset($customs->io_date) ? $customs->io_date : ''}}" name="sailing_at" id="sailing_at" class="form-control" data-parsley-required data-parsley-trigger="change" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 m-b">
                            <div class="form-group">
                                <label for="export_date" class="col-sm-4 necessary_front control-label">出口日期</label>
                                <div class="col-sm-8">
                                    <input type="text" onclick="laydate()" value="{{ isset($customs->io_date) ? $customs->io_date : ''}}" id="export_date" name="export_date" class="form-control" data-parsley-required data-parsley-trigger="change" placeholder="请选择日期" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 m-b">
                            <div class="form-group">
                                <label for="ship_name" class="col-sm-4 control-label necessary_front">船名航次</label>
                                <div class="col-sm-8">
                                    <input type="text" value="{{ isset($customs->transport_name) ? $customs->transport_name : ''}}{{ isset($customs->transport_vovage_no) ? $customs->transport_vovage_no : ''}}"
                                    id="ship_name" name="ship_name" class="form-control" data-parsley-required="" data-parsley-trigger="change" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 m-b">
                            <div class="form-group">
                                <label for="loading_mode" class="col-sm-4 control-label necessary_front">装柜方式</label>
                                <div class="col-sm-8">
                                    <select type="text" id="loading_mode" name="loading_mode" class="form-control" data-parsley-required="" data-parsley-trigger="change" />
                                        <option value="192">整箱</option>
                                        <option value="193">拼箱</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 m-b">
                            <div class="form-group">
                                <label for="box_type" class="col-sm-4 control-label">货柜箱型</label>
                                <div class="col-sm-8">
                                    <input type="text" value="{{ isset($customs->box_type) ? $customs->box_type : ''}}" id="box_type" name="box_type" class="form-control" data-parsley-trigger="change" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 m-b">
                            <div class="form-group">
                                <label for="tdnumber" class="col-sm-4 control-label">货柜提单号</label>
                                <div class="col-sm-8">
                                    <input type="text" value="{{ isset($customs->lading_bill_no) ? $customs->lading_bill_no : ''}}" id="tdnumber" name="tdnumber" class="form-control" data-parsley-trigger="change" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 m-b">
                            <div class="form-group">
                                <label for="box_number" class="col-sm-4 control-label">货柜箱号</label>
                                <div class="col-sm-8">
                                    <input type="text" value="{{ isset($customs->container_no) ? $customs->container_no : ''}}" name="box_number" class="form-control" data-parsley-required="" data-parsley-trigger="change" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 m-b">
                            <div class="form-group">
                                <label for="transport" class="col-sm-4 control-label necessary_front">运输方式</label>
                                <div class="col-sm-8">
                                    <input type="text" value="{{ isset($customs->transport_code) ? $customs->transport_code : ''}}" id="transport_name" class="form-control" onclick="dataLayer(16,'运输方式',$(this))" data-parsley-required="" data-parsley-trigger="change" readonly />
                                    <input type="hidden" id="transport" name="transport" value="" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 m-b">
                            <div class="form-group">
                                <label for="clearance_port" class="col-sm-4 control-label necessary_front">出境关别</label>
                                <div class="col-sm-8">
                                    <input type="text" value="{{ isset($customs->io_port) ? $customs->io_port : ''}}" id="clearance_port_name" class="form-control" onclick="dataLayer(2,'出境关别',$(this))" data-parsley-required="" data-parsley-trigger="change" readonly />
                                    <input type="hidden" id="clearance_port" name="clearance_port" value="" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 m-b">
                            <div class="form-group">
                                <label for="plate_number" class="col-sm-4 control-label">集装箱号</label>
                                <div class="col-sm-8">
                                    <input type="text" value="{{ isset($customs->plate_number) ? $customs->plate_number : ''}}" id="plate_number" name="plate_number" class="form-control" data-parsley-trigger="change" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 m-b">
                            <div class="form-group">
                                <label for="miscellaneous_fee" class="col-sm-4 control-label">杂费</label>
                                <div class="col-sm-8">
                                    <input type="text" value="{{ isset($customs->mixed_fee_rate) ? $customs->mixed_fee_rate : ''}}" id="miscellaneous_fee" name="miscellaneous_fee" class="form-control" data-parsley-trigger="change" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 m-b">
                            <div class="form-group">
                                <label for="transport_fee" class="col-sm-4 control-label">运费</label>
                                <div class="col-sm-8">
                                    <input type="text" value="{{ isset($customs->freight_rate) ? $customs->freight_rate : ''}}" id="transport_fee" name="transport_fee" class="form-control" data-parsley-trigger="change" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 m-b">
                            <div class="form-group">
                                <label for="insurance_fee" class="col-sm-4 control-label">保险费</label>
                                <div class="col-sm-8">
                                    <input type="text" value="{{ isset($customs->premium_rate) ? $customs->premium_rate : ''}}" id="insurance_fee" name="insurance_fee" class="form-control" data-parsley-trigger="change" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 m-b">
                            <div class="form-group">
                                <label for="shipping_mark" class="col-sm-4 control-label">唛头</label>
                                <div class="col-sm-8">
                                    <input type="text" value="{{ isset($customs->shipping_mark) ? $customs->shipping_mark : ''}}" id="shipping_mark" name="shipping_mark" class="form-control" data-parsley-trigger="change" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 m-b">
                            <div class="form-group">
                                <label for="white_card" class="col-sm-4 control-label">白卡号</label>
                                <div class="col-sm-8">
                                    <input type="text" value="{{ isset($customs->white_card) ? $customs->white_card : ''}}" id="white_card" name="white_card" class="form-control" data-parsley-trigger="change" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 m-b">
                            <div class="form-group">
                                <label for="plate_number" class="col-sm-4 control-label">车牌号</label>
                                <div class="col-sm-8">
                                    <input type="text" value="{{ isset($customs->plate_number) ? $customs->plate_number : ''}}" id="plate_number" name="plate_number" class="form-control" data-parsley-trigger="change" />
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="m-b-xs m-l-sm" style="line-height: 19px;">
                            <!-- <b class="badge bg-danger">5</b> -->
                            <label class="h5 text-danger m-l-xs">其他</label>
                        </div>
                        <div class="col-sm-4 m-b">
                            <div class="form-group">
                                <label for="is_special" class="col-sm-4 control-label">特殊关系确认</label>
                                <div class="col-sm-8">
                                    <select id="is_special" class="form-control" name="is_special" data-parsley-required data-parsley-trigger="change">
                                        <option value="0">否</option>
                                        <option value="1">是</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 m-b">
                            <div class="form-group">
                                <label for="is_pay_special" class="col-sm-4 control-label">支持特权使用费确认</label>
                                <div class="col-sm-8">
                                    <select id="is_pay_special" class="form-control" name="is_pay_special" data-parsley-required data-parsley-trigger="change">
                                        <option value="0">否</option>
                                        <option value="1">是</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 m-b">
                            <div class="form-group">
                                <label for="operator" class="col-sm-4 control-label">操作人</label>
                                <div class="col-sm-8">
                                    <input type="text" value="{{ isset($customs->operator) ? $customs->operator : ''}}" id="operator" name="operator" class="form-control" />
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="clearfix"></div>
                    <footer style="text-align:center">
                        <button class="btn btn-success submit_btn">提交</button>
                    </footer>
                </div>
            </div>
        </section>
    </section>
</section>
<script>
    $(function(){
        //匹配字段id
        field_match_id()
        function field_match_id(){
            let data = [] //商品数组
                tr_list = $('.otbody tr')
            $.each(tr_list,function(i,v){
                data.push({'measure_unit_cn':$(this).find(`input.measure_unit_cn`).val(),'default_unit_name':$(this).find(`input.default_unit_name`).val(),
                'destination_country':$(this).find(`input.destination_country`).val(),'domestic_source':$(this).find(`input.domestic_source`).val(),
                'drawer_name':$(this).find('input.drawer_name').val()})
            })
            console.log(data);
            $.ajax({
                url: '/admin/order/field_match_id',
                method: 'post',
                data: {
                    customer_name: $('#cusName').val(),
                    company: $('#company_name').val(),
                    trader_name: $('#trader_name').val(),
                    clearance_port_name:$('#clearance_port_name').val(),
                    price_clause_name:$('#price_clause_name').val(),
                    aim_country_name:$('#aim_country_name').val(),
                    unloading_port_name:$('#unloading_port_name').val(),
                    package_name:$('#package_name').val(),
                    transport_name:$('#transport_name').val(),
                    pro: data
                },
                success: function (res) {
                    console.log(res);
                    if(res.code == 200){
                        $.each(res.data,function(i,v){
                            if(v != ''){
                                $(`input[name=${i}]`).val(v)
                                if(i == 'aim_country'){
                                    $('#trade_country').val($('#aim_country_name').val())
                                    $(`input[name=${i}],input[name=trade_country]`).val(v)
                                }
                            } else {
                                $(`input#${i}_name`).val('')
                                if(i == 'trader_id'){
                                    $('input#trader_name').val('')
                                }
                                if(i == 'customer_id'){
                                    $('input#cusName').val('')
                                }
                            }
                        })
                        $.each($('.otbody tr'),function(i,item){
                            let $this = $(this)
                                input = $this.find('input.pro_match')
                                name = ''
                            $.each(res.data.pro,function(k,j){
                                $.each(input,function(){
                                    name = $(this).data('name')
                                    let value = j[`${name}`]
                                    if(k == i){
                                        if(value != ''){
                                            $(`input[name='pro[${i}][${name}]'`).val(value)
                                        } else {
                                            $(`input[name='pro[${i}][${name}]'`).prev().val('')
                                        }
                                    }
                                })
                            })
                        })
                    } else {
                        layer.msg(res.msg)
                    }
                }
            })
        }
        $(document).on('change','.propertychange',function(){
            console.log(1);
            $('#unloading_port_name,#unloading_port').val('')
        })
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
            let th = $(this)
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
                            th.val(cusname)
                            th.parent().find('input[type=hidden]').val(id)
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
                        }
                    })
                }
            })
        })
        // 选择离境口岸
        $(document).off('click','.choose_port')
        $(document).on('click','.choose_port',function () {
            //选择国家
            var th=$(this),
                outerTitle = '选择离境口岸'
                api = '/admin/port/list';
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
                                portName = layero.find('.bg-active .port_name').text()
                            th.val(portName)
                            th.parent().find('input[type=hidden]').val(id)
                            layer.close(i)
                        }
                    })
                }
            })
        })
        // 选择国家
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
                        }
                    })
                }
            })
        })
        //选择单位
        $(document).off('click','.unit_openCon')
        $(document).on('click','.unit_openCon',function () {
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
                        }
                    })
                }
            })
        })
        $('.relation_btn').on('click',function () {
            let btn = ['添加并关联开票人','取消']
                width = (document.body.clientWidth) * 0.85 + 'px'
                height = (document.body.clientHeight) * 0.75 +'px'
                customer_id = $('#customer_id').val()
                $this = $(this)
            if(!customer_id){
                layer.msg('请先选择客户')
                return false
            }
            $.ajax({
                url: '/admin/order/drawer_product_relate',
                method: 'get',
                success: function (res) {
                    layer.open({
                        type: 1,
                        title: '商品明细',
                        area: [width,height],
                        offset: '50px',
                        btn: btn,
                        maxmin: true,
                        content: res,
                        yes: function (index,layero) {
                            var flag = validated($(".proDetail form"))
                            if(flag){
                                let data = layero.find('.proForm').serializeObject()
                                $.ajax({
                                    url: '/admin/order/drawer_product_relate',
                                    method: 'post',
                                    data: data,
                                    success: function (res) {
                                        if(res.code == 200){
                                            //成功后赋值
                                            let input_list = layero.find('input')
                                            $.each(input_list,function(i,item){
                                                let id = $(this).attr('id')
                                                    val = $(this).val()
                                                $this.closest('tr').children().find(`input[data-name='${id}']`).val(val)
                                            })
                                            let goods_attribute = layero.find('#goods_attribute').val()
                                                enjoy_offer = layero.find('#enjoy_offer').val()
                                            $this.closest('tr').children().find(`input[data-name='drawer_product_id']`).val(res.data)
                                            $this.closest('tr').children().find(`input[data-name='goods_attribute']`).val(goods_attribute)
                                            $this.closest('tr').children().find(`input[data-name='enjoy_offer']`).val(enjoy_offer)
                                            sum('number', '.total_num', 4)
                                            sum('total_price', '.total_value', 4)
                                            sum('net_weight', '.total_net_weight', 4)
                                            sum('volume', '.total_volume', 4)
                                            sum('weight', '.total_weight', 4)
                                            sum('pack_number', '.total_packnum', 4)
                                            layer.closeAll()
                                        }
                                        layer.msg(res.msg)
                                    }
                                })
                            }
                        },
                        success: function(layero,index){
                            let pro_input = $this.closest('tr').children().find('input')
                            $.each(pro_input,function(i,item){
                                if($(this).val() != ''){
                                    let name = $(this).data('name')
                                    layero.find(`input#${name}`).val($(this).val())
                                }
                            })
                        }
                    })
                }

            })
        })
        $('.del_btn').on('click',function () {
            let $this = $(this)
            layer.confirm('是否删除此产品',function(index){
                $this.closest('tr').remove()
                layer.close(index)
            })
        })
        //提交订单
        $('.submit_btn').on('click',function(){

            let data= ''
                data1 = $('.ord_form').serializeObject()
                data2 = $('.pro_table_form').serializeObject()
                data3 = $('.form_3').serializeObject()
                data = $.extend(data1,data2,data3)
            $.ajax({
                url: '/admin/order/customs_order_save',
                method: 'post',
                data: data,
                success: function (res) {
                    if(res.code == 200){
                        window.close()
                        $('.submit_btn').hide()
                    }
                    layer.msg(res.msg)
                }
            })
        })
    });
    function dataLayer(id,name,th) {
        let title = '选择' + name
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
                    let id = layero.find('.bg-active').data('id')
                        name = layero.find('.bg-active').data('name')
                    $(this).addClass('bg-active').siblings().removeClass('bg-active');
                    th.val(name)
                    th.parent().find('input[type=hidden]').val(id)
                    layer.close(index)
                })
            }
        })
    }
    function dataList(th,id){
        var html = ''
            url =  ''
        if(id == 14){
            let aim = $('.aim_country').val()
            if(aim != ''){
                url = '/admin/order/harbor/' + aim
            } else {
                layer.msg('请先选择运抵国')
                return false
            }
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
    function sum(num, aim, dot) { //计算公式
        var total = 0
        $('input.' + num ).map(function (index, item) {
            item.value == '' ? (a = 0) : (a = item.value * 1)
            total = total + a
        })
        total = total.toFixed(dot)
        $(aim).val(total)
    }
</script>
@endsection
