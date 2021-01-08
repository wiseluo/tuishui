<?php
namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\LinkApiRequest;
use Illuminate\Http\Request;
use App\Link;

class LinkController extends BaseController
{
    public function index(Request $request)
    {
        $param['userlimit'] = $this->getUserLimit();
        $data = Link::where($param['userlimit'])
                    ->orderBy('id', 'desc')
                    ->paginate($request->input('pageSize'));
        return response()->json(['code'=>200, 'data'=> $data, 'user'=> $request->user]);
    }

    public function store(LinkApiRequest $request)
    {
        $data = $request->input('data');
        $link = new Link($data);
        $res = $link->save();
        if($res){
            return response()->json(['code'=>200, 'msg'=> '操作成功']);
        }else{
            return response()->json(['code'=>400, 'msg'=> '操作失败']);
        }
    }

    public function show($id)
    {
        $data = Link::find($id);
        return response()->json(['code'=>200, 'data'=> $data]);
    }

    public function update(LinkApiRequest $request, $id)
    {
        $link = Link::find($id);
        $res = $link->update($request->input('data'));
        if($res){
            return response()->json(['code'=>200, 'msg'=> '操作成功']);
        }else{
            return response()->json(['code'=>400, 'msg'=> '操作失败']);
        }
    }

    public function destroy($id)
    {
        $link = Link::find($id);
        $res = $link->delete();
        if($res){
            return response()->json(['code'=>200, 'msg'=> '操作成功']);
        }else{
            return response()->json(['code'=>400, 'msg'=> '操作失败']);
        }
    }
}
