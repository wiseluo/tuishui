<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Route;

class CompanyPostRequest extends FormRequest
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
        return [
            'name' => 'required|unique:companies,name,'. $id .',id,deleted_at,NULL',
            'customs_code' => 'required',
            'tax_id' => 'required',
            'domain' => 'required|unique:companies,domain,'. $id .',id,deleted_at,NULL',
            'invoice_name' => 'required',
            'invoice_en_name' => 'required',
            'address' => 'required',
            'telephone' => 'required',
            'bankname' => 'required',
            'bankaccount' => 'required',
            'invoice_receipt_addr' => 'required',
            'invoice_recipient' => 'required',
            'recipient_call' => 'required',
        ];
    }
        
    public function messages(){
        return [
            'name.required' => '名称必填',
            'name.unique' => '该名称已存在',
            'customs_code.required' => '海关编码必填',
            'tax_id.required' => '纳税人识别号必填',
            'domain.required' => '域名必填',
            'domain.unique' => '域名已存在',
            'invoice_name.required' => '发票名称必填',
            'invoice_en_name.required' => '发票英文名称必填',
            'address.required' => '地址必填',
            'telephone.required' => '电话必填',
            'bankname.required' => '开户行必填',
            'bankaccount.required' => '账号必填',
            'invoice_receipt_addr.required' => '发票收件地址必填',
            'invoice_recipient.required' => '发票收件人必填',
            'recipient_call.required' => '收件人电话必填',
        ];
    }
}
