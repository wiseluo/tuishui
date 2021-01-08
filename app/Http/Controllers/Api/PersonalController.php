<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\PersonalApiRequest;
use App\Repositories\PersonalRepository;
use Illuminate\Http\Request;
use App\Services\Api\PersonalService;
use App\Personal;

class PersonalController extends BaseController
{
    public function __construct(PersonalService $personalService)
    {
        parent::__construct();
        $this->personalService = $personalService;
    }

    public function index(Request $request)
    {
        $where = $this->getBaseWhere();
        $param['cid'] = $where['cid'];
        $param['created_user_id'] = $where['euid'];
        $data = $this->personalService->index($param);
        return response()->json(['code'=>200, 'data'=> $data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PersonalApiRequest $request)
    {
        $data = $request->input();
        $where = $this->getBaseWhere();
        $param['cid'] = $where['cid'];
        $param['created_user_id'] = $where['euid'];
        $param['ucenterid'] = $this->getUcenterid();
        $result = $this->personalService->save($data, $param);
        //return response()->json(['code'=>'400', 'msg'=>'失败', 'res'=> $result]);
        if($result) {
            return response()->json(['code'=>'200', 'msg'=>'成功']);
        } else {
            return response()->json(['code'=>'400', 'msg'=>'失败']);
        }
    }

    public function upload_img(Request $request)
    {
        $res = Personal::where('created_user_id', $this->getUserid())->update(['service_agreement_pic'=> $request->input('service_agreement_pic'), 'tax_refund_agreement_pic' => $request->input('tax_refund_agreement_pic')]);
        if($res){
            return response()->json(['code'=>'200', 'msg'=>'上传成功']);
        }else{
            return response()->json(['code'=>'400', 'msg'=>'上传失败']);
        }
    }

}
