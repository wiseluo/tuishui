<?php

namespace App\Http\Controllers\Member;

use App\Repositories\DataRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class DataController extends Controller
{

    public function index(DataRepository $dataRepository)
    {
        return view('member.data.index', ['types'=> $dataRepository->getTypes()]);
    }

    public function read(DataRepository $dataRepository, $id)
    {
        return $dataRepository->getTypesByFatherIdAndPaginate($id, request()->input('pageSize', 10));
    }

    public function save(DataRepository $dataRepository, Request $request, $id)
    {
        $father_id = $id;

        $params = $request->only('name', 'key', 'value') + compact('father_id');

        if ('edit' === $request->oper) {
            $dataRepository->update($params, $request->id);
        } elseif ('del' === $request->oper) {
            $dataRepository->delete($request->id);
        } else {
            $dataRepository->save($params);
        }
    }

    public function father(DataRepository $dataRepository, $id)
    {
        return $dataRepository->getTypesByFatherId($id);
    }
}