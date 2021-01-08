<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Route; 

class FilingPostRequest extends FormRequest
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
            case 'adminFilingSave' :
                return [
                    'applied_at' => 'required|date_format:Y-m-d',
                    'batch' => 'required|unique:filings,batch'
                ];
            default :
                return [];
        }
    }
    public function messages(){
        return [
            'applied_at.required' => '申报日期不能为空',
            'applied_at.date_format' => '申报日期格式不正确',
            'batch.required' => '申报批次不能为空',
            'batch.unique' => '申报批次已重复'
        ];
    }
}
