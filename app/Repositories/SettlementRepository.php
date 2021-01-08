<?php

namespace App\Repositories;

use DB;
use App\Settlement;
use App\Repositories\CustomerfundRepository;

class SettlementRepository
{
    public function __construct(CustomerfundRepository $customerfundRepository)
    {
        $this->customerfundRepository = $customerfundRepository;
    }
    
    public function renderStatus()
    {
        return Settlement::renderStatus();
    }

    public function settlementIndex($param)
    {
        $where = [];
        if($param['cid'] !== ''){
            $where[] = ['cid', '=', $param['cid']];
        }
        if($param['status']){
            $where[] = ['status', '=', $param['status']];
        }
        $keyword = isset($param['keyword']) ? $param['keyword'] : '';
        $pageSize = isset($param['pageSize']) ? $param['pageSize'] : 10;
        $apply_start_date = isset($param['apply_start_date']) ? $param['apply_start_date'] : '1970-01-01';
        $apply_end_date = isset($param['apply_end_date']) ? $param['apply_end_date'] : date('Y-m-d');
        return Settlement::with(['order.customer'])
            ->when($keyword, function($query)use($keyword){
                return $query->whereHas('order', function($query)use($keyword){
                    $query->whereHas('drawerProducts.drawer', function ($query) use ($keyword){
                        $query->where('company', 'LIKE', '%'. $keyword .'%');
                    })
                    ->orWhereHas('customer', function ($query) use ($keyword){
                        $query->where('name', 'LIKE', '%'. $keyword .'%')
                            ->orWhere('salesman', 'LIKE', '%'. $keyword .'%')
                            ->orWhere('created_user_name', 'LIKE', '%'. $keyword .'%');
                    })
                    ->orWhere('ordnumber', 'LIKE', '%'. $keyword .'%')
                    ->orWhere('customs_number', 'LIKE', '%'. $keyword .'%');
                });
            })
            ->whereHas('order', function($query)use($apply_start_date, $apply_end_date){
                $query->whereBetween('created_at', [$apply_start_date . ' 00:00:01', $apply_end_date . ' 23:59:59']);
            })
            ->where($where)
            ->orderBy('id', 'desc')
            //->toSql();
            ->paginate($pageSize)
            ->toArray();
    }

    public function save($data, ...$args)
    {
        $settlement = new Settlement($data);
        $settlement->save();
        return $settlement->id;
    }

    public function update($param, $id)
    {
        $settlement = Settlement::find($id);
        return (int) $settlement->update($param);
    }

    public function delete($id)
    {
        return Settlement::destroy($id);
    }

    public function find($id)
    {
        return Settlement::find($id);
    }

    public function findByWhere($where)
    {
        return Settlement::where($where)->first();
    }

    public function approve($param, $id)
    {
        DB::beginTransaction();
        $settlement = Settlement::find($id);
        $settlement_res = $settlement->update($param);
        if($param['status'] != 3 && $settlement_res) { //审核不通过
            DB::commit();
            return ['status'=> 1, 'msg' => '成功'];
        }
        if(!$settlement_res) {
            DB::rollback();
            return ['status'=> 0, 'msg' => '修改结算单失败'];
        }
        //退税款添加到客户余额上
        $customerfund_data = [
            'customer_id' => $settlement->order->customer_id,
            'change_amount' => $settlement->payable_refund_tax_sum,
            'style'=> 2,
            'content'=> '退税结算-添加rmb'. $settlement->payable_refund_tax_sum,
        ];
        $change_res = $this->customerfundRepository->changeCustomerfund($customerfund_data);
        if($change_res) {
            DB::commit();
            return ['status'=> 1, 'msg' => '成功'];
        }else{
            DB::rollback();
            return ['status'=> 0, 'msg' => $change_res['msg']];
        }
    }
    
}