<?php

namespace App\Services\Api;

use App\Repositories\Api\InvoiceRepository;

class InvoiceService
{
    public function __construct(InvoiceRepository $invoiceRepository)
    {
        $this->invoiceRepository = $invoiceRepository;
    }

    public function invoiceIndex($param)
    {
        $list = $this->invoiceRepository->invoiceIndex($param);
        $data = array_map(function($value){
            $item = [];
            $item['id'] = $value['id'];
            $item['ordnumber'] = $value['order']['ordnumber'];
            $item['drawer_company'] = $value['drawer']['company'];
            $item['number'] = $value['number'];
            $item['billed_at'] = $value['billed_at'];
            $item['received_at'] = $value['received_at'];
            $item['invoice_amount'] = $value['invoice_amount'];
            //应退税款
            $item['refund_tax_amount_sum'] = array_reduce($value['drawer_product_orders'], function($carry, $v){
                return bcadd($carry, $v['pivot']['refund_tax_amount'], 2);
            }, 0);
            //已退税款
            $item['refunded_tax_amount_sum'] = $value['status'] == 6 ? $item['refund_tax_amount_sum'] : 0;
            $item['received_at'] = $value['received_at'];
            $item['status'] = $value['status'];
            $item['status_str'] = $value['status_str'];
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }

    public function find($id)
    {
        return $this->invoiceRepository->find($id);
    }

    public function showService($id)
    {
        $invoice = $this->invoiceRepository->findWithRelation($id);
        return [
            'drawer_company' => $invoice->drawer->company,
            'tax_id' => $invoice->drawer->tax_id,
            'ordnumber' => $invoice->order->ordnumber,
            'number' => $invoice->number,
            'received_at' => $invoice->received_at,
            'billed_at' => $invoice->billed_at,
            'invoice_amount' => $invoice->invoice_amount,
            'drawer_product_orders' => $invoice->drawerProductOrders,
        ];
    }

}
