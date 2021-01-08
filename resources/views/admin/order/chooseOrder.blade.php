<!--选择产品-->
<link rel="stylesheet" href="">
<div class="choosePro panel-body">
    <div class="form-group" style="margin-left: 25px">
        {{--<label for="customerName" class="col-lg-4 control-label">订单名称</label>--}}
        <div class=" " style="width: 200px;">
            <input style="width: 200px;float: left" type="text" class="form-control chooseOrder_name" name="keyword" placeholder="输入订单名称">
        </div>
        <button onclick="GetEnterprise(1)" class="btn btn-success m-l-md">查询</button>
    </div>
    <div class="table-responsive panel" style="height: 450px;">
        <table class="table table-hover b-t b-light" style="table-layout: fixed;">
            <thead>
            <tr class="info">
                <th width="20%">订单号</th>
                <th width="20%">客户</th>
                <th width="20%">报关金额</th>
                <th width="20%">下单日期</th>
                <th width="20%">提单号</th>
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
    })
    GetEnterprise(1)
    dataObj.page_enterprise = 1
    function GetEnterprise(page){
        var keyword = $('.chooseOrder_name').val()
        var html = ''
        $.get('/admin/order/order_choose',{keyword:keyword,page:page,action:'data'},function (res) {
            console.log(res);
            total = res.data.total
            $('.bnoteTbody').html("");
            $.each(res.data.data,function (a,b) {
                let tdnumber = ''
                if(b.tdnumber == null){
                    tdnumber = ''
                } else {
                    tdnumber = b.tdnumber
                }
                html += '<tr data-id="' + b.id + '">' +
                    '<td class="ordnumber">' + b.ordnumber + '</td>' +
                    '<td class="customerName">' + b.customer_name + '</td>' +
                    '<td class="">' + b.total_value_invoice + '</td>' +
                    '<td class="">' + b.created_at + '</td>' +
                    '<td class="">' + tdnumber + '</td>' +
                '</tr>';
            })
            $('.bnoteTbody').html(html);
            //调用分页
            Creatpage('enterpriseOrder',total, GetEnterprise)
        })
    }

</script>
