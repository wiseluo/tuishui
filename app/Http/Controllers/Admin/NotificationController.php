<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Traits\Pagination;
use App\Notification;

class NotificationController extends Controller
{
    use Pagination;

    public function index(Request $request){
        if ($request->ajax()) {
            $builder = Notification::with('senderuser', 'receiveruser')->search($request->input('keyword'))->where('receiver', $request->user()->id)->where(['cid'=> $this->getUserCid()]);
            return $this->paginateRender($builder, $request->input('pageSize'));
        }
        return view('admin.notification.index');
    }
    public function read($type = 'read', $id = 0)
    {
        $view = view('admin.notification.notifydetail');
        if ($id) {
            $notification = Notification::findOrFail($id);
            $notification->update(['readed'=> 1]);
            $view->with('notification', $notification);
        }
        $view->with('disabled', 'read' === $type ? 'disabled' : '');
        return $view;
    }

    public function save(Request $request)
    {
        $notification = new Notification();
        $notification->sender_id = $request->sender_id;
        $notification->receiver_id = $request->receiver_id;
        $notification->content = $request->content;
        $notification->status = $request->status;
        return $notification->save();
    }
}
