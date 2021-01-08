<!--选择产品-->
<link rel="stylesheet" href="">
<div class="choosePeople panel-body">
    <div class="form-group" style="margin-left: 25px">
        {{--<label for="peopleName" class="col-lg-4 control-label">联系人</label>--}}
        <div class=" " style="width: 200px;">
                <input  style="width: 200px;float: left" type="text" class="form-control" id="keyword" name="keyword" placeholder="输入联系人">
        </div>
        <button onclick="GetEnterprise(1)" class="btn btn-success m-l-md">查询</button>
    </div>
    <div class="table-responsive panel" style="margin-top: 15px;height: 450px;">
        <table class="table table-hover b-t b-light" style="table-layout: fixed;">
            <thead>
            <tr class="info">
                <th width="33%">联系人</th>
                <th width="33%">手机号码</th>
                <th width="25%">创建者</th>
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
        var keyword = $('#keyword').val()
        var html = ''
        $.get('/admin/link/linklist',{keyword:keyword,page:page,action:'data'},function (res) {
            console.log(res);
            if(res.code == 403){
                layer.msg(res.msg)
                return false
            }
            let total = res.data.total
            $('.bnoteTbody').html("");
            $.each(res.data.data,function (a,b) {
                html += '<tr data-id="' + b.id + '">' +
                    '<td class="name">'+ b.name +'</td>' +
                    '<td class="phone">'+ b.phone +'</td>' +
                    '<td class="">'+ b.created_user_name +'</td>' +
                '</tr>'
            })
            $('.bnoteTbody').html(html);
            //调用分页
            Creatpage('enterpriseOrder',total, GetEnterprise)
        })
    }
</script>
