<?php

namespace App\Services\Zjport;

use App\Repositories\ClearanceRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\OrderRepository;
use App\Repositories\SettlementRepository;
use App\Repositories\ZjportRepository;
use App\Services\Zjport\PzxsendService;
use Curl\Curl;

class ZjportService
{
    public function __construct(OrderRepository $orderRepository,ClearanceRepository $clearanceRepository, SettlementRepository $settlementRepositorye,InvoiceRepository $invoiceRepository, ZjportRepository $zjportRepository, PzxsendService $pzxsendService, Curl $curl)
    {
        $this->orderRepository = $orderRepository;
        $this->zjportRepository = $zjportRepository;
        $this->clearanceRepository = $clearanceRepository;
        $this->settlementRepositorye = $settlementRepositorye;
        $this->invoiceRepository = $invoiceRepository;
        $this->pzxsendService = $pzxsendService;
        $this->curl = $curl;
    }

    public function ScmOrder($id)
    {
        $ordObj = $this->orderRepository->find($id);
        $scmOrderData = $this->zjportRepository->ScmOrder($ordObj);
        $response = $this->curl->post(config('app.zjpot_url'). '/sxport/zjmade/scmorder', json_encode($scmOrderData))->response;
        if($this->curl->error){
            return ['code'=> 0, 'msg'=> $this->curl->error_code . ': ' . $this->curl->error_message];
        }
        if(json_decode($response,true)['body']['code'] === '0') {
            $pzxsend_res = $this->pzxsendService->pzxSendService($id, 'scmorder');
            return ['code'=>1, 'msg'=>json_decode($response,true)['body']['msg']];
        }else{
            return ['code'=>0, 'msg'=>json_decode($response,true)['body']['msg']];
        }
    }

    public function ExpCustoms($id)
    {
        $order = $this->orderRepository->find($id);
        $expCustomsData = $this->zjportRepository->ExpCustoms($order);
        $response = $this->curl->post(config('app.zjpot_url'). '/sxport/zjmade/expcustoms', json_encode($expCustomsData))->response;
        if($this->curl->error){
            return ['code'=> 0, 'msg'=> $this->curl->error_code . ': ' . $this->curl->error_message];
        }
        if(json_decode($response,true)['body']['code'] === '0') {
            $pzxsend_res = $this->pzxsendService->pzxSendService($id, 'expcustoms');
            return ['code'=>1, 'msg'=>json_decode($response,true)['body']['msg']];
        }else{
            return ['code'=>0, 'msg'=>json_decode($response,true)['body']['msg']];
        }
    }

    public function ScmOrderext($param, $order_id)
    {
        $money = $param['money'];
        $order = $this->orderRepository->find($order_id);
        $orderTotalMoney = $money + $order->receipted_amount;
        $remitStatus = $orderTotalMoney == $order->total_value? 'Y' : 'N';
        $expCustomsData = $this->zjportRepository->ScmOrderext($order, $money, $remitStatus);
        $response = $this->curl->post(config('app.zjpot_url'). '/sxport/zjmade/scmorderext', json_encode($expCustomsData))->response;
        if($this->curl->error){
            return ['code'=> 0, 'msg'=> $this->curl->error_code . ': ' . $this->curl->error_message];
        }
        if(json_decode($response,true)['body']['code'] === '0') {
            $pzxsend_res = $this->pzxsendService->pzxSendService($order_id, 'recpay');
            return ['code'=>1, 'msg'=>json_decode($response,true)['body']['msg']];
        }else{
            return ['code'=>0, 'msg'=>json_decode($response,true)['body']['msg']];
        }
    }

    public function purinvoice($invoice_id)
    {
        $invoiceObj = $this->invoiceRepository->find($invoice_id);
        $purinvoiceData = $this->zjportRepository->purinvoice($invoiceObj);
        $response = $this->curl->post(config('app.zjpot_url'). '/sxport/zjmade/purinvoice', json_encode($purinvoiceData))->response;
        if($this->curl->error){
            return ['code'=> 0, 'msg'=> $this->curl->error_code . ': ' . $this->curl->error_message];
        }
        if(json_decode($response,true)['body']['code'] === '0') {
            $pzxsend_res = $this->pzxsendService->pzxSendService($invoiceObj->order->id, 'purinvoice');
            return ['code'=>1, 'msg'=>json_decode($response,true)['body']['msg']];
        }else{
            return ['code'=>0, 'msg'=>json_decode($response,true)['body']['msg']];
        }
    }


    public function scmordtaxback($id)
    {
        $settleObj = $this->settlementRepositorye->find($id);
        $scmordtaxbackData = $this->zjportRepository->scmordtaxback($settleObj);
        $response = $this->curl->post(config('app.zjpot_url'). '/sxport/zjmade/scmordtaxback', json_encode($scmordtaxbackData))->response;
        if($this->curl->error){
            return ['code'=> 0, 'msg'=> $this->curl->error_code . ': ' . $this->curl->error_message];
        }
        if(json_decode($response,true)['body']['code'] === '0') {
            $pzxsend_res = $this->pzxsendService->pzxSendService($settleObj->order->id, 'taxback');
            return ['code'=>1, 'msg'=>json_decode($response,true)['body']['msg']];
        }else{
            return ['code'=>0, 'msg'=>json_decode($response,true)['body']['msg']];
        }
    }
}
