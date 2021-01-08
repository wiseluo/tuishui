<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Route;

class BrandPostRequest extends FormRequest
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
            'name' => 'required|max:255', //品牌名称
            'link_id' => 'required|numeric', //联系人
            'type' => 'required|numeric', //品牌类型
            'logo_img' => 'required|image_str:jpeg,png,bmp,gif,svg,png,jpg', //品牌 Logo 图
            'auth_img' => 'required|image_str:jpeg,png,bmp,gif,svg,png,jpg', //授权书
            'status' => 'required|numeric', //状态标识
            'classify' => 'required|numeric', //产品分类
        ];
    }
    public function messages(){
        return [
            'name.required'  => '产品名称不能为空',
            'name.max' => '产品名称不能超过255位',
            'link_id.required' => '联系人不能为空',
            'link_id.numeric' => '联系人必须为数字',
            'type.required' => '品牌类型不能为空',
            'type.numeric' => '品牌类型必须为数字',
            'logo_img.required' => '品牌Logo图不能为空',
            'auth_img.required' => '授权书不能为空',
            'status.required' => '状态标识必填',
            'status.numeric' => '状态标识必须为数字',
            'classify.required' => '产品分类必填',
            'classify.numeric' => '产品分类必须为数字',
        ];
    }
}
