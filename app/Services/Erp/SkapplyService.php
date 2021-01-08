<?php

namespace App\Services\Erp;

use App\Repositories\ReceiptRepository;

class SkapplyService
{
    public function __construct(ReceiptRepository $receiptRepository)
    {
        $this->receiptRepository = $receiptRepository;
    }

    public function receiptReturnService($param)
    {
        $receipt = $this->receiptRepository->findByWhere(['sk_applyid'=> $param['sk_applyid']]);
        if(!$receipt) {
            return ['status'=> 0, 'msg'=> '结汇单不存在'];
        }
        $receipt_res = $this->receiptRepository->receiptReturnRepository($param, $receipt->id);
        if($receipt_res){
            return ['status'=> 1, 'msg'=> '修改成功'];
        }else{
            return ['status'=> 0, 'msg'=> $receipt_res['msg']];
        }
    }

    public function receiptExchangeService($param)
    {
        $receipt = $this->receiptRepository->findByWhere(['sk_applyid'=> $param['sk_applyid']]);
        if(!$receipt) {
            return ['status'=> 0, 'msg'=> '结汇单不存在'];
        }
        $receipt_res = $this->receiptRepository->receiptExchange($param, $receipt->id);
        if($receipt_res['status']) {
            return ['status'=> 1, 'msg'=> '结汇成功'];
        }else{
            return ['status'=> 0, 'msg'=> $receipt_res['msg']];
        }
    }
}
