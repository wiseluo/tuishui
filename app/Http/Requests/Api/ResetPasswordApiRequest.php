<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ResetPasswordApiRequest extends FormRequest
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
            //'username' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'newpassword' => 'required|string|min:6',
            //'phone' => 'required|numeric|digits:11',
        ];
    }
    public function messages(){
        return [
            'username.required' => '用户名称不能为空',
            'username.string' => '用户名必须是字符串',
            'username.max' => '用户名称不能超过255位',
            'password.required' => '旧密码不能为空',
            'password.string' => '旧密码必须是字符串',
            'password.min' => '旧密码不能小于6位',
            'newpassword.required' => '新密码不能为空',
            'newpassword.string' => '新密码必须是字符串',
            'newpassword.min' => '新密码不能小于6位',
            'password.confirmed' => '密码必须是确认的',
            'phone.required' => '手机号不能为空',
            'phone.numeric' => '手机号必须为数字',
            'phone.digits' => '手机号不能小于11位',
        ];
    }
}
