<?php

namespace App\Repositories;

use App\Link;

class LinkRepository
{
    public function linkIndex($param)
    {
        $userlimit = [];
        if(isset($param['userlimit'])){
            $userlimit = $param['userlimit'];
        }

        $keyword = $param['keyword'];
        $pageSize = isset($param['pageSize']) ? $param['pageSize'] : 10;
        return Link::where(function($query)use($keyword){
                $query->when($keyword, function($query)use($keyword){
                    return $query->where('name', 'LIKE', '%'. $keyword .'%')
                        ->orWhere('phone', 'LIKE', '%'. $keyword .'%');
                });
            })
            ->where($userlimit)
            ->paginate($pageSize)
            ->toArray();
    }

    public function save($param, ...$args)
    {
        $link = new Link($param);
        $link->save();
        return $link->id;
    }

    public function update($param, $id)
    {
        $link = Link::find($id);
        return (int) $link->update($param);
    }

    public function delete($id)
    {
        return Link::destroy($id);
    }

    public function find($id)
    {
        return Link::find($id);
    }
}