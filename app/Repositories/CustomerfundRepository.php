<?php

namespace App\Repositories;

use DB;
use App\Customer;
use App\Customerfund;
use App\Statement;

class CustomerfundRepository
{
    public function save($param)
    {
        $customerfund = new Customerfund($param);
        $customerfund->save();
        return $customerfund->id;
    }

    public function update($param, $id)
    {
        $customerfund = Customerfund::find($id);
        return (int)$customerfund->update($param);
    }

    public function delete($id)
    {
        return Customerfund::destroy($id);
    }

    public function find($id)
    {
        return Customerfund::find($id);
    }

    public function findWhere($where)
    {
        return Customerfund::where($where)->first();
    }

    public function customerfundIndex($param)
    {
        $where = [];
        if(isset($param['keyword']) && $param['keyword'] != ''){
            $where[] = ['customer_name', 'like', '%'. $param['keyword'] .'%'];
        }
        
        return Customerfund::where($where)
            ->orderBy('id', 'desc')
            ->paginate($param['pageSize'])
            ->toArray();
    }

    //客户变更金额 param: customer_id,change_amount,style,content
    public function changeCustomerfund($param)
    {
        DB::beginTransaction();
        $customer = Customer::find($param['customer_id']);
        $customerfund = Customerfund::where('customer_id', '=', $param['customer_id'])->first();
        if(!$customerfund) {
            $customerfund_res = $this->save(['customer_id'=> $param['customer_id'], 'customer_name'=> $customer->name, 'amount'=> 0, 'deduct_amount'=> 0]);
            if(!$customerfund_res) {
                DB::rollback();
                return ['status'=> 0, 'msg'=> '创建客户金额数据失败'];
            }
        }
        $customerfund = Customerfund::lockForUpdate()->where('customer_id', '=', $param['customer_id'])->first();
        switch($param['style']) {
            case 0: //结汇
                $type = 0;
                $amount = bcadd($customerfund->amount, $param['change_amount'], 2);
                $deduct_amount = $customerfund->deduct_amount;
                break;
            case 1: //付款
                $type = 1;
                $amount = bcsub($customerfund->amount, $param['change_amount'], 2);
                $deduct_amount = $customerfund->deduct_amount;
                break;
            case 2: //退税结算
                $type = 0;
                $amount = bcadd($customerfund->amount, $param['change_amount'], 2);
                $deduct_amount = $customerfund->deduct_amount;
                break;
            case 3: //退税预抵扣
                $type = 1;
                $amount = bcsub($customerfund->amount, $param['change_amount'], 2);
                $deduct_amount = bcadd($customerfund->deduct_amount, $param['change_amount'], 2);
                break;
            case 4: //退税抵扣
                $type = 1;
                $amount = $customerfund->amount;
                $deduct_amount = bcsub($customerfund->deduct_amount, $param['change_amount'], 2);
                break;
            case 5: //取消预抵扣
                $type = 0;
                $amount = bcadd($customerfund->amount, $param['change_amount'], 2);
                $deduct_amount = bcsub($customerfund->deduct_amount, $param['change_amount'], 2);
                break;
            default:
                DB::rollback();
                return ['status'=> 0, 'msg'=> '客户金额变更类型错误'];
        }
        $customerfund->amount = $amount;
        $customerfund->deduct_amount = $deduct_amount;
        $customerfund_res = $customerfund->save();
        if(!$customerfund_res) {
            DB::rollback();
            return ['status'=> 0, 'msg' => '修改客户金额失败'];
        }

        $statement_data = [
            'customer_id'=> $param['customer_id'],
            'customer_name'=> $customer->name,
            'style'=> $param['style'],
            'type'=> $type,
            'change_amount'=> $param['change_amount'],
            'amount'=> $amount,
            'deduct_amount'=> $deduct_amount,
            'content'=> $param['content'],
        ];
        $statement = New Statement($statement_data);
        $statement_res = $statement->save();
        if($statement_res) {
            DB::commit();
            return ['status'=> 1, 'msg' => '客户变更金额成功'];
        }else{
            DB::rollback();
            return ['status'=> 0, 'msg' => '保存客户金额记录失败'];
        }
    }

    public function statementList()
    {
        $where = [];
        if($param['keyword']){
            $where[] = ['customer_name', 'like', '%'. $param['keyword'] .'%'];
        }
        if($param['sdate'] && $param['edate']) {
            $where[] = ['created_at', 'BETWEEN', $param['sdate'] .' 00:00:00,'. $param['edate'] .' 23:59:59'];
        }
        return Statement::where($where)
            ->orderBy('id', 'desc')
            ->paginate($param['pageSize'])
            ->toArray();
    }
}