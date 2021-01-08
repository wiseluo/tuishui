<?php

namespace App\Services\Admin;

use App\Repositories\TransportRepository;

class TransportService
{
    public function __construct(TransportRepository $transportRepository)
    {
        $this->transportRepository = $transportRepository;
    }

    public function renderStatus()
    {
        return $this->transportRepository->renderStatus();
    }

    public function transportIndexService($param)
    {
        $list = $this->transportRepository->transportIndex($param);
        //dd($list);
        $data = array_map(function($value){
            $item = [];
            $item['id'] = $value['id'];
            $item['name'] = $value['name'];
            $item['company_name'] = $value['company']['name'];
            $item['number'] = $value['number'];
            $item['applied_at'] = $value['applied_at'];
            $item['billed_at'] = $value['billed_at'];
            $item['money'] = $value['money'];
            $item['status'] = $value['status'];
            $item['status_str'] = $value['status_str'];
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }

    public function findService($id)
    {
        return $this->transportRepository->find($id);
    }

}
