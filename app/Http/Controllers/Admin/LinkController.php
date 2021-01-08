<?php
namespace App\Http\Controllers\Admin;

use App\Http\Requests\LinkPostRequest;
use App\Services\Admin\LinkService;
use Illuminate\Http\Request;
use App\Link;

class LinkController extends Controller
{
    protected $linkService;

    public function __construct(LinkService $linkService)
    {
        parent::__construct();
        $this->linkService = $linkService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $param = $request->input();
            $param['pageSize'] = $request->input('pageSize', 10);
            $param['userlimit'] = $this->getUserLimit();
            $list = $this->linkService->linkIndex($param);
            return response()->json(['code'=>200, 'total'=> $list['total'], 'rows'=> $list['data']]);
        }
        return view('admin.link.index');
    }

    public function save(LinkPostRequest $request)
    {
        $res = $this->linkService->save($request->input());
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> '添加成功']);
        }else{
            return response()->json(['code'=> 403, 'msg'=> '添加失败']);
        }
    }

    public function read($type = 'read', $id)
    {
        $view = view('admin.link.linkdetail');
        if ($id) {
            $view->with('link', $this->linkService->find($id));
        }
        return $this->disable($view);
    }

    public function update(LinkPostRequest $request, $id)
    {
        $res = $this->linkService->update($request->input(), $id);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> '修改成功']);
        }else{
            return response()->json(['code'=> 403, 'msg'=> '修改失败']);
        }
    }

    public function delete($id)
    {
        $res = $this->linkService->delete($id);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> '删除成功']);
        }else{
            return response()->json(['code'=> 403, 'msg'=> '删除失败']);
        }
    }

    public function linkList(Request $request)
    {
        $action = $request->input('action');
        if($action == 'html'){
            return view('admin.link.chooseLink');
        }else if($action == 'data'){
            $param = $request->input();
            $param['pageSize'] = $request->input('pageSize', 10);
            $param['userlimit'] = $this->getUserLimit();
            $data = $this->linkService->linkIndex($param);
            return response()->json(['code'=>200, 'data'=> $data]);
        }
        return response()->json(['code'=> 400, 'msg'=> '无效参数']);
    }
}
