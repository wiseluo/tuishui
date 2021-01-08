<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Route;

class TraderPostRequest extends FormRequest
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
            'name' =>'required|unique:traders,name,'. $id .',id,deleted_at,NULL',
            'customer_id' =>'required',
            'country_id' =>'required',
            'address' =>'required',
            'email' =>'required',
            'cellphone' =>'required',
        ];
    }
        
    public function messages(){
        return [
            'name.required' => '请填写贸易商名称',
            'name.unique' => '该贸易商名称已存在',
            'customer_id.required' => '客户必填',
            'country_id.required' => '请填写国家',
            'address.required'  => '请填写地址',
            'email.required'  => '请填写邮箱',
            'cellphone.required'  => '请填写手机号',
        ];
    }
}
