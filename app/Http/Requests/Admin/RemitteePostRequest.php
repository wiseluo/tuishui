<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Route;

class RemitteePostRequest extends FormRequest
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
            case 'adminRemitteeSave' :
                return [
                    'remit_type' =>'required',
                    'remit_id' =>'required', 
                    'name' =>'required', 
                    'number' =>'required',
                    'bank' =>'required',
                ];
            case 'adminRemitteeUpdate' :
                return [
                    'name' =>'required', 
                    'number' =>'required',
                    'bank' =>'required',
                ];
            default :
                return [];
        }
    }
        
    public function messages(){
        return [
            'remit_type.required' => '请选择收款单位类型',
            'remit_id.required' => '请选择收款单位',
            'name.required' => '请填写收款户名',
            'number.required'  => '请填写收款账号',
            'bank.required'  => '请填写开户银行',
        ];
    }
}
