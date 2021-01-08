<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Repositories\CountryRepository;
use App\Http\Requests\TraderPostRequest;
use App\Services\Admin\TraderService;
use App\Exports\Admin\TraderExport;

class TraderController extends Controller
{
    public function __construct(TraderService $traderService, TraderExport $traderExport)
    {
        parent::__construct();
        $this->traderService = $traderService;
        $this->traderExport = $traderExport;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $param = $request->input();
            $param['userlimit'] = $this->getUserLimit();
            $list = $this->traderService->traderIndex($param);
            return response()->json(['code'=>200, 'total'=> $list['total'], 'rows'=> $list['data']]);
        }
        return view('admin.trader.index');
    }

    public function read(CountryRepository $countryRepository, $type = 'read', $id = 0)
    {
        $country_id = $countryRepository->getCountrylist();
        $view = view('admin.trader.traderdetail');

        if ($id) {
            $view->with('trader', $this->traderService->find($id));
        }
        $view->with(compact('country_id'));
        return $this->disable($view);
    }

    public function save(TraderPostRequest $request)
    {
        $res = $this->traderService->save($request->input());
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    public function update(TraderPostRequest $request, $id)
    {
        $res = $this->traderService->update($request->input(), $id);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    public function delete($id)
    {
        $res = $this->traderService->delete($id);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    public function traderChoose(Request $request)
    {
        $action = $request->input('action');
        if($action == 'html'){
            return view('admin.trader.chooseTrader');
        }else if($action == 'data'){
            $param = $request->input();
            $param['userlimit'] = $this->getUserLimit();
            $data = $this->traderService->traderIndex($param);
            return response()->json(['code'=> 200, 'data'=> $data]);
        }
        return response()->json(['code'=> 400, 'msg'=> '无效参数']);
    }

    public function traderExport(Request $request)
    {
        $param = $request->input();
        $param['keyword'] = $request->input('keyword', '');
        $param['userlimit'] = $this->getUserLimit();
        return $this->traderExport->traderListExport($param);
    }
}
