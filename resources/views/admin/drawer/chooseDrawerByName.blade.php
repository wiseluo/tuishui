<!--选择客户-->
<link rel="stylesheet" href="">
<div class="chooseCus panel-body">
    <div class="form-group" style="margin-left: 25px">
        {{--<label for="customerName" class="col-lg-4 control-label">开票人名称</label>--}}
        <div class=" " style="width: 200px;">
            <input style="width: 200px;float: left" type="text" class="form-control chooseCustomer_name" name="keyword" placeholder="输入开票人名称">
            <input type="hidden" class="customer_id" value="{{$customer_id}}">
        </div>
        <button onclick="GetEnterprise(1)" class="btn btn-success m-l-md">查询</button>
    </div>
    <div class="table-responsive panel m-t" style="max-height: 450px">
        <table class="table table-hover b-t b-light" style="table-layout: fixed;">
            <thead>
            <tr class="info">
                <th width="33%">公司名称</th>
                <th width="33%">纳税人识别号</th>
                <th width="33%">联系电话</th>
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
    dataObj.page_enterprise = 1
    function GetEnterprise(page){
        var keyword = $('.chooseCustomer_name').val()
        var html = ''
            id = $('.customer_id').val()
        $.get('/admin/drawer/drawer_choose',{keyword:keyword,action:'data',customer_id: id},function (res) {
            console.log(res);
            $('.bnoteTbody').html("");
            $.each(res.data,function (a,b) {
              html += '<tr data-id="' + b.id + '">' +
                '<td class="customer_name">' + b.company + '</td>' +
                '<td class="">' + b.tax_id + '</td>' +
                '<td class="">' + b.telephone + '</td>' +
                '</tr>';
            })
            $('.bnoteTbody').html(html);
        })
    }

</script>
