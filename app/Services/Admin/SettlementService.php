<?php

namespace App\Services\Admin;

use App\Repositories\SettlementRepository;
use App\Repositories\SettlepaymentRepository;
use App\Pay;
use DB;

class SettlementService
{
    const SCALE = 2;
    protected $settleRepository;
    protected $settlepayRepository;

    public function __construct(SettlementRepository $settleRepository, SettlepaymentRepository $settlepayRepository)
    {
        $this->settleRepository = $settleRepository;
        $this->settlepayRepository = $settlepayRepository;
    }

    public function renderStatus()
    {
        return $this->settleRepository->renderStatus();
    }

    public function settlementIndex($param)
    {
        $list = $this->settleRepository->settlementIndex($param);
        $data = array_map(function($value){
            $item = [];
            $item['id'] = $value['id'];
            $item['order_created_at'] = $value['order']['created_at'];
            $item['ordnumber'] = $value['order']['ordnumber'];
            $item['customer_name'] = $value['order']['customer']['name'];
            $item['refund_tax_sum'] = $value['refund_tax_sum'];
            $item['commission_sum'] = $value['commission_sum'];
            $item['payable_refund_tax_sum'] = $value['payable_refund_tax_sum'];
            $item['settle_at'] = $value['settle_at'];
            $item['opinion'] = $value['opinion'];
            $item['status'] = $value['status'];
            $item['status_str'] = $value['status_str'];
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }

    /*
     * 总已收票金额及各开票工厂的已收票金额
     * params  invoice该结算订单对应的发票模型
     * return array = ['invoice_sum'=>'总已收票金额', 'received_invoice'=>'各开票工厂的已收票金额,drawer_id与已收票金额关联关系数组']
     */
    public function settleReceivedInvoice($invoice)
    {
        $received_invoice = [];
        $invoice_sum = $invoice->reduce(function($carry, $item)use(&$received_invoice){
            if(array_key_exists($item->drawer_id, $received_invoice)){
                $received_invoice[$item->drawer_id] = bcadd($received_invoice[$item->drawer_id], $item->invoice_amount, self::SCALE);
            }else{
                $received_invoice[$item->drawer_id] = $item->invoice_amount;
            }
            return bcadd($carry, $item->invoice_amount, self::SCALE);
        });
        return ['invoice_sum'=> $invoice_sum, 'received_invoice'=> $received_invoice];
    }

    /*
     * 已开发票才能计算退税款
     * 总应退税款=开票金额/（1+税率） *退税率   及各开票工厂的应退税款
     * params  invoice：该结算订单对应的发票模型
     * return array = ['refund_tax_sum'=>'总应退税款', 'refund_tax'=>'各开票工厂的应退税款,drawer_id与应退税款关联关系数组']
     */
    public function settleRefundTax($invoice)
    {
        $refund_tax = [];
        $refund_tax_sum = $invoice->reduce(function($carry, $item)use(&$refund_tax){
            $incoices_refund = $item->drawerProductOrders->reduce(function($n, $i)use(&$refund_tax){
                //$refund = bcmul(bcdiv($i->pivot->amount, bcadd(1, bcdiv($i->pivot->tax_rate, 100, self::SCALE), self::SCALE), self::SCALE), bcdiv($i->drawerProduct->product->tax_refund_rate, 100, self::SCALE), self::SCALE);
                $refund = $i->pivot->refund_tax_amount;
                return bcadd($n, $refund, self::SCALE);
            }); //某个发票的开票工厂的应退税款
            if(array_key_exists($item->drawer_id, $refund_tax)){
                $refund_tax[$item->drawer_id] = bcadd($refund_tax[$item->drawer_id], $incoices_refund, self::SCALE);
            }else{
                $refund_tax[$item->drawer_id] = $incoices_refund;
            }
            return bcadd($carry, $incoices_refund, self::SCALE);
        });
        return ['refund_tax_sum'=> $refund_tax_sum, 'refund_tax'=> $refund_tax];
    }

    /*
     *总已付款金额及各开票工厂的金额
     * params  order_id：订单id，type：付款类型 0定金、1货款
     * return array = ['paid_sum'=>'总应退税款', 'paid'=>'各开票工厂的应退税款,drawer_id与已付款关联关系数组']
     */
    public function settlePaid($order_id, $type)
    {
        $paid = [];
        $paid_sum = Pay::where(['type'=> $type, 'status'=> 3])->whereHas('order', function($query)use($order_id){
            $query->where('order_id', $order_id);
        })->get()->reduce(function($carry, $item)use(&$paid){
            $drawer_id = $item->remittee->remit_id;
            if(array_key_exists($drawer_id, $paid)){
                $paid[$drawer_id] = bcadd($paid[$drawer_id], $item->money, self::SCALE);
            }else{
                $paid[$drawer_id] = $item->money;
            }
            return bcadd($carry, $item->money, self::SCALE);
        }, 0);
        return ['paid_sum'=> $paid_sum, 'paid'=> $paid];
    }

    /*
     * 开票工厂列表合并
     * params  order：该结算的订单模型
     * return array 二维数组 ['drawer_id'=> ['drawer_id'=>'开票工厂id', 'company'=>'开票工厂名', 'invoice_value'=>'开票金额', 'received_invoice'=>'已开票金额', 'paid_deposit'=>'已付定金', 'paid_payment'=>'已付货款', 'refund_tax'=>'应退税款', 'commission'=>'押金', 'payable_refund_tax'=>'应付退税款']]
     */
    public function mergeDrawer($order)
    {
        $drawer_list = [];
        $order->drawerProducts->map(function($item)use(&$drawer_list){
            if(array_key_exists($item->drawer_id, $drawer_list)){
                $drawer_list[$item->drawer_id]['invoice_amount'] = bcadd($drawer_list[$item->drawer_id]['invoice_amount'], $item->pivot->value, self::SCALE);
                $drawer_list[$item->drawer_id]['customs_amount'] = bcadd($drawer_list[$item->drawer_id]['customs_amount'], $item->pivot->total_price, self::SCALE);
            }else{
                $drawer_list[$item->drawer_id] = [
                    'drawer_id' => $item->drawer_id,
                    'company'=> $item->drawer->company,
                    'invoice_amount'=> $item->pivot->value, //开票金额(RMB)
                    'customs_amount'=> $item->pivot->total_price, //报关金额
                    'tax_rate' => $item->pivot->tax_rate, //税率
                    'received_invoice'=> 0,
                    'paid_deposit'=> 0,
                    'paid_payment'=> 0,
                    'refund_tax'=> 0,
                    'bill_base' => $item->drawer->customer->bill_base,
                    'service_rate' => $item->drawer->customer->service_rate,
                ];
            }
        });
        return $drawer_list;
    }

    public function settleSave($id, $settle_data, $pay_data)
    {
        DB::beginTransaction();
        $settlepay = $this->settlepayRepository->select(['settlement_id'=> $id]);
        if($settlepay){
            $this->settlepayRepository->forceDeleteBySettlementId($id);
        }
        $res = $this->settleRepository->update($settle_data, $id);
        $all_is_ok = 0;
        if($res){
            foreach($pay_data as $k => $v){
                $v['settlement_id'] = $id;
                $drawer_res = $this->settlepayRepository->save($v);
                if(!$drawer_res){
                    $all_is_ok == 1;
                }
            }
        }else{
            $all_is_ok == 1;
        }
        if($all_is_ok == 1){
            DB::rollback();
            return 0;
        }else{
            DB::commit();
            return 1;
        }
    }

    public function find($id)
    {
        return $this->settleRepository->find($id);
    }

    public function updateService($param, $id)
    {
        $res = $this->settleRepository->update($param, $id);
        if($res){
            return ['status'=> 1, 'msg'=> '修改成功'];
        }else{
            return ['status'=> 0, 'msg'=> '修改失败'];
        }
    }

    public function approveService($param, $id)
    {
        $res = $this->settleRepository->approve($param, $id);
        if($res['status']){
            return ['status'=> 1, 'msg'=> $res['msg']];
        }else{
            return ['status'=> 0, 'msg'=> $res['msg']];
        }
    }
}
