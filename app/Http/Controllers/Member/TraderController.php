<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Repositories\CountryRepository;
use App\Http\Requests\TraderPostRequest;
use App\Services\Admin\TraderService;

class TraderController extends Controller
{
    public function __construct(TraderService $traderService, TraderExport $traderExport)
    {
        parent::__construct();
        $this->traderService = $traderService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $param = $request->input();
            $param['userlimit'] = $this->getUserLimit();
            $list = $this->traderService->traderIndex($param);
            return response()->json(['code'=>200, 'total'=> $list['total'], 'rows'=> $list['data']]);
        }
        return view('member.trader.index');
    }

    public function read(CountryRepository $countryRepository, $type = 'read', $id = 0)
    {
        $country_id = $countryRepository->getCountrylist();
        $view = view('member.trader.traderdetail');

        if ($id) {
            $view->with('trader', $this->traderService->select($id));
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
}
