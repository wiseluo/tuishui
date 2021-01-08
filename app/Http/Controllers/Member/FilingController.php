<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Requests\FilingPostRequest;
use App\Services\Member\FilingService;

class FilingController extends Controller
{
    public function __construct(FilingService $filingService)
    {
        parent::__construct();
        $this->filingService = $filingService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $status = 0)
    {
        if ($request->ajax()) {
            $param['keyword'] = $request->input('keyword', '');
            $param['status'] = $status;
            $param['userlimit'] = $this->getUserLimit();
            $list = $this->filingService->filingIndex($param);
            return response()->json(['code'=>200, 'total'=> $list['total'], 'rows'=> $list['data']]);
        }
        return view('member.filing.index');
    }

    public function read($type = 'read', $id = 0)
    {
        $view = view('member.filing.fildetail');
        if ($id) {
            $view->with('filing', $this->filingService->findService($id));
        }
        return $this->disable($view);
    }

    public function select($id)
    {
        return $this->filingService->findService($id);
    }

    public function letterList(Request $request)
    {
        if($request->type == 0){
            $view = view('member.filing.letter');
            return $view;
        }
        $user = $this->getUser();
        $list = $this->filingService->getFilingLetterList($user->cid);
        return json_encode($list);
    }

    public function letterDetail(Request $request, $id)
    {
        if($request->type == 0){
            $view = view('member.filing.letterdetail');
            return $view;
        }
        $list = $this->filingService->getFilingLetterDetail($id);
        return json_encode($list);
    }
    
    public function letterInvoiceList(Request $request, $id)
    {
        $list = $this->filingService->getFilingletterInvoiceList($id);
        return json_encode($list);
    }
}
