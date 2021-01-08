<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Repositories\BillingRepository;
use App\Http\Requests\BillingPostRequest;
use App\Http\Traits\Pagination;
use App\Billing;
use App\Data;

class BillingController extends Controller
{
    use Pagination;
    public function index(Request $request)
    {
    	if($request->ajax()){
            $keyword = $request->input('keyword');
            $builder = Data::with('billing')->where('father_id', 1)->when($keyword, function($query) use($keyword){
                return $query->where('name', 'like', '%'.$keyword.'%');
            })->where(['cid'=> $this->getUserCid()]);
            return $this->paginateRender($builder, $request->input('pageSize'));
        }

    	return view('admin.billing.index');
    }

    public function read(BillingRepository $repository, $type = 'read', $id = 0)
    {

        $view = view('admin.billing.billingdetail');
        if ($id) {
            $view->with('data', $repository->select($id));
        }
        return $this->disable($view);
    }

    public function save(BillingRepository $repository, BillingPostRequest $request)
    {
        return $repository->save($request->input());
    }

    public function update(BillingRepository $repository, BillingPostRequest $request, $id)
    {
        return $repository->update($request->input(), $id);
    }

    public function delete(BillingRepository $repository, $id)
    {
        return $repository->delete($id);
    }
}
