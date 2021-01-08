<!--分配业务员-->
<link rel="stylesheet" href="">
<div class="chooseCus panel-body">
    <div class="form-group" style="margin-left: 25px">
        {{--<label for="customerName" class="col-lg-4 control-label">客户名称</label>--}}
        <div class=" " style="width: 500px;">
            <input type="text" id="chooseCusNumber" placeholder="输入业务编号">
            <input type="text" id="chooseCusSealingnum" placeholder="输入箱号">
            <button onclick="GetEnterprise(1)" class="btn btn-success m-l-md">查询</button>
        </div>
    </div>
    <div class="table-responsive panel" style="overflow-x:auto;height: 450px;">
        <table class="table table-hover b-t b-light" style="table-layout: fixed;">
            <thead>
            <tr class="info">
                <th width="25%">业务编号</th>
                <th width="25%">客户</th>
                <th width="25%">起运港</th>
                <th width="25%">抵运港</th>
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
    var dataObj = {
        page_enterprise : 1,
        page_order : 1,
        page_log: 1,
        page_log_info: 1,
        limit_enterprise: 10,
        limit_order: 10,
        limit_log:   10,
        limit_log_info: 10
    }
    function GetEnterprise(page){
        var chooseCusNumber = $('#chooseCusNumber').val()
        var chooseCusSealingnum = $('#chooseCusSealingnum').val()
        var html = '';
        $.get('/member/bnotelist',{number:chooseCusNumber, bnote_sealingnum:chooseCusSealingnum, page:page,action:'data'},function (res) {
            let total = JSON.parse(res).data.total
            $('.bnoteTbody').html("");
            $.each(JSON.parse(res).data.data,function (a,b) {
                html += '<tr data-id="' + b.bnote_id + '">' +
                    '<td class="">' + b.bnote_num + '</td>' +
                    '<td class="">' + b.bnote_cus + '</td>' +
                    '<td class="">' + b.bnote_sport + '</td>' +
                    '<td class="">' + b.bnote_eport + '</td>' +
                    '<td style="display: none"  class="bnote_id">' + b.bnote_id + '</td>' +
                '</tr>';
            })
            $('.bnoteTbody').html(html);
            //调用分页
            layui.use(['laypage', 'layer'], function() {
                var laypage = layui.laypage,
                    layer = layui.layer;
                laypage.render({
                    elem: 'enterpriseOrder',
                    count: total,
                    limit: dataObj.limit_enterprise,
                    first: '首页',
                    last: '尾页',
                    layout: ['count', 'prev', 'page', 'next', 'limit', 'skip'],
                    curr: dataObj.page_enterprise,
                    theme: '#00A0E9',
                    jump:function(obj,first){
                        if(!first) {
                            //***第一次不执行,一定要记住,这个必须有,要不然就是死循环,哈哈
                            var curr = obj.curr;
                            //更改存储变量容器中的数据,是之随之更新数据
                            dataObj.page_enterprise = obj.curr;
                            dataObj.limit_enterprise= obj.limit;
                            //回调该展示数据的方法,数据展示
                            GetEnterprise(curr)
                        }
                    }
                });
            });
        })
    }

</script>
