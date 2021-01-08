<!--选择境内货源地-->
<div class="choosePro panel-body">
    <div class="form-group" style="margin-left: 25px">
        {{--<label for="customerName" class="col-lg-4 control-label">境内货源地名称</label>--}}
        <div class=" " style="width: 200px;">
            <input style="width: 200px;float: left" type="text" class="form-control chooseCustomer_name" name="keyword" placeholder="输入境内货源地名称">
        </div>
        <button onclick="GetEnterprise(1)" class="btn btn-success m-l-md">查询</button>
    </div>
    <div class="table-responsive panel" style="max-height: 450px">
        <table class="table table-hover b-t b-light" style="table-layout: fixed;">
            <thead>
            <tr class="info">
                <th width="50%">境内货源地</th>
                <th width="50%">境内货源地编号</th>
            </tr>
            </thead>
            <tbody class="bnoteTbody">

            </tbody>
        </table>
    </div>
    {{--分页--}}
    <div class="pagePicker">
        <div id="enterpriseOrder"></div>
    </div>
</div>
<script>
    $(function () {
      $(document).on('click', '.bnoteTbody tr', function () {
        $(this).addClass('bg-active').siblings().removeClass('bg-active');
      })
        $(document).on('dblclick', '.bnoteTbody tr', function () {
            $(this).addClass('bg-active').siblings().removeClass('bg-active');
            $('.layui-layer-btn0:eq(1)').trigger('click');
        })
    })
    GetEnterprise(1)
    dataObj.page_enterprise = 1
    function GetEnterprise(page){
        var keyword = $('.chooseCustomer_name').val()
        var html = ''
            id = $('.choose_customer_id').val()
        $.get('/member/district/list',{keyword:keyword,page:page,action:'data'},function (res) {
            total = res.data.total
            $('.bnoteTbody').html("");
            $.each(res.data.data,function (a,b) {
              html += '<tr data-id="' + b.id + '">' +
                '<td class="customer_name">' + b.district_name + '</td>' +
                '<td class="">' + b.district_code + '</td>' +
                '</tr>';
            })
            $('.bnoteTbody').html(html);
            //调用分页
            Creatpage('enterpriseOrder',total, GetEnterprise)
        })
    }

</script>
