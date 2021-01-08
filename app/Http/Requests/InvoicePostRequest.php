<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Route; 

class InvoicePostRequest extends FormRequest
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
            case 'taxInvoiceComplete' :
                return [
                    'invoice' => 'required',
                    'invoice.id' => 'required|integer',
                    'invoice.company_id' => 'required|integer',
                    'invoice.order_id' => 'required|integer',
                    'invoice.drawer_id' => 'required|integer',
                    'invoice.invoice_number' => 'required|unique:invoices,number|size:18',
                    'invoice.billed_at' => 'required|date_format:Y-m-d',
                    'invoice.received_at' => 'required|date_format:Y-m-d',
                    'product' => 'required|array',
                    'product.*.drawer_product_order_id' => 'required',
                    'product.*.product_tax_rate' => 'required|numeric',
                    'product.*.product_single_price' => 'required|numeric',
                    'product.*.product_invoice_quantity' => 'required|numeric',
                    'product.*.product_invoice_amount' => 'required|numeric',
                ];
            default :
                return [];
        }
    }
    
    public function messages()
    {
        return[
            'drawer_id.required' => '请选择开票工厂',
            'number.required' => '请填写发票号码', 
            'number.unique' => '发票号已存在',
            'number.size' => '发票号码至少18个字符', 
            'billed_at.required' => '请选择开票日期', 
            'received_at.required' => '请选择收票日期',
        ];
    }
}
