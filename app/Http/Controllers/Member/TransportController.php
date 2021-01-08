<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Requests\TransportPostRequest;
use App\Services\Member\TransportService;

class TransportController extends Controller
{
    public function __construct(TransportService $transportService)
    {
        parent::__construct();
        $this->transportService = $transportService;
    }

    public function index(Request $request, $status = 2)
    {
        if ($request->ajax()) {
            $param = $request->input();
            $param['status'] = $status;
            $param['cid'] = $this->getUserCid();
            $list = $this->transportService->transportIndexService($param);
            return response()->json(['code'=>200, 'total'=> $list['total'], 'rows'=> $list['data']]);
        }

        return view('member.transport.index', $this->transportService->renderStatus());
    }

    // public function save(TransportPostRequest $request)
    // {
    //     return $this->transportService->save($request->input());
    // }

    // public function delete($id)
    // {
    //     return $this->transportService->delete($id);
    // }

    // public function update(TransportPostRequest $request, $id)
    // {
    //     return $this->transportService->update($request->input(), $id);
    // }

    public function read($type = 'read', $id = 0)
    {
        $view = view('member.transport.tradetail');
        if ($id) {
            $view->with('transport', $this->transportService->findService($id));
        }
        return $this->disable($view);
    }

}
