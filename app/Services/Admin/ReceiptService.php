<?php

namespace App\Services\Admin;

use App\Repositories\OrderRepository;
use App\Repositories\ReceiptRepository;
use DB;
use Curl\Curl;

class ReceiptService
{
    protected $orderRepository;
    protected $receiptRepository;

    public function __construct(ReceiptRepository $receiptRepository, OrderRepository $orderRepository)
    {
        $this->receiptRepository = $receiptRepository;
        $this->orderRepository = $orderRepository;
    }

    public function renderStatus()
    {
        return $this->receiptRepository->renderStatus();
    }

    public function receiptIndex($param)
    {
        $list = $this->receiptRepository->receiptIndex($param);
        $data = array_map(function($value){
            $item = [];
            $item['id'] = $value['id'];
            $item['customer_name'] = $value['customer']['name'];
            $item['received_at'] = $value['received_at'];
            $item['receipt_number'] = $value['receipt_number'];
            $item['amount'] = $value['amount'];
            $item['currency_name'] = $value['currency']['name'];
            $item['rate'] = $value['rate'];
            $item['rmb'] = $value['rmb'];
            $item['payer'] = $value['payer'];
            $item['apply_uname'] = $value['apply_uname'];
            $item['created_at'] = $value['created_at'];
            $item['relate_str'] = $value['relate_str'];
            $item['status'] = $value['status'];
            $item['status_str'] = $value['status_str'];
            $item['exchange_type'] = $value['exchange_type'];
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }

    public function find($id)
    {
        return $this->receiptRepository->find($id);
    }

    public function save($param)
    {
        DB::beginTransaction();
        $receipt_id = $this->receiptRepository->save($param);
        if(!$receipt_id){
            DB::rollback();
            return 0;
        }
        //提交到erp收款申请
        $erp_res = $this->erpSkapply($receipt_id, 'save');

        if($erp_res['status'] == 1){
            DB::commit();
            return $erp_res;
        }else{
            DB::rollback();
            return $erp_res;
        }
    }

    public function update($param, $id)
    {
        DB::beginTransaction();
        $res = $this->receiptRepository->update($param, $id);
        if(!$res){
            DB::rollback();
            return 0;
        }
        //提交到erp收款申请
        $erp_res = $this->erpSkapply($id, 'update');
        //$erp_res['status'] = 1;

        if($erp_res['status'] == 1){
            DB::commit();
            return $erp_res;
        }else{
            DB::rollback();
            return $erp_res;
        }
    }

    public function erpSkapply($receipt_id, $action)
    {
        $receipt = $this->receiptRepository->find($receipt_id);

        $sk_curr = [
            '人民币' => 0,
            '美元' => 1,
            '欧元' => 2,
            '日元' => 4,
            '韩币' => 5,
            '英镑' => 6,
            '澳大利亚元' => 7,
        ];
        if(!isset($sk_curr[$receipt->currency->name])){
            return ['status'=> 0, 'msg'=> '币种未匹配成功'];
        }
        $currency_type = $sk_curr[$receipt->currency->name];

        $curl = new Curl();
        //通过当前登录人的用户中心token获取申请人的erpapp的token，用这token去提交业务申请
        $center_token = session('center_token');
        $token_data = [
            'token' => $center_token,
            'uid' => $receipt->apply_uid,
        ];
        $tokenData = $curl->get(config('app.erpapp_url').'/tslogins', $token_data)->response;
        // dd($tokenData);
        $token_res = json_decode($tokenData);
        if(isset($token_res->code) && $token_res->code != 200){
            return ['status'=> 0, 'msg'=> $token_res->msg];
        }else{
            $token = $token_res->token;
        }

        //把图片通过erpapp接口提交到erp中去
        $pics = '';
        if(!empty($receipt->picture)){
            $pics = saveFileToErpFunc($receipt->picture);
        }


        $post_data = array(
            'token' => $token,
            'fid' => 31,
            'sk_curr' => $currency_type,
            'sk_paycorp' => $receipt->customer->name,
            'sk_receiptcorp' => $receipt->receiptcorp,
            'sk_receiptcorpid' => $receipt->receiptcorpid,
            'sk_paynumber' => $receipt->account,
            'sk_paynumberid' => $receipt->account_id,
            'sk_paynumberopen' => $receipt->bank,
            'sk_paymoney' => $receipt->advance_amount,
            'sk_detail' => $receipt->remark,
            'applytype' => 2,
            'pics' => $pics,
            'sk_countryid' => $receipt->country_id,
            'sk_country' => isset($receipt->country->country_na) ? $receipt->country->country_na : '',
            'sk_applytos' => $receipt->business_type,
            'sk_soe' => $receipt->exchange_type,
            'sk_paydate' => $receipt->expected_received_at,
            'sk_paycorpucenterid' => $receipt->customer->ucenterid,
        );
        
        if($action == 'save'){
            $res = $curl->post(config('app.erpapp_url'). '/createapplys/sk', $post_data)->response;
        }else if($action == 'update'){
            $res = $curl->put(config('app.erpapp_url'). '/createapplys/sk/'. $receipt->sk_applyid, $post_data, true)->response;
        }
        // dd($res);
        $result = json_decode($res);
        
        if(isset($result->data)){
            if($result->data == 'success'){
                if($action == 'save'){
                    $receipt_data['sk_applyid'] = $result->id;
                    $receipt_update_res = $this->receiptRepository->update($receipt_data, $receipt_id);
                    if($receipt_update_res){
                        return ['status'=> 1, 'msg' => '成功'];
                    }else{
                        return ['status'=> 0, 'msg' => '修改receipt失败'];
                    }
                }else{
                    return ['status'=> 1, 'msg' => '成功'];
                }
            }else{
                return ['status'=> 0, 'msg'=> $result->data];
            }
        }
        return ['status'=> 0, 'msg'=> '接口错误'];
    }

    public function exchangeService($param, $id)
    {
        $exchange = $this->receiptRepository->receiptExchange($param, $id);
        if($exchange['status']){
            return ['status'=> 1, 'msg' => '结汇成功'];
        }else{
            return ['status'=> 0, 'msg' => $exchange['msg']];
        }
    }

    public function findWithRelateService($id)
    {
        return $this->receiptRepository->findWithRelate($id);
    }

    public function getUnbindReceiptOrderService($id)
    {
        return $this->orderRepository->getUnbindReceiptOrderList($id);
    }

    public function binding($param, $id)
    {
        $order_id = $param['order_id'];
        $money = $param['money'];

        $receipt = $this->receiptRepository->find($id);
        $order = $this->orderRepository->find($order_id);
        if($receipt->currency_id != $order->currency){
            return ['status'=>'0', 'msg'=> '订单币种与收汇币种不一致'];
        }

        $totalMoney = $money + $receipt->receipted_amount;
        $orderTotalMoney = $money + $order->receipted_amount;

        DB::beginTransaction();
        if($totalMoney > $receipt->amount) {
            DB::rollback();
            return ['status'=>'0', 'msg'=> '总关联金额大于收汇金额'];
        }else if($orderTotalMoney == $order->total_value) {
            $orderStatus = $this->orderRepository->update(['is_remit'=>2], $order_id);
        }else if($orderTotalMoney < $order->total_value) {
            $orderStatus = $this->orderRepository->update(['is_remit'=>1], $order_id);
        }
        $receipt->orders()->attach($order_id, ['money'=> $money]);

        if($orderStatus){
            DB::commit();
            return ['status'=>'1', 'msg'=> '关联成功'];
        }else{
            DB::rollback();
            return ['status'=>'0', 'msg'=> '修改失败'];
        }
    }

    public function unbinding($param, $id)
    {
        $order_id = $param['order_id'];
        $order = $this->orderRepository->find($order_id);
        if ($order->total_value != $order->receipted_amount) {
            DB::beginTransaction();

            $receipt = $this->receiptRepository->find($id);
            $receipt->orders()->detach([$order_id]);
            $order_res = $this->orderRepository->update(['is_remit'=>0], $order_id);
            if($order_res){
                DB::commit();
                return ['status'=> 1, 'msg'=> '取消关联成功'];
            }else{
                DB::rollback();
                return ['status'=> 0, 'msg'=> '取消关联失败'];
            }
        }else{
            return ['status'=> 0, 'msg'=> '该订单已全部收齐，不能取消关联'];
        }
    }
}
