<?php

namespace App\Repositories;

use DB;
use App\Pay;
use App\Order;
use App\Receipt;
use App\Repositories\CustomerfundRepository;

class PayRepository
{
    public function __construct(CustomerfundRepository $customerfundRepository)
    {
        $this->customerfundRepository = $customerfundRepository;
    }

    public function renderStatus()
    {
        return Pay::renderStatus();
    }

    public function payType()
    {
        return Pay::TYPE;
    }

    public function payIndex($param)
    {
        $where = [];
        if($param['cid'] !== ''){
            $where[] = ['cid', '=', $param['cid']];
        }
        if($param['pay_type'] != ''){
            $where[] = ['type', '=', $param['pay_type']];
        }
        if($param['status']){
            $where[] = ['status', '=', $param['status']];
        }
        $keyword = $param['keyword'];
        $pageSize = isset($param['pageSize']) ? $param['pageSize'] : 10;
        return Pay::where(function($query)use($keyword){
                $query->when($keyword, function($query) use($keyword){
                    return $query->whereHas('order', function($query)use($keyword){
                        $query->where('ordnumber', 'LIKE', '%'. $keyword .'%');
                    })
                    ->orWhereHas('remittee', function($query)use($keyword){
                        $query->where('name', 'LIKE', '%'. $keyword .'%');
                    })
                    ->orWhereHas('customer', function($query)use($keyword){
                        $query->where('name', 'LIKE', '%'. $keyword .'%')
                            ->orWhere('salesman', 'LIKE', '%'. $keyword .'%');
                    });
                });
            })
            ->where($where)
            ->with(['remittee'])
            ->orderBy('id', 'desc')
            ->paginate($pageSize)
            ->toArray();
    }

    public function saveWithOrder($param)
    {
        $pay = new Pay($param);
        if($pay->save()){
            if(isset($param['ord']) && count($param['ord']) > 0){ //非定金要关联订单
                $pay->order()->attach($param['ord']);
            }
            return $pay->id;
        }
        return 0;
    }

    public function updateWithOrder($param, $id)
    {
        $pay = Pay::find($id);
        if($pay->order()){
            $pay->order()->detach();
        }
        if(isset($param['ord']) && count($param['ord']) > 0){
            $pay->order()->attach($param['ord']);
        }
        return (int) $pay->update($param);
    }

    //erp付款流程结束
    public function payReturnRepository($param, $id)
    {
        DB::beginTransaction();
        $pay = Pay::find($id);
        $data['status'] = $param['status'];
        if($param['status'] == 3) {
            //保存水单
            $data['yw_billpics'] = savePicFromErpFunc($param['yw_billpics']);
        }
        $pay_res = $pay->update($data);
        if(!$pay_res) {
            DB::rollback();
            return ['status'=> 0, 'msg' => '修改付款单失败'];
        }
        if($param['status'] == 3) {
            $customerfund_data = [
                'customer_id' => $pay->customer_id,
                'change_amount' => $pay->money,
                'style'=> 1,
                'content'=> '付款管理-'. $pay->type_str .'扣减rmb'. $pay->money,
            ];
            $change_res = $this->customerfundRepository->changeCustomerfund($customerfund_data);
            if(!$change_res) {
                DB::rollback();
                return ['status'=> 0, 'msg' => $change_res['msg']];
            }
        }
        DB::commit();
        return ['status'=> 1, 'msg' => '成功'];
    }

    public function delete($id)
    {
        return Pay::destroy($id);
    }

    public function find($id)
    {
        return Pay::find($id);
    }

    public function findByWhere($where)
    {
        return Pay::where($where)->first();
    }

    public function findPayWithRelation($id)
    {
        return Pay::with(['customer','order'])->find($id);
    }

    public function save($param)
    {
        $pay = new Pay($param);
        $pay->save();
        return $pay->id;
    }

    public function update($param, $id)
    {
        $pay = Pay::find($id);
        return (int) $pay->update($param);
    }

    public function relateOrder($param, $id)
    {
        $pay = Pay::find($id);
        $pay->order()->detach();
        $pay->order()->attach($param['ord']);
        return 1;
    }

    public function relateReceipt($param, $id)
    {
        $pay = Pay::find($id);
        $pay->receipt()->detach();
        $pay->receipt()->attach($param['receipt']);
        return 1;
    }

    public function payOrderList($param)
    {
        return Order::where(['customer_id'=> $param['customer_id'], 'company_id'=> $param['company_id'], 'cid'=> $param['cid'], 'status'=> 5])
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->toArray();
    }

    public function payReceiptList($param)
    {
        return Receipt::where(['customer_id'=> $param['customer_id'], 'cid'=> $param['cid']])
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->toArray();
    }

    public function getPayExportData($param)
    {
        $where = [];
        if($param['cid']){
            $where[] = ['cid', '=', $param['cid']];
        }
        return Pay::where($where)->with('order','remittee','customer')->whereYear('applied_at', date('Y'))->get();
    }
}