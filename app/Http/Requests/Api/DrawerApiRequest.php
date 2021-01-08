<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Route; 

class DrawerApiRequest extends FormRequest
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
        throw new HttpException(401, $validator->errors()->first());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch(Route::currentRouteName()){
            case 'apiDrawerUpdate' :
                return [
                    // 'company' =>'required',
                    'telephone' =>'required',
                    'tax_id' =>'required',
                    'tax_at' =>'required',
                    'addressee' =>'required',
                    'raddress' =>'required',
                    'address' =>'required',
                    'domestic_source_id' =>'required',
                    'pic_register' =>'required|image_str:jpeg,png,bmp,gif,svg,png,jpg',
                    'pic_verification' =>'required|image_str:jpeg,png,bmp,gif,svg,png,jpg',
                    //'pic_business_license' =>'required|image_str:jpeg,png,bmp,gif,svg,png,jpg',
                    'pic_brand' =>'required|image_str:jpeg,png,bmp,gif,svg,png,jpg',
                    'pic_production' =>'required|image_str:jpeg,png,bmp,gif,svg,png,jpg',
                    //'pic_home' =>'required|image_str:jpeg,png,bmp,gif,svg,png,jpg',
                    'tax_rate' =>'required',
                    'export' =>'required',
                    'customs_code' => 'required_if:export,0',
                    'tax_customs_code' => 'required_if:export,1',
                ];
            case 'apiDrawerRelateProducts' :
                return [
                    'pid' => 'required|array',
                ];
            case 'apiDrawerUpdateDone' :
                return [
                    'tax_rate' =>'required|integer',
                ];
            default :
                return [];
        }
    }
    
     public function messages(){
        return [
          //  'customer_id.required' => '请选择客户名称',
            'company.required' => '请填写开票人公司名称',
            'telephone.required' => '请填写开票人联系方式',
            'tax_id.required'  => '请填写纳税人识别号',
            'tax_at.required' => '请选择纳税人认定时间',
            'address.required' => '请填写工厂生产地址',
            'domestic_source_id.required' => '境内货源地必须',
            'pic_register.required' => '请选择营业执照图片',
            'pic_register.image_str' => '上传营业执照图片不合法',
            'pic_verification.required' => '请选择上传纳税人认定证书图片',
            'pic_verification.image_str' => '上传纳税人认定证书图片不合法',
            'pic_business_license.required' => '请选择上传税务登记图片',
            'pic_business_license.image_str' => '上传传税务登记图片不合法',
            'pic_brand.required' => '请选择上传厂牌图片',
            'pic_brand.image_str' => '上传厂牌图片不合法',
            'pic_production.required' => '请选择上传生产线图片',
            'pic_production.image_str' => '上传生产线图片不合法',
            'pic_home.required' => '请选择上传厂房租赁合同或房产证',
            'pic_home.image_str' => '上传厂房租赁合同或房产证图片不合法',
            'tax_rate.required' => '请添写开票人增值税率',
            'export.required' => '请添写出口权',
            'addressee.required' => '请添写开票工厂收件人',
            'raddress.required' => '请添写开票工厂收件地址',
            'customs_code.required_if' => '请添写海关注册登记编码',
            'tax_customs_code.required_if' => '请添写税局备案海关代码',

        ];
    }

    // protected function validationData()
    // {
    //     return $this->input('data');
    // }

}
