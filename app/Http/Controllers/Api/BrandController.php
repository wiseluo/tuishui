<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\BrandApiRequest;
use App\Http\Traits\Status;
use App\Http\Traits\Pagination;
use App\Repositories\BrandRepository;
use Illuminate\Http\Request;
use App\Brand;
use App\Services\Api\BrandService;

class BrandController extends BaseController
{
    protected $brandService;

    public function __construct(BrandService $brandService)
    {
        parent::__construct();
        $this->brandService = $brandService;
    }

    public function index(Request $request)
    {
        $param = $request->input();
        $param['status'] = $request->input('status', 0);
        $where = $this->getBaseWhere();
        $param['cid'] = $where['cid'];
        $param['created_user_id'] = $where['euid'];
        $list = $this->brandService->brandIndex($param);
        return response()->json(['code'=>200, 'data'=> $list]);
        // return response()->json(['code'=>200, 'data'=>Brand::search($request->keyword)->with(['link'=>function($query) {
        //     $query->select('id','name','phone');
        // }])->status($request->input('status', 0))->where($this->getBaseWhere())->select('id','name','link_id','type','status','classify')->orderBy('id', 'desc')->paginate($request->input('pageSize'))]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BrandApiRequest $request)
    {
        $res = $this->brandService->save($request->input());
        if($res['status']){
            return response()->json(['code'=>'200', 'msg'=> $res['msg']]);
        } else {
            return response()->json(['code'=>'400', 'msg'=> $res['msg']]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = $this->brandService->showService($id);
        return response()->json(['code'=>200, 'data'=> $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BrandApiRequest $request, $id)
    {
        $param = $request->input();
        $res = $this->brandService->update($param, $id);
        if($res['status']) {
            return response()->json(['code'=>'200', 'msg'=> $res['msg']]);
        } else {
            return response()->json(['code'=>'400', 'msg'=> $res['msg']]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $res = $this->brandService->destroy($id);
        if($status) {
            return response()->json(['code'=>'200', 'msg'=> $res['msg']]);
        } else {
            return response()->json(['code'=>'400', 'msg'=> $res['msg']]);
        }
    }
}
