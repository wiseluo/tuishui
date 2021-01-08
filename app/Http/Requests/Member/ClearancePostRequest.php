<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Route; 

class ClearancePostRequest extends FormRequest
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
        switch(Route::currentRouteName()){
            case 'memberCustomsEntry' :
                return [
                    'clearance_port' =>'required',
                    'customs_number' =>'required',
                    'export_date' =>'required|date_format:Y-m-d',
                    'pro' => 'required|array',
                    'pro.*.drawer_product_order_id' => 'required|integer',
                    'pro.*.value' => 'required|numeric',
                ];
            default :
                return [];
        }
    }
    
     public function messages(){
        return [
            'clearance_port.required' =>'请选择出境关别',
            'customs_number.required' => '请填写报关单号',
            'export_date.required' => '请填写出口日期',
            'pro.required' => '开票产品必填',
            'pro.array' => '开票产品必须是数组',
            'pro.*.drawer_product_order_id.required' => '订单开票产品id必填',
            'pro.*.value.required' =>'产品开票金额必填',
            'pro.*.value.numeric' =>'产品开票金额必须是数字',
        ];
    }
}
