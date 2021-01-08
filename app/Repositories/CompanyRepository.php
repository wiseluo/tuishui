<?php

namespace App\Repositories;

use App\Company;

class CompanyRepository
{
    public function companyIndex($param)
    {
        $where = [];
        if(isset($param['cid'])){
            $where['cid'] = $param['cid'];
        }
        $keyword = isset($param['keyword']) ? $param['keyword'] : '';
        $pageSize = isset($param['pageSize']) ? $param['pageSize'] : 10;
        return Company::where(function($query)use($keyword){
                $query->when($keyword, function($query)use($keyword){
                    return $query->where('name', 'LIKE', '%'. $keyword .'%');
                });
            })
            ->where($where)
            ->paginate($pageSize)
            ->toArray();
    }

    public function save($param)
    {
        $company = new Company($param);
        $res = $company->save();
        if($res){
            return $company->id;
        }
        return 0;
    }

    public function update($param, $id)
    {
        $company = Company::find($id);
        return (int) $company->update($param);
    }

    public function delete($id)
    {
        return Company::destroy($id);
    }

    public function find($id)
    {
        return Company::find($id);
    }

}