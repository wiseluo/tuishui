<?php

namespace App\Http\Requests;

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
        $id = $this->route('id');
        switch(Route::currentRouteName()){
            case 'adminCustomerSave' :
            case 'adminCustomerUpdate' :
                return [
                    'number' => 'required|max:20|unique:customers,number,'. $id .',id,deleted_at,NULL',
                    'name' => 'required|max:150',
                    'ucenterid' => 'required|integer',
                    'card_num' => 'required_if:custype,1',
                    'bill_base' => 'required|integer',
                    'service_rate' => 'required|numeric',
                    'linkman' =>'required|max:150',
                    'telephone' =>'required|min:8',
                    'address' =>'required|max:255',
                    'picture_lic' => 'required|image_str:jpeg,png,bmp,gif,svg,png,jpg',
                ];
            case 'adminCustomerUpdateDone' :
                return [
                    'ucenterid' => 'required|integer',
                    'bill_base' => 'required|integer',
                    'service_rate' => 'required|numeric',
                    'linkman' =>'required|max:150',
                    'telephone' =>'required|min:8',
                    'address' =>'required|max:255',
                    'picture_lic' => 'required|image_str:jpeg,png,bmp,gif,svg,png,jpg',
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
            'ucenterid.required'  => 'ucenterid不能为空',
            'card_num.required_if'  => '证件号码必须',
            'bill_base.required' => '计费基数必须',
            'service_rate.required' => '服务费率必须',
            'linkman.required' => '联系人不能为空',
            'linkman.max' => '联系人名称至多150个字符',
            'telephone.required' => '联系电话不能为空',
            'telephone.min' => '联系电话长度至少8位',
            'address.required' => '地址不能为空',
            'address.max' => '地址至多255个字符',
            'picture_lic.required' => '证照不能为空',
        ];
    }
}
