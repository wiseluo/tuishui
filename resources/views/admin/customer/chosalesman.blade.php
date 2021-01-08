<!--分配业务员-->
<div class="chooseCus panel-body">
    <form class="form-horizontal" style="overflow: hidden;">
        <div class="col-sm-6">
            <div class="form-group">
                <label class="col-sm-3 control-label">已选择客户</label>
                <div class="col-sm-8 m-b-xs">
                    <input type="text" value="{{ isset($customers) ? $customers->implode('name', ',') : '' }}" class="form-control" readonly>
                    <input type="hidden" name="ids" value="{{ isset($customers) ? $customers->implode('id', ',') : '' }}">
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label class="col-sm-4 control-label necessary_front">待分配员工</label>
                <div class="col-sm-8 m-b-xs">
                    <input type="text" class="form-control user_name" readonly data-parsley-required data-parsley-trigger="change">
                    <input type="hidden" name="u_name">
                    <input type="hidden" name="salesman">
                </div>
            </div>
        </div>
    </form>
    <div class="table-responsive panel" style="overflow-x:auto;">
        <table class="table table-hover b-t b-light" style="table-layout: fixed;">
            <thead>
            <tr class="info">
                <th width="30%">员工账号</th>
                <th width="40%">姓名</th>
                <th width="30%">已分配客户数</th>
            </tr>
            </thead>
            <tbody class="salTbody">
            @if (isset($salesman))
            @foreach($salesman as $man)
                <tr data-id="{{ $man->u_name }}" data-salesman="{{ $man->u_truename }}">
                    <td>{{ $man->u_name }}</td>
                    <td>{{ $man->u_truename }}</td>
                    <td>{{ $man->num }}</td>
                </tr>
            @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>
<script>
  $('.salTbody tr').click( function () {
    $(this).addClass('bg-active').siblings().removeClass('bg-active');
    $('.user_name').val($(this).find('td')[1].textContent);
    $('[name=u_name]').val($(this).data('id'));
    $('[name=salesman]').val($(this).data('salesman'));
  })
</script>