<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Route;

class CustomerPostRequest extends FormRequest
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
            case 'memberCustomerUpdate' :
                return [
                    'bill_base' => 'required',
                    'service_rate' => 'required',
                    'address' =>'required|max:255',
                ];
            case 'memberCustomerUpdateDone' :
                return [
                    'bill_base' => 'required',
                    'service_rate' => 'required',
                    'address' => 'required|max:255',
                ];
            default :
                return [];
        }
    }
    
     public function messages(){
        return [
            'number.required' => '客户编号不能为空',
            'number.max' => '客户编号至多18个字符',
            'number.unique' => '客户编号已存在',
            'name.required'  => '客户名称不能为空',
            'name.max'  => '客户名称至多150个字符',
            'linkman.required' => '联系人不能为空',
            'linkman.max' => '联系人名称至多150个字符',
            'telephone.required' => '联系电话不能为空',
            'telephone.min' => '联系电话长度至少8位',
            'address.required' => '地址不能为空',
            'address.max' => '地址至多255个字符',
        ];
    }
}
