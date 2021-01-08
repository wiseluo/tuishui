<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
//use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Route; 

class TaxapiPostRequest extends FormRequest
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
        //throw new HttpException(403, $validator->errors()->first(), null, ['msg'=> json_encode($validator->errors()->first())]);
    }
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch(Route::currentRouteName()){
            // case 'taxDrawerList' :
            //     return [
            //         'tax_type' => 'required|in:0,1',
            //         'company_id' => 'required|integer',
            //     ];
            case 'taxUninvoiceOrderProList' :
                return [
                    'tax_type' => 'required|in:0,1',
                    'company_id' => 'required|integer',
                ];
            case 'taxOrderWithProDetail' :
                return [
                    'order_id' => 'required|integer',
                    'drawer_id' => 'required|integer',
                ];
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
            case 'taxFilingComplete' :
                return [
                    'filing.id' => 'required|integer',
                    'filing.batch' => 'required|unique:filings,batch',
                    'filing.applied_at' => 'required|date_format:Y-m-d',
                    'filing.amount' => 'required|numeric',
                    'filing.invoice_quantity' => 'required|integer',
                    'filing.returned_at' => 'required|date_format:Y-m-d',
                    'invoice' => 'required|array',
                    'invoice.*.id' => 'required|integer',
                ];
            case 'taxFilingStart' :
                return [
                    'id' => 'required|integer',
                    'invoice' => 'required|array',
                    'invoice.*.id' => 'required|integer',
                ];
            default :
                return [];
        }
    }
    
     public function messages(){
        return [
            'tax_type.required' => '退税类型必填',
            'company_id.required' => '公司id必填',
            'order_id.required'  => '订单id必填',
            'drawer_id.required'  => '开票人id必填',
        ];
    }
}
