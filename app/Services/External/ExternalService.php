<?php

namespace App\Services\External;

use Curl\Curl;
use App\Repositories\OrderRepository;

class ExternalService
{
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function sendOrder($id)
    {
        $order = $this->orderRepository->find($id);
        $curl = new Curl();
        if($order->cid == 1){ //聚达
            $orderData = $this->sendJdtData($id);
            $data['order'] = $orderData;
            // $data['mod'] = 'ccontroller';
            // $data['ac'] = 'addorder_controller';
            $curl->post(config('app.jdt_url'). '/?mod=ccontroller&ac=addorder_controller', $data);
            // if($curl->error){
            //     return ['code'=> 400, 'msg'=> $curl->error_code . ': ' . $curl->error_message];
            // }
            $res = $curl->response;
            $curl->close();
        }elseif($order->cid == 2){ //贸易
            $orderData = $this->sendSxtData($id);
            //dd($orderData);
            $data['order'] = $orderData;
            $curl->post(config('app.sxt_url'). '/get_data', $data);
            // if($curl->error){
            //     return ['code'=> 400, 'msg'=> $curl->error_code . ': ' . $curl->error_message];
            // }
            $res = $curl->response;
            $curl->close();
        }
        return $res;
    }

    public function sendSxtData($id)
    {
        return $this->orderRepository->sendSxtOrderData($id)->toArray();
    }

    public function sendJdtData($id)
    {
        return $this->orderRepository->sendSxtOrderData($id)->toArray();
    }

    public function dsyOrderStatisticsService($year)
    {
        $total = $this->orderRepository->totalStatisticsByYear($year);
        $total->sum_value = round($total->sum_value, 2);
        $total->sum_invoice_value = round($total->sum_invoice_value, 2);
        $pie_chart_order = $this->orderRepository->pieChartOrderStatisticsByYear($year);
        $pie_chart_invoice = $this->orderRepository->pieChartInvoiceStatisticsByYear($year);
        $pie_chart_customs = $this->orderRepository->pieChartCustomsStatisticsByYear($year);
        $bar_chart_labels = [];
        $pie_chart_order->each(function($item, $key)use(&$bar_chart_labels){
            $bar_chart_labels[] = $item->name;
        });
        $bar_chart_order_res = $this->orderRepository->barChartOrderStatisticsByYear($year);
        $bar_chart_order = [];
        $bar_chart_order_res->each(function($item, $key)use(&$bar_chart_order){
            $bar_chart_order[] = $item->value;
        });
        $bar_chart_product_res = $this->orderRepository->barChartProductStatisticsByYear($year);
        $bar_chart_product = [];
        $bar_chart_product_res->each(function($item, $key)use(&$bar_chart_product){
            $bar_chart_product[] = round($item->value);
        });
        return ['total'=> $total, 'pie_chart_order'=> $pie_chart_order, 'pie_chart_invoice'=> $pie_chart_invoice, 'pie_chart_customs'=> $pie_chart_customs, 'bar_chart_labels'=> $bar_chart_labels, 'bar_chart_order'=> $bar_chart_order, 'bar_chart_product'=> $bar_chart_product];
    }

    public function dsyOrderService($param)
    {
        $list = $this->orderRepository->dsyGetOrderList($param);
        //dd($list);
        $data = [];
        $data = array_map(function($value){
            $item = [];
            $item['id'] = $value['id'];
            $item['company_name'] = $value['company']['name'];
            $item['customer_id'] = $value['customer_id'];
            $item['customer_name'] = $value['customer']['name'];
            $item['customer_ucenterid'] = $value['customer']['ucenterid'];
            $item['ordnumber'] = $value['ordnumber'];
            $item['export_date'] = $value['export_date']; //出口日期
            $item['country_id'] = $value['aim_country'];
            $item['country_co'] = $value['country']['country_co'];
            $item['country_en'] = $value['country']['country_en'];
            $item['port_id'] = $value['unloading_port'];
            $item['port_e_code'] = $value['unloadingport']['port_e_cod'];
            $item['prepackage_date'] = $value['sailing_at'];
            $item['package'] = $value['order_package']['name']; //包装方式
            $item['total_value'] = $value['total_value'];
            $products = array_map(function ($v){
                $carry = [];
                $carry['hscode'] = $v['product']['hscode'];
                $carry['name'] = $v['product']['name'];
                $carry['standard'] = $v['pivot']['standard'];
                $carry['number'] = $v['pivot']['number'];
                $carry['pack_number'] = $v['pivot']['pack_number'];
                $carry['ctn'] = bcdiv($carry['number'], $carry['pack_number']);
                $carry['measure_unit_cn'] = $v['pivot']['measure_unit_cn'];
                $carry['total_price'] = $v['pivot']['total_price'];
                $carry['single_price'] = bcdiv($carry['total_price'], $carry['number'], 3);
                $carry['volume'] = $v['pivot']['volume'];
                $carry['single_volume'] = bcdiv($carry['volume'], $carry['pack_number'], 3);
                $carry['net_weight'] = $v['pivot']['net_weight'];
                $carry['total_weight'] = $v['pivot']['total_weight'];
                $carry['single_weight'] = bcdiv($carry['total_weight'], $carry['pack_number'], 3);
                $carry['drawer_name'] = $v['drawer']['company'];
                $carry['drawer_telephone'] = $v['drawer']['telephone'];
                $carry['drawer_address'] = $v['drawer']['address'];
                return $carry;
            }, $value['drawer_products']);
            $item['product'] = $products;
            return $item;
        }, $list['data']);
        return $data;
    }
}
