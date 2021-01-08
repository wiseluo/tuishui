<?php

namespace App\Repositories;

use App\Filing;
use App\Invoice;

class FilingRepository
{
    public function filingIndex($param)
    {
        $where = [];
        $userlimit = [];
        if(isset($param['userlimit'])){
            $userlimit = $param['userlimit'];
        }
        if($param['status']){
            $where[] = ['status', '=', $param['status']];
        }
        
        $keyword = isset($param['keyword']) ? $param['keyword'] : '';
        $pageSize = isset($param['pageSize']) ? $param['pageSize'] : 10;
        return Filing::where(function($query)use($keyword){
            $query->when($keyword, function($query) use($keyword){
                return $query->whereHas('invoice.order', function ($query) use ($keyword){
                    $query->where('ordnumber', 'LIKE', '%'. $keyword .'%')
                        ->orWhere('customs_number', 'LIKE', '%'. $keyword .'%');
                })
                ->orWhereHas('invoice', function ($query) use ($keyword){
                    $query->where('number', 'LIKE', '%'. $keyword .'%');
                })
                ->orWhereHas('invoice.drawer', function ($query) use ($keyword){
                    $query->where('company', 'LIKE', '%'. $keyword .'%');
                })
                ->orWhere('batch', 'LIKE', '%'. $keyword .'%')
                ->orWhere('applied_at', 'LIKE', '%'. $keyword .'%')
                ->orWhere('returned_at', 'LIKE', '%'. $keyword .'%')
                ->orWhere('amount', 'LIKE', '%'. $keyword .'%');
            });
        })
        ->where($where)
        ->where($userlimit)
        ->orderBy('id', 'desc')
        ->paginate($pageSize)
        ->toArray();
    }

    public function save($data)
    {
        $filing = new Filing($data);
        $filing->save();
        return $filing->id;
    }

    public function update($params, $id)
    {
        $filing = Filing::find($id);
        return (int) $filing->update($params);
    }

    public function delete($id)
    {
        return Filing::destroy($id);
    }

    public function find($id)
    {
        return Filing::find($id);
    }

    public function getFilingExportData($param)
    {
        $where = [];
        $userlimit = [];
        if(isset($param['userlimit'])){
            $userlimit = $param['userlimit'];
        }
        $where[] = ['filing_id', '<>', null];
        $keyword = isset($param['keyword']) ? $param['keyword'] : '';
        return Invoice::where(function($query)use($keyword){
                $query->when($keyword, function($query) use($keyword){
                    return $query->whereHas('filing', function ($query) use ($keyword){
                        $query->where('batch', 'LIKE', '%'. $keyword .'%')
                            ->orWhere('applied_at', 'LIKE', '%'. $keyword .'%')
                            ->orWhere('returned_at', 'LIKE', '%'. $keyword .'%')
                            ->orWhere('amount', 'LIKE', '%'. $keyword .'%');
                    })
                    ->orWhereHas('drawer', function ($query) use ($keyword){
                        $query->where('company', 'LIKE', '%'. $keyword .'%');
                    })
                    ->orWhereHas('order', function ($query) use ($keyword){
                        $query->where('ordnumber', 'LIKE', '%'. $keyword .'%')
                            ->orWhere('customs_number', 'LIKE', '%'. $keyword .'%');
                    })
                    ->orWhere('number', 'LIKE', '%'. $keyword .'%');
                });
            })
            ->with(['order','filing','drawer','drawerProductOrders'])
            ->where($where)
            ->where($userlimit)
            ->get();
    }

    public function getFilingYearExportData($param)
    {
        $userlimit = [];
        if(isset($param['userlimit'])){
            $userlimit = $param['userlimit'];
        }
        return Filing::where($userlimit)->whereYear('applied_at', date('Y'))->get();
    }
}