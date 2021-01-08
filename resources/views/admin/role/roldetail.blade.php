<script src="{{ URL::asset('/js/laypage/laypage.js') }}"></script>
<link rel="stylesheet" href="../../../js/layui/css/layui.css" media="all">
<style>
    .parsley-errors-list{
        margin-top:3px;
    }
    tbody {
        display: table-row-group;
        vertical-align: middle;
        border-color: inherit;
    }
    .td {
        padding: 6px 15px;
        border-top: 1px solid #f1f1f1;
        text-align: center;
        word-break: break-all;
        vertical-align: middle;
        line-height: 1.42857143;
    }
</style>
<!--角色修改，新增-->
<div class="panel-body roldetail">
    <form class="form-horizontal" style="overflow: hidden;">
        <div class="form-group">
            <label for="display_name" class="col-sm-2 control-label necessary_front">角色信息</label>
            <div class="col-sm-9">
                <input type="text" id="name" name="name" class="form-control" data-parsley-required data-parsley-trigger="change" value="{{isset($role) ? $role->name : ''}}"/>
            </div>
        </div>
        <div class="form-group">
            <label for="display_name" class="col-sm-2 control-label necessary_front">角色名称</label>
            <div class="col-sm-9">
                <input type="text" id="display_name" name="display_name" class="form-control" data-parsley-required data-parsley-trigger="change" value="{{ isset($role) ? $role->display_name : ''}}"/>
            </div>
        </div>
        <div class="form-group">
            <label for="description" class="col-sm-2 control-label necessary_front">描述</label>
            <div class="col-sm-9">
                <textarea id="description" name="description" class="form-control" rows="3" data-parsley-required data-parsley-trigger="change">{{isset($role) ? $role->description : ''}}</textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">权限</label>
            <div class="col-sm-9">
                <a href="javascript:;" class="btn btn-s-md btn-success" data-check="permission" data-content=".pertbody" data-outwidth="600px" {{ $disabled }}>分配权限</a>
                <input type="hidden" name="permission_id">
            </div>
        </div>
        <div class="form-group">
            <div class="table-responsive panel" style="overflow-x:auto;margin:0 15px;text-align: center">
                <table class="table table-hover b-t b-light" style="table-layout: fixed;">
                    <thead>
                    <tr class="info">
                        <th width="80">权限名称</th>
                        <th width="120">描述</th>
                        <th width="50" class="text-center">操作</th>
                    </tr>
                    </thead>
                    <tbody class="pertbody">
                    {{--新增不显示，修改&查看&审核显示--}}
                    @if (isset($role) && null !== $role->perms)
                        @foreach($role->perms as $key => $permission)
                        <tr data-id="{{$permission->id}}">
                            <td>{{$permission->name}}</td>
                            <td>{{$permission->description}}</td>
                            <td class="text-center text-danger deleteBox">删除</td>
                        </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
                {{--<div id="test1"></div>--}}
            </div>
        </div>
    </form>
</div>
<script>
  $(function(){
      var permissions = '{{ isset($role) ? json_encode($role->perms) : '' }}';
      var permissionsCount = '{{ isset($role) ? count($role->perms) : 0 }}';
      if(permissions){
        permissions = permissions.replace(/&quot;/g,'"');
        console.log(window['perlist'])
        var arr = JSON.parse(permissions);
        var nums = 5;
        var thisDate = function(curr){
            var DD='',last = curr*nums -1;
            last = last >= arr.length ? (arr.length-1) : last;
            console.log(last);
            for(var i = (curr*nums - nums);i<=last;i++){
                DD += "<tr data-id="+ arr[i].id +">" + "<td class='td'>" + arr[i].name +"</td>" +"<td>" + arr[i].description +"</td>" +
                    "<td class='text-center text-danger deleteBox'>" + "删除" +"</td>" +"</tr>";
            }
            return DD;
        };
      }
      layui.use('laypage', function(){
          var laypage = layui.laypage;
          //执行一个laypage实例
          laypage.render({
              elem: 'test1',
              limit: 5,
              count: permissionsCount,
              jump: function(obj){
//                      $(".pertbody").html(thisDate(obj.curr));
              }
          });
      });
          //      删除所选产品
          $('.pertbody').on('click','.deleteBox',function(){
              $(this).closest('tr').remove()
          })
  })
</script>



