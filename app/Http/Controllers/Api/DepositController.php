<?php
namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\DepositApiRequest;
use Illuminate\Http\Request;
use App\Deposit;

class DepositController extends BaseController
{
    public function index(Request $request)
    {
        $where = $this->getBaseWhere();
        $param['cid'] = $where['cid'];
        $param['created_user_id'] = $where['euid'];
        $data = Deposit::where($param)
                    ->orderBy('id', 'desc')
                    ->paginate($request->input('pageSize'));
        return response()->json(['code'=>200, 'data'=> $data]);
    }

    public function store(DepositApiRequest $request)
    {
        $data = $request->input('data');
        $deposit = new Deposit($data);
        $res = $deposit->save();
        if($res){
            return response()->json(['code'=>200, 'msg'=> '操作成功']);
        }else{
            return response()->json(['code'=>400, 'msg'=> '操作失败']);
        }
    }

    public function show($id)
    {
        $data = Deposit::find($id);
        return response()->json(['code'=>200, 'data'=> $data]);
    }

    public function update(DepositApiRequest $request, $id)
    {
        $deposit = Deposit::find($id);
        $res = $deposit->update($request->input('data'));
        if($res){
            return response()->json(['code'=>200, 'msg'=> '操作成功']);
        }else{
            return response()->json(['code'=>400, 'msg'=> '操作失败']);
        }
    }

    public function destroy($id)
    {
        $deposit = Deposit::find($id);
        $res = $deposit->delete();
        if($res){
            return response()->json(['code'=>200, 'msg'=> '操作成功']);
        }else{
            return response()->json(['code'=>400, 'msg'=> '操作失败']);
        }
    }
}
