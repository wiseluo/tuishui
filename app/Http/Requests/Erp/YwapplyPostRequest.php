<?php

namespace App\Http\Requests\Erp;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Route; 

class YwapplyPostRequest extends FormRequest
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
            case 'ywapplyPayReturn' :
                return [
                    'token' => 'required',
                    'yw_applyid' => 'required|integer',
                    'status' => 'required|integer',
                    'yw_billpics' => 'required_if:status,3',
                ];
            default :
                return [];
        }
    }
        
    public function messages(){
        return [
            'token.required' => 'token必须',
            'yw_applyid.required' => '业务报销申请id必须',
            'status.required' => '流程状态必须',
            'yw_billpics.required_if' => '出纳上传水单必须',
        ];
    }
}
