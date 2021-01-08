<?php

namespace App\Http\Requests\Erp;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
        throw new HttpException(403, $validator->errors()->first(), null, ['msg'=> json_encode($validator->errors()->first())]);
    }
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch(Route::currentRouteName()){ //当前路由别名
            case 'transportOrderList' :
                return [
                    'token' => 'required',
                    'tax_type' => 'required|integer',
                    'company_id' => 'required|integer',
                ];
            case 'transportComplete' :
                return [
                    'token' => 'required',
                    'transport.id' => 'required|integer',
                    'transport.tax_type' => 'required|in:0,1',
                    'transport.company_id' => 'required|integer',
                    'transport.name' => 'required',
                    'transport.number' => 'required',
                    'transport.money' => 'required|numeric',
                    'transport.billed_at' => 'required|date_format:Y-m-d',
                    'transport.applied_at' => 'required|date_format:Y-m-d',
                    'order' => 'required|array',
                    'order.*.order_id' => 'required',
                    'order.*.money' => 'required|numeric',
                ];
            default :
                return [];
        }
    }
        
    public function messages(){
        return [
            'token.required' => 'token必须',
            'tax_type.required' => '退税类型必须',
            'company_id.required' => '开票公司必须',
        ];
    }
}
