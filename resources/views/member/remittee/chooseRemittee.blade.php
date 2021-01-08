<!--选择户名-->
<link rel="stylesheet" href="">
<div class="choosePro panel-body">
    <div class="form-group" style="margin-left: 25px">
        {{--<label for="customerName" class="col-lg-4 control-label">客户名称</label>--}}
        <div class=" " style="width: 200px;">
            <input style="width: 200px;float: left" type="text" class="form-control chooseRemittee_name" name="keyword" placeholder="输入收款单位名称">
        </div>
        <button onclick="GetEnterprise(1)" class="btn btn-success m-l-md">查询</button>
    </div>
    <div class="table-responsive panel" style="max-height: 650px">
        <table class="table table-hover b-t b-light" style="table-layout: fixed;">
            <thead>
            <tr class="info">
                <th width="20%">收款单位类型</th>
                <th width="20%">收款单位</th>
                <th width="20%">收款户名</th>
                <th width="20%">收款账号</th>
                <th width="20%">开户银行</th>
            </tr>
            </thead>
            <tbody class="bnoteTbody">

            </tbody>
        </table>
    </div>
    {{--分页--}}
    <div class="pagePicker">
        <div id="enterpriseRem"></div>
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
        var keyword = $('.chooseRemittee_name').val()
        var html = ''
        $.get('/member/finance/remittee_choose',{keyword:keyword,page:page,action:'data'},function (res) {
            console.log(res);
            total = res.data.total
            $('.bnoteTbody').html("");
            $.each(res.data.data,function (a,b) {
              html += `<tr data-id="${b.id}">
                    <td class="customer_name">${b.remit_type_str}</td>
                    <td class="">${b.tag}</td>
                    <td class="">${b.name}</td>
                    <td class="">${b.number}</td>
                    <td class="">${b.bank}</td>
                </tr>`
            })
            $('.bnoteTbody').html(html);
            //调用分页
            Creatpage('enterpriseRem',total, GetEnterprise)
        })
    }

</script>
