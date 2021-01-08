<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Route;

class ProductPostRequest extends FormRequest
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
        switch(Route::currentRouteName()){ //当前路由别名
            case 'adminProductSave':
            case 'adminProductUpdate' :
                return [
                    'customer_id' => 'required|integer', //客户id
                    'name' => 'required|max:255', //产品名称
                    'en_name' => 'required|max:255', //产品英文名称
                    'hscode' => 'required|numeric', //HSCode
                    'tax_refund_rate' => 'required|numeric', //退税率
                    'standards' => 'required', //规格
                    'link_id' => 'required|integer',
                    'picture' => 'required|image_str:jpeg,png,bmp,gif,svg,png,jpg|max:255', //产品图片
                ];
            case 'adminProductUpdateDone' :
                return [
                    'tax_refund_rate' => 'required|numeric',
                    'en_name' => 'required|max:255',
                    'hscode' => 'required|numeric',
                    'standards' => 'required',
                    'link_id' => 'required|integer',
                    'picture' => 'required|image_str:jpeg,png,bmp,gif,svg,png,jpg|max:255',
                ];
            case 'adminProductRelateDrawer' :
                return [
                    'drawer_id' => 'required|integer',
                    'product_id' => 'required|integer',
                ];
            default :
                return [];
        }
        
    }
    public function messages(){
        return [
            'customer_id.required' => '客户不能为空',
            'name.required'  => '产品名称不能为空',
            'name.max' => '产品名称不能超过255位',
            'en_name.required'  => '产品英文名称不能为空',
            'en_name.max' => '产品英文名称不能超过255位',
            'hscode.required' => 'HSCode不能为空',
            'hscode.numeric' => 'HSCode必须是数字',
            'tax_refund_rate.required' => '退税率不能为空',
            'tax_refund_rate.numeric' => '退税率必须是数字',
            'standards.required' => '规格不能为空',
            'link_id.required' => '联系人不能为空',
            'picture.required' => '产品图片不能为空',
            'picture.image_str' => '请检查产品图片格式',
            'picture.max' => '产品图片最多上传4张',
            'product_id.required' => '产品不能为空',
            'drawer_id.required' => '开票人不能为空',
        ];
    }
}
