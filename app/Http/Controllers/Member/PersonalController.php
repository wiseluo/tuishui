<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Requests\Member\PersonalPostRequest;
use App\Http\Traits\Pagination;
use App\Services\Member\PersonalService;
use App\Personal;

class PersonalController extends Controller
{
    use Pagination;
    public function __construct(PersonalService $personalService)
    {
        parent::__construct();
        $this->personalService = $personalService;
    }

    public function index(Request $request, $status = 0)
    {
        if ($request->ajax()) {
            return $this->paginateRender(Personal::search($request->input('keyword'))->status($status)->where(['cid'=> $this->getUserCid()]), $request->input('pageSize'));
        }
        return view('member.personal.index', Personal::renderStatus());
    }

    public function delete($id)
    {
        return $this->personalService->destroy($id);
    }

    public function update(Request $request, $id)
    {
        return $this->personalService->update($request->input(), $id);
    }

    public function approve(Request $request, $id)
    {
        return $this->personalService->approve($request->input(), $id);
    }

    public function read($type = 'read', $id = 0)
    {
        $view = view('member.personal.personaldetail');
        if ($id) {
            $personal = $this->personalService->read($id);
            $view->with('personal', $personal);
        }
        
        return $this->disable($view);
    }

    public function resetPassword(PersonalPostRequest $request)
    {
        $user = $this->getUser();
        $userid = $user->id;
        $personalid = $request->input('id');
        return $this->personalService->resetPasswordService($userid, $personalid);
    }
}
