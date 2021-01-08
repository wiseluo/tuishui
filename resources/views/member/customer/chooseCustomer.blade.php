<!--选择产品-->
<link rel="stylesheet" href="">
<div class="choosePro panel-body">
    <div class="form-group" style="margin-left: 25px">
        {{--<label for="customerName" class="col-lg-4 control-label">客户名称</label>--}}
        <div class=" " style="width: 200px;">
            <input style="width: 200px;float: left" type="text" class="form-control chooseCustomer_name" name="keyword" placeholder="输入客户名称">
        </div>
        <button onclick="GetEnterprise(1)" class="btn btn-success m-l-md">查询</button>
    </div>
    <div class="table-responsive panel" style="height: 450px;">
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
      page_log_info: 1
    }
    function GetEnterprise(page){
        var keyword = $('.chooseCustomer_name').val()
        var html = ''
            id = $('.choose_customer_id').val()
        $.get('/member/customer/customer_choose',{keyword:keyword,page:page,action:'data'},function (res) {
            console.log(res);
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
            layui.use(['laypage', 'layer'], function() {
              var laypage = layui.laypage,
                layer = layui.layer;
              laypage.render({
                elem: 'enterpriseOrder',
                count: total,
                first: '首页',
                last: '尾页',
                layout: ['count', 'prev', 'page', 'next', 'skip'],
                curr: dataObj.page_enterprise,
                theme: '#00A0E9',
                jump:function(obj,first){
                  if(!first) {
                    //***第一次不执行,一定要记住,这个必须有,要不然就是死循环,哈哈
                    var curr = obj.curr;
                    //更改存储变量容器中的数据,是之随之更新数据
                    dataObj.page_enterprise = obj.curr;
                    //回调该展示数据的方法,数据展示
                    GetEnterprise(curr)
                  }
                }
              });
            });
        })
    }

</script>
