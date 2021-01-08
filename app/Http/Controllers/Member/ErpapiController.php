<?php

namespace App\Http\Controllers\Member;

use Curl\Curl;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use App\Data;
use App\Order;
use \Firebase\JWT\JWT;

class ErpapiController extends BaseController
{
    public function analysis_token($token)
    {
        if($token){
            JWT::decode($token, config('app.user_center_jwt_key'), ['HS256']);
            return true;
        }
        return false;
    }

    public function getOrdInfo(Request $request)
    {
        $access = $this->analysis_token($request->input('token'));
        if($access == false){
            return json_encode(array('code'=> 400, 'msg'=> 'token错误'), JSON_UNESCAPED_UNICODE);
        }
        $bnote_num = $request->input('bnote_num');
        if(!$bnote_num){
            return json_encode(array('code'=> 400, 'msg'=> '参数错误'), JSON_UNESCAPED_UNICODE);
        }
        $order = Order::where('bnote_num', $bnote_num)->first();
        if(!$order){
            return json_encode(array('code'=> 400, 'msg'=> '订单未找到'), JSON_UNESCAPED_UNICODE);
        }
        $order = Order::where('bnote_num', $bnote_num)->with('drawerProducts')->where('status', '>=', '3')->first();
        if(!$order){
            return json_encode(array('code'=> 400, 'msg'=> '订单未审核'), JSON_UNESCAPED_UNICODE);
        }
        $product = $order->drawerProducts->map(function($item){
            return array(
                'product_id'=> $item->product_id,
                'name'=> $item->product->name,
                'hscode'=> $item->product->hscode,
                'standard'=> $item->product->standard,
                'company'=> $item->drawer->company,
                'number'=> $item->pivot->number,
                'unit'=> $item->pivot->unit,
                'single_price'=> $item->pivot->single_price,
                'total_price'=> $item->pivot->total_price,
                'default_num'=> $item->pivot->default_num,
                'value'=> $item->pivot->value,
                'total_weight'=> $item->pivot->total_weight,
                'net_weight'=> $item->pivot->net_weight,
                'volume'=> $item->pivot->volume,
                'pack_number'=> $item->pivot->pack_number
            );
        });
        $data = [
            'id'=> $order->id,
            'ordnumber'=> $order->ordnumber,
            'bnote_num'=> $order->bnote_num,
            'tdnumber'=> $order->tdnumber,
            'sales_unit'=> $order->company->invoice_name,
            'district_code'=> $order->district_code,
            'district_name'=> $order->district_name_str,
            'total_package'=> $order->total_packnum,
            'total_value'=> $order->total_value,
            'total_weight'=> $order->total_weight,
            'total_net_weight'=> $order->total_net_weight,
            'total_volume'=> $order->total_volume,
            'product'=> $product->toArray()
        ];
        return json_encode(array('code'=> 200, 'data'=> $data), JSON_UNESCAPED_UNICODE);
    }

    public function ordInfo(Request $request)
    {
        $access = $this->analysis_token($request->input('token'));
        if($access == false){
            return json_encode(array('code'=> 400, 'msg'=> 'token错误'), JSON_UNESCAPED_UNICODE);
        }
        if(!$request->has('bnote_num') || !$request->has('bnote_transportstyle') || !$request->has('bnote_sportid') || !$request->has('bnote_ecountryid') || !$request->has('bnote_eportid') || !$request->has('export_port')){
            return json_encode(array('code'=> 400, 'msg'=> '参数不全'), JSON_UNESCAPED_UNICODE);
        }

        $transport = Data::where(['father_id'=> 16, 'key'=> $request->input('bnote_transportstyle')])->first();
        if(!$transport){
            return json_encode(array('code'=> 400, 'msg'=> '运输方式未找到'), JSON_UNESCAPED_UNICODE);
        }
        $export_port = Data::where(['father_id'=> 2, 'key'=> $request->input('export_port')])->first();
        if(!$export_port){
            $params = [
                'father_id' => 2,
                'name' => $request->input('export_port'),
                'key' => $request->input('export_port'),
                'value' => '',
                'sort' => 1
            ];
            $clearance_port = DB::table('data')->insertGetId($params);
        }else{
            $clearance_port = $export_port->id;
        }
        $ord = array(
            'shipping_at' => $request->has('bnote_floaddate') ? $request->input('bnote_floaddate') : null,//预计装柜日期
            'packing_at' => $request->has('bnote_rloaddate') ? $request->input('bnote_rloaddate') : null,//装柜日期
            'customs_at' => $request->has('customs_at') ? $request->input('customs_at') : null,//报关日期
            'sailing_at' => $request->has('bnote_fsailingdate') ? $request->input('bnote_fsailingdate') : null,//开船日期
            'broker_name' => $request->has('bnote_declarname') ? $request->input('bnote_declarname') : null,//报关行名称
            'shipment_port' => $request->input('bnote_sportid'),//起运港
            'aim_country' => $request->input('bnote_ecountryid'),//最终目的国
            'unloading_port' => $request->input('bnote_eportid'),//抵运港
            'ship_name' => $request->has('bnote_voyagecount') ? $request->input('bnote_voyagecount') : null,//船名/航次
            'transport' => $transport->id,//运输方式
            'clearance_port' => $clearance_port,//报关口岸
            'operator' => $request->has('bnote_operator') ? $request->input('bnote_operator') : null,//操作人
            'white_card' => $request->has('white_cardnum') ?$request->input('white_cardnum') : null,//白卡号
            'plate_number' => $request->has('service') ? $request->input('service') : null,//车牌号
            'box_number' => $request->has('bonte_boxnumber') ? $request->input('bnote_sealingnum') : null,//货柜箱号
            'box_type' => $request->has('bnote_boxtype') ? $request->input('bnote_boxtype') : null,//货柜箱型
            'tdnumber' => $request->has('bnote_billnum') ? $request->input('bnote_billnum') : null//提单号
        );
        $res = DB::table('orders')->where('bnote_num', $request->input('bnote_num'))->update($ord);
        if($res){
            return json_encode(array('code'=> 200, 'msg'=> '更新成功'), JSON_UNESCAPED_UNICODE);
        }else{
            return json_encode(array('code'=> 400, 'msg'=> '更新失败'), JSON_UNESCAPED_UNICODE);
        }
        
    }
    // 同步港口
    public function portSync(Request $request)
    {
        $access = $this->analysis_token($request->input('token'));
        if($access == false){
            return json_encode(array('code'=> 400, 'msg'=> 'token错误'), JSON_UNESCAPED_UNICODE);
        }

        if(!$request->has('type') || !$request->has('id')){
            return json_encode(array('code'=> 400, 'msg'=> '参数不全'), JSON_UNESCAPED_UNICODE);
        }
        $type = $request->input('type');
        $id = $request->input('id');
        $port_c_cod = $request->input('port_c_cod');
        $port_e_cod = $request->input('port_e_cod');
        $port_count = $request->input('port_count');
        $port_code = $request->input('port_code');
        if($type == 1){
            $res = DB::table('ports')->insert(['id'=> $id, 'port_c_cod'=> $port_c_cod, 'port_e_cod'=> $port_e_cod, 'port_count'=> $port_count, 'port_code'=> $port_code]);
        }else if($type == 2){
            $res = DB::table('ports')->where('id', $id)->delete();
        }else if($type == 3){
            $res = DB::table('ports')->where('id', $id)->update(['port_c_cod'=> $port_c_cod, 'port_e_cod'=> $port_e_cod, 'port_count'=> $port_count, 'port_code'=> $port_code]);
        }else{
            return json_encode(array('code'=> 400, 'msg'=> '参数类型错误'), JSON_UNESCAPED_UNICODE);
        }
        if($res){
            return json_encode(array('code'=> 200, 'msg'=> '同步成功'), JSON_UNESCAPED_UNICODE);
        }else{
            return json_encode(array('code'=> 400, 'msg'=> '同步失败'), JSON_UNESCAPED_UNICODE);
        }
    }

    public function customer(Curl $curl, $id) {

        $url = config('app.erpapp_url'). "/crm/customer/get/id/". $id . '?token=' . session('center_token');
        $response = $curl->get($url);
        return $response->response;
    }
}
