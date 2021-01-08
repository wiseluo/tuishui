<?php

namespace App\Services\Zjport;

use App\Repositories\PzxsendRepository;

class PzxsendService
{
    public function __construct(PzxsendRepository $pzxsendRepository)
    {
        $this->pzxsendRepository = $pzxsendRepository;
    }

    public function pzxSendService($id, $type)
    {
        $data = [];
        if($type == 'scmorder'){
            $data['scmorder'] = 1;
        }else if($type == 'expcustoms'){
            $data['expcustoms'] = 1;
        }else if($type == 'purinvoice'){
            $data['purinvoice'] = 1;
        }else if($type == 'recpay'){
            $data['recpay'] = 1;
        }else if($type == 'taxback'){
            $data['taxback'] = 1;
        }
        $pzxsend = $this->pzxsendRepository->findByWhere(['order_id'=> $id]);
        if($pzxsend){
            $pzxsend_res = $this->pzxsendRepository->update($data, $pzxsend->id);
        }else{
            $data['order_id'] = $id;
            $pzxsend_res = $this->pzxsendRepository->save($data);
        }
        
        if($pzxsend_res) {
            return ['status'=> 1, 'msg'=> '成功'];
        }else{
            return ['status'=> 0, 'msg'=> '失败'];
        }
    }

}
