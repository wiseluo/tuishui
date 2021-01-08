<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\ClearanceApiRequest;
use App\Services\Api\ClearanceService;

class ClearanceController extends BaseController
{
    public function __construct(ClearanceService $clearanceService)
    {
        parent::__construct();
        $this->clearanceService = $clearanceService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $param = $request->input();
            $where = $this->getBaseWhere();
            $param['cid'] = $where['cid'];
            $param['created_user_id'] = $where['euid'];
            $list = $this->clearanceService->clearanceIndex($param);
            return response()->json(['code'=>200, 'total'=> $list['total'], 'rows'=> $list['data']]);
        }
        return view('admin.clearance.index');
    }

    public function get($id)
    {
        return $this->clearanceService->get($id);
    }

    public function update(Request $request, $id)
    {
        $res = $this->clearanceService->update($request->input(), $id);
        if($res['status']){
            return response()->json(['code'=>200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=>400, 'msg'=> $res['msg']]);
        }
    }

}
