<?php

namespace App\Repositories;

use App\Customer;

class CustomerRepository
{
    public function renderStatus()
    {
        return Customer::renderStatus();
    }

    public function customerIndex($param)
    {
        $where = [];
        $userlimit = [];
        if(isset($param['userlimit'])){
            $userlimit = $param['userlimit'];
        }
        if($param['status']){
            $where[] = ['status', '=', $param['status']];
        }
        
        $keyword = $param['keyword'];
        $pageSize = isset($param['pageSize']) ? $param['pageSize'] : 10;
        return Customer::where(function($query)use($keyword){
            $query->when($keyword, function($query)use($keyword){
                return $query->where('name', 'LIKE', '%'. $keyword .'%')
                    ->orWhere('salesman', 'LIKE', '%'. $keyword .'%')
                    ->orWhere('created_user_name', 'LIKE', '%'. $keyword .'%');
            });
        })
        ->where($where)
        ->where($userlimit)
        ->orderBy('id', 'desc')
        ->paginate($pageSize)
        ->toArray();
    }

    public function getCustomerList($param)
    {
        $where = [];
        $userlimit = [];
        if(isset($param['userlimit'])){
            $userlimit = $param['userlimit'];
        }
        if($param['status']){
            $where[] = ['status', '=', $param['status']];
        }
        
        $keyword = $param['keyword'];
        return Customer::where(function($query)use($keyword){
            $query->when($keyword, function($query)use($keyword){
                return $query->where('name', 'LIKE', '%'. $keyword .'%')
                    ->orWhere('salesman', 'LIKE', '%'. $keyword .'%')
                    ->orWhere('created_user_name', 'LIKE', '%'. $keyword .'%');
            });
        })
        ->where($where)
        ->where($userlimit)
        ->orderBy('id', 'desc')
        ->get();
    }

    public function save($param, ...$args)
    {
        $customer = new Customer($param);
        $customer->save();
        return $customer->id;
    }

    public function update($param, $id)
    {
        $customer = Customer::find($id);
        return (int) $customer->update($param);
    }

    public function destroy($id)
    {
        return Customer::destroy($id);
    }

    public function find($id)
    {
        return Customer::find($id);
    }
        
    public function findByWhere($where) {
        return Customer::where($where)->first();
    }

    public function countByWhere($where)
    {
        return Customer::where($where)->count();
    }
}