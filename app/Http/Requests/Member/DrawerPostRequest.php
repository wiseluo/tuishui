<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Route;

class DrawerPostRequest extends FormRequest
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
            case 'memberDrawerSave' :
            case 'memberDrawerUpdate' :
                return [
                    'customer_id' =>'required|integer',
                    'company' =>'required',
                    'telephone' =>'required',
                    'tax_id' =>'required',
                    'tax_at' =>'required',
                    'address' =>'required',
                    'domestic_source_id' =>'required',
                    'pic_register' =>'required|image_str:jpeg,png,bmp,gif,svg,png,jpg',
                    'pic_verification' =>'required|image_str:jpeg,png,bmp,gif,svg,png,jpg',
                    //'pic_business_license' =>'required|image_str:jpeg,png,bmp,gif,svg,png,jpg|max:255',
                    'pic_brand' =>'required|image_str:jpeg,png,bmp,gif,svg,png,jpg',
                    'pic_production' =>'required|image_str:jpeg,png,bmp,gif,svg,png,jpg',
                    //'pic_home' =>'required|image_str:jpeg,png,bmp,gif,svg,png,jpg|max:255',
                    'tax_rate' =>'required',
                    'export' =>'required',
                    'addressee' =>'required',
                    'raddress' =>'required',
                    // 'customs_code' => 'required_if:export,0',
                    // 'tax_customs_code' => 'required_if:export,1',
                ];
            case 'memberDrawerRelateProducts' :
                return [
                    'pid' => 'required|array',
                ];
            case 'taxDrawerList' :
                return [
                    'tax_type' => 'required|in:0,1',
                    'company_id' => 'required|integer',
                ];
            case 'memberDrawerUpdateDone' :
                return [
                    'company' =>'required',
                    'telephone' =>'required',
                    'tax_at' =>'required',
                    'address' =>'required',
                    'domestic_source_id' =>'required',
                    'pic_register' =>'required|image_str:jpeg,png,bmp,gif,svg,png,jpg',
                    'pic_verification' =>'required|image_str:jpeg,png,bmp,gif,svg,png,jpg',
                    'pic_brand' =>'required|image_str:jpeg,png,bmp,gif,svg,png,jpg',
                    'pic_production' =>'required|image_str:jpeg,png,bmp,gif,svg,png,jpg',
                    'tax_rate' =>'required|integer',
                    'export' =>'required',
                    'addressee' =>'required',
                    'raddress' =>'required',
                ];
            default :
                return [];
        }
    }
    
     public function messages(){
        return [
            'customer_id.required' => '请选择客户名称',
            'company.required' => '请填写开票人公司名称',
            'telephone.required' => '请填写开票人联系方式',
            'tax_id.required'  => '请填写纳税人识别号',
            'licence.required'  => '请填写营业执照注册号',
            'tax_at.required' => '请选择纳税人认定时间',
            'address.required' => '请填写开票人地址',
            'domestic_source_id.required' => '境内货源地必须',
            'pic_register.required' => '请选择营业执照图片',
            'pic_register.image_str' => '上传营业执照图片不合法',
            'pic_verification.required' => '请选择上传纳税人认定证书图片',
            'pic_verification.image_str' => '上传纳税人认定证书图片不合法',
            'pid.required' => '请选择产品信息',
            'tax_rate.required' => '请填写增值税率',
        ];
    }
}
