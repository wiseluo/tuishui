<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\ProductPostRequest;
use App\Services\Admin\ProductService;
use App\Services\Admin\DataService;
use App\Exports\Admin\ProductExport;

class ProductController extends Controller
{
    public function __construct(ProductService $productService)
    {
        parent::__construct();
        $this->productService = $productService;
    }

    public function index(Request $request, $status = 0)
    {
        if ($request->ajax()) {
            $param = $request->input();
            $param['status'] = $status;
            $param['userlimit'] = $this->getUserLimit();
            $list = $this->productService->productIndex($param);
            return response()->json(['code'=>200, 'total'=> $list['total'], 'rows'=> $list['data']]);
        }
        $returnData = $this->productService->renderStatus();
        $returnData['outsideUser'] = $this->isOutsideUser();
        return view('admin.product.index', $returnData);
    }

    public function save(ProductPostRequest $request)
    {
        $param = $request->except('standards');
        $param['standard'] = implode('|', $request->standards);
        $param['cid'] = $this->getUserCid();
        $res = $this->productService->saveProcess($param);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    public function delete($id)
    {
        $res = $this->productService->destroy($id);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    public function update(ProductPostRequest $request, $id)
    {
        $param = $request->except('standards');
        $param['standard'] = implode('|', $request->standards);
        $param['cid'] = $this->getUserCid();
        $res = $this->productService->updateProcess($param, $id);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    public function approve(Request $request, $id)
    {
        $res = $this->productService->update($request->input(), $id);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    public function retrieve($id)
    {
        $res = $this->productService->retrieve($id);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    //修改已完成的产品
    public function updateDone(ProductPostRequest $request, $id)
    {
        $param = $request->except('standards');
        $param['standard'] = implode('|', $request->standards);
        $res = $this->productService->update($param, $id);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    public function read(DataService $dataService, $type, $id = 0)
    {
        $tax_refund_rate = $dataService->getTypesNameByFatherId(12);
        $measure_unit = $dataService->getTypesByFatherId(15);
        
        $view = view('admin.product.prodetail');
        $view->with(compact('tax_refund_rate','measure_unit'));
        if ($id) {
            $view->with('product', $this->productService->find($id));
        }
        if($type == 'update_done' || $type == 'save' || $type == 'update'){//用于修改已完成产品的某些属性
            $view->with('updateDoneDisabled', false);
        }else{
            $view->with('updateDoneDisabled', 'disabled');
        }
        return $view->with('disabled', in_array(request()->route('type'), ['read', 'approve', 'update_done']) ? 'disabled' : false);
    }

    public function productChoose(Request $request)
    {
        $action = $request->input('action');
        if($action == 'html'){
            $customer_id = $request->input('customer_id', 0);
            return view('admin.product.chooseProduct', compact('customer_id'));
        }else if($action == 'data'){
            $param = $request->input();
            $param['status'] = 3;
            $param['userlimit'] = $this->getUserLimit();
            $param['customer_id'] = $request->input('customer_id');
            $data = $this->productService->productIndex($param);
            return response()->json(['code'=> 200, 'data'=> $data]);
        }
        return response()->json(['code'=> 400, 'msg'=> '无效参数']);
    }

    public function productInfo($id)
    {
        $data = $this->productService->getProductInfo($id);
        return response()->json(['code'=> 200, 'data'=> $data]);
    }

    public function relateDrawer(ProductPostRequest $request)
    {
        $drawer_id = $request->input('drawer_id');
        $product_id = $request->input('product_id');
        $res = $this->productService->relateDrawerService($drawer_id, $product_id);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }
    
    public function delDrawer(ProductPostRequest $request)
    {
    	$drawer_id = $request->input('drawer_id');
    	$product_id = $request->input('product_id');
    	$res = $this->productService->delDrawerService($drawer_id, $product_id);
    	if($res['status']){
    		return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
    	}else{
    		return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
    	}
    }
    public function relatedDrawer(Request $request)
    {
        $product_id = $request->input('product_id');
        $pageSize = isset($request->input['pageSize']) ? $request->input['pageSize'] : 10;
        $res = $this->productService->getRelatedDrawer($product_id,$pageSize);
        if($res['status']){
            return response()->json(['code'=> 200, 'data'=> $res['data']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    public function productExport(Request $request, ProductExport $productExport)
    {
        $param = $request->input();
        $param['status'] = 3;
        $param['keyword'] = $request->input('keyword', '');
        $param['userlimit'] = $this->getUserLimit();
        return $productExport->productListExport($param);
    }
}
