<?php

namespace App\Http\Controllers\Member;

use DB;
use Illuminate\Http\Request;
use App\Http\Requests\Member\SettlementPostRequest;
use App\Services\Member\SettlementService;
use App\Services\Zjport\ZjportService;

class SettlementController extends Controller
{
    public function __construct(SettlementService $settlementService, ZjportService $zjportService)
    {
        parent::__construct();
        $this->settlementService = $settlementService;
        $this->zjportService = $zjportService;
    }

    public function index(Request $request, $status = 0)
    {
        if ($request->ajax()) {
            $param = $request->input();
            $param['status'] = $status;
            $param['cid'] = $this->getUserCid();
            $list = $this->settlementService->settlementIndex($param);
            return response()->json(['code'=>200, 'total'=> $list['total'], 'rows'=> $list['data']]);
        }
        $renderStatus = $this->settlementService->renderStatus();
        return view('member.settlement.index', $renderStatus);
    }
    
    public function update(Request $request, $id)
    {
        return $this->settlementService->updateService($request->input(), $id);
    }

    public function approve(Request $request, $id)
    {
        return $this->settlementService->approveService($request->input(), $id);
    }

    public function read($type = 'read', $id = 0)
    {
        $view = view('member.settlement.seldetail');
        $settlement = $this->settlementService->find($id);
        $view->with('settlement', $settlement);
        return $view;
    }

    public function settle($id)
    {
        $view = view('member.settlement.settlementdetail');
        $settlement = $this->settlementService->find($id);
        $order = $settlement->order;
        $invoice = $order->invoice;

        //总已收票金额及各开票工厂的已收票金额
        $settle_invoice = $this->settlementService->settleReceivedInvoice($invoice);
        $invoice_sum = $settle_invoice['invoice_sum'];
        $received_invoice = $settle_invoice['received_invoice'];
        
        // 总应退税款=开票金额/（1+税率） *退税率   及各开票工厂的应退税款
        $settle_refund_tax = $this->settlementService->settleRefundTax($invoice);
        $refund_tax = $settle_refund_tax['refund_tax'];
        $refund_tax_sum = $settle_refund_tax['refund_tax_sum'];

        //总已付定金金额及各开票工厂的定金
        $order_id = $settlement->order_id;
        $settle_paid_deposit = $this->settlementService->settlePaid($order_id, 0);
        $paid_deposit = $settle_paid_deposit['paid']; //drawer_id与定金关联关系数组  每个开票工厂定金之和
        $paid_deposit_sum = $settle_paid_deposit['paid_sum'];
        
        //总已付货款及各开票工厂的已付货款
        $settle_paid = $this->settlementService->settlePaid($order_id, 1);
        $paid_payment = $settle_paid['paid']; //drawer_id与货款关联关系数组  每个开票工厂货款之和
        $paid_payment_sum = $settle_paid['paid_sum'];

        //开票工厂列表合并
        $drawer_list = $this->settlementService->mergeDrawer($order);
        
        $drawers = array_map(function($item)use($received_invoice, $paid_deposit, $paid_payment, $refund_tax){
            if($item['bill_base'] == 1){ // 计费基数，1开票金额、2报关金额、3退税金额
                $commission = bcmul($item['invoice_amount'], bcdiv($item['service_rate'], 100, 2), 2);
            }else if($item['bill_base'] == 2){
                $commission = bcmul($item['customs_amount'], bcdiv($item['service_rate'], 100, 2), 2);
            }else if($item['bill_base'] == 3){
                $commission = bcmul($item['refund_tax'], bcdiv($item['service_rate'], 100, 2), 2);
            }else{
                $commission = 0;
            }
            $refund_tax = isset($refund_tax[$item['drawer_id']]) ? $refund_tax[$item['drawer_id']] : 0;
            return [
                'drawer_id'=> $item['drawer_id'],
                'company'=> $item['company'],
                'invoice_amount'=> $item['invoice_amount'],
                'received_invoice'=> isset($received_invoice[$item['drawer_id']]) ? $received_invoice[$item['drawer_id']] : 0,
                'paid_deposit'=> isset($paid_deposit[$item['drawer_id']]) ? $paid_deposit[$item['drawer_id']] : 0,
                'paid_payment'=> isset($paid_payment[$item['drawer_id']]) ? $paid_payment[$item['drawer_id']] : 0,
                'refund_tax'=> $refund_tax,
                'commission'=> $commission,
                'payable_refund_tax'=> bcsub($refund_tax, $commission, 2),
            ];
        }, $drawer_list);
        
        $view->with('settlement', $settlement);
        $view->with('refund_tax_sum', $refund_tax_sum);
        $view->with('invoice_sum', $invoice_sum);
        $view->with('paid_deposit_sum', $paid_deposit_sum);
        $view->with('paid_payment_sum', $paid_payment_sum);
        $view->with('drawers', $drawers);
        return $view;
    }

    public function settlesave(SettlementPostRequest $request, $id)
    {
        $settleinput = $request->input();
        $settlement = $this->settlementService->find($id);
        $order = $settlement->order;
        $invoice = $order->invoice;
        if(count($invoice->toArray()) === 0){
            return response()->json(['code'=> 403, 'msg'=> '该订单未登记发票']);
        }
        //报关资料齐全  才能退税结算
        $clearance = $order->clearance;
        if($clearance->generator == null || $clearance->prerecord == null || $clearance->declare == null || $clearance->release == null || $clearance->transport == null || $clearance->lading == null || $clearance->commercial_invoice == null || $clearance->packing_List == null || $clearance->inventory == null || $clearance->purchase_sale_contract == null){
            return response()->json(['code'=> 403, 'msg'=> '报关资料不齐全，不能结算']);
        }
        
        //总已收票金额及各开票工厂的已收票金额
        $settle_invoice = $this->settlementService->settleReceivedInvoice($invoice);
        $invoice_sum = $settle_invoice['invoice_sum'];
        $received_invoice = $settle_invoice['received_invoice'];

        //总开票金额=总已收票金额  才能退税结算
        if($order->total_value_invoice != $invoice_sum){
            return response()->json(['code'=> 403, 'msg'=> '总开票金额不等于总已收票金额，不能结算']);
        }
        
        // 总应退税款=开票金额/（1+税率） *退税率   及各开票工厂的应退税款
        $settle_refund_tax = $this->settlementService->settleRefundTax($invoice);
        $refund_tax = $settle_refund_tax['refund_tax'];
        $refund_tax_sum = $settle_refund_tax['refund_tax_sum'];

        //总已付定金金额及各开票工厂的定金
        $order_id = $settlement->order_id;
        $settle_paid_deposit = $this->settlementService->settlePaid($order_id, 0);
        $paid_deposit = $settle_paid_deposit['paid']; //drawer_id与定金关联关系数组  每个开票工厂定金之和
        $paid_deposit_sum = $settle_paid_deposit['paid_sum'];

        //总已付货款及各开票工厂的已付货款
        $settle_paid = $this->settlementService->settlePaid($order_id, 1);
        $paid_payment = $settle_paid['paid']; //drawer_id与货款关联关系数组  每个开票工厂货款之和
        $paid_payment_sum = $settle_paid['paid_sum'];

        //开票工厂列表合并
        $drawer_list = $this->settlementService->mergeDrawer($order);

        $drawerinput = $settleinput['drawer'];
        $refundable = 1;
        $drawers = array_map(function($item)use($received_invoice, $paid_deposit, $paid_payment, $refund_tax, $drawerinput, &$refundable){
            $paid_deposit = isset($paid_deposit[$item['drawer_id']]) ? $paid_deposit[$item['drawer_id']] : 0;
            $paid_payment = isset($paid_payment[$item['drawer_id']]) ? $paid_payment[$item['drawer_id']] : 0;
            $refund_tax = isset($refund_tax[$item['drawer_id']]) ? $refund_tax[$item['drawer_id']] : 0;
            $commission = 0;
            foreach($drawerinput as $k => $v){
                if($item['drawer_id'] == $v['drawer_id']){
                    $commission = $v['commission'];
                }
            }
            $paid_amount = bcadd($paid_deposit, $paid_payment, 2); //已付款金额
            $payable_refund_tax = bcsub($refund_tax, $commission, 2);
            // if($paid_amount != bcsub($item['invoice_amount'], $payable_refund_tax, 2)){ //已付款金额=开票金额-应付退税额  才可退税
            //     $refundable = 0;
            // }
            if($paid_amount != $item['invoice_amount']){ //已付款金额=开票金额  才可退税
                $refundable = 0;
            }
            return [
                'drawer_id'=> $item['drawer_id'],
                'company'=> $item['company'],
                'invoice_amount'=> $item['invoice_amount'],
                'received_invoice'=> isset($received_invoice[$item['drawer_id']]) ? $received_invoice[$item['drawer_id']] : 0,
                'paid_deposit'=> $paid_deposit,
                'paid_payment'=> $paid_payment,
                'refund_tax'=> $refund_tax,
                'commission'=> $commission,
                'payable_refund_tax'=> $payable_refund_tax
            ];
        }, $drawer_list);
        if($refundable == 0){
            return response()->json(['code'=> 403, 'msg'=> '已付款金额不等于开票金额，不能结算']);
        }
        
        $commission_sum = array_reduce($drawerinput, function($carry, $item){
            return bcadd($carry, $item['commission'], 2);
        });
        $settle_data = [
            'total_value'=> $order->total_value,
            'currency'=> $order->currency,
            'invoice_sum'=> $invoice_sum,
            'paid_deposit_sum'=> $paid_deposit_sum,
            'paid_payment_sum'=> $paid_payment_sum,
            'refund_tax_sum'=> $refund_tax_sum,
            'commission_sum'=> $commission_sum,
            'payable_refund_tax_sum'=> bcsub($refund_tax_sum, $commission_sum, 2),
            'settle_at'=> $settleinput['settle_at'],
            'status'=> 2
        ];

        DB::beginTransaction();
        $settlement_res = $this->settlementService->settleSave($id, $settle_data, $drawers);
        $scmordtaxback_res = $this->zjportService->scmordtaxback($id);
        
        if($settlement_res){
            DB::commit();
            return response()->json(['code'=>200, 'msg'=> '结算成功'.$scmordtaxback_res['msg']]);
        }else{
            DB::rollback();
            return response()->json(['code'=>200, 'msg'=> '结算失败'.$scmordtaxback_res['msg']]);
        }
    }

}
