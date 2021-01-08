<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Route; 

class PurchasePostRequest extends FormRequest
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
            case 'purchaseThrowTax' :
                return [
                    'token' => 'required',
                    'purchase' => 'required|array',
                    'purchase_products' => 'required|array',
                ];
            default :
                return [];
        }
    }
        
    public function messages(){
        return [
            'token.required' => 'token必须',
            'purchase.required' => '采购合同信息必须',
            'purchase_products.required' => '采购合同产品必须',
        ];
    }
}
