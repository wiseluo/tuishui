<?php

namespace App\Repositories;

use DB;
use App\Order;
use App\Data;
use App\Company;
use App\Customer;
use App\Trader;
use App\Drawer;
use App\District;
use App\DrawerProductOrder;

class OrderRepository
{
    public function renderStatus()
    {
        return Order::renderStatus();
    }

    public function orderIndex($param)
    {
        $where = [];
        $userlimit = [];
        if(isset($param['userlimit'])){
            $userlimit = $param['userlimit'];
        }
        if($param['status']){
            $where[] = ['status', '=', $param['status']];
        }
        // dd($where);
        $keyword = isset($param['keyword']) ? $param['keyword'] : '';
        $pageSize = isset($param['pageSize']) ? $param['pageSize'] : 10;
        $apply_start_date = isset($param['apply_start_date']) ? $param['apply_start_date'] : '';
        $apply_end_date = isset($param['apply_end_date']) ? $param['apply_end_date'] : '';
        return Order::searchDateTime('orders.created_at', $apply_start_date, $apply_end_date)
            ->with(['customer', 'declareModeData', 'company'])
            ->where(function($query)use($keyword){
                $query->when($keyword, function($query)use($keyword){
                    return $query->whereHas('drawerProducts.drawer', function ($query) use ($keyword){
                            $query->where('company', 'LIKE', '%'. $keyword .'%');
                        })
                        ->orWhereHas('customer', function ($query) use ($keyword){
                            $query->where('name', 'LIKE', '%'. $keyword .'%')
                                ->orWhere('salesman', 'LIKE', '%'. $keyword .'%')
                                ->orWhere('created_user_name', 'LIKE', '%'. $keyword .'%');
                        })
                        ->orWhereHas('company', function ($query) use ($keyword){
                            $query->where('name', 'LIKE', '%'. $keyword .'%');
                        })
                        ->orWhere('ordnumber', 'LIKE', '%'. $keyword .'%')
                        ->orWhere('customs_number', 'LIKE', '%'. $keyword .'%');
                });
            })
            ->where($where)
            ->where($userlimit)
            ->orderBy('id', 'desc')
            //->toSql();
            ->paginate($pageSize)
            ->toArray();
    }

    public function getOrderList($param)
    {
        $userlimit = [];
        if(isset($param['userlimit'])){
            $userlimit = $param['userlimit'];
        }
        $keyword = $param['keyword'];
        $apply_start_date = isset($param['apply_start_date']) ? $param['apply_start_date'] : '';
        $apply_end_date = isset($param['apply_end_date']) ? $param['apply_end_date'] : '';
        return Order::searchDateTime('orders.created_at', $apply_start_date, $apply_end_date)
        ->with(['customer', 'declareModeData', 'trader.country', 'drawerProducts', 'company'])
        ->where(function($query)use($keyword){
            $query->when($keyword, function($query)use($keyword){
                return $query->whereHas('drawerProducts.drawer', function ($query) use ($keyword){
                        $query->where('company', 'LIKE', '%'. $keyword .'%');
                    })
                    ->orWhereHas('customer', function ($query) use ($keyword){
                        $query->where('name', 'LIKE', '%'. $keyword .'%')
                            ->orWhere('salesman', 'LIKE', '%'. $keyword .'%')
                            ->orWhere('created_user_name', 'LIKE', '%'. $keyword .'%');
                    })
                    ->orWhereHas('company', function ($query) use ($keyword){
                        $query->where('name', 'LIKE', '%'. $keyword .'%');
                    })
                    ->orWhere('ordnumber', 'LIKE', '%'. $keyword .'%')
                    ->orWhere('customs_number', 'LIKE', '%'. $keyword .'%');
            });
        })
        ->where($userlimit)
        ->get();
    }

    public function save($param)
    {
        $order = new Order($param);
        $order->save();
        return $order->id;
    }

    public function update($param, $id)
    {
        $order = Order::find($id);
        return $order->update($param);
    }

    public function relateProduct($id, $pro)
    {
        if(count($pro) == 0) {
            return 1;
        }
        $order = Order::find($id);
        $order->drawerProducts()->detach();
        //同一开票人产品不要合并
        array_map(function($carry)use($order){
            //换汇成本= (开票金额/(1+开票人增值税率%))*(1+开票人增值税率%-产品退税率%)/货值
            // $carry['exchange_cost'] = bcdiv(bcmul(bcdiv($carry['value'], bcadd(1, $carry['tax_rate'], 2), 2), bcsub(bcadd(1, $carry['tax_rate'], 2), bcdiv($carry['tax_refund_rate'], 100, 2), 2)), $carry['total_price'], 2);
            $carry['measure_unit_cn'] = Data::find($carry['measure_unit'])->name;
            $carry['measure_unit_en'] = Data::find($carry['measure_unit'])->value;
            $order->drawerProducts()->attach([$carry['drawer_product_id'] => $carry]);
        }, $pro);
        return 1;
    }

    public function delete($id)
    {
        return Order::destroy($id);
    }

    public function find($id)
    {
        return Order::find($id);
    }

    public function findWithRelation($id)
    {
        return Order::with(['drawerProducts.product.brand','link','receive','deposit', 'tradecountry'])->find($id);
    }

    public function getOrdersByStatus($status)
    {
        return Order::status($status)->get();
    }

    public function getOneOrderByWhere($where)
    {
        return Order::where($where)->first();
    }

    public function getOrderWithDrawerProducts($id)
    {
        return Order::with('drawerProducts')->find($id);
    }

    public function countByWhere($where)
    {
        return Order::where($where)->count();
    }

    public function updateByWhereIn($data, $whereIn)
    {
        return Order::whereIn('id', $whereIn)->update($data);
    }

    public function getUnbindReceiptOrderList($param)
    {
        return Order::where(['is_remit'=> 0, 'status'=> 5, 'customer_id'=> $param['customer_id']])
                ->with('receipt', 'currencyData')
                ->orderBy('id', 'desc')
                ->paginate($param['pageSize'])
                ->toArray();
    }

    public function TransportOrderChoose($param)
    {
        $where = [];
        if($param['tax_type'] == 0){
            $where[] = ['cid', '=', 0];
        }else{
            $where[] = ['cid', '=', $param['company_id']];
        }
        $where[] = ['status', '>=', 5];
        $where[] = ['company_id', '=', $param['company_id']];

        $keyword = isset($param['keyword']) ? $param['keyword'] : '';
        $pageSize = isset($param['pageSize']) ? $param['pageSize'] : 10;
        return Order::where(function($query)use($keyword){
                $query->when($keyword, function($query)use($keyword){
                    return $query->where('ordnumber', 'LIKE', '%'. $keyword .'%')
                        ->orWhere('customs_number', 'LIKE', '%'. $keyword .'%');
                });
            })
            ->with('customer')
            ->where($where)
            ->orderBy('id', 'desc')
            ->paginate($pageSize)
            ->toArray();
    }

    public function sendSxtOrderData($id)
    {
        return Order::with(['drawerProducts.product.brand','drawerProducts.drawer','customer','orderPackageData','clearancePortData','transPortData','priceClauseData','declareModeData','shipmentport','unloadingport','currencyData','link','receive','deposit','tradecountry','user'])->find($id);
    }

    public function getNextOrdnumber($cid)
    {
        $companies = Company::where('id', $cid)->first();
        $ordnumber = $companies->prefix.date('ymd');
        $last = Order::where('from_type',1)->whereYear('created_at',date('Y'))->orderBy('id','desc')->limit(1)->get()->first();
        if($last){
            $num = (int)substr($last->ordnumber,-5);
            $last = sprintf("%05d",$num+1);
            $ordnumber .= $last;
        }else{
            $ordnumber .= '00001';
        }
        return $ordnumber;
    }

    public function matchCompanyRepository($where)
    {
        return Company::where($where)->first();
    }

    public function matchCustomerRepository($where)
    {
        return Customer::where($where)->first();
    }

    public function matchTraderRepository($where)
    {
        return Trader::where($where)->first();
    }

    public function matchDrawerRepository($where)
    {
        return Drawer::where($where)->first();
    }

    public function matchDomesticSourceRepository($where)
    {
        return District::where($where)->first();
    }

    //电商园统计
    public function totalStatisticsByYear($year)
    {
        return DB::table('orders')
            ->select(DB::raw('count(id) orders,count(DISTINCT customer_id) customers,IFNULL(sum(total_value),0) sum_value,IFNULL(sum(total_value_invoice),0) sum_invoice_value'))
            ->whereBetween('export_date', [$year. '-01-01', $year. '-12-31'])
            //->toSql();
            ->first();
    }

    public function pieChartOrderStatisticsByYear($year)
    {
        return DB::table('orders')
            ->select(DB::raw('count(orders.id) value,companies.name name'))
            ->join('companies', 'orders.company_id', '=', 'companies.id')
            ->whereBetween('export_date', [$year. '-01-01', $year. '-12-31'])
            ->groupBy('orders.company_id')
            ->get();
    }

    public function pieChartInvoiceStatisticsByYear($year)
    {
        return DB::table('orders')
            ->select(DB::raw('IFNULL(sum(total_value_invoice),0) value,companies.name name'))
            ->join('companies', 'orders.company_id', '=', 'companies.id')
            ->whereBetween('export_date', [$year. '-01-01', $year. '-12-31'])
            ->groupBy('orders.company_id')
            ->get();
    }

    public function pieChartCustomsStatisticsByYear($year)
    {
        return DB::table('orders')
            ->select(DB::raw('IFNULL(sum(total_value),0) value,companies.name name'))
            ->join('companies', 'orders.company_id', '=', 'companies.id')
            ->whereBetween('export_date', [$year. '-01-01', $year. '-12-31'])
            ->groupBy('orders.company_id')
            ->get();
    }

    public function barChartOrderStatisticsByYear($year)
    {
        return DB::table('orders')
            ->select(DB::raw('count(orders.id) value'))
            ->join('companies', 'orders.company_id', '=', 'companies.id')
            ->whereBetween('export_date', [$year. '-01-01', $year. '-12-31'])
            ->groupBy('orders.company_id')
            ->get();
    }

    public function barChartProductStatisticsByYear($year)
    {
        return DB::table('orders')
            ->select(DB::raw('IFNULL(sum(total_num),0) value'))
            ->join('companies', 'orders.company_id', '=', 'companies.id')
            ->whereBetween('export_date', [$year. '-01-01', $year. '-12-31'])
            ->groupBy('orders.company_id')
            ->get();
    }

    public function dsyGetOrderList($param)
    {
        $year = $param['year'];
        $pageSize = isset($param['pageSize']) ? $param['pageSize'] : 10;
        return Order::with(['customer','company','country','unloadingport','orderPackage','drawerProducts.drawer','drawerProducts.product'])
            ->whereBetween('export_date', [$year. '-01-01', $year. '-12-31'])
            ->orderBy('id', 'asc')
            //->toSql();
            ->paginate($pageSize)
            ->toArray();
    }
}