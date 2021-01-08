<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use \Firebase\JWT\JWT;
use App\Http\Requests\TaxapiPostRequest;
use App\Data;
use App\Order;
use App\Invoice;
use App\Filing;
use App\Company;
use App\DrawerProductOrder;

class TaxapiController extends BaseController
{
    public function analysis_token($token)
    {
        if($token){
            JWT::decode($token, config('app.user_center_jwt_key'), ['HS256']);
            return true;
        }
        return false;
    }

    //获取经营公司列表
    public function taxCompanyList(Request $request)
    {
        $access = $this->analysis_token($request->input('token'));
        if($access == false){
            return json_encode(array('code'=> 400, 'msg'=> 'token错误'), JSON_UNESCAPED_UNICODE);
        }
        $company = Company::search($request->input('keyword'))->paginate(10)->toArray();
        if($company){
            $data = array_map(function($value){
                return ['id'=> $value['id'], 'name'=> $value['name']];
            }, $company['data']);
            $company['data'] = $data;
            return json_encode(array('code'=> 200, 'data'=> $company), JSON_UNESCAPED_UNICODE);
        }else{
            return json_encode(array('code'=> 400, 'msg'=> '获取失败'), JSON_UNESCAPED_UNICODE);
        }
    }

    //获取开票人列表
    // public function taxDrawerList(TaxapiPostRequest $request)
    // {
    //     $access = $this->analysis_token($request->input('token'));
    //     if($access == false){
    //         return json_encode(array('code'=> 400, 'msg'=> 'token错误'), JSON_UNESCAPED_UNICODE);
    //     }
    //     if($request->input('tax_type') == 0){
    //         $where['cid'] = 0;
    //     }else{
    //         $where['cid'] = $request->input('company_id');
    //     }
    //     $keyword = $request->input('keyword', '');
    //     $drawer = Drawer::when($keyword, function($query)use($keyword){
    //             return $query->whereHas('customer', function ($query) use ($keyword){
    //                     $query->where('name', 'LIKE', '%'. $keyword .'%');
    //                 })
    //                 ->orWhere('company', 'LIKE', '%'. $keyword .'%')
    //                 ->orWhere('tax_id', 'LIKE', '%'. $keyword .'%');
    //         })->status(3)->where($where)->paginate(10)->toArray();
    //     if($drawer){
    //         $data = array_map(function($value){
    //             return ['id'=> $value['id'], 'company'=> $value['company'], 'tax_id'=> $value['tax_id']];
    //         }, $drawer['data']);
    //         $drawer['data'] = $data;
    //         return json_encode(array('code'=> 200, 'data'=> $drawer), JSON_UNESCAPED_UNICODE);
    //     }else{
    //         return json_encode(array('code'=> 400, 'msg'=> '获取失败'), JSON_UNESCAPED_UNICODE);
    //     }
    // }

    //获取未开完发票的订单开票产品列表
    public function taxUninvoiceOrderProList(TaxapiPostRequest $request)
    {
        $access = $this->analysis_token($request->input('token'));
        if($access == false){
            return json_encode(array('code'=> 400, 'msg'=> 'token错误'), JSON_UNESCAPED_UNICODE);
        }
        $company_id = $request->input('company_id');
        if($request->input('tax_type') == 0){
            $cid = 0;
        }else{
            $cid = $company_id;
        }
        $keyword = $request->input('keyword', '');
        $list = DrawerProductOrder::whereHas('order', function($query)use($cid, $company_id){
            $query->where([['status', '=', 5], ['invoice_complete', '=', 0], ['cid', '=', $cid], ['company_id', '=', $company_id]]); //已结案，未开票完成
        })
        ->whereColumn('number', '>', 'invoice_quantity') //产品数量>累计已开票产品数量
        ->where(function($query)use($keyword){
            $query->when($keyword, function($query)use($keyword){
                return $query->whereHas('order', function($query) use($keyword){
                    $query->where('ordnumber', 'LIKE', '%'. $keyword  .'%');
                })
                ->orWhere('company', 'LIKE', '%'. $keyword .'%');
            });
        })
        ->with(['order', 'drawerProduct.drawer'])
        ->orderBy('id', 'desc')
        //->toSql();
        ->paginate(10)->toArray();
        //dd($list);
        if($list){
            $data = array_map(function($value){
                $item = [];
                $item['drawer_product_order_id'] = $value['id'];
                $item['company'] = $value['company'] ? : $value['drawer_product']['drawer']['company'];
                $item['tax_id'] = $value['drawer_product']['drawer']['tax_id'];
                $item['drawer_id'] = $value['drawer_product']['drawer']['id'];
                $item['order_id'] = $value['order']['id'];
                $item['ordnumber'] = $value['order']['ordnumber'];
                return $item;
            }, $list['data']);
            $list['data'] = $data;
            return json_encode(array('code'=> 200, 'data'=> $list), JSON_UNESCAPED_UNICODE);
        }else{
            return json_encode(array('code'=> 400, 'msg'=> '获取失败'), JSON_UNESCAPED_UNICODE);
        }
    }

    //根据订单id，开票人id获取订单产品详情
    public function taxOrderWithProDetail(TaxapiPostRequest $request)
    {
        $access = $this->analysis_token($request->input('token'));
        if($access == false){
            return json_encode(array('code'=> 400, 'msg'=> 'token错误'), JSON_UNESCAPED_UNICODE);
        }
        $drawer_id = $request->input('drawer_id', '');
        $order_id = $request->input('order_id', '');
        $order = Order::whereHas('drawerProducts', function($query) use ($drawer_id){
            $query->whereHas('drawer', function($query) use ($drawer_id){
                $query->where('id', $drawer_id);
            });
        })
        ->with('drawerProducts.product')
        ->where('id', $order_id)
        ->first();
        
        if($order){
            $drawer_products = $order->drawerProducts->toArray();
            $drawer_product_order = [];
            foreach($drawer_products as $key => $value){
                if($value['pivot']['number'] <= $value['pivot']['invoice_quantity']){ //去除已全部开过票的产品
                    continue;
                }
                $item = [];
                $item['drawer_product_order_id'] = $value['pivot']['id'];
                $item['serial_no'] = $key+1;
                $item['drawer_id'] = $value['drawer_id'];
                $item['product_id'] = $value['product_id'];
                $item['hscode'] = $value['product']['hscode'];
                $item['name'] = $value['product']['name'];
                $item['tax_refund_rate'] = $value['product']['tax_refund_rate'];
                $item['unit'] = $value['pivot']['measure_unit_cn'];
                $item['quantity'] = $value['pivot']['number'];
                $item['value'] = $value['pivot']['total_price'];
                $item['un_invoice_quantity'] = bcsub($value['pivot']['number'], $value['pivot']['invoice_quantity']);
                $item['invoice_sum_amount'] = $value['pivot']['invoice_amount'];
                $item['default_unit'] = $value['pivot']['default_unit'];
                $item['default_num'] = $value['pivot']['default_num'];
                $drawer_product_order[] = $item;
            }
            $order_data = [];
            $order_data['id'] = $order->id;
            $order_data['order_invoice_amount'] = $order->total_value_invoice;
            $order_data['export_date'] = $order->export_date;
            $order_data['customs_number'] = $order->customs_number;
            $order_data['drawer_product_order'] = $drawer_product_order;
            return json_encode(array('code'=> 200, 'data'=> $order_data), JSON_UNESCAPED_UNICODE);
        }else{
            return json_encode(array('code'=> 400, 'msg'=> '获取失败'), JSON_UNESCAPED_UNICODE);
        }
    }

    //根据开票人id获取报关单列表
    // public function taxDrawerClearanceList(Request $request, $drawer_id)
    // {
    //     $access = $this->analysis_token($request->input('token'));
    //     if($access == false){
    //         return json_encode(array('code'=> 400, 'msg'=> 'token错误'), JSON_UNESCAPED_UNICODE);
    //     }
    //     $keyword = $request->input('keyword', '');
    //     $clearances = Clearance::whereHas('order', function($query) use ($drawer_id){
    //         $query->whereHas('drawerProducts', function($query) use ($drawer_id){
    //             $query->whereHas('drawer', function($query) use ($drawer_id){
    //                 $query->where('id', $drawer_id);
    //             });
    //         })
    //         ->where([['status', '=', 5], ['invoice_complete', '=', 0]]); //已结案，未开票完成
    //     })
    //     ->when($keyword, function($query)use($keyword){
    //         return $query->whereHas('order', function($query) use($keyword){
    //             $query->where('ordnumber', 'LIKE', '%'. $keyword  .'%');
    //         });
    //     })
    //     ->with('order')
    //     ->orderBy('id', 'desc')
    //     ->paginate(10)->toArray();
    //     if($clearances){
    //         $data = array_map(function($value){
    //             $item = [];
    //             $item['id'] = $value['id'];
    //             $item['order_id'] = $value['order_id'];
    //             $item['ordnumber'] = $value['order']['ordnumber'];
    //             return $item;
    //         }, $clearances['data']);
    //         $clearances['data'] = $data;
    //         return json_encode(array('code'=> 200, 'data'=> $clearances), JSON_UNESCAPED_UNICODE);
    //     }else{
    //         return json_encode(array('code'=> 400, 'msg'=> '获取失败'), JSON_UNESCAPED_UNICODE);
    //     }
    // }

    //根据报关单id获取报关单详情
    // public function taxClearanceDetail(Request $request, $clearance_id)
    // {
    //     $access = $this->analysis_token($request->input('token'));
    //     if($access == false){
    //         return json_encode(array('code'=> 400, 'msg'=> 'token错误'), JSON_UNESCAPED_UNICODE);
    //     }
    //     $clearance = Clearance::whereId($clearance_id)->with(['order'=>function ($query) {
    //         $query->with(['drawerProducts' => function ($query) {
    //             $query->with(['product'=>function($query){
    //                 $query->with('measureUnitData');
    //             }]);
    //         }])->select(['id', 'total_value_invoice', 'customs_number', 'export_date']);
    //     }])->first(['id', 'order_id']);
    //     if($clearance){
    //         $clearance_detail = $clearance->append(['product_count'])->toArray();
    //         $product_count = $clearance_detail['product_count'];
    //         //dd($product_count);
    //         $drawer_products = $clearance_detail['order']['drawer_products'];
    //         $drawer_product_order = [];
    //         foreach($drawer_products as $key => $value){
    //             $invoice_sum_quantity = array_get($product_count, $value['pivot']['id']. '.sum_quantity'); //产品已开票总数
    //             $product_number = $value['pivot']['number'];
    //             if($invoice_sum_quantity >= $product_number){ //去除已全部开过票的产品
    //                 continue;
    //             }
    //             $item = [];
    //             $item['drawer_product_order_id'] = $value['pivot']['id'];
    //             $item['serial_no'] = $key+1;
    //             $item['drawer_id'] = $value['drawer_id'];
    //             $item['product_id'] = $value['product_id'];
    //             $item['hscode'] = $value['product']['hscode'];
    //             $item['name'] = $value['product']['name'];
    //             $item['tax_refund_rate'] = $value['product']['tax_refund_rate'];
    //             $item['unit'] = $value['product']['measure_unit_data']['name'];
    //             $item['quantity'] = $value['pivot']['number'];
    //             $item['value'] = $value['pivot']['total_price'];
    //             $item['un_invoice_quantity'] = bcsub($product_number, $invoice_sum_quantity);
    //             $item['invoice_sum_amount'] = array_get($product_count, $value['pivot']['id']. '.sum_amount') ? : 0;
    //             $drawer_product_order[] = $item;
    //         }
    //         if(count($drawer_product_order) == 0){
    //             return json_encode(array('code'=> 400, 'msg'=> '该订单未开票数量不足，请重新选择订单'), JSON_UNESCAPED_UNICODE);
    //         }
    //         $clearance_detail['order_invoice_amount'] = $clearance_detail['order']['total_value_invoice'];
    //         $clearance_detail['customs_number'] = $clearance_detail['order']['customs_number'];
    //         $clearance_detail['export_date'] = $clearance_detail['order']['export_date'];
    //         $clearance_detail['drawer_product_order'] = $drawer_product_order;
    //         unset($clearance_detail['order']);
    //         return json_encode(array('code'=> 200, 'data'=> $clearance_detail), JSON_UNESCAPED_UNICODE);
    //     }else{
    //         return json_encode(array('code'=> 400, 'msg'=> '获取失败'), JSON_UNESCAPED_UNICODE);
    //     }
    // }

    //获取数据维护
    public function getData(Request $request)
    {
        $access = $this->analysis_token($request->input('token'));
        if($access == false){
            return json_encode(array('code'=> 400, 'msg'=> 'token错误'), JSON_UNESCAPED_UNICODE);
        }
        if(!$request->has('father_id')){
            return json_encode(array('code'=> 400, 'msg'=> '参数不全'), JSON_UNESCAPED_UNICODE);
        }
        $data = Data::where('father_id', $request->input('father_id'))->get()->toArray();
        if($data){
            $data_return = array_map(function($value){
                $item = [];
                $item['id'] = $value['id'];
                $item['father_id'] = $value['father_id'];
                $item['name'] = $value['name'];
                $item['key'] = $value['key'];
                $item['value'] = $value['value'];
                return $item;
            }, $data);
            return json_encode(array('code'=> 200, 'data'=> $data_return), JSON_UNESCAPED_UNICODE);
        }else{
            return json_encode(array('code'=> 400, 'msg'=> '获取失败'), JSON_UNESCAPED_UNICODE);
        }
        return json_encode(['code'=> 200, 'data'=> $data], JSON_UNESCAPED_UNICODE);
    }

    //发票审核完成同步到退税
    public function taxInvoiceComplete(TaxapiPostRequest $request)
    {
        $access = $this->analysis_token($request->input('token'));
        if($access == false){
            return json_encode(array('code'=> 400, 'msg'=> 'token错误'), JSON_UNESCAPED_UNICODE);
        }
        $invoice = $request->input('invoice');
        $product = $request->input('product');

        DB::beginTransaction();
        $tax_invoice = [];
        if($invoice['tax_type'] == 0){
            $tax_invoice['cid'] = 0;
        }else{
            $tax_invoice['cid'] = $invoice['company_id'];
        }
        $tax_invoice['erp_id'] = $invoice['id'];
        $tax_invoice['order_id'] = $invoice['order_id'];
        $tax_invoice['drawer_id'] = $invoice['drawer_id'];
        $tax_invoice['number'] = $invoice['invoice_number'];
        $tax_invoice['billed_at'] = $invoice['billed_at'];
        $tax_invoice['received_at'] = $invoice['received_at'];
        $tax_invoice['invoice_amount'] = $invoice['invoice_amount'];
        $tax_invoice['status'] = 3;
        $tax_invoice['approved_at'] = date('Y-m-d');
        $tax_invoice['created_user_id'] = 0;
        $tax_invoice['created_user_name'] = $invoice['created_username'];

        $invoiceModel = new Invoice($tax_invoice);
        $invoice_res = $invoiceModel->save();
        if(!$invoice_res){
            DB::rollback();
            return json_encode(array('code'=> 400, 'msg'=> '添加发票失败'), JSON_UNESCAPED_UNICODE);
        }
        
        array_map(function($value)use($invoiceModel){
            $item = [];
            $item['tax_rate'] = $value['product_tax_rate'];
            $item['single_price'] = $value['product_single_price'];
            $item['quantity'] = $value['product_invoice_quantity'];
            $item['amount'] = $value['product_invoice_amount'];
            $item['refund_tax_amount'] = $value['product_refund_tax_amount'];
            $item['product_untaxed_amount'] = $value['product_untaxed_amount'];
            $item['product_tax_amount'] = $value['product_tax_amount'];
            $invoiceModel->drawerProductOrders()->attach([$value['drawer_product_order_id'] => $item]);
            //累加已开票数量,发票金额到订单产品关系表中
            $drawerProductOrders = DrawerProductOrder::find($value['drawer_product_order_id']);
            $invoice_quantity = bcadd($value['product_invoice_quantity'], $drawerProductOrders->invoice_quantity, 2);
            $invoice_amount = bcadd($value['product_invoice_amount'], $drawerProductOrders->invoice_amount, 2);
            $drawerProductOrders->update(['invoice_quantity'=> $invoice_quantity, 'invoice_amount'=> $invoice_amount]);
        }, $product);
        //判断订单是否已全部开完票
        $order = Order::where('id', $invoice['order_id'])->first();
        $invoice_complete = 1; //已全部开完
        $order->drawerProducts->each(function($item, $key)use(&$invoice_complete){
            if($item->pivot->number > $item->pivot->invoice_quantity){
                $invoice_complete = 0;
            }
        });
        if($invoice_complete == 1){
            $res = $order->update(['invoice_complete'=> 1]);
        }

        DB::commit();

        return json_encode(array('code'=> 200, 'msg'=> '添加发票成功'), JSON_UNESCAPED_UNICODE);
    }

    //申报流程开始时修改退税发票状态为已申报
    public function taxFilingStart(TaxapiPostRequest $request)
    {
        $access = $this->analysis_token($request->input('token'));
        if($access == false){
            return json_encode(array('code'=> 400, 'msg'=> 'token错误'), JSON_UNESCAPED_UNICODE);
        }
        $id = $request->input('id');
        $invoice = $request->input('invoice');
        DB::beginTransaction();
        $invoice_ids = array_reduce($invoice, function($ret, $value){
            array_push($ret, $value['id']);
            return $ret;
        },[]);
        //Invoice::where([['filing_id','=', null], ['status','=', 5]])->update(['status'=> 3]);
        $res = Invoice::whereIn('erp_id', $invoice_ids)->update(['status'=> 5]);
        if($res){
            DB::commit();
            return json_encode(array('code'=> 400, 'msg'=> '关联发票成功'), JSON_UNESCAPED_UNICODE);
        }else{
            DB::rollback();
            return json_encode(array('code'=> 400, 'msg'=> '关联发票失败'), JSON_UNESCAPED_UNICODE);
        }
    }

    //申报审核完成同步到退税
    public function taxFilingComplete(TaxapiPostRequest $request)
    {
        $access = $this->analysis_token($request->input('token'));
        if($access == false){
            return json_encode(array('code'=> 400, 'msg'=> 'token错误'), JSON_UNESCAPED_UNICODE);
        }
        $filing = $request->input('filing');
        $invoice = $request->input('invoice');
        //return json_encode(array('code'=> 400, 'msg'=> $filing), JSON_UNESCAPED_UNICODE);
        DB::beginTransaction();
        $tax_filing = [];
        if($filing['tax_type'] == 0){
            $tax_filing['cid'] = 0;
        }else{
            $tax_filing['cid'] = $filing['company_id'];
        }
        $tax_filing['erp_id'] = $filing['id'];
        $tax_filing['batch'] = $filing['batch'];
        $tax_filing['applied_at'] = $filing['applied_at'];
        $tax_filing['declared_amount'] = $filing['declared_amount'];
        $tax_filing['amount'] = $filing['amount'];
        $tax_filing['invoice_quantity'] = $filing['invoice_quantity'];
        $tax_filing['returned_at'] = $filing['returned_at'];
        $tax_filing['letter'] = isset($filing['letter']) ? $filing['letter'] : '';
        $tax_filing['status'] = 2;
        $tax_filing['approved_at'] = date('Y-m-d');
        $tax_filing['created_user_id'] = 0;
        $tax_filing['created_user_name'] = $filing['created_username'];

        $filingModel = new Filing($tax_filing);
        $filing_res = $filingModel->save();
        if(!$filing_res){
            DB::rollback();
            return json_encode(array('code'=> 400, 'msg'=> '添加申报失败'), JSON_UNESCAPED_UNICODE);
        }
        $invoice_ids = array_reduce($invoice, function($ret, $value){
            array_push($ret, $value['id']);
            return $ret;
        },[]);
        $invoice_res = Invoice::whereIn('erp_id', $invoice_ids)->update(['filing_id'=> $filingModel->id, 'status'=> 6]);
        if(!$invoice_res){
            DB::rollback();
            return json_encode(array('code'=> 400, 'msg'=> '关联发票失败'), JSON_UNESCAPED_UNICODE);
        }
        DB::commit();

        return json_encode(array('code'=> 200, 'msg'=> '添加申报成功'), JSON_UNESCAPED_UNICODE);
    }
}
