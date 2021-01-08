<?php

namespace App\Http\Controllers\Member;

use App\Repositories\ProductlogRepository;
use Illuminate\Http\Request;
use App\Http\Traits\Pagination;
use App\Productlog;

class ProductlogController extends Controller
{
    use Pagination;
    protected $productlogRepository;

    public function __construct(ProductlogRepository $productlogRepository)
    {
        $this->productlogRepository = $productlogRepository;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->paginateRender(Productlog::whereHas('product', function($query) use($request){
                $query->where('name', 'LIKE', '%'. $request->keyword .'%');
            })->with('product')->where(['cid'=> $this->getUserCid()]), $request->input('pageSize'));
        }
        return view('member.productlog.index');
    }

    public function save(Request $request)
    {
        return $this->productlogRepository->save($request->input());
    }

    public function delete($id)
    {
        return $this->productlogRepository->delete($id);
    }

    public function update(Request $request, $id)
    {
        return $this->productlogRepository->update($request->input(), $id);
    }

    public function read($type, $id = 0)
    {
        $view = view('member.productlog.prodetail');

        if ($id) {
            $view->with('product', $this->productlogRepository->select($id));
        }
        
        return $this->disable($view);
    }
}
