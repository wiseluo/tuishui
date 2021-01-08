<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ReceiveApiRequest extends FormRequest
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
            'name' => 'required',
            'country' => 'required',
            'address' => 'required',
            // 'aeocode' => 'required',
            // 'phone' => 'required',
            
        ];
    }
    
    public function messages(){
        return [
            'name.required' => '名称必填',
            'country.required' => '国家必填',
            'address.required' => '详细地址必填',
            'aeocode.required' => 'AEO编码必填',
            'phone.required' => '电话必填',
            
        ];
    }

    protected function validationData()
    {
        return $this->input('data');
    }
}
