<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\UserRepository;
use App\User;
use Illuminate\Http\Request;
use App\Http\Traits\Pagination;
use Curl\Curl;

class AccountController extends Controller
{
    use Pagination;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->paginateRender(User::search($request->keyword)->where(['cid'=> $this->getUserCid()]), $request->pageSize);
        }
        return view('admin.account.index');
    }

    public function save(Curl $curl, UserRepository $repository, Request $request)
    {
        $post_data = array(
            'username'=> $request->input('username'),
            'password'=> md5($request->input('password')),
            'action'=> 'login'
        );
        $res = $curl->post(config('app.user_center_url'). '/api/usercenter/api/Api.php', $post_data)->response;
        $result = json_decode($res);

        if($result->code == 200){
            $params = array(
                'name'=> $request->input('name'),
                'username'=> $request->input('username'),
                'password'=> '123456',
                'cid' => $request->input('cid') ? $request->input('cid') : 0,
                'email'=> $request->input('email'),
            );
            return $repository->save($params);
        }else{
            abort(403, 'erp账号密码错误', ['msg'=> json_encode('erp账号密码错误')]);
        }
    }

    public function delete(UserRepository $repository, $id)
    {
        return $repository->delete($id);
    }

    public function update(UserRepository $repository, Request $request, $id)
    {
        return $repository->update($request->input(), $id);
    }

    public function read(UserRepository $repository, $type = 'read', $id = 0)
    {
        $view = view('admin.account.accdetail');

        if ($id) {
            $view->with('user', $repository->select($id));
        }

        $view->with('disabled', 'update' === $type ? 'disabled' : '');

        return $view;
    }

    public function chosalesman()
    {
        return view('admin.customer.chosalesman');
    }

    public function choose()
    {
        return view('admin.dialogs.customer');
    }
}
