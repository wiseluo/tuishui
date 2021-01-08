<style>
    .parsley-errors-list{
        margin-top:3px;
    }
</style>
<!--权限修改，新增-->
<div class="accdetail panel-body">
    <form class="form-horizontal" style="overflow: hidden;">
        <div class="form-group">
            <label for="name" class="col-sm-2 control-label necessary_front">姓名</label>
            <div class="col-sm-9">
                <input type="text" value="{{ isset($user) ? $user->name : '' }}" id="name" name="name"
                       class="form-control" data-parsley-required data-parsley-trigger="change"/>
            </div>
        </div>
        <div class="form-group">
            <label for="username" class="col-sm-2 control-label necessary_front">用户名</label>
            <div class="col-sm-9">
                <input type="text" value="{{ isset($user) ? $user->username : '' }}" id="username" name="username"
                       class="form-control" data-parsley-required data-parsley-trigger="change" {{ $disabled }}/>
            </div>
        </div>
        <div class="form-group">
            <label for="email" class="col-sm-2 control-label necessary_front">邮箱</label>
            <div class="col-sm-9">
                <input type="text" value="{{ isset($user) ? $user->email : '' }}" id="email" name="email"
                       class="form-control" data-parsley-required data-parsley-trigger="change"/>
            </div>
        </div>
        <div class="form-group">
            <label for="email" class="col-sm-2 control-label">公司</label>
            <div class="col-sm-9">
                <input type="text" id="company_name" value="{{ isset($user) ? (isset($user->company) ? $user->company->name:''):'' }}" class="form-control" placeholder="选择公司"   data-outwidth="800px" readonly/>
                <input type="hidden" id="company_id" name="cid" value="{{ isset($user) ? $user->cid : ''}}" class="form-control">
            </div>
        </div>
        @if('save' === request()->route('type'))
            <div class="form-group">
                <label for="password" class="col-sm-2 control-label necessary_front">密码</label>
                <div class="col-sm-9">
                    <input type="password" id="password" name="password" class="form-control" data-parsley-required
                           data-parsley-trigger="change"/>
                </div>
            </div>
        @endif
        @if('update' === request()->route('type'))
            <div class="form-group">
                <label class="col-sm-2 control-label necessary_front">角色</label>
                <div class="col-sm-9">
                    <a href="javascript:;" class="btn btn-s-md btn-success" data-check="role" data-content=".roltbody" data-outwidth="600px">选择角色</a>
                    <input type="hidden" name="role_id" >
                </div>
            </div>
            <div class="form-group">
                <div class="table-responsive panel" style="overflow-x:auto;margin:0 15px;">
                    <table class="table table-hover b-t b-light" style="table-layout: fixed;">
                        <thead>
                        <tr class="info">
                            <th width="80">角色名称</th>
                            <th width="120">描述</th>
                            <th width="50" class="text-center">操作</th>
                        </tr>
                        </thead>
                        <tbody class="roltbody">
                        @foreach($user->roles as $role)
                            <tr data-id="{{ $role->id }}">
                            <td>{{ $role->display_name }}</td>
                            <td>{{ $role->description }}</td>
                            <td class="text-center text-danger deleteBox">删除</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </form>
</div>
<script>
  $(function(){
    //      删除所选产品
    $('.roltbody').on('click','.deleteBox',function(){
      $(this).closest('tr').remove();
    })
  })
</script>