<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Route;

class SettlementPostRequest extends FormRequest
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
            case 'adminSettlementSave' :
                return [
                    'settle_at' => 'required|date',
                    'drawer' => 'required|array',
                    'drawer.*.drawer_id' => 'required|integer',
                    'drawer.*.commission' => 'required|numeric',
                ];
            default :
                return [];
        }
    }
        
     public function messages(){
        return [
            'settle_at.required' => '结算日期必填',
            'drawer.*.commission' => '佣金必填',
        ];
    }
}
