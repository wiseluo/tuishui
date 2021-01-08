<?php

namespace App\Repositories;

use App\Remittee;

class RemitteeRepository
{
    public function getRemitteeType()
    {
        return Remittee::TYPE;
    }

    public function remitteeIndex($param)
    {
        $where = [];
        if($param['cid'] !== ''){
            $where[] = ['cid', '=', $param['cid']];
        }
        $keyword = isset($param['keyword']) ? $param['keyword'] : '';
        $pageSize = isset($param['pageSize']) ? $param['pageSize'] : 10;
        return Remittee::where(function($query)use($keyword){
                $query->when($keyword, function($query)use($keyword){
                    return $query->where('name', 'LIKE', '%'. $keyword .'%')
                        ->orWhere('bank', 'LIKE', '%'. $keyword .'%')
                        ->orWhere('number', 'LIKE', '%'. $keyword .'%');
                });
            })
            ->where($where)
            ->with(['remit'])
            ->orderBy('id', 'desc')
            ->paginate($pageSize)
            ->toArray();
    }

    public function save($data)
    {
        $remittee = new Remittee($data);
        $remittee->save();
        return $remittee->id;
    }

    public function update($params, $id)
    {
        $remittee = Remittee::find($id);
        return  (int) $remittee->update($params);
    }

    public function delete($id)
    {
        return Remittee::destroy($id);
    }

    public function find($id)
    {
        return Remittee::find($id);
    }
}