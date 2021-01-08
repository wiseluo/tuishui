<?php

namespace App\Services\Admin;

use App\Repositories\PayRepository;
use DB;
use Curl\Curl;

class PayService
{
    protected $payRepository;

    public function __construct(PayRepository $payRepository)
    {
        $this->payRepository = $payRepository;
    }

    public function renderStatus()
    {
        return $this->payRepository->renderStatus();
    }

    public function payType()
    {
        return $this->payRepository->payType();
    }

    public function payIndex($param)
    {
        $list = $this->payRepository->payIndex($param);
        $data = array_map(function($value){
            $item = [];
            $item['id'] = $value['id'];
            $item['type'] = $value['type'];
            $item['status'] = $value['status'];
            $item['pay_number'] = $value['pay_number'];
            $item['applied_at'] = $value['applied_at'];
            $item['type_str'] = $value['type_str'];
            $item['content'] = $value['content'];
            $item['remittee_name'] = $value['remittee']['name'];
            $item['money'] = $value['money'];
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }

    public function find($id)
    {
        return $this->payRepository->findPayWithRelation($id);
    }

    public function save($param)
    {
        DB::beginTransaction();
        $pay_id = $this->payRepository->saveWithOrder($param);
        if(!$pay_id){
            DB::rollback();
            return 0;
        }
        //提交到erp业务报销
        $erp_res = $this->erpYwapply($pay_id, 'save');

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
        $res = $this->payRepository->updateWithOrder($param, $id);
        if(!$res){
            DB::rollback();
            return 0;
        }
        //提交到erp业务报销
        $erp_res = $this->erpYwapply($id, 'update');
        //$erp_res['status'] = 1;

        if($erp_res['status'] == 1){
            DB::commit();
            return $erp_res;
        }else{
            DB::rollback();
            return $erp_res;
        }
    }

    public function relateOrder($param, $id)
    {
        return $this->payRepository->relateOrder($param, $id);
    }

    public function relateReceipt($param, $id)
    {
        return $this->payRepository->relateReceipt($param, $id);
    }

    public function payOrderList($param)
    {
        return $this->payRepository->payOrderList($param);
    }

    public function payReceiptList($param)
    {
        return $this->payRepository->payReceiptList($param);
    }

    public function erpYwapply($pay_id, $action)
    {
        $pay = $this->payRepository->find($pay_id);
        $curl = new Curl();
        //通过当前登录人的用户中心token获取申请人的erpapp的token，用这申请人的token去提交业务申请
        $center_token = session('center_token');
        $token_data = [
            'token' => $center_token,
            'uid' => $pay->apply_uid,
        ];
        $tokenData = $curl->get(config('app.erpapp_url').'/tslogins', $token_data)->response;
        // dd($tokenData);
        $token_res = json_decode($tokenData);
        if(isset($token_res->code) && $token_res->code != 200){
            return ['status'=> 0, 'msg'=> $token_res->msg];
        }else{
            $token = $token_res->token;
        }

        //从erpapp中获取经营单位
        $saleData = $curl->get(config('app.erpapp_url').'/createapplys/yw', ['token'=>$token])->response;
        // dd($saleData);
        $sale_res = json_decode($saleData);
        if(isset($sale_res->code) && $sale_res->code != 200){
            return ['status'=> 0, 'msg'=> $sale_res->msg];
        }else{
            $bxgs = $sale_res->bxgs;
        }
        $sale_name = $pay->company->name;
        $resultData = collect($bxgs)->map(function($item, $key)use($sale_name) {
            if($item->dept_name === $sale_name) {
                return $item;
            }
        });
        $business = $resultData->filter()->first();
        if(null === $business){
            return ['status'=> 0, 'msg'=> '本订单的经营单位在ERP中不存在，请核查'];
        }

        //把图片通过erpapp接口提交到erp中去
        $pics = '';
        if(!empty($pay->picture)){
            $pics = saveFileToErpFunc($pay->picture);
        }
        
        $sk_dept = [];
        $contract_num = '';
        if(isset($pay->order) && count($pay->order) > 0){
            $pay->order->map(function($carry)use(&$sk_dept, &$contract_num, $pay){
                $item = [];
                $item['name'] = $pay->customer->name;
                $item['skrid'] = $pay->customer->id;
                $item['rec_comp_name'] = $pay->remittee->name;
                $item['bank_name'] = $pay->remittee->bank;
                $item['bank_account'] = $pay->remittee->number;
                $item['amount'] = $carry->pivot->money;
                $item['ucenter_type'] = 10;
                $item['purchase_code'] = $carry->ordnumber;
                $item['tos'] = $pay->business_type;
                $sk_dept[] = $item;
                $contract_num = ($contract_num == '' ? $carry->ordnumber : $contract_num .','. $carry->ordnumber);
            });
        }else{
            $item = [];
            $item['name'] = $pay->customer->name;
            $item['skrid'] = $pay->customer->id;
            $item['rec_comp_name'] = $pay->remittee->name;
            $item['bank_name'] = $pay->remittee->bank;
            $item['bank_account'] = $pay->remittee->number;
            $item['amount'] = $pay->money;
            $item['ucenter_type'] = 10;
            $item['purchase_code'] = '';
            $item['tos'] = $pay->business_type;
            $sk_dept[] = $item;
        }

        $post_data = array(
            'token' => $token,
            'fid' => $pay->fid,
            'money_type' => 1, //人民币
            'dj_count' => $pay->dj_count,
            'cus_name' => $pay->customer->name,
            'apply_company' => $pay->company->name,
            'apply_companyid' => $pay->company->erp_companyid,
            'yw_bills' => $pay->yw_bills,
            'amount' => $pay->money,
            'contract_num' => $contract_num,
            'use' => $pay->content,
            'dept_bursar' => $business->dept_bursar,
            'dept_bursarname' => $business->dept_bursarname,
            'dept_cashier' => $business->dept_cashier,
            'dept_cashiername' => $business->dept_cashiername,
            'dept_connect' => $business->dept_connect,
            'dept_connectname' => $business->dept_connectname,
            'yw_public' => $pay->yw_public,
            'applytype' => 2,
            'pics' => $pics,
            'ucenterid' => $pay->customer->ucenterid,
            'sk_dept' => json_encode($sk_dept),
        );
        
        if($action == 'save'){
            $res = $curl->post(config('app.erpapp_url'). '/createapplys/yw', $post_data)->response;
        }else if($action == 'update'){
            $res = $curl->put(config('app.erpapp_url'). '/createapplys/yw/'. $pay->yw_applyid, $post_data, true)->response;
        }
        // dd($res);
        $result = json_decode($res);
        
        if(isset($result->data)){
            if($result->data == 'success'){
                if($action == 'save'){
                    $pay_data['yw_applyid'] = $result->id;
                    $pay_update_res = $this->payRepository->update($pay_data, $pay_id);
                    if($pay_update_res){
                        return ['status'=> 1, 'msg' => '成功'];
                    }else{
                        return ['status'=> 0, 'msg' => '修改pay失败'];
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
}
