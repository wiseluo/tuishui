<?php

namespace App\Http\Requests\Erp;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Route; 

class SkapplyPostRequest extends FormRequest
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
        throw new HttpException(403, $validator->errors()->first(), null, ['msg'=> json_encode($validator->errors()->first())]);
    }
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch(Route::currentRouteName()){ //当前路由别名
            case 'skapplyReceiptReturn' :
                return [
                    'token' => 'required',
                    'sk_applyid' => 'required|integer',
                    'status' => 'required|integer',
                    'received_at' => 'required_if:status,3',
                    'amount' => 'required_if:status,3',
                    'payer' => 'required_if:status,3',
                ];
            case 'receiptExchange' :
                return [
                    'token' => 'required',
                    'sk_applyid' => 'required|integer',
                    'rate' => 'required',
                ];
            default :
                return [];
        }
    }
        
    public function messages(){
        return [
            'token.required' => 'token必须',
            'sk_applyid.required' => '业务报销申请id必须',
            'status.required' => '流程状态必须',
            'received_at.required_if' => '收款日期必须',
            'amount.required_if' => '实收金额必须',
            'payer.required_if' => '付款人必须',
            'rate.required' => '汇率必须',
        ];
    }
}
