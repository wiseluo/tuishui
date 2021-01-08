<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Route;

class OrderPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
        
    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, response()->json(['code'=> 403, 'msg'=> $validator->errors()->first()]));
    }
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('id');
        switch(Route::currentRouteName()){ //当前路由别名
            case 'adminOrderDraftSave' : //草稿
            case 'adminOrderDraftUpdate' :
                return [
                    'customer_id' =>'required',
                    'ordnumber' => 'required|unique:orders,ordnumber,'. $id .',id,deleted_at,NULL',
                    'pro' => 'array',
                    'pro.*.drawer_product_id' => 'required|integer',
                    'pro.*.standard' => 'required',
                    'pro.*.company' => 'required',
                    'pro.*.value' => 'required|numeric',
                    'pro.*.tax_refund_rate' => 'required|numeric',
                    'pro.*.tax_rate' => 'required|numeric',
                    'pro.*.total_price' => 'required|numeric|min:1',
                    'pro.*.goods_attribute' => 'required|numeric',
                    'pro.*.pack_number' => 'required|numeric',
                    'pro.*.net_weight' => 'required|numeric',
                    'pro.*.total_weight' => 'required|numeric',
                    'pro.*.volume' => 'required|numeric',
                    'pro.*.number' => 'required|numeric',
                    'pro.*.measure_unit' => 'required|integer',
                    'pro.*.single_price' => 'required|numeric',
                    'pro.*.default_num' => 'required|numeric',
                    'pro.*.default_unit_id' => 'required|integer',
                    'pro.*.origin_country_id' => 'required|integer',
                    'pro.*.destination_country_id' => 'required|integer',
                    'pro.*.domestic_source_id' => 'required|integer',
                    'pro.*.merge' => 'required|integer',
                ];
            case 'adminOrderSave' :
            case 'adminOrderUpdate' :
            case 'customsOrderSave' :
                return [
                    'customer_id' =>'required',
                    'ordnumber' => 'required|unique:orders,ordnumber,'. $id .',id,deleted_at,NULL',
                    'company_id' =>'required',
                    //'clearance_port' =>'required',
                    'declare_mode' =>'required',
                    'business' =>'required',
                    'currency' =>'required',
                    'price_clause' =>'required',
                    'package' =>'required',
                    'loading_mode' =>'required',
                    'order_package' =>'required',
                    'trader_id' =>'required',
                    'shipment_port' =>'required|exists:ports,id,deleted_at,NULL',
                    'trade_country' =>'required',
                    'aim_country' =>'required',
                    'unloading_port' =>'required|exists:ports,id,deleted_at,NULL',
                    'transport' =>'required',
                    'box_number' =>'required',
                    'ship_name' =>'required',
                    'customs_at' =>'required',
                    'sailing_at' =>'required',
                    'pro' => 'required|array',
                    'pro.*.drawer_product_id' => 'required|integer',
                    'pro.*.standard' => 'required',
                    'pro.*.company' => 'required',
                    //'pro.*.value' => 'required|numeric',
                    'pro.*.tax_refund_rate' => 'required|numeric',
                    'pro.*.tax_rate' => 'required|numeric',
                    'pro.*.total_price' => 'required|numeric|min:1',
                    'pro.*.goods_attribute' => 'required|numeric',
                    'pro.*.pack_number' => 'required|numeric',
                    'pro.*.net_weight' => 'required|numeric',
                    'pro.*.total_weight' => 'required|numeric',
                    'pro.*.volume' => 'required|numeric',
                    'pro.*.number' => 'required|numeric',
                    'pro.*.measure_unit' => 'required|integer',
                    'pro.*.single_price' => 'required|numeric',
                    'pro.*.default_num' => 'required|numeric',
                    'pro.*.default_unit_id' => 'required|integer',
                    'pro.*.origin_country_id' => 'required|integer',
                    'pro.*.destination_country_id' => 'required|integer',
                    'pro.*.domestic_source_id' => 'required|integer',
                    'pro.*.merge' => 'required|integer',
                ];
            case 'drawerProductRelate' :
                return [
                    'drawer_id' => 'required|integer',
                    'name' => 'required',
                    'en_name' => 'required',
                    'hscode' => 'required',
                    'standard' => 'required',
                    'measure_unit' => 'required|integer',
                    'tax_refund_rate' => 'required|numeric',
                ];
            // case 'fieldMatchId' :
            //     return [
            //         'company' => 'required',
            //         'customer_name' => 'required',
            //         'trader_name' => 'required',
            //         'price_clause_name' => 'required',
            //         'aim_country_name' => 'required',
            //         'unloading_port_name' => 'required',
            //         'package_name' => 'required',
            //         'transport_name' => 'required',
            //         'clearance_port_name' => 'required',
            //         'pro' => 'required|array',
            //         'pro.*.drawer_name' => 'required',
            //         'pro.*.measure_unit_cn' => 'required',
            //         'pro.*.default_unit_name' => 'required',
            //         'pro.*.destination_country' => 'required',
            //         'pro.*.domestic_source' => 'required',
            //     ];
            default :
                return [];
        }
    }
    
    public function messages(){
        return [
            'customer_id.required' => '请选择客户',
            'ordnumber.required' => '请填写订单号',
            'ordnumber.unique' => '订单号重复请重新输入',
            'company_id.required' => '请选择经营单位',
            //'clearance_port.required' =>'请选择出境关别',
            'declare_mode.required' => '请选择报关方式',
            'business.required' => '请选择业务类型',
            'currency.required' => '请选择报关币种',
            'price_clause.required' => '请选择价格条款',
            'package.required' => '请选择包装方式',
            'loading_mode.required' => '请选择装柜方式',
            'order_package.required' => '请填写整体包装方式',
            'trader_id.required' => '请选择贸易商',
            'shipment_port.required' => '离境口岸必填',
            'shipment_port.exists' => '离境口岸数据错误',
            'trade_country.required' => '贸易国必填',
            'aim_country.required' => '运抵国必填',
            'unloading_port.exists' => '抵运港数据错误',
            'transport.required' => '请选择运输方式',
            'box_number.required' => '请填写货柜箱号',
            'ship_name.required' => '请填写船名',
            'customs_at.required' => '报关日期必填',
            'sailing_at.required' => '开船日期必填',
            
            'pro.required' => '开票产品必填',
            'pro.array' => '开票产品必须是数组',
            'pro.*.drawer_product_id.required' => '开票产品id必填',
            'pro.*.standard.required' =>'申报要素必填',
            'pro.*.company.required' => '开票人名称必填',
            'pro.*.goods_attribute.required' => '货物属性必填',
            'pro.*.pack_number.required' => '最大包装件数必填',
            'pro.*.pack_number.numeric' => '最大包装件数必须是数字',
            'pro.*.net_weight.required' => '净重必填',
            'pro.*.net_weight.numeric' => '净重必须是数字',
            'pro.*.total_weight.required' => '毛重必填',
            'pro.*.total_weight.numeric' => '毛重必须是数字',
            'pro.*.volume.required' => '体积必填',
            'pro.*.volume.numeric' => '体积必须是数字',
            'pro.*.number.required' => '产品数量必填',
            'pro.*.number.numeric' => '产品数量必须是数字',
            'pro.*.measure_unit.required' => '申报单位必填',
            'pro.*.single_price.required' => '单价必填',
            'pro.*.single_price.numeric' => '单价必须是数字',
            'pro.*.default_num.required' => '法定数量必填',
            'pro.*.default_num.numeric' => '法定数量必须是数字',
            'pro.*.default_unit.required' => '法定单位必填',
            'pro.*.default_unit_id.required' => '法定单位必填',
            'pro.*.default_unit_id.integer' => '法定单位id必须是整数',
            'pro.*.domestic_source_id.required' => '境内货源地必填',
            'pro.*.domestic_source_id.integer' => '境内货源地id必须是整数',
            'pro.*.origin_country_id.required' => '原产国必填',
            'pro.*.origin_country_id.integer' => '原产国id必须是整数',
            'pro.*.destination_country_id.required' => '最终目的国必填',
            'pro.*.destination_country_id.integer' => '最终目的国id必须是整数',
            'pro.*.value.required' =>'产品开票金额必填',
            'pro.*.value.numeric' =>'产品开票金额必须是数字',
            'pro.*.tax_refund_rate.required' =>'产品退税率必填',
            'pro.*.tax_refund_rate.numeric' =>'产品退税率必须是数字',
            'pro.*.total_price.required' =>'产品报关金额必填',
        	'pro.*.total_price.numeric' =>'产品报关金额必须是数字',
        	'pro.*.total_price.min' =>'产品报关金额必填大于0',
            'pro.*.merge.required' => '产品分组必填',
            'pro.*.merge.integer' => '产品分组必须是整数',

            'drawer_id.required' =>'请选择开票人',
            'name.required' =>'产品名称必填',
            'en_name.required' => '英文品名必填',
            'hscode.required' =>'hscode必填',
            'standard.required' =>'申报要素必填',
            'measure_unit.required' => '申报单位必填',
            'measure_unit.integer' => '申报单位id必须是整数',
            'tax_refund_rate.required' =>'产品退税率必填',
            'tax_refund_rate.numeric' =>'产品退税率必须是数字',

            'company.required' => '经营单位必填',
            'customer_name.required' => '客户名称必填',
            'trader_name.required' => '贸易商名称必填',
            'price_clause_name.required' => '价格条款必填',
            'aim_country_name.required' => '运抵国必填',
            'unloading_port_name.required' => '指运港必填',
            'package_name.required' => '包装方式必填',
            'transport_name.required' => '运输方式必填',
            'clearance_port_name.required' => '出境关别必填',
            'pro.*.drawer_name.required' => '开票人名称必填',
            'pro.*.measure_unit_cn.required' => '申报单位必填',
            'pro.*.default_unit_name.required' => '法定单位必填',
            'pro.*.destination_country.required' => '最终目的国必填',
            'pro.*.domestic_source.required' => '境内货源地必填',
        ];
    }
}
