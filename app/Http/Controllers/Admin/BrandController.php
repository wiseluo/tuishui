<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\Admin\BrandPostRequest;
use App\Services\Admin\BrandService;
use App\Services\Admin\DataService;

class BrandController extends Controller
{
    protected $brandService;

    public function __construct(BrandService $brandService)
    {
        parent::__construct();
        $this->brandService = $brandService;
    }

    public function index(Request $request, $status = 0)
    {
        if ($request->ajax()) {
            $param = $request->input();
            $param['status'] = $status;
            $param['userlimit'] = $this->getUserLimit();
            $list = $this->brandService->brandIndex($param);
            return response()->json(['code'=>200, 'total'=> $list['total'], 'rows'=> $list['data']]);
        }
        return view('admin.brand.index', $this->brandService->renderStatus());
    }

    public function delete($id)
    {
        $res = $this->brandService->destroy($id);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    public function save(BrandPostRequest $request)
    {
        $res = $this->brandService->save($request->input());
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    public function update(BrandPostRequest $request, $id)
    {
        $res = $this->brandService->update($request->input(), $id);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    public function approve(Request $request, $id)
    {
        $res = $this->brandService->update($request->input(), $id);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    public function read(DataService $dataService, $type = 'read', $id = 0)
    {
        $view = view('admin.brand.branddetail', [
            'type' => $dataService->getTypesByFatherId(17),
            'classify' => $dataService->getTypesByFatherId(18)
        ]);
        if ($id) {
            $brand = $this->brandService->find($id);
            $view->with('brand', $brand);
        }
        
        return $this->disable($view);
    }

    public function brandlist(Request $request)
    {
        $action = $request->input('action');
        if($action == 'html'){
            return view('admin.brand.chooseBrand');
        }else if($action == 'data'){
            $param = $request->input();
            $param['status'] = 3;
            $param['userlimit'] = $this->getUserLimit();
            $data = $this->brandService->brandIndex($param);
            return response()->json(['code'=> 200, 'data'=> $data]);
        }
        return response()->json(['code'=> 400, 'msg'=> '无效参数']);
    }
}
