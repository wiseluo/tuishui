<!--选择客户-->
<link rel="stylesheet" href="">
<div class="chooseCus panel-body">
    <div class="form-group" style="margin-left: 25px">
        {{--<label for="customerName" class="col-lg-4 control-label">客户名称</label>--}}
        <div class=" " style="width: 200px;">
            <input style="width: 200px;float: left" type="text" class="form-control chooseCustomer_name" name="keyword" placeholder="输入客户名称">
        </div>
        <button onclick="GetEnterprise(1)" class="btn btn-success m-l-md">查询</button>
    </div>
    <div class="table-responsive panel m-t" style="max-height: 450px;">
        <table class="table table-hover b-t b-light" style="table-layout: fixed;">
            <thead>
            <tr class="info">
                <th width="25%">客户名称</th>
                <th width="25%">客户编号</th>
                <th width="25%">联系人</th>
                <th width="25%">联系电话</th>
            </tr>
            </thead>
            <tbody class="bnoteTbody">

            </tbody>
        </table>
    </div>
    {{--分页--}}
    <div class="pagePicker">
        <div id="enterpriseCus"></div>
    </div>
</div>
<script>
    $(function () {
        $(document).off('click', '.bnoteTbody tr')
        $(document).on('click', '.bnoteTbody tr', function () {
            $(this).addClass('bg-active').siblings().removeClass('bg-active');
        })
        $(document).off('dblclick', '.bnoteTbody tr')
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
        $.get('/admin/customer/customer_choose',{keyword:keyword,page:page,action:'data'},function (res) {
            console.log(res);
            if(res.code == 403){
                layer.msg(res.msg)
                return false
            }
            total = res.data.total
            $('.bnoteTbody').html("");
            $.each(res.data.data,function (a,b) {
              html += '<tr data-id="' + b.id + '">' +
                '<td class="customer_name">' + b.name + '</td>' +
                '<td class="">' + b.number + '</td>' +
                '<td class="">' + b.linkman + '</td>' +
                '<td class="">' + b.telephone + '</td>' +
                '</tr>';
            })
            $('.bnoteTbody').html(html);
            //调用分页
            Creatpage('enterpriseCus',total, GetEnterprise)
        })
    }

</script>
