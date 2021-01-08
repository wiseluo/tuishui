<?php

namespace App\Http\Controllers\Member;

use App\Repositories\BusinessRepository;
use App\Repositories\DataRepository;
use Illuminate\Http\Request;
use App\Http\Traits\Pagination;
use App\Business;

class BusinessController extends Controller
{
    use Pagination;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->paginateRender(
                Business::with(['salesUnit', 'business'])->search($request->keyword)->where(['cid'=> $this->getUserCid()]), $request->pageSize
            );
        }
        return view('member.business.index');
    }

    public function save(BusinessRepository $repository, Request $request)
    {
        return $repository->save($request->input());
    }

    public function delete(BusinessRepository $repository, $id)
    {
        return $repository->delete($id);
    }

    public function update(BusinessRepository $repository, Request $request, $id)
    {
        return $repository->update($request->input(), $id);
    }

    public function read(BusinessRepository $repository, DataRepository $dataRepository, $type = 'read', $id = 0)
    {
        $salesUnit = $dataRepository->getTypesByFatherId(1);
        $business = $dataRepository->getTypesByFatherId(11);

        $view = view('member.business.busdetail', compact('salesUnit', 'business'));

        if ($id) {
            $view->with('bus', $repository->select($id));
        }

        return $this->disable($view);
    }

    public function contract(Request $request)
    {
        return Business::whereSalesUnit($request->sales_unit)
            ->whereBusiness($request->business)
            ->value('contract');
    }
}
