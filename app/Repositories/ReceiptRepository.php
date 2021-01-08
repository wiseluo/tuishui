<?php

namespace App\Repositories;

use DB;
use App\Receipt;
use App\Repositories\CustomerfundRepository;

class ReceiptRepository
{
    public function __construct(CustomerfundRepository $customerfundRepository)
    {
        $this->customerfundRepository = $customerfundRepository;
    }

    public function renderStatus()
    {
        return Receipt::renderStatus();
    }

    public function receiptIndex($param)
    {
        $where = [];
        if($param['cid'] !== ''){
            $where[] = ['cid', '=', $param['cid']];
        }
        $keyword = isset($param['keyword']) ? $param['keyword'] : '';
        $pageSize = isset($param['pageSize']) ? $param['pageSize'] : 10;
        return Receipt::where(function($query)use($keyword){
                $query->when($keyword, function($query)use($keyword){
                    return $query->whereHas('customer', function ($query) use ($keyword){
                        $query->where('company', 'LIKE', '%'. $keyword .'%')
                            ->orWhere('salesman', 'LIKE', '%'. $keyword .'%');
                    })
                    ->orWhere('payer', 'LIKE', '%'. $keyword .'%');
                });
            })
            ->where($where)
            ->with(['customer', 'currency'])
            ->orderBy('id', 'desc')
            ->paginate($pageSize)
            ->toArray();
    }

    public function find($id, $columns = ['*'])
    {
        return Receipt::find($id, $columns);
    }

    public function findByWhere($where)
    {
        return Receipt::where($where)->first();
    }

    public function findWithRelate($id)
    {
        return Receipt::with('orders.currencyData')->find($id);
    }

    public function save($param)
    {
        $receipt = new Receipt($param);
        $receipt->save();
        return $receipt->id;
    }

    public function update($param, $id)
    {
        $receipt = Receipt::find($id);
        return (int) $receipt->update($param);
    }

    //erp收款流程结束
    public function receiptReturnRepository($param, $id)
    {
        DB::beginTransaction();
        $receipt = Receipt::find($id);
        $data['status'] = $param['status'];
        if($param['status'] == 3) {
            $data['received_at'] = $param['received_at'];
            $data['amount'] = $param['amount'];
            $data['payer'] = $param['payer'];
            if($receipt->exchange_type == 0) { //无需结汇
                $data['status'] = 5;
                $data['rate'] = 1;
                $data['rmb'] = $param['amount'];
            }else if($receipt->exchange_type == 1) { //到账结汇
                $data['status'] = 5;
                $data['rate'] = $param['rate'];
                $data['rmb'] = bcmul($param['amount'], $param['rate'], 2);
            }
        }
        $receipt_res = $receipt->update($data);
        if($receipt_res) {
            if($data['status'] == 5) { //已结汇
                $customerfund_data = [
                    'customer_id' => $receipt->customer_id,
                    'change_amount' => $data['rmb'],
                ];
                $change_res = $this->changeCustomerfundRepository($customerfund_data);
                if(!$change_res) {
                    DB::rollback();
                    return ['status'=> 0, 'msg' => $change_res['msg']];
                }
            }
            DB::commit();
            return ['status'=> 1, 'msg' => '成功'];
        }else{
            DB::rollback();
            return ['status'=> 0, 'msg' => '修改结汇单失败'];
        }
    }

    //结汇
    public function receiptExchange($param, $id)
    {
        DB::beginTransaction();
        $receipt = Receipt::find($id);
        $data['rate'] = $param['rate'];
        $data['rmb'] = bcmul($receipt->amount, $param['rate'], 2);
        $data['status'] = 5;
        $receipt_res = $receipt->update($data);
        if(!$receipt_res) {
            DB::rollback();
            return ['status'=> 0, 'msg' => '修改结汇单失败'];
        }

        $customerfund_data = [
            'customer_id' => $receipt->customer_id,
            'change_amount' => $data['rmb'],
        ];
        $change_res = $this->changeCustomerfundRepository($customerfund_data);
        if($change_res) {
            DB::commit();
            return ['status'=> 1, 'msg' => '成功'];
        }else{
            DB::rollback();
            return ['status'=> 0, 'msg' => $change_res['msg']];
        }
    }

    //修改客户金额
    public function changeCustomerfundRepository($param)
    {
        $customerfund_data = [
            'customer_id' => $param['customer_id'],
            'change_amount' => $param['change_amount'],
            'style'=> 0,
            'content'=> '收汇管理-结汇添加rmb'. $param['change_amount'],
        ];
        $change_res = $this->customerfundRepository->changeCustomerfund($customerfund_data);
        if($change_res) {
            return ['status'=> 1, 'msg' => $change_res['msg']];
        }else{
            return ['status'=> 0, 'msg' => $change_res['msg']];
        }
    }
}