<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProductApiRequest extends FormRequest
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
            'data.name' => 'required|max:255', //产品名称
            'data.en_name' => 'required|max:255', //产品英文名称
            'data.hscode' => 'required|numeric', //HSCode
            'data.brand_id' => 'numeric', //品牌
            'data.tax_refund_rate' => 'required|numeric', //退税率
            'data.standards' => 'required', //规格
            'data.link_id' => 'required|numeric', //规格
            'data.picture' => 'required|image_str:jpeg,png,bmp,gif,svg,png,jpg|max:255', //产品图片
            //'data.appearance_img' => 'required|image_str:jpeg,png,bmp,gif,svg,png,jpg|max:255', //外观图
            //'data.pack_img' => 'required|image_str:jpeg,png,bmp,gif,svg,png,jpg|max:255', //包装图
        ];
    }
    public function messages(){
        return [
            'data.name.required'  => '产品名称不能为空',
            'data.name.max' => '产品名称不能超过255位',
            'data.en_name.required'  => '产品英文名称不能为空',
            'data.en_name.max' => '产品英文名称不能超过255位',
            'data.en_name.alpha' => '产品英文名称必须为字母',
            'data.hscode.required' => 'HSCode不能为空',
            'data.hscode.numeric' => 'HSCode必须是数字',
            'data.brand_id.numeric' => '品牌ID必须是数字',
            'data.tax_refund_rate.required' => '退税率不能为空',
            'data.tax_refund_rate.numeric' => '退税率必须是数字',
            'data.standards.required' => '规格不能为空',
            'data.link_id.required' => '联系人不能为空',
            'data.link_id.numeric' => '联系人必须是数字',
            'data.picture.required' => '产品图片不能为空',
            'data.picture.image_str' => '请检查产品图片格式',
            'data.picture.max' => '产品图片最多上传4张',
            'data.appearance_img.required' => '外观图不能为空',
            'data.appearance_img.image_str' => '请检查外观图片格式',
            'data.appearance_img.max' => '外观图片最多上传4张',
            'data.pack_img.required' => '包装图不能为空',
            'data.pack_img.image_str' => '请检查包装图片格式',
            'data.pack_img.max' => '包装图片最多上传4张',
        ];
    }
}
