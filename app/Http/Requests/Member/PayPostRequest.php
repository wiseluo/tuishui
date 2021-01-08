<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Route;

class PayPostRequest extends FormRequest
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
        switch(Route::currentRouteName()){ //当前路由别名
            case 'memberPayOrderChoose' :
                return [
                    'type' => 'required',
                    'customer_id' => 'required',
                    'company_id' => 'required',
                ];
            case 'memberPayReceiptChoose' :
                return [
                    'customer_id' => 'required',
                ];
            case 'memberPayRelateOrder' :
                return [
                    'ord' => 'required|array',
                    'ord.*.order_id' => 'required|integer',
                    'ord.*.money' => 'required|numeric',
                ];
            case 'memberPayRelateReceipt' :
                return [
                    'receipt' => 'required|array',
                    'receipt.*.receipt_id' => 'required|integer',
                    'receipt.*.pay_money' => 'required|numeric',
                ];
            case 'memberPaySave' :
            case 'memberPayUpdate' :
                return [
                    'status' => 'required',
                    'type' =>'required',
                    'customer_id' => 'required|integer',
                    'company_id' => 'required|integer',
                    'remittee_id' => 'required|integer',
                    'money' => 'required|numeric',
                    'content' => 'required',
                    'pay_at' => 'required',
                    'fid' => 'required',
                    'yw_bills' => 'required',
                    'yw_public' => 'required',
                    'dj_count' => 'required',
                    'apply_uid' => 'required',
                    'business_type' => 'required|in:1,2,3,4,5',
                    'ord' => 'required_unless:type,0|array',
                    'ord.*.order_id' => 'required_unless:type,0|integer',
                    'ord.*.money' => 'required_unless:type,0|numeric',
                ];
            default :
                return [];
        }
    }
        
     public function messages(){
        return [
            'type.required' => '请选择款项类型',
            'customer_id.required' => '请选择客户',
            'company_id.required' => '请选择报销公司',
            'remittee_id.required' => '请选择收款单位',
            'money.required' => '金额必填',
            'content.required' => '请填写款项内容',
            'pay_at.required' => '请选择付款日期',
            'fid.required' => '请选择业务类型',
            'yw_bills.required' => '请选择单据类型',
            'yw_public.required' => '请选择支付类型',
            'dj_count.required' => '单据张数必填',
            'apply_uid.required' => '申请人必填',
            'business_type.required' => '业务类型必填',
            'business_type.in' => '业务类型错误',
            'ord.required_unless' => '非定金类型必须关联订单',
            'ord.*.order_id.required_unless' => '订单id必填',
            'ord.*.money.required_unless' => '订单金额必填',
        ];
    }
}
