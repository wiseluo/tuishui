<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\RoleRepository;
use Illuminate\Http\Request;
use App\Http\Traits\Pagination;
use App\Role;

class RoleController extends Controller
{
    use Pagination;
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->paginateRender(Role::search($request->keyword), $request->pageSize);
        }
        return view('admin.role.index');
    }

    public function save(RoleRepository $repository, Request $request)
    {
        return $repository->save($request->input());
    }

    public function delete(RoleRepository $repository, $id)
    {
        return $repository->delete($id);
    }

    public function update(RoleRepository $repository, Request $request, $id)
    {
        return $repository->update($request->input(), $id);
    }

    public function read(RoleRepository $repository, $type = 'read', $id = 0)
    {
        $view = view('admin.role.roldetail');

        if ($id) {
            $view->with('role', $repository->get($id));
        }

        $view->with('disabled', 'read' === $type ? 'disabled' : '');

        return $view;
    }

    public function choose()
    {
        return view('admin.dialogs.customer');
    }
}
