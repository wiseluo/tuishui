<style>
    .parsley-errors-list{
        margin-top:3px;
    }
</style>
<!--权限修改，新增-->
<div class="panel-body perdetail">
    <form class="form-horizontal" style="overflow: hidden;">
        <div class="form-group">
            <label for="sender" class="col-sm-2 control-label necessary_front">发送者</label>
            <div class="col-sm-9">
                <input type="text" id="sender" name="sender" class="form-control" value="{{isset($notification) ? $notification->senderuser->username : ''}}" data-parsley-required data-parsley-trigger="change" {{ $disabled }}/>
            </div>
        </div>
        <div class="form-group">
            <label for="receiver" class="col-sm-2 control-label necessary_front" >接受者</label>
            <div class="col-sm-9">
                <input type="text" id="receiver" name="receiver" class="form-control" value="{{isset($notification) ? $notification->receiveruser->username : ''}}" data-parsley-required data-parsley-trigger="change" {{ $disabled }}/>
            </div>
        </div>
        <div class="form-group">
            <label for="status" class="col-sm-2 control-label necessary_front" >通知类型</label>
            <div class="col-sm-9">
                <input type="text" id="status" name="status" class="form-control" value="{{isset($notification) ? $notification->status_str : ''}}" data-parsley-required data-parsley-trigger="change" {{ $disabled }}/>
            </div>
        </div>
        <div class="form-group">
            <label for="content" class="col-sm-2 control-label necessary_front">内容</label>
            <div class="col-sm-9">
                <textarea id="content" name="content" class="form-control" rows="3" data-parsley-required data-parsley-trigger="change" {{ $disabled }}>{{isset($notification) ?  $notification->content : ''}}</textarea>
            </div>
        </div>
    </form>
</div>