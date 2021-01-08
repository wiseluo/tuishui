<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Traits\Pagination;
use App\Purchase;
use App\Purchaseproduct;
use Curl\Curl;
use App\Services\Admin\PurchaseService;
use App\Http\Requests\PurchasePostRequest;
use \Firebase\JWT\JWT;

class PurchaseController extends Controller
{
    use Pagination;
    protected $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    public function analysis_token($token)
    {
        if($token){
            JWT::decode($token, config('app.user_center_jwt_key'), ['HS256']);
            return true;
        }
        return false;
    }

    public function index(Request $request, $refund = 0)
    {
        if ($request->ajax()) {
            $cid = $this->getUserCid();
            if($cid) {
                $where = ['cid'=> $cid];
            }else {
                $where = [];
            }
            return $this->paginateRender(Purchase::search($request->input('keyword'))->where(['refundable'=> $refund])->where($where), $request->input('pageSize'));
        }
        return view('admin.purchase.index');
    }

    public function purchaseThrowTax(PurchasePostRequest $request)
    {
        //return json_encode(array('code'=> 400, 'msg'=> $request->input()), JSON_UNESCAPED_UNICODE);
        $access = $this->analysis_token($request->input('token'));
        if($access == false){
            return json_encode(array('code'=> 400, 'msg'=> 'token错误'), JSON_UNESCAPED_UNICODE);
        }
        $res = $this->purchaseService->savePurchaseFromErp($request->input());
        if($res){
            return json_encode(array('code'=> 200, 'msg'=> '成功'), JSON_UNESCAPED_UNICODE);
        }else{
            return json_encode(array('code'=> 400, 'msg'=> '失败'), JSON_UNESCAPED_UNICODE);
        }
    }

    public function read($type = 'read', $id = 0)
    {
        $view = view('admin.purchase.purdetail');
        if ($id) {
            $view->with($this->purchaseService->getPurchaseAndProductList($id));
        }
        
        return $this->disable($view);
    }

    public function update(Curl $curl, Request $request, $id)
    {
        $res = $this->purchaseService->update($request->input(), $id);
        if($res){
            $post_data = array(
                'token'=> session('center_token'),
                'id'=> $id,
                'tax_refundable'=> $request->input('refundable'),
                'tax_opinion'=> $request->input('opinion'),
                'tax_approved_at'=> $request->input('approved_at'),
            );
            $res = $curl->post(config('app.erpapp_url'). '/erptrade/purchase_tax_return', $post_data)->response;
            $result = json_decode($res);
            if($result->code == 200){

            }
        }
        return $res;
    }

    public function approve(Curl $curl, Request $request, $id)
    {
        $res = $this->purchaseService->update($request->input(), $id);
        if($res){
            $post_data = array(
                'token'=> session('center_token'),
                'id'=> $id,
                'tax_refundable'=> $request->input('refundable'),
                'tax_opinion'=> $request->input('opinion'),
                'tax_approved_at'=> $request->input('approved_at'),
            );
            $res = $curl->post(config('app.erpapp_url'). '/erptrade/purchase_tax_return', $post_data)->response;
            $result = json_decode($res);
            if($result->code == 200){

            }
        }
        return $res;
    }
}
