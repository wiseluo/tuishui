<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BillingPostRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' =>'required',
            'identification_num' =>'required',
            'address' =>'required',
            'telephone' =>'required',
            'bankname' =>'required',
            'bankaccount' =>'required',
            'invoice_receipt_addr' =>'required',
            'invoice_recipient' =>'required',
            'recipient_call' =>'required',
            'customs_code' =>'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '请填写开票名称',
            'identification_num.required' => '请填写纳税人识别号',
            'address.required'  => '请填写地址',
            'telephone.required'  => '请填写电话',
            'bankname.required'  => '请填写开户行',
            'bankaccount.required'  => '请填写账号',
            'invoice_receipt_addr.required'  => '请填写发票收件地址',
            'invoice_recipient.required'  => '请填写发票收件人',
            'recipient_call.required'  => '请填写收件人电话',
            'customs_code.required' => '请填写海关编码',
        ];
    }
}
