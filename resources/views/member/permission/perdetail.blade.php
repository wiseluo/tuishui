<style>
    .parsley-errors-list{
        margin-top:3px;
    }
</style>
<!--权限修改，新增-->
<div class="panel-body perdetail">
    <form class="form-horizontal" style="overflow: hidden;">
        <div class="form-group">
            <label for="name" class="col-sm-2 control-label necessary_front">权限信息</label>
            <div class="col-sm-9">
                <input type="text" id="name" name="name" class="form-control" value="{{isset($permission) ? $permission->name : ''}}" data-parsley-required data-parsley-trigger="change"/>
            </div>
        </div>
        <div class="form-group">
            <label for="display_name" class="col-sm-2 control-label necessary_front">权限名称</label>
            <div class="col-sm-9">
                <input type="text" id="display_name" name="display_name" class="form-control" value="{{isset($permission) ? $permission->display_name : ''}}" data-parsley-required data-parsley-trigger="change"/>
            </div>
        </div>
        <div class="form-group">
            <label for="description" class="col-sm-2 control-label necessary_front">描述</label>
            <div class="col-sm-9">
                <textarea id="description" name="description" class="form-control" rows="3" data-parsley-required data-parsley-trigger="change">{{isset($permission) ?  $permission->description : ''}}</textarea>
            </div>
        </div>
    </form>
</div>