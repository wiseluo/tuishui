<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\Api\ProductApiRequest;
use App\Services\Api\ProductService;

class ProductController extends BaseController
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        parent::__construct();
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $param = $request->input();
        $param['status'] = $request->input('status', 0);
        $param['userlimit'] = $this->getUserLimit();
        $list = $this->productService->productIndex($param);
        return response()->json(['code'=>200, 'data'=> $list]);
    }

    public function save(ProductApiRequest $request)
    {
        $param = array_diff_key($request->input('data'), ['standards'=>0]);
        $param['cid'] = $this->getUserCid();
        $param['customer_id'] = $this->getCustomerid();
        if($param['customer_id'] == 0){
            return response()->json(['code'=>401, 'msg'=> '未找到客户信息，请重新登录！']);
        }
        $param['standard'] = implode('|', $request->input('data.standards'));
        $res = $this->productService->saveProcess($param);
        if($res['status']){
            return response()->json(['code'=>200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=>401, 'msg'=> $res['msg']]);
        }
    }

    public function delete($id)
    {
        $res = $this->productService->destroy($id);
        if($res['status']){
            return response()->json(['code'=>'200', 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=>'401', 'msg'=> $res['msg']]);
        }
    }

    public function update(ProductApiRequest $request, $id)
    {
        $param = array_diff_key($request->input('data'), ['standards'=>0]);
        $param['cid'] = $this->getUserCid();
        $param['customer_id'] = $this->getCustomerid();
        $param['standard'] = implode('|', $request->input('data.standards'));
        $res = $this->productService->updateProcess($param, $id);
        if($res['status']){
            return response()->json(['code'=>'200', 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=>'401', 'msg'=> $res['msg']]);
        }
    }

    public function show($id)
    {
        $data = $this->productService->showService($id);
        return response()->json(['code'=>200, 'data'=> $data]);
    }

    public function productChoose(Request $request)
    {
        $param = $request->input();
        $param['status'] = 3;
        $param['userlimit'] = $this->getUserLimit();
        $data = $this->productService->productIndex($param);
        return response()->json(['code'=>200, 'data'=> $data]);
    }
}
