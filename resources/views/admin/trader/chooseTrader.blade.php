<!--选择客户-->
<link rel="stylesheet" href="">
<div class="chooseCus panel-body">
    <div class="form-group" style="margin-left: 25px">
        {{--<label for="traderName" class="col-lg-4 control-label">贸易商名称</label>--}}
        <div class=" " style="width: 200px;">
            <input style="width: 200px;float: left" type="text" class="form-control chooseTrader_name" name="keyword" placeholder="输入贸易商名称">
        </div>
        <button onclick="GetEnterprise(1)" class="btn btn-success m-l-md">查询</button>
    </div>
    <div class="table-responsive panel m-t" style="max-height: 425px;overflow:auto">
        <table class="table table-hover b-t b-light" style="table-layout: fixed;">
            <thead>
            <tr class="info">
                <th width="25%">贸易商名称</th>
                <th width="25%">国家</th>
                <th width="25%">联系电话</th>
                <th width="25%">地址</th>
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
      $(document).on('click', '.bnoteTbody tr', function () {
        $(this).addClass('bg-active').siblings().removeClass('bg-active');
      })
    })
    GetEnterprise(1)
    dataObj.page_enterprise = 1
    function GetEnterprise(page){
        var keyword = $('.chooseTrader_name').val()
        var html = ''
        $.get('/admin/trader/trader_choose',{keyword:keyword,page:page,action:'data'},function (res) {
            console.log(res);
            if(res.code == 403){
                layer.msg(res.msg)
                return false
            }
            total = res.data.total
            $('.bnoteTbody').html("");
            $.each(res.data.data,function (a,b) {
              html += '<tr data-id="' + b.id + '" data-name="'+b.name+'">' +
                '<td class="customer_name">' + b.name + '</td>' +
                '<td class="">' + b.country_en + '</td>' +
                '<td class="">' + b.cellphone + '</td>' +
                '<td class="">' + b.address + '</td>' +
                '</tr>';
            })
            $('.bnoteTbody').html(html);
            //调用分页
            Creatpage('enterpriseCus',total, GetEnterprise)
        })
    }

</script>
