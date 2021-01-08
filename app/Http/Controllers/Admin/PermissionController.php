<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/10
 * Time: 10:00
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Traits\Pagination;
use App\Permission;

class PermissionController extends Controller
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
            return $this->paginateRender(Permission::search($request->keyword), $request->pageSize);
        }
        return view('admin.permission.index');
    }

    public function save(Request $request)
    {
        $permission = new Permission();
        $permission->name = $request->name;
        $permission->display_name = $request->display_name;
        $permission->description = $request->description;
        return (int) $permission->save();
    }

    public function delete($id)
    {
        return Permission::destroy($id);
    }

    public function update(Request $request, $id)
    {
        $permission = Permission::find($id);
        
        return (int) $permission->update($request->input());
    }

    public function read($type = 'read', $id = 0)
    {
        $view = view('admin.permission.perdetail');

        if ($id) {
            $permission = Permission::findOrFail($id);
            $view->with('permission', $permission);
        }

        $view->with('disabled', 'read' === $type ? 'disabled' : '');

        return $view;
    }

    public function choose()
    {
        return view('admin.dialogs.permission');
    }
}