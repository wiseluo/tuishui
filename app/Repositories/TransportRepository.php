<?php

namespace App\Repositories;

use App\Transport;

class TransportRepository
{
    public function renderStatus()
    {
        return Transport::renderStatus();
    }

    public function transportIndex($param)
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
        return Transport::where(function($query)use($keyword){
                $query->when($keyword, function($query)use($keyword){
                    return $query->whereHas('order', function ($query) use ($keyword){
                        $query->where('ordnumber', 'LIKE', '%'. $keyword .'%')
                            ->orWhere('customs_number', 'LIKE', '%'. $keyword .'%');
                    });
                })
                ->orWhere('name', 'LIKE', '%'. $keyword .'%');
            })
            ->where($where)
            ->with(['company'])
            ->orderBy('id', 'desc')
            ->paginate($pageSize)
            ->toArray();
    }

    public function save($data, ...$args)
    {
        $transport = new Transport($data);
        $transport->save();
        return $transport->id;
    }

    public function relateOrder($id, $ord)
    {
        $transport = Transport::find($id);
        $transport->order()->detach();
        array_map(function($carry)use($transport){
            $transport->order()->attach([$carry['order_id'] => $carry]);
        }, $ord);
        return 1;
    }

    public function update($params, $id)
    {
        $transport = Transport::find($id);
        return $transport->update($params);
    }

    public function delete($id)
    {
        return Transport::destroy($id);
    }

    public function find($id)
    {
        return Transport::find($id);
    }
}