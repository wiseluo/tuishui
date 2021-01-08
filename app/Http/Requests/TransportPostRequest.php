<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Route; 

class TransportPostRequest extends FormRequest
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
        return [
            'applied_at' =>'required',
            'orderName' =>'required', 
            'name' =>'required', 
            'billed_at' =>'required',
            'number' =>'required',
            'money' =>'required',
            //'picture' =>'required|image_str:jpeg,png,bmp,gif,svg,png,jpg',
        ];
    }
    
     public function messages(){
        return [
            'applied_at.required' => '请选择申请日期',
            'orderName.required' => '请选择订单号',
            'name.required' => '请填写开票名称',
            'billed_at.required'  => '请选择开票日期',
            'number.required'  => '请填写发票号码',
            'money.required' => '请填写金额',
            //'picture.required' => '请上传相关图片',
            //'picture.image_str' => '上传图片信息不合法',
        ];
    }
}
