<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PersonalApiRequest extends FormRequest
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
        return [
            'drawer_name' => 'required|max:150',
            'legal_person' => 'required|string',
            'phone' => 'required|numeric|digits:11',
            'tax_id' => 'required|string',
            'tax_at' => 'required|date_format:Y-m-d',
            'original_addr' => 'required|string',
            'pic_invoice' => 'required|image_str:jpeg,png,bmp,gif,svg,png,jpg',
            'pic_verification' => 'required|image_str:jpeg,png,bmp,gif,svg,png,jpg',
            'pic_register' => 'required|image_str:jpeg,png,bmp,gif,svg,png,jpg',
        ];
    }
    public function messages(){
        return [
            'drawer_name.required'  => '开票人名称不能为空',
            'drawer_name.max' => '开票人名称不能超过150位',
            'legal_person.required' => '法人不能为空',
            'legal_person.string' => '法人必须为字符',
            'phone.required' => '联系电话不能为空',
            'phone.numeric' => '联系电话必须为数字',
            'phone.digits' => '联系电话不能小于11位',
            'tax_id.required' => '纳税人识别号不能为空',
            'tax_id.string' => '纳税人识别号必须为字符',
            'tax_at.required' => '一般纳税人认定时间不能为空',
            'tax_at.date_format' => '一般纳税人认定时间格式必须为Y-m-d',
            'original_addr.required' => '境内货源地不能为空',
            'original_addr.string' => '境内货源地必须为字符',
            'pic_invoice.required' => '发票不能为空',
            'pic_invoice.image_str' => '发票必须为图片',
            'pic_verification.required' => '一般纳税人认定书不能为空',
            'pic_verification.image_str' => '一般纳税人认定书必须为图片',
            'pic_register.required' => '营业执照不能为空',
            'pic_register.image_str' => '营业执照必须为图片',
        ];
    }
}
