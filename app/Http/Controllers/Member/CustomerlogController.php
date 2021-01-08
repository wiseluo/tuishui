<?php

namespace App\Http\Controllers\Member;

use App\Repositories\CustomerlogRepository;
use Illuminate\Http\Request;
use App\Http\Traits\Pagination;
use App\Customerlog;

class CustomerlogController extends Controller
{
    use Pagination;
    protected $customerlogRepository;

    public function __construct(CustomerlogRepository $customerlogRepository)
    {
        $this->customerlogRepository = $customerlogRepository;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->paginateRender(Customerlog::whereHas('customer', function($query) use($request){
                $query->where('name', 'LIKE', '%'. $request->keyword .'%');
            })->with('customer')->where(['cid'=> $this->getUserCid()]), $request->input('pageSize'));
        }
        return view('member.customerlog.index');
    }

    public function save(Request $request)
    {
        return $this->customerlogRepository->save($request->input());
    }

    public function delete($id)
    {
        return $this->customerlogRepository->delete($id);
    }

    public function update(Request $request, $id)
    {
        return $this->customerlogRepository->update($request->input(), $id);
    }

    public function read($type, $id = 0)
    {
        $view = view('member.customerlog.cusdetail');

        if ($id) {
            $view->with('customer', $this->customerlogRepository->select($id));
        }
        
        return $this->disable($view);
    }
}
