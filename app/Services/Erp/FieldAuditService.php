<?php

namespace App\Services\Erp;

use App\Repositories\DrawerRepository;

class fieldAuditService
{
    public function __construct(DrawerRepository $drawerRepository)
    {
        $this->drawerRepository = $drawerRepository;
    }

    public function drawerListService($param)
    {
        $list = $this->drawerRepository->drawerList($param);
        $data = array_map(function($value){
            $item = [];
            $item['id'] = $value['id'];
            $item['company'] = $value['company'];
            $item['address'] = $value['address'];
            $item['raddress'] = $value['raddress'];
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }
    
}
