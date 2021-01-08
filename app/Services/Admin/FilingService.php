<?php

namespace App\Services\Admin;

use Curl\Curl;
use App\Repositories\FilingRepository;

class FilingService
{
    public function __construct(FilingRepository $filingRepository)
    {
        $this->filingRepository = $filingRepository;
    }

    public function findService($id)
    {
        return $this->filingRepository->find($id);
    }

    public function filingIndex($param)
    {
        $list = $this->filingRepository->filingIndex($param);
        $data = array_map(function($value){
            $item = [];
            $item['id'] = $value['id'];
            $item['applied_at'] = $value['applied_at'];
            $item['batch'] = $value['batch'];
            $item['amount'] = $value['amount'];
            $item['invoice_quantity'] = $value['invoice_quantity'];
            $item['returned_at'] = $value['returned_at'];
            $item['entry_person'] = $value['entry_person'];
            $item['status'] = $value['status'];
            $item['status_str'] = $value['status_str'];
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }

    public function getFilingLetterList($cid)
    {
        $curl = new Curl();
        $data = array(
            'token'=> session('center_token'),
            'tax_type'=> 0, //0退税、1外综
            'cid'=> $cid,
        );
        $curl->get(config('app.erpapp_url'). '/drawback/tax_filing_list', $data);
        if($curl->error){
            $res = $curl->error_code;
            return ['code'=> '400', 'msg'=> $res];
        }
        $res = $curl->response;
        $result = json_decode($res);
        $curl->close();
        return $result;
    }

    public function getFilingLetterDetail($id)
    {
        $curl = new Curl();
        $data = array(
            'token'=> session('center_token'),
            'id'=> $id,
        );
        $curl->get(config('app.erpapp_url'). '/drawback/tax_filing_detail', $data);
        if($curl->error){
            $res = $curl->error_code;
            return ['code'=> '400', 'msg'=> $res];
        }
        $res = $curl->response;
        $result = json_decode($res);
        $curl->close();
        return $result;
    }

    public function getFilingletterInvoiceList($id)
    {
        $curl = new Curl();
        $data = array(
            'token'=> session('center_token'),
            'id'=> $id,
        );
        $curl->get(config('app.erpapp_url'). '/drawback/filing_invoice_list', $data);
        if($curl->error){
            $res = $curl->error_code;
            return ['code'=> '400', 'msg'=> $res];
        }
        $res = $curl->response;
        $result = json_decode($res);
        $curl->close();
        return $result;
    }

    public function getFilingExportFromErp($param)
    {
        $curl = new Curl();
        $data = array(
            'token'=> session('center_token'),
            'keyword'=> $param['keyword'],
        );
        $curl->get(config('app.erpapp_url'). '/drawback/tax_filing_export', $data);
        if($curl->error){
            $res = $curl->error_code;
            return ['code'=> '400', 'msg'=> $res];
        }
        $res = $curl->response;
        $result = json_decode($res, true);
        $curl->close();
        return $result;
    }
}
