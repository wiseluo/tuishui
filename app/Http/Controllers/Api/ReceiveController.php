<?php
namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\ReceiveApiRequest;
use Illuminate\Http\Request;
use App\Receive;

class ReceiveController extends BaseController
{
    public function index(Request $request)
    {
        $where = $this->getBaseWhere();
        $param['cid'] = $where['cid'];
        $param['created_user_id'] = $where['euid'];
        $data = Receive::where($param)
                    ->orderBy('id', 'desc')
                    ->paginate($request->input('pageSize'));
        return response()->json(['code'=>200, 'data'=> $data]);
    }

    public function store(ReceiveApiRequest $request)
    {
        $data = $request->input('data');
        $receive = new Receive($data);
        $res = $receive->save();
        if($res){
            return response()->json(['code'=>200, 'msg'=> '操作成功']);
        }else{
            return response()->json(['code'=>400, 'msg'=> '操作失败']);
        }
    }

    public function show($id)
    {
        $data = Receive::find($id);
        return response()->json(['code'=>200, 'data'=> $data]);
    }

    public function update(ReceiveApiRequest $request, $id)
    {
        $receive = Receive::find($id);
        $res = $receive->update($request->input('data'));
        if($res){
            return response()->json(['code'=>200, 'msg'=> '操作成功']);
        }else{
            return response()->json(['code'=>400, 'msg'=> '操作失败']);
        }
    }

    public function destroy($id)
    {
        $receive = Receive::find($id);
        $res = $receive->delete();
        if($res){
            return response()->json(['code'=>200, 'msg'=> '操作成功']);
        }else{
            return response()->json(['code'=>400, 'msg'=> '操作失败']);
        }
    }
}
