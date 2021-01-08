<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Route;

class ReceiptPostRequest extends FormRequest
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
            case 'memberBinding' :
                return [
                    'order_id' => 'required|integer',
                    'money' => 'required|numeric',
                ];
            case 'memberUnbinding' :
                return [
                    'order_id' => 'required|integer',
                    'pivot_id' => 'required|integer',
                ];
            case 'memberReceiptSave' :
            case 'memberReceiptUpdate' :
                return [
                    'customer_id' => 'required',
                    'receiptcorp' => 'required',
                    'account' => 'required',
                    'account_id' => 'required',
                    'bank' => 'required',
                    'currency_id' => 'required',
                    'advance_amount' => 'required|numeric',
                    'picture' =>'required',
                    'business_type' => 'required|in:1,2,3,4,5',
                    'exchange_type' => 'required_unless:currency_id,354|in:1,2',
                    'expected_received_at' => 'required|date_format:Y-m-d',
                ];
            case 'memberReceiptExchange' :
                return [
                    'rate' => 'required|numeric',
                ];
            default :
                return [];
        }
    }
    
    public function messages(){
        return [
            'customer_id.required' => '请选择付款方客户',
            'account.required'  => '请选择收款账号',
            'account_id.required' => '请选择收款账号',
            'bank.required' => '请选择开户银行',
            'currency_id.required'  => '请选择币种',
            'advance_amount.required' => '请填写收款金额',
            'advance_amount.numeric' => '收款金额必须是数字',
            'rate.required' => '汇率必填',
            'picture.required' => '附件必须',
            'business_type.required' => '业务类型必填',
            'business_type.in' => '业务类型错误',
            'exchange_type.required_unless' => '结汇方式必填',
            'exchange_type.in' => '结汇方式错误',
            'expected_received_at.required' => '预计收款日期必填',
            'expected_received_at.date_format' => '预计收款日期格式错误',
        ];
    }
}
