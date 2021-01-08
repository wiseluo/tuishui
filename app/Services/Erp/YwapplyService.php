<?php

namespace App\Services\Erp;

use App\Repositories\PayRepository;

class YwapplyService
{
    protected $payRepository;

    public function __construct(PayRepository $payRepository)
    {
        $this->payRepository = $payRepository;
    }

    public function payReturnService($param)
    {
        $pay = $this->payRepository->findByWhere(['yw_applyid'=> $param['yw_applyid']]);
        if(!$pay) {
            return ['status'=> 0, 'msg'=> '结汇单不存在'];
        }
        $pay_res = $this->payRepository->payReturnRepository($param, $pay->id);
        if($pay_res) {
            return ['status'=> 1, 'msg'=> '修改成功'];
        }else{
            return ['status'=> 0, 'msg'=> $pay_res['msg']];
        }
    }
    
    public function payReturnTempService($param)
    {
        foreach($param as $k => $v){
            $pay = $this->payRepository->findByWhere(['yw_applyid'=> $v['yw_applyid']]);
            $data['yw_billpics'] = savePicFromErpFunc($v['yw_billpics']);
            $data['status'] = 3;
            $pay_res = $this->payRepository->payReturnRepository($data, $pay->id);
        }
        return ['status'=> 1, 'msg'=> '修改成功'];
    }
}
