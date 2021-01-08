<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Route; 

class OrderApiRequest extends FormRequest
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
        throw new HttpException(401, $validator->errors()->first());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch(Route::currentRouteName()){
            case 'apiOrderSave' :
            case 'apiOrderUpdate' :
                return [
                    //'clearance_port' =>'required',
                    'declare_mode' =>'required',
                    'currency' =>'required',
                    'price_clause' =>'required',
                    'package' =>'required',
                    'loading_mode' =>'required',
                    'order_package' =>'required',
                    'shipment_port' =>'required|exists:ports,id,deleted_at,NULL',
                    'trade_country' =>'required',
                    'aim_country' =>'required',
                    'unloading_port' =>'required|exists:ports,id,deleted_at,NULL',
                    'transport' =>'required',
                    'link_id' => 'required|exists:links,id,deleted_at,NULL',
                    'receive_id' => 'exists:receives,id,deleted_at,NULL',
                    'deposit_id' => 'exists:deposits,id,deleted_at,NULL',
                    'customs_at' =>'required',
                    'sailing_at' =>'required',

                    'pro' => 'required|array',
                    'pro.*.drawer_product_id' => 'required|numeric',
                    'pro.*.standard' => 'required',
                    'pro.*.company' => 'required',
                    'pro.*.value' => 'required|numeric',
                    'pro.*.tax_refund_rate' => 'required|numeric',
                    'pro.*.tax_rate' => 'required|numeric',
                    'pro.*.total_price' => 'required|numeric|min:1',
                    'pro.*.domestic_source' => 'required',
                    'pro.*.origin_country' => 'required',
                    'pro.*.destination_country' => 'required',
                    'pro.*.goods_attribute' => 'required|numeric',
                    'pro.*.pack_number' => 'required|numeric',
                    'pro.*.net_weight' => 'required|numeric',
                    'pro.*.total_weight' => 'required|numeric',
                    'pro.*.volume' => 'required|numeric',
                    'pro.*.number' => 'required|numeric',
                    'pro.*.single_price' => 'required',
                    'pro.*.measure_unit' => 'required',
                    'pro.*.default_num' => 'required|numeric',
                    'pro.*.default_unit' => 'required',
                    'pro.*.default_unit_id' => 'required|integer',
                    'pro.*.origin_country_id' => 'required|integer',
                    'pro.*.destination_country_id' => 'required|integer',
                    'pro.*.domestic_source_id' => 'required|integer',
                ];
            default :
                return [];
        }
    }
    
    public function messages(){
        return [
            'link_id.required' => '本单联系人必填',
            'link_id.exists' => '本单联系人数据错误',
            'currency.required' => '请选择报关币种',
            'price_clause.required' => '请选择成交方式',
            'order_package.required' => '请填写整体包装方式',
            'shipment_port.required' => '离境口岸必填',
            'shipment_port.exists' => '离境口岸数据错误',
            'trade_country.required' => '贸易国必填',
            'aim_country.required' => '运抵国必填',
            'unloading_port.exists' => '抵运港数据错误',
            'transport.required' =>'请选择运输方式',
            'package.required' => '请选择包装种类',
            'receive_id.exists' => '境外收货人数据错误',
            'deposit_id.exists' => '货物存放地址数据错误',
            'declare_mode.required' => '请选择报关方式',
            'customs_at.required' =>'报关日期必填',
            'sailing_at.required' =>'开船日期必填',
            
            'pro.required' => '开票产品必填',
            'pro.array' => '开票产品必须是数组',
            'pro.*.drawer_product_id.required' => '开票产品id必填',
            'pro.*.standard.required' =>'申报要素必填',
            'pro.*.company.required' => '开票人名称必填',
            'pro.*.domestic_source.required' => '境内货源地必填',
            'pro.*.origin_country.required' => '原产国必填',
            'pro.*.destination_country.required' => '最终目的国必填',
            'pro.*.goods_attribute.required' => '货物属性必填',
            'pro.*.pack_number.required' => '最大包装件数必填',
            'pro.*.net_weight.required' => '净重必填',
            'pro.*.total_weight.required' => '毛重必填',
            'pro.*.volume.required' => '体积必填',
            'pro.*.number.required' => '产品数量必填',
            'pro.*.measure_unit.required' => '申报单位必填',
            'pro.*.single_price.required' => '单价必填',
            'pro.*.default_num.required' => '法定数量必填',
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
            'pro.*.tax_refund_rate.required' =>'产品退税率必填',
            'pro.*.total_price.required' =>'产品报关金额必填',
            'pro.*.total_price.numeric' =>'产品报关金额必填是数字',
            'pro.*.total_price.min' =>'产品报关金额必填大于0',
        ];
    }

    // protected function validationData()
    // {
    //     return $this->input('data');
    // }
}
