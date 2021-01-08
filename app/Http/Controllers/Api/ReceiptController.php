<?php

namespace App\Http\Controllers\Api;

use App\Order;
use App\Receipt;
use App\Repositories\OrderRepository;
use App\Repositories\ReceiptRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class ReceiptController extends BaseController
{
    public function index(Request $request)
    {
        $resultData = Receipt::search($request->keyword)->with(['customer'=>function($query) {
            $query->select('id','name');
        }, 'currency'=>function($query) {
            $query->select('id','name','key','value');
        }])->where(['customer_id'=>$this->getCustomerid(), 'cid'=>$this->getUserCid()])
        ->select('id','status','currency_id','receipt_number','picture','bank','receiptcorp','customer_id','payer','received_at','rate','amount','rmb','created_at')->orderBy('id', 'desc')->paginate($request->input('pageSize', 15));
        
        return response()->json(['code'=>200, 'data'=>$resultData]);

    }

//    public function read(DataRepositoryInterface $dataRepository, ReceiptRepository $repository, $type = 'read', $id = 0)
//    {
//        $currency = $dataRepository->getTypesByFatherId(5);
//
//        $view = view('admin.receipt.recdetail', compact('currency'));
//
//        if ($id) {
//            $receipt = $repository->select($id);
//            //去商翔通获取客户子账号
//            // $post_data = ['token'=> session('center_token'), ' cus_order_id'=> $receipt->customer->number];
//            // $curl = new Curl();
//            // $subAccountData = $curl->get(config('app.sxt_url'). '/Subaccounts', $post_data)->response;
//            // $subAccountJson = json_decode($subAccountData);
//            // $view->with('cusSubAccount', $subAccountJson->data->virtual_account);
//            $view->with('receipt', $receipt);
//        }
//
//        return $this->disable($view);
//    }
//
//    public function save(ReceiptRepository $repository, ReceiptPostRequest $request)
//    {
//        return $repository->save($request->input());
//    }
//
//    public function update(ReceiptRepository $repository, Request $request, $id)
//    {
//        return $repository->update($request->input(), $id);
//    }
//
//    public function delete(ReceiptRepository $repository, $id)
//    {
//        return $repository->delete($id);
//    }

    public function number($id)
    {
        $resultData = Receipt::with(['customer'=>function($query) {
            $query->select('id','name');
        }, 'currency'=>function($query) {
            $query->select('id','name','key','value');
        }])->findOrFail($id, ['id','currency_id','picture','bank','receipt_number','customer_id','payer','received_at','rate','amount','rmb','created_at']);
    
        return response()->json(['code'=>200, 'data'=>$resultData]);
    }

    public function order(OrderRepository $orderRepository, ReceiptRepository $receiptRepository, Request $request, $id, $flag)
    {
        if ($flag) {
            $retrunData = $receiptRepository->findWithRelate($id);
            // $retrunData = Receipt::with(['orders.currencyData'])->whereId($id)
            //     ->select('id')->paginate($request->input('pageSize', 15));
        } else {
            $param['pageSize'] = $request->input('pageSize', 15);
            $param['customer_id'] = $receiptRepository->find($id)->customer_id;
            $retrunData = $orderRepository->getUnbindReceiptOrderList($param);
            
            // $retrunData = Order::where('is_remit', 0)->status(5)
            // ->where('customer_id', $receiptRepository->select($id)->customer_id)
            // ->with('receipt')->select('id','created_at','ordnumber','total_value')->paginate($request->input('pageSize', 15));
        }
        return response()->json(['code'=>200, 'data'=>$retrunData]);
    }

    public function binding(OrderRepository $orderRepository, ReceiptRepository $receiptRepository, Request $request, $id)
    {
        $param = $request->input();
        $validator = Validator::make($param, [
            'order_id' => 'required|integer',
            'money' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $validator->errors();
        }
        $order_id = $param['order_id'];
        $money = $param['money'];
    
        $receipt = $receiptRepository->find($id);
        $order = $orderRepository->find($order_id);
        if($receipt->currency_id != $order->currency){
            return response()->json(['code'=>401, 'msg'=>'订单币种与收汇币种不一致']);
        }
        // $receiptOrder = Receipt::findOrFail($id)->orders()->wherePivot('receipt_id', $id)->get();
        // $pivotMoney = $receiptOrder->reduce(function ($carry, $item) {
        //     return $carry += $item->pivot->money;
        // }, 0);
        // $orderMoney = Receipt::findOrFail($id)->orders()->wherePivot('receipt_id', $id)->wherePivot('order_id', $request->order_id)->get();
        // $orderPivotMoney = $orderMoney->reduce(function ($carry, $item) {
        //     return $carry += $item->pivot->money;
        // }, 0);
        // $totalMoney = $request->money + $pivotMoney;
        // $orderTotalMoney = $request->money + $orderPivotMoney;
        // if($totalMoney > $receipt->money) {
        //    return response()->json(['code'=>401, 'msg'=>'请检查未关联金额']);
        // }elseif ($orderTotalMoney == $order->total_value) {
        //      $orderRepository->update(['is_remit'=>1], $request->order_id);
        // }
        $totalMoney = $money + $receipt->receipted_amount;
        $orderTotalMoney = $money + $order->receipted_amount;

        DB::beginTransaction();
        if($totalMoney > $money) {
           return response()->json(['code'=>401, 'msg'=>'总关联金额大于收汇金额']);
        }else if($orderTotalMoney == $order->total_value) {
            $orderStatus = $orderRepository->update(['is_remit'=>2], $order_id);
        }else if($orderTotalMoney < $order->total_value) {
            $orderStatus = $orderRepository->update(['is_remit'=>1], $order_id);
        }
        $receipt->orders()->attach($order_id, ['money'=> $money]);
        if($orderStatus){
            DB::commit();
            return response()->json(['code'=>200, 'msg'=>'关联成功']);
        }else{
            DB::rollback();
            return response()->json(['code'=>200, 'msg'=>'修改失败']);
        }
    }

    public function unbinding(OrderRepository $orderRepository, ReceiptRepository $receiptRepository, Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|integer',
            'pivot_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return $validator->errors();
        }
        
        //DB::table('order_receipt')->where('id', $request->pivot_id)->delete();
        $order = $orderRepository->find($request->order_id);
        if ($order->total_value != $order->receipted_amount) {
            $orderRepository->update(['is_remit'=>0], $request->order_id);
            return response()->json(['code'=>200, 'msg'=>'取消关联成功']);
            DB::beginTransaction();

            $receipt = $receiptRepository->find($id);
            $receipt->orders()->detach([$request->order_id]);
            $order_res = $orderRepository->update(['is_remit'=>0], $request->order_id);
            if($order_res){
                DB::commit();
                return ['status'=> 1, 'msg'=> '取消关联成功'];
            }else{
                DB::rollback();
                return ['status'=> 0, 'msg'=> '取消关联失败'];
            }
        }else{
            return response()->json(['code'=>401, 'msg'=>'该订单已全部收齐，不能取消关联']);
        }
    }
}
