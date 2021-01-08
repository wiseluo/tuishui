<?php

namespace App\Http\Controllers\Member;

use App\Repositories\OrderlogRepository;
use Illuminate\Http\Request;
use App\Http\Traits\Pagination;
use App\Orderlog;

class OrderlogController extends Controller
{
    use Pagination;
    protected $orderlogRepository;

    public function __construct(OrderlogRepository $orderlogRepository)
    {
        $this->orderlogRepository = $orderlogRepository;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->paginateRender(Orderlog::whereHas('order', function($query) use($request){
                $query->where('ordnumber', 'LIKE', '%'. $request->keyword .'%');
            })->with('order')->where(['cid'=> $this->getUserCid()]), $request->input('pageSize'));
        }
        return view('member.orderlog.index');
    }

    public function save(Request $request)
    {
        return $this->orderlogRepository->save($request->input());
    }

    public function delete($id)
    {
        return $this->orderlogRepository->delete($id);
    }

    public function update(Request $request, $id)
    {
        return $this->orderlogRepository->update($request->input(), $id);
    }

    public function read($type, $id = 0)
    {
        $view = view('member.orderlog.ordlogdetail');

        if ($id) {
            $view->with('orderlog', $this->orderlogRepository->select($id));
        }
        
        return $this->disable($view);
    }
}
