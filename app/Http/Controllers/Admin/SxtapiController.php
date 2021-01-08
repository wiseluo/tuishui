<?php

namespace App\Http\Controllers\Admin;

use Curl\Curl;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use App\Customer;
use App\Product;
use App\Drawer;
use App\Trader;
use App\Order;
use App\Data;
use \Firebase\JWT\JWT;
use Illuminate\Support\Facades\Storage;

class SxtapiController extends BaseController
{
    public function analysis_token($token)
    {
        if($token){
            JWT::decode($token, config('app.user_center_jwt_key'), ['HS256']);
            return true;
        }
        return false;
    }
    //远程获取图片
    public function remoteGetImg($path)
    {
        $curl = new Curl();
        $img_url = explode('|', $path);
        $img_str = '';
        $date = date('Y-m');
        foreach($img_url as $k => $v){
            $url = config('app.sxt_url') . $v;
            $url_arr = explode('/', $v);
            $filename = $url_arr[count($url_arr)-1];
            $curl->get($url);
            if($curl->error){
                continue;
            }else{
                Storage::put('/images/'. $date .'/'. $filename, $curl->response);
                $img_str .= ($img_str == '' ? '/images/'. $date .'/'. $filename : '|/images/'. $date .'/'. $filename);
            }
        }
        return $img_str;
    }
    // public function remoteGetImage(Request $request)
    // {
    //     $curl = new Curl();
    //     $img_url = explode('|', $request->input('url'));
    //     $img_str = '';
    //     $date = date('Y-m');
    //     foreach($img_url as $k => $v){
    //         $url = config('app.sxt_url') . $v;
    //         $url_arr = explode('/', $v);
    //         $filename = $url_arr[count($url_arr)-1];
    //         $curl->get($url);
    //         if($curl->error){
    //             continue;
    //         }else{
    //             Storage::put('/images/'. $date .'/'. $filename, $curl->response);
    //             $img_str .= ($img_str == '' ? '/images/'. $date .'/'. $filename : '|/images/'. $date .'/'. $filename);
    //         }
    //     }
    //     return $img_str;
    // }

    //添加客户
    public function customerStore(Request $request)
    {
        $access = $this->analysis_token($request->input('token'));
        if($access == false){
            return json_encode(array('code'=> 400, 'msg'=> 'token错误'), JSON_UNESCAPED_UNICODE);
        }
        $param = 0;
        $request->has('custype') ? '' : $param = 1;
        $request->has('name') ? '' : $param = 1;
        $request->has('number') ? '' : $param = 1;

        if($request->input('custype') == 2){
            $request->has('usc_code') ? '' : $param = 1;
            $request->has('reg_capital') ? '' : $param = 1;
            $request->has('set_date') ? '' : $param = 1;
            $request->has('legal_name') ? '' : $param = 1;
            $request->has('legal_num') ? '' : $param = 1;
        }else{
            $request->has('card_type') ? '' : $param = 1;
            $request->has('card_num') ? '' : $param = 1;
        }
        
        $request->has('bill_base') ? '' : $param = 1;
        $request->has('service_rate') ? '' : $param = 1;
        $request->has('linkman') ? '' : $param = 1;
        $request->has('telephone') ? '' : $param = 1;
        $request->has('address') ? '' : $param = 1;
        $request->has('create_name') ? '' : $param = 1;
        if($param == 1){
            return json_encode(array('code'=> 400, 'msg'=> '参数不全'), JSON_UNESCAPED_UNICODE);
        }

        $data = [
            'custype' => $request->input('custype'), //客户类型
            'name' => $request->input('name'), //客户名称
            'number' => $request->input('number'), //客户编号

            'usc_code' => $request->has('usc_code') ? $request->input('usc_code') : '', //社会统一信用代码
            'reg_capital' => $request->has('reg_capital') ? $request->input('reg_capital') : '', //注册资本
            'set_date' => $request->has('set_date') ? $request->input('set_date') : null, //成立日期
            'legal_name' => $request->has('legal_name') ? $request->input('legal_name') : '', //法人姓名
            'legal_num' => $request->has('legal_num') ? $request->input('legal_num') : '', //法人身份证号

            'card_type' => $request->has('card_type') ? $request->input('card_type') : '', //证件类型
            'card_num' => $request->has('card_num') ? $request->input('card_num') : '', //证件号码
            'card_name' => $request->has('card_name') ? $request->input('card_name') : '', //户名

            'bill_base' => $request->input('bill_base'), //计费基数
            'service_rate' => (float)$request->input('service_rate'), //服务费率(%)
            'line_credit' => $request->has('line_credit') ? $request->input('line_credit') : '', //授信额度
            'linkman' => $request->input('linkman'), //联系人
            'telephone' => $request->input('telephone'), //联系电话
            'u_name' => $request->has('u_name') ? $request->input('u_name') : '', //erp用户账号，业务员id
            'salesman' => $request->has('salesman') ? $request->input('salesman') : '', //业务员名
            'email' => $request->has('email') ? $request->input('email') : '', //邮箱
            'deposit_bank' => $request->has('deposit_bank') ? $request->input('deposit_bank') : '', //开户银行
            'bank_account' => $request->has('bank_account') ? $request->input('bank_account') : '', //银行账号
            'receiver' => $request->has('receiver') ? $request->input('receiver') : '', //收款人
            'address' => $request->input('address'), //地址
            'created_user_name' => $request->input('create_name'), //创建者
            'created_at' => date('Y-m-d H:i:s'),
            'approved_at' => date('Y-m-d'),
            'status' => 3,
        ];
        //远程获取图片
        if($request->has('picture_lic')){ //证照上传
            $img_str = $this->remoteGetImg($request->input('picture_lic'));
            $data['picture_lic'] = $img_str;
        }
        if($request->has('picture_pact')){ //合同上传
            $img_str = $this->remoteGetImg($request->input('picture_pact'));
            $data['picture_pact'] = $img_str;
        }
        if($request->has('picture_other')){ //其它上传
            $img_str = $this->remoteGetImg($request->input('picture_other'));
            $data['picture_other'] = $img_str;
        }

        $cus_id = DB::table('customers')->insertGetId($data);
        if($cus_id){
            return json_encode(array('code'=> 200, 'msg'=> '添加客户成功', 'cus_id'=> $cus_id), JSON_UNESCAPED_UNICODE);
        }else{
            return json_encode(array('code'=> 400, 'msg'=> '添加客户失败', 'cus_id'=> $cus_id), JSON_UNESCAPED_UNICODE);
        }
    }
    //添加产品
    public function productStore(Request $request)
    {
        $access = $this->analysis_token($request->input('token'));
        if($access == false){
            return json_encode(array('code'=> 400, 'msg'=> 'token错误'), JSON_UNESCAPED_UNICODE);
        }
        if($request->has('cus_num')){ //客户编号
            $customer_id = Customer::where('number', $request->input('cus_num'))->value('id');
            if(!$customer_id){
                return json_encode(array('code'=> 400, 'msg'=> '客户编号不存在'), JSON_UNESCAPED_UNICODE);
            }
        }else{
            return json_encode(array('code'=> 400, 'msg'=> '参数不全'), JSON_UNESCAPED_UNICODE);
        }
        $param = 0;
        $request->has('id') ? '' : $param = 1;
        $request->has('name') ? '' : $param = 1;
        $request->has('hscode') ? '' : $param = 1;
        $request->has('tax_refund_rate') ? '' : $param = 1;
        $request->has('standard') ? '' : $param = 1;
        $request->has('create_name') ? '' : $param = 1;
        if($param == 1){
            return json_encode(array('code'=> 400, 'msg'=> '参数不全'), JSON_UNESCAPED_UNICODE);
        }
        $product_id = Product::where('sxt_id', $request->input('id'))->value('id');
        if($product_id){
            return json_encode(array('code'=> 400, 'msg'=> '该产品已存在，不能再次同步'), JSON_UNESCAPED_UNICODE);
        }

        $data = [
            'sxt_id' => $request->input('id'), //sxt产品id
            'customer_id' => $customer_id,
            'name' => $request->input('name'), //产品名称
            'en_name' => $request->has('en_name') ? $request->input('en_name') : '', //英文名称
            'hscode' => $request->input('hscode'),
            'standard' => $request->input('standard'), //型号
            'unit' => $request->has('unit') ? $request->input('unit') : '', //法定单位
            'number' => $request->has('number') ? $request->input('number') : '', //货号
            'tax_refund_rate' => (float)$request->input('tax_refund_rate'), //退税率
            'remark' => $request->has('remark') ? $request->input('remark') : '', //备注
            'created_user_name' => $request->input('create_name'), //创建者
            'created_at' => date('Y-m-d H:i:s'),
            'approved_at' => date('Y-m-d'),
            'status' => 3,
        ];
        if($request->has('picture')){ //图片
            $img_str = $this->remoteGetImg($request->input('picture'));
            $data['picture'] = $img_str;
        }

        $pro_id = DB::table('products')->insertGetId($data);
        if($pro_id){
            return json_encode(array('code'=> 200, 'msg'=> '添加产品成功', 'pro_id'=> $pro_id), JSON_UNESCAPED_UNICODE);
        }else{
            return json_encode(array('code'=> 400, 'msg'=> '添加产品失败', 'pro_id'=> $pro_id), JSON_UNESCAPED_UNICODE);
        }
    }
    //添加开票人
    public function drawerStore(Request $request)
    {
        $access = $this->analysis_token($request->input('token'));
        if($access == false){
            return json_encode(array('code'=> 400, 'msg'=> 'token错误'), JSON_UNESCAPED_UNICODE);
        }
        if($request->has('cus_num')){ //客户编号
            $customer_id = Customer::where('number', $request->input('cus_num'))->value('id');
            if(!$customer_id){
                return json_encode(array('code'=> 400, 'msg'=> '客户编号不存在'), JSON_UNESCAPED_UNICODE);
            }
        }else{
            return json_encode(array('code'=> 400, 'msg'=> '参数不全'), JSON_UNESCAPED_UNICODE);
        }

        $param = 0;
        $request->has('id') ? '' : $param = 1;
        $request->has('company') ? '' : $param = 1;
        $request->has('telephone') ? '' : $param = 1;
        $request->has('tax_id') ? '' : $param = 1;
        $request->has('licence') ? '' : $param = 1;
        $request->has('tax_at') ? '' : $param = 1;
        $request->has('address') ? '' : $param = 1;
        $request->has('source') ? '' : $param = 1;
        $request->has('pic_register') ? '' : $param = 1;
        $request->has('pic_verification') ? '' : $param = 1;
        $request->has('pic_brand') ? '' : $param = 1;
        $request->has('pic_production') ? '' : $param = 1;
        $request->has('pid') ? '' : $param = 1;
        $request->has('create_name') ? '' : $param = 1;
        if($param == 1){
            return json_encode(array('code'=> 400, 'msg'=> '参数不全'), JSON_UNESCAPED_UNICODE);
        }
        
        $data = [
            'sxt_id' => $request->input('id'), //sxt开票人id
            'customer_id' => $customer_id, //客户名称
            'company' => $request->input('company'), //开票人公司名称
            'telephone' => $request->input('telephone'), //开票人联系方式
            'phone' => $request->has('phone') ? $request->input('phone') : '', //座机
            'purchaser' => $request->has('purchaser') ? $request->input('purchaser') : '', //采购人
            'tax_id' => $request->input('tax_id'), //纳税人识别号
            'licence' => $request->input('licence'), //营业执照注册号
            'tax_at' => $request->input('tax_at'), //一般纳税人认定时间
            'address' => $request->input('address'), //开票人地址
            'source' => $request->input('source'), //境内货源地
            'created_user_name' => $request->input('create_name'), //创建者
            'created_at' => date('Y-m-d H:i:s'),
            'approved_at' => date('Y-m-d'),
            'status' => 3,
        ];
        if($request->has('pic_register')){ //营业执照副本
            $img_str = $this->remoteGetImg($request->input('pic_register'));
            $data['pic_register'] = $img_str;
        }
        if($request->has('pic_verification')){ //一般纳税人认定书
            $img_str = $this->remoteGetImg($request->input('pic_verification'));
            $data['pic_verification'] = $img_str;
        }
        if($request->has('pic_brand')){ //厂牌
            $img_str = $this->remoteGetImg($request->input('pic_brand'));
            $data['pic_brand'] = $img_str;
        }
        if($request->has('pic_production')){ //生产线
            $img_str = $this->remoteGetImg($request->input('pic_production'));
            $data['pic_production'] = $img_str;
        }
        if($request->has('pic_other')){ //其他
            $img_str = $this->remoteGetImg($request->input('pic_other'));
            $data['pic_other'] = $img_str;
        }

        $drawer_id = DB::table('drawers')->insertGetId($data);
        if($drawer_id){
            $pids = explode(',', $request->input('pid'));
            foreach($pids as $k => $v){
                $product_id = Product::where('sxt_id', $v)->value('id');
                if(!$product_id){
                    continue;
                }
                $attach = ['drawer_id'=> $drawer_id, 'product_id'=> $product_id, 'created_at'=> date('Y-m-d H:i:s'), 'updated_at'=> date('Y-m-d H:i:s')];
                $res = DB::table('drawer_product')->insertGetId($attach);
            }
            return json_encode(array('code'=> 200, 'msg'=> '添加开票人成功', 'drawer_id'=> $drawer_id), JSON_UNESCAPED_UNICODE);
        }else{
            return json_encode(array('code'=> 400, 'msg'=> '添加开票人失败', 'drawer_id'=> $drawer_id), JSON_UNESCAPED_UNICODE);
        }
        
    }
    //添加或更新贸易商
    public function traderStore(Request $request)
    {
        $access = $this->analysis_token($request->input('token'));
        if($access == false){
            return json_encode(array('code'=> 400, 'msg'=> 'token错误'), JSON_UNESCAPED_UNICODE);
        }
        $param = 0;
        $request->has('id') ? '' : $param = 1;
        $request->has('name') ? '' : $param = 1;
        $request->has('country_id') ? '' : $param = 1;
        $request->has('address') ? '' : $param = 1;
        $request->has('email') ? '' : $param = 1;
        $request->has('cellphone') ? '' : $param = 1;
        $request->has('address') ? '' : $param = 1;
        $request->has('create_name') ? '' : $param = 1;
        if($param == 1){
            return json_encode(array('code'=> 400, 'msg'=> '参数不全'), JSON_UNESCAPED_UNICODE);
        }
        $data = [
            'sxt_id' => $request->input('id'), //sxt贸易商id
            'name' => $request->input('name'), //贸易商名
            'country_id' => $request->input('country_id'), //国家id
            'address' => $request->input('address'), //地址
            'email' => $request->input('email'), //邮箱
            'cellphone' => $request->input('cellphone'), //手机
            'url' => $request->input('url'), //网址
        ];
        $trader_id = Trader::where('sxt_id', $request->input('id'))->value('id');
        if($trader_id){
            $data['updated_user_name'] = $request->input('create_name'); //创建者
            $data['updated_at'] = date('Y-m-d H:i:s');
            $res = DB::table('traders')->where('id', $trader_id)->update($data);
            if($res){
                return json_encode(array('code'=> 200, 'msg'=> '更新贸易商成功', 'trader_id'=> $trader_id), JSON_UNESCAPED_UNICODE);
            }else{
                return json_encode(array('code'=> 400, 'msg'=> '更新贸易商失败', 'trader_id'=> $trader_id), JSON_UNESCAPED_UNICODE);
            }
        }else{
            $data['created_user_name'] = $request->input('create_name'); //创建者
            $data['created_at'] = date('Y-m-d H:i:s');
            $trader_id = DB::table('traders')->insertGetId($data);
            if($trader_id){
                return json_encode(array('code'=> 200, 'msg'=> '添加贸易商成功', 'trader_id'=> $trader_id), JSON_UNESCAPED_UNICODE);
            }else{
                return json_encode(array('code'=> 400, 'msg'=> '添加贸易商失败', 'trader_id'=> $trader_id), JSON_UNESCAPED_UNICODE);
            }
        }

    }
    //添加订单
    public function orderStore(Request $request)
    {
        $access = $this->analysis_token($request->input('token'));
        if($access == false){
            return json_encode(array('code'=> 400, 'msg'=> 'token错误'), JSON_UNESCAPED_UNICODE);
        }
        if($request->has('cus_num')){ //客户编号
            $customer_id = Customer::where('number', $request->input('cus_num'))->value('id');
            if(!$customer_id){
                return json_encode(array('code'=> 400, 'msg'=> '客户编号不存在'), JSON_UNESCAPED_UNICODE);
            }
        }else{
            return json_encode(array('code'=> 400, 'msg'=> '参数不全'), JSON_UNESCAPED_UNICODE);
        }
        if($request->has('trader_id')){ //贸易商id
            $trader_id = Trader::where('sxt_id', $request->input('trader_id'))->value('id');
            if(!$trader_id){
                return json_encode(array('code'=> 400, 'msg'=> '贸易商不存在'), JSON_UNESCAPED_UNICODE);
            }
        }else{
            return json_encode(array('code'=> 400, 'msg'=> '参数不全'), JSON_UNESCAPED_UNICODE);
        }
        $param = 0;
        $request->has('id') ? '' : $param = 1; //商翔通订单id
        $request->has('contract') ? '' : $param = 1; //合同类型
        $request->has('number') ? '' : $param = 1; //订单号
        $request->has('declare_mode') ? '' : $param = 1; //报关方式
        $request->has('business') ? '' : $param = 1; //业务类型
        $request->has('currency') ? '' : $param = 1; //报关币种
        $request->has('price_clause') ? '' : $param = 1; //价格条款
        $request->has('package') ? '' : $param = 1; //包装方式
        $request->has('loading_mode') ? '' : $param = 1; //装柜方式
        $request->has('package_number') ? '' : $param = 1; //整体包装件数
        $request->has('order_package') ? '' : $param = 1; //整体包装方式
        $request->has('district_code') ? '' : $param = 1; //境内货源地编码
        $request->has('shipment_port') ? '' : $param = 1; //起运港
        $request->has('aim_country') ? '' : $param = 1; //目的国
        $request->has('unloading_port') ? '' : $param = 1; //抵运港
        $request->has('transport') ? '' : $param = 1; //运输方式
        $request->has('clearance_port') ? '' : $param = 1; //报关口岸
        $request->has('create_name') ? '' : $param = 1;
        $request->has('pro') ? '' : $param = 1; //产品
        if($param == 1){
            return json_encode(array('code'=> 400, 'msg'=> '参数不全'), JSON_UNESCAPED_UNICODE);
        }
        $pro = json_decode($request->input('pro'));
        if(!is_array($pro)){
            return json_encode(array('code'=> 400, 'msg'=> '产品参数错误'), JSON_UNESCAPED_UNICODE);
        }
        
        $data = [
            'logistics' => 0, //非公司物流
            'customer_id' => $customer_id,
            'contract' => $request->input('contract'), //合同类型
            'number' => $request->input('number'), //单号
            'ordnumber' => $request->input('contract') .'-'. $request->input('number'), //订单号
            'business' => $request->input('business'), //业务类型
            'sales_unit' => 207, //经营单位，默认商翔
            'clearance_port' => $request->input('clearance_port'),//报关口岸
            'declare_mode' => $request->input('declare_mode'),//报关方式
            'currency' => $request->input('currency'),//币种
            'package' => $request->input('package'),//包装方式
            'price_clause' => $request->input('price_clause'),//价格条款
            'loading_mode' => $request->input('loading_mode'),//装柜方式
            'package_number' => $request->input('package_number'),//整体包装件数
            'order_package' => $request->input('order_package'),//整体包装方式
            'status' => 1, //草稿
            'shipment_port' => $request->input('shipment_port'),//起运港
            'aim_country' => $request->input('aim_country'),//最终目的国
            'unloading_port' => $request->input('unloading_port'),//抵运港
            'broker_number' => $request->has('broker_number') ? $request->input('broker_number') : null,//报关行代码(10位)
            'broker_name' => $request->has('broker_name') ? $request->input('broker_name') : null,//报关行名称
            'ship_name' => $request->has('ship_name') ? $request->input('ship_name') : null,//船名/航次
            'transport' => $request->input('transport'),//运输方式
            'district_code' => $request->input('district_code'),//境内货源地编码
            'shipping_at' => $request->has('shipping_at') ? $request->input('shipping_at') : null,//预计装柜日期
            'packing_at' => $request->has('packing_at') ? $request->input('packing_at') : null,//装柜日期
            'customs_at' => $request->has('customs_at') ? $request->input('customs_at') : null,//报关日期
            'sailing_at' => $request->has('sailing_at') ? $request->input('sailing_at') : null,//开船日期
            'trader_id' => $trader_id, //贸易商id
            'operator' => $request->has('operator') ? $request->input('operator') : null,//操作人
            'white_card' => $request->has('white_card') ?$request->input('white_card') : null,//白卡号
            'plate_number' => $request->has('plate_number') ? $request->input('plate_number') : null,//车牌号
            'box_number' => $request->has('box_number') ? $request->input('box_number') : null,//货柜箱号
            'box_type' => $request->has('box_type') ? $request->input('box_type') : null, //货柜箱型
            'tdnumber' => $request->has('tdnumber') ? $request->input('tdnumber') : null, //提单号

            'total_num'=> $request->has('total_num') ? $request->input('total_num') : null, //产品总数量
            'total_packnum'=> $request->has('total_packnum') ? $request->input('total_packnum') : null, //产品总件数
            'total_value'=> $request->has('total_value') ? $request->input('total_value') : null, //总货值
            'total_net_weight'=> $request->has('total_net_weight') ? $request->input('total_net_weight') : null, //总净重
            'total_weight'=> $request->has('total_weight') ? $request->input('total_weight') : null, //总毛重
            'total_volume'=> $request->has('total_volume') ? $request->input('total_volume') : null, //总体积
            'sxt_id' => $request->input('id'), //商翔通订单id
        ];
        $order_id = Order::where('sxt_id', $request->input('id'))->value('id');
        if($order_id){
            $data['updated_user_name'] = $request->input('create_name'); //创建者
            $data['updated_at'] = date('Y-m-d H:i:s');
            $res = DB::table('orders')->where('id', $order_id)->update($data);
            if($res){
                DB::table('drawer_product_order')->where('order_id', $order_id)->delete();
                foreach($pro as $k => $v){
                    $drawer_id = Drawer::where('sxt_id', $v->drawer_id)->value('id');
                    if(!$drawer_id){
                        continue;
                    }
                    $product_id = Product::where('sxt_id', $v->product_id)->value('id');
                    if(!$product_id){
                        continue;
                    }
                    $drawer_product_id = DB::table('drawer_product')->where('drawer_id', $drawer_id)->where('product_id', $product_id)->value('id');
                    if(!$drawer_product_id){
                        continue;
                    }
                    $attach_data = [
                        'order_id' => $order_id,
                        'drawer_product_id' => $drawer_product_id,
                        'number' => $v->number,
                        'unit' => $v->unit,
                        'single_price' => $v->single_price,
                        'total_price' => $v->total_price,
                        'default_num' => $v->default_num,
                        'value' => $v->value,
                        'total_weight' => $v->total_weight,
                        'net_weight' => $v->net_weight,
                        'volume' => $v->volume,
                        'pack_number' => $v->pack_number,
                        'u_name' => $v->u_name,
                        'merge' => $v->merge,
                        'created_at' => date('Y-m-d H:i:s'),
                    ];
                    DB::table('drawer_product_order')->insertGetId($attach_data);
                }
                return json_encode(array('code'=> 200, 'msg'=> '更新订单成功', 'order_id'=> $order_id), JSON_UNESCAPED_UNICODE);
            }else{
                return json_encode(array('code'=> 400, 'msg'=> '更新订单失败', 'order_id'=> $order_id), JSON_UNESCAPED_UNICODE);
            }
        }else{
            $data['created_user_name'] = $request->input('create_name'); //创建者
            $data['created_at'] = date('Y-m-d H:i:s');
            $order_id = DB::table('orders')->insertGetId($data);
            if($order_id){
                foreach($pro as $k => $v){
                    $drawer_id = Drawer::where('sxt_id', $v->drawer_id)->value('id');
                    if(!$drawer_id){
                        continue;
                    }
                    $product_id = Product::where('sxt_id', $v->product_id)->value('id');
                    if(!$product_id){
                        continue;
                    }
                    $drawer_product_id = DB::table('drawer_product')->where('drawer_id', $drawer_id)->where('product_id', $product_id)->value('id');
                    if(!$drawer_product_id){
                        continue;
                    }
                    $attach_data = [
                        'order_id' => $order_id,
                        'drawer_product_id' => $drawer_product_id,
                        'number' => $v->number,
                        'unit' => $v->unit,
                        'single_price' => $v->single_price,
                        'total_price' => $v->total_price,
                        'default_num' => $v->default_num,
                        'value' => $v->value,
                        'total_weight' => $v->total_weight,
                        'net_weight' => $v->net_weight,
                        'volume' => $v->volume,
                        'pack_number' => $v->pack_number,
                        'u_name' => $v->u_name,
                        'merge' => $v->merge,
                        'created_at' => date('Y-m-d H:i:s'),
                    ];
                    DB::table('drawer_product_order')->insertGetId($attach_data);
                }
                return json_encode(array('code'=> 200, 'msg'=> '添加订单成功', 'order_id'=> $order_id), JSON_UNESCAPED_UNICODE);
            }else{
                return json_encode(array('code'=> 400, 'msg'=> '添加订单失败', 'order_id'=> $order_id), JSON_UNESCAPED_UNICODE);
            }
        }
    }

    //获取数据维护
    public function getData(Request $request)
    {
        $access = $this->analysis_token($request->input('token'));
        if($access == false){
            return json_encode(array('code'=> 400, 'msg'=> 'token错误'), JSON_UNESCAPED_UNICODE);
        }
        if(!$request->has('father_id')){
            return json_encode(array('code'=> 400, 'msg'=> '参数不全'), JSON_UNESCAPED_UNICODE);
        }
        $data = Data::where('father_id', $request->input('father_id'))->get();
        return json_encode(['code'=> 200, 'data'=> $data], JSON_UNESCAPED_UNICODE);
    }
}
